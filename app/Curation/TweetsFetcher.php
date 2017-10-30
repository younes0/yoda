<?php 

namespace Yoda\Curation;

use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Yoda\Models\Origin;
use Yoda\Models\Collect;
use Yoda\Models\Tweet;
use Yoda\Libraries\Utils;
use Yoda\DataProcessors\TweetFilterer;
use Yoda\DataProcessors\TweetTransformer;
use Yeb\Guzzle\SleepOnLimitReachSubscriber;

/**
 * Starting point for curation: fetch new tweets & insert them in database
 */
class TweetsFetcher
{
    protected $origin;

    protected $isMocked;

    protected $collect;

    protected $since;

    protected $sinceId;

    public function __construct(Origin $origin, $isMocked = false)
    {
        $this->origin   = $origin;
        $this->isMocked = $isMocked;
        $this->collect  = Collect::create(['origin_id' => $origin->id]);

        $minutes = config('yoda.curation.sourceAge') + (5 * 60); // timezone diff
        $this->since = Carbon::now()->subMinutes($minutes);

        $tweet = $this->origin->getLatestTweet();
        $this->sinceId = $tweet ? $tweet->source_id : 1;
    }

    public function __get($name) 
    {
        if (in_array($name, ['collect'])) {
            return $this->$name;
        } 
    }

    public function boot()
    {
        $filterer = new TweetFilterer;

        // don't sleep on limit reach
        \ApiClients::twitter()->getEmitter()->detach(new SleepOnLimitReachSubscriber);

        foreach ($this->getTweets() as $raw) {

            if ($filterer->filterRaw($raw)) continue;

            Tweet::createFromRaw($this->collect, $raw);
        }

        // duplicate collect
        if ($this->collect->tweets->isEmpty()) {
            $this->collect->delete();
        }

        return $this;
    }

    protected function getTweets()
    {
        try {
            if ($this->isMocked || (\App::environment() === 'testing')) {
                return $this->getMocked();
            }
            
            $results = $this->apiCall() ?: [];
            $oldest  = last($results); // oldest tweet

            // request older tweets until one is found before since
            while ( $oldest['published_at']->gt($this->since) ) {
                $prevCount = count($results);
                $results   = array_merge($results, $this->apiCall($oldest['source_id']));
                $oldest    = last($results);

                // list fetch limit reached
                if (in_array(count($results), [$prevCount, $prevCount+1])) break;
            }

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() != 429) throw $e;

        } catch (\Exception $e) {
            $this->collect->update(['exception' => $e->getMessage()]);
            throw $e;
        }

        if (empty($results)) return $results;

        // keep only tweets after since date
        return array_where($results, function($key, $value) {
            return $value['published_at']->gt($this->since);
        });
    }

    protected function apiCall($maxId = null)
    {
        $accountId = $this->origin->account_id;
        
        $query = [
            'count'           => 200,
            'exclude_replies' => true,
            'include_rts'     => true,
            'since_id'        => $this->sinceId,
        ];

        $maxId && $query['max_id'] = $maxId;

        $response = ($this->origin->type === 'list')
            ? \ApiHelper::getListStatuses($this->origin->list_id, $query, $accountId)
            : \ApiHelper::getHomeTimeline($query, $accountId);

        return Utils::transformData($response->json(), new TweetTransformer);
    }

    protected function getMocked()
    {
        $filename = base_path().'/resources/fixtures/TweetsFetcher.getTweets.php';
        
        if ( !file_exists($filename)) {
            throw new Exception();
        }

        return array_slice(include($filename), 0, 50);
    }
}

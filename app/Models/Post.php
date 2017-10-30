<?php

namespace Yoda\Models;

use Yeb\Laravel\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use YebString;

// later: hasMedia; override: is_approved + taggable
class Post extends ExtendedModel
{
    use SoftDeletes;

    public $table = 'posts';

    protected $dates = ['deleted_at', 'publish_at'];

    protected $guarded = ['id'];

    protected $casts = [
        'is_ignored' => 'boolean',
        'has_failed' => 'boolean',
    ];

    public function link()
    {
        return $this->belongsTo(Link::class, 'link_id');
    }

    static public function createFromLink(Link $link)
    {
        return static::create(['link_id' => $link->id]);
    }

    public function publish($votes = 0)
    {
        if ($this->published_id || $this->is_ignored || $this->has_failed) {
            return false;
        }

        $publishAt = Carbon::now()->addMinutes(config('yoda.curation.publishDelay'));

        try {
            $response = \ApiHelper::postJediwp(
                $this->link->url, 
                $this->title ?: $this->makeTitle(), 
                $this->description ?: $this->makeExcerpt(), 
                $this->link->getTags(true)->all(),
                $publishAt,
                $votes,
                $this->publisher_id
            )->json();

            $this->update([
                'has_failed'    => false,
                'publish_at'    => $publishAt,
                'published_id'  => $response['ID'],
                'published_url' => $response['link'],
            ]);

        } catch (ClientException $e) {
            $this->update(['has_failed' => true]);
        }

        return $this;
    }

    public function unpublish()
    {
        $this->update(['is_ignored' => true]);

        try {
            if ($id = $this->published_id) {
               \ApiClients::jediwp()->delete('posts/'.$id);

                $this->update([
                    'published_id'  => null,
                    'published_url' => null,
                ]);
            }

        } catch (ClientException $e) {
            $this->update(['has_failed' => true]);
        }

        return $this;
    }

    // later: caps
    public function makeTitle()
    {
        $original = $this->link->title;
        $string   = $original;

        foreach ([' - ', ' | ', ' > ', '. Par '] as $separator) {
            if (strpos($string, $separator) !== false) {
                $string = explode($separator, $string)[0];
                break;
            }
        }
        
        return (strlen($string) > 30) ? $string : $original;
    }

    public function makeExcerpt($min = 400, $max = 1000)
    {
        $string = $this->link->description;

        // if original desc not enough, add content (without 1st sentences if same)
        if (strlen($string) < $min) {
            $content = $this->link->content;
            
            similar_text(
                YebString::extractSentences($string)[0],
                YebString::extractSentences($content)[0],
                $similarity
            );

            $string = $similarity > 80 ? $content : $string . PHP_EOL .$content;
        }

        // limit string to min/max length
        if ((strlen($string) > $max) || (strlen($string) < $min)) {
           
            $output     = null;
            $prevOutput = null;

            foreach (YebString::extractSentences($string) as $sentence) {
                $output.= $sentence;
                $length = strlen($output);

                if ($length > $max) {
                    $string = strlen($prevOutput) > $min ? $prevOutput : str_limit($output, $max);
                    break;

                } else if ($length > $min) {
                    $string = $output;
                    break;
                }

                $prevOutput = $output;
            }
        }

        return YebString::squeeze($string);
    }
}

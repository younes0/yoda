<?php

namespace Yoda\Datatable;

use DB, Datatable;
use Carbon\Carbon;
use Yeb\Libraries\Datatable as YebDatatable;
use Yoda\Libraries\Utils;
use Label;

class LinksDatatable extends YebDatatable
{
    protected $domains;

    protected $untaggued;

    public function __construct($domains, $untaggued = false)
    {
        parent::__construct();
        $this->domains   = $domains;
        $this->untaggued = $untaggued;
    }

    /**
     * Columns for the Datatable
     * See parent class for further documentation
     * 
     * @var array
     */
    // later: hosts.can_have_paywall
    protected $columns = [
        [ 'data' => 'id' ],
        [ 'data' => 'is_human_approved', 'title' => '&nbsp;', 'encode' => false ],
        [ 'data' => 'publish_at', 'class' => 'td10', 'encode' => false ],
        [ 'data' => 'title', 'class' => 'td30', 'encode' => false ],
        [ 'data' => 'tag', 'encode' => false ],
        // [ 'data' => 'images_url', 'class' => 'td10', 'encode' => false ],
        [ 'data' => 'tweets', 'encode' => false ],
        [ 'data' => 'created_at', 'class' => 'td10'],
        // [ 'data' => 'rating', 'encode' => false ],
    ];

   /**
     * $hasActions determines if a 'Action(s)' column should be added 
     * See parent class for further documentation
     * 
     * @var array
     */
    protected $hasActions = true;

    /**
     * Returns all data after decoration & Chumper\Datatable\Datatable wrapping
     * See parent class for further documentation
     * Todo: add Post
     * 
     * @return array Chumper/Datatable data array
     */
    public function getData()
    {
        $columns = array_fetch($this->columns, 'data');

        $this->query = DB::table('links AS l')
            ->select(
                'l.id', 'l.title', 'l.description', 
                'l.url', 'l.images_url', 'l.is_human_approved',
                'l.created_at', 'p.publish_at', 'p.published_id',
                DB::raw('ARRAY_TO_JSON(ARRAY_AGG(tg.tag_codename)) AS tags'),
                DB::raw('ARRAY_TO_JSON(ARRAY_AGG(t.id)) AS tweets')
            )
            ->leftJoin('links_metrics AS lm', 'lm.link_id', '=', 'l.id')
            ->leftJoin('tweets AS t', 't.expanded_url', '=', 'l.url')
            ->leftJoin('tagged AS tg', 'tg.taggable_id', '=', \DB::raw(
                "l.id AND tg.taggable_type = 'Yoda\Models\Link'"
            ))
            ->leftJoin('posts AS p', 'p.link_id', '=', 'l.id')
            ->where('l.is_machine_approved', true)
            ->groupBy('l.id', 'p.publish_at', 'p.published_id')
        ;

        if ( !$this->untaggued) {
            $this->query->whereIn('tg.tag_domain', $this->domains);
        }

        // decorates data & and use data to build and return Chumper\Datatable\Datatable array
        return $this->addDecorators(Datatable::query($this->query)->showColumns($columns))
            // ->searchColumns(['content', 'expanded_url'])
            ->setSearchWithAlias()
            ->orderColumns($columns)
            ->setAliasMapping()
            ->make();
    }

    public function decorateActions($m)
    {
        $contents = [
            ['url' => $m->url, 'label' => 'Goto Link' ],
            ['url' => '/post/'.$m->id, 'label' => 'Edit Post' ],
        ];

        if ($m->published_id) {
            $contents[] = [
                'label' => 'Wordpress',
                'url'   => Utils::getJediwpPostLink($m->published_id),
            ];
        }

        $contents = array_merge($contents, [
            \DropdownButton::DIVIDER,
            ['url' => '/link/'.$m->id.'/approve/true', 'label' => 'Approve' ],
            ['url' => '/link/'.$m->id.'/approve/false', 'label' => 'Unapprove' ],
        ]);

        return \DropdownButton::normal('Actions')->withContents($contents)->small();
    }

    protected function decorateIsHumanApproved($m)
    {
        if ($m->is_human_approved) {
            return Label::success('t');

        } else if ($m->is_human_approved === false) {
            return Label::danger('f');
        }
    }

    protected function decorateTitle($m)
    {
        return '<strong>'.$m->title.'</strong><br>'.$m->description;
    }

    protected function decorateTag($m)
    {
        $tags = array_filter(json_decode($m->tags));
        $tag  = $tags[0] ?? null;

        return Label::info($tag);
    }

    protected function decorateTweets($m)
    {
        return is_array($m->tweets) ? explode(json_decode($m->tweets), ', ') : null;
    }

    protected function decorateImagesUrl($m)
    {
        $urls = json_decode($m->images_url);
        $url =  $urls[0] ?? null;
        
        return $url ? \HTML::image($url, 'alt', ['width' => 70 ]) : null;
    }

    protected function decoratePublishAt($m)
    {
        $date   = Carbon::parse($m->publish_at);
        $method = $date->isFuture() ? 'primary' : 'normal'; 

        return Label::$method($date->diffForHumans(null, true));
    }

    protected function decorateCreatedAt($m)
    {
        return Carbon::parse($m->created_at)->diffForHumans(null, true);
    }
}

<?php

namespace Yoda\Curation;

use Yoda\Models\Link;
use Illuminate\Database\Eloquent\Collection;
use GuzzleHttp\Event\EndEvent;
use GuzzleHttp\Pool;

class LinksScraper
{
    protected $links;

    public function __construct(Collection $links)
    {
        $this->links = $links;
    }

    public function boot()
    {
        foreach ($this->getHtml() as $id => $html) {
            $this->links->where('id', $id)->first()->update([
                'html' => $html,
            ]);
        }
    }

    public function getHtml()
    {
        $client = \ApiClients::basic();

        $out      = [];
        $requests = [];

        foreach ($this->links as $link) {
            $request = $client->createRequest('GET', $link->url);
            $request->getConfig()->set('id', $link->id);
            $requests[] = $request;
        }

        Pool::batch($client, $requests, [
            'end' => function(EndEvent $e) use (&$out) {
                $id = $e->getRequest()->getConfig()->get('id');
                $response = $e->getResponse();

                $out[$id] = ($response && !$e->getException())
                    ? $response->getBody()->getContents()
                    : null;
            },
        ]);

        return $out;
    }
}

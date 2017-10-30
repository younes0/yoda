<?php

namespace Yoda\Http\Controllers\Nlp;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use Yoda\Nlp\Models\Document;
use Yoda\Nlp\Config;
use Yoda\Nlp\Tools;

class LinkTagController extends PageController
{
    public function getIndex()
    {
        $links = Link::all();
        $events = \fUtils::getEventsQueryBuilder(!DEBUG, $this->placeIds)
            // ->where('p.id', null)
            ->get();

        if ($events) {
            $event       = new \Swar\Contents\Event($events[0]->id);
            $association = new \Swar\Core\Association($event);

            $this->data = array_merge($this->data, [
                'event'          => $event->orm(),
                'suggestedPages' => $association->getSuggestedPages(),
                'cats'           => (new \Swar\Contents\Event($event->id))->getCats(),
            ]);

        }

        $this->jsVars['nlpKeywords'] = \Swar\Nlp\Tools::getKeywords();

        $this->data = array_merge($this->data, [
            'placeIds'    => $this->placeIds,
            'eventsCount' => count($events),
        ]);

        return $this->render('main.eventsAssociate');
    }

}

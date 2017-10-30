<?php

namespace Yoda\Http\Controllers\Nlp;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use Yoda\Nlp\Tools;
use Yoda\Models\Link;
use Session;

class TrainController extends PageController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();

        if ($id = Request::route('model_id')) {
            $this->model = Link::find($id);
        }   
    }

    public function getIndex()
    {
        if ( !$this->model) {
            $query = Link::approved()->where('is_nlpdoc_checked', false);

            Session::put('nlpTrainRemaining', $query->count());
            $model = $query->first();

            return $model
                ? redirect('nlp/train/'.$model->id)
                : view('nlp.train');
        }

        $options = [];

        $domains = \DB::connection('nlp')
             ->table('documents')
             ->groupBy('domain')
             ->lists('domain');

        foreach ($domains as $domain) {
            $classes = Tools::getClasses((array) $domain);

            $values = [];
            foreach ($classes as $class) {
                $values[] = json_encode([
                    'domain' => $domain, 
                    'class'  => $class,
                ]);
            }
 
            $options[$domain] = array_combine($values, $classes);
        }
         
        $this->data['model']    = $this->model;
        $this->data['classes']  = $options;
        $this->jsVars['tokens'] = $this->model->tokenize('classic');

        return view('nlp.train');
    }

    public function anyAction()
    {
        $this->model->update(['is_nlpdoc_checked' => true]);
        
        $action = key(Request::get('action'));

        if ($action === 'create') {
            $class = json_decode(Request::get('class'), true);

            $doc = $this->model->createNlpDoc([
                'domain'     => $class['domain'],
                'class'      => $class['class'],
                'is_checked' => true,
            ]);
        }

        Alert::success('Document created')->flash();
        return redirect('nlp/train');
    }

    // public function getIndex()
    // {
    //     $showUnclassed = Request::get('unclassed', false);

    //     // find Link without NLP Document
    //     if ($id = Request::get('linkId')) {
    //         $link = Link::find($id);

    //     } else {
    //         foreach (Link::all()->sortBy('id') as $item) {
    //             if ($item->nlpDoc()) continue;    

    //             // no nlpDoc
    //             if ( ($item->nlpClassed && !$showUnclassed) || $showUnclassed) {
    //                 $link = $item;
    //                 break;
    //             }
    //         }
    //     }
    // }
}

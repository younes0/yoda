<?php

namespace Yoda\Http\Controllers\Nlp;

use Yeb\Http\Controllers\PageController;
use Request, Response, Alert, Auth;
use Yoda\Nlp\Models\Document;
use Yoda\Nlp\Tools;
use Session;

class DocumentsController extends PageController
{
    protected $doc;

    public function __construct()
    {
        parent::__construct();

        if ($id = Request::route('document_id')) {
            $this->doc = Document::find($id);
        }   
    }

    public function getIndex()
    {
        if ( !$this->doc) {

            $where = "
                is_checked IS false
                AND class = 'affaires' 
                AND classified_as != 'affaires'
            ";

            // $where = "
            //     class != classified_as
            //     AND is_checked = FALSE
            //     AND (class NOT LIKE '%travail%' AND classified_as NOT LIKE '%travail%')
            //     AND (class NOT LIKE '%propriete%')
            //     -- AND count(*) < 20
            // ";

            // $where = " is_checked = FALSE AND classified_as != 'public > administratif' and class = 'public > administratif'";

            // $where = "domain = 'law-fr' AND classified_as = 'false' AND is_checked = FALSE";

            $remaining = \DB::connection('nlp')
                ->table('documents')
                ->whereRaw($where)
                ->selectRaw('count(id)')
                ->first()
                ->count;

            $doc = Document::whereRaw($where)->first();
            Session::put('nlpDocsRemaining', $remaining);

            return $doc
                ? redirect('nlp/documents/'.$doc->id)
                : view('nlp.documents');
        }

        $this->data['doc']      = $this->doc;
        $this->data['classes']  = Tools::getClasses(['law-fr', 'nonlaw-fr']);
        $this->jsVars['tokens'] = $this->doc->tokens;

        return view('nlp.documents');
    }

    public function anyAction()
    {
        $this->doc->update(['is_checked' => true]);
        $action = key(Request::get('action'));

        if ($action === 'reclassify') {
            $this->doc->update(['class' => Request::get('class')]);

        } else if ($action === 'delete') {
            $this->doc->delete();
        }

        Alert::success('classé')->flash();
        return redirect('nlp/documents');
    }
}

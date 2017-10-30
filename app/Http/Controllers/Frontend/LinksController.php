<?php

namespace Yoda\Http\Controllers\Frontend;

use Yeb\Http\Controllers\PageController;
use Yeb\Http\Controllers\DatatableControllerTrait;
use Request, Response, Alert, Auth;
use Yoda\Datatable\LinksDatatable;
use Yoda\Models\Host;

class LinksController extends PageController
{
    use DatatableControllerTrait;

    /**
     * Init UsersDatatable object used in getIndex() and getDatatable()
     */
    public function __construct()
    {
        parent::__construct();
        $this->domains   = ['law-fr'];
        $this->untaggued = Request::get('untaggued') === 'true';
        $this->datatable = new LinksDatatable($this->domains, $this->untaggued);
    }

    /**
     * Users list with datatable
     *
     * @return view
     */    
    public function getIndex()
    {
        $this->jsVars = array_merge($this->jsVars, [
            'columns'  => $this->datatable->getColumns(),
            'dataPush' => [
                [ 'name' => 'domains', 'value' => $this->domains ],
                [ 'name' => 'untaggued', 'value' => $this->untaggued ],
            ],
        ]);

        $this->data['title'] = 'Links';

        return view('frontend.links');
    }

    public function getHostModal()
    {
        return view('frontend.hostModal', ['id' => 'hostModal']);
    }

    public function postHostModal(\Illuminate\Http\Request $request)
    {
        $this->validate($request, ['url' => 'required']);

        $host = Host::firstOrCreateFromUrl(Request::get('url'));
        $host->update(Request::except('url'));

        $message = $host->created_at->diffInSeconds() > 2
            ? 'mis à jour'
            : 'créé';

        Alert::success('Host '.$message)->flash();

        return redirect(Request::header('referer'));
    }
}

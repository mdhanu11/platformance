<?php

namespace App\Http\Controllers;

use App\Service\TableauService;
use Illuminate\Http\Request;

class TableauProjectController extends Controller
{
    public function __construct(private TableauService $tableauService)
    {
    }

    public function getProjects(Request $request){
        $tableauToken = $request->header('tableau-token');
        $siteId = $request->header('site-id');
        return $this->tableauService->getProjects($tableauToken,$siteId);
    }

    public function getWorkbooks(Request $request, $projectName){
        $tableauToken = $request->header('tableau-token');
        $siteId = $request->header('site-id');
        return $this->tableauService->getWorkbooksByProject($tableauToken,$siteId,$projectName);
    }

    public function getViews(Request $request, $projectName){
        $tableauToken = $request->header('tableau-token');
        $siteId = $request->header('site-id');
        return $this->tableauService->getViewsByProject($tableauToken,$siteId,$projectName);
    }

    public function getViewData(Request $request){
        $viewContentUrl = $request->get('viewContentUrl');
        return $this->tableauService->getViewUrl($viewContentUrl);
    }

    public function getViewDataWithViewId(Request $request,$viewId){
        $siteId = $request->header('site-id');
        $tableauToken = $request->header('tableau-token');
        return $this->tableauService->getViewsByViewId($tableauToken,$siteId,$viewId);
    }
}

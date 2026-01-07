<?php

namespace App\Http\Controllers\Admin\Special;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\Special\AdminSpecialService;

class AdminSpecialController extends Controller
{
    private function getParamData(Request $req):array
    {
        return [
            'st' => $req->input('st', null),
            'page' => $req->input('page', null),
        ];
    }

    public function index(Request $req)
    {
        $this->data['paramData'] = $this->getParamData($req);
        $service = new AdminSpecialService();
        $this->data['dataList'] = $this->getList($this->data);
        return view('admin.pages.special.index', $this->data);
    }
}

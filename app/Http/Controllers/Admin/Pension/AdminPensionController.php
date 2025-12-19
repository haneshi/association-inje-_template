<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Admin\AdminController;
use App\Services\Admin\Pension\AdminPensionService;
use Illuminate\Http\Request;

class AdminPensionController extends AdminController
{
    private function getParamData(Request $req): array
    {
        return [
            'st' => $req->input('st', null),
        ];
    }
    //
    public function index(Request $req)
    {
        $this->data['paramData'] = $this->getParamData($req);
        $service = new AdminPensionService();
        $this->data['dataList'] = $service->getPaginate($this->data);
        return view('admin.pages.pension.index', $this->data);
    }

    public function write()
    {
        return view('admin.pages.pension.write');
    }
}

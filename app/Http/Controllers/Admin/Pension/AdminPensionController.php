<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Models\Pension;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Services\Admin\Pension\AdminPensionService;

class AdminPensionController extends AdminController
{
    private function getParamData(Request $req): array
    {
        return [
            'st' => $req->input('st', null),
            'page' => $req->input('page', null),
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

    public function view(Request $req, int $id)
    {
        $this->data['paramData'] = $this->getParamData($req);
        $this->data['pension'] = Pension::getData(['id' => $id]);
        $this->data['pensions'] = pension::where('is_active', true)->orderBy('seq')->get();
        $this->data['files'] = $this->data['pension']->files;

        return view('admin.pages.pension.view', $this->data);
    }
    public function write(Request $req)
    {
        $this->data['paramData'] = $this->getParamData($req);
        return view('admin.pages.pension.write', $this->data);
    }

    public function data(Request $req) {
        if($req->ajax() && $req->isMethod('post')) {
            $service = new AdminPensionService();
            return match($req->pType) {
                'addPension' => $service->addPension($req),
                'setPension' => $service->setPension($req),
                'setImagesSeq' => $service->setImagesSeq($req),
                'deleteImages' => $service->deleteImages($req),

                'addRoom' => $service->addRoom($req),
            };
        }
    }
}

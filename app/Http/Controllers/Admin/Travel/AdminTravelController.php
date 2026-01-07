<?php

namespace App\Http\Controllers\Admin\Travel;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Services\Admin\Travel\AdminTravelService;

class AdminTravelController extends AdminController
{
    //
    private function getParamData(Request $req): array
    {
        return [
            'st' => $req->input('st', null),
            'page' => $req->input('page', null),
        ];
    }

    public function index(Request $req)
    {
        $this->data['paramData'] = $this->getParamData($req);
        $service = new AdminTravelService();
        $this->data['dataList'] = $service->getList($this->data);
        return view('admin.pages.travel.index', $this->data);
    }

    public function write(Request $req)
    {
        $this->data['paramData'] = $this->getParamData($req);
        return view('admin.pages.travel.write', $this->data);
    }

    public function view(Request $req, int $id)
    {
        $this->data['paramData'] = $this->getParamData($req);
        $this->data['travel'] = Travel::getData(['id' => $id]);
        return view('admin.pages.travel.view', $this->data);
    }

    public function data(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            $service = new AdminTravelService();
            return match ($req->pType) {
                'setSeq' => $service->setSeq($req),
                'addTravel' => $service->addTravel($req),
                'setTravel' => $service->setTravel($req),

                'setImagesSeq' => $service->setImagesSeq($req),
                'deleteImages' => $service->deleteImages($req),
            };
        }
    }
}

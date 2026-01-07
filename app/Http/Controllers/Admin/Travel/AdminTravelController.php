<?php

namespace App\Http\Controllers\Admin\Travel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Admin\Travel\AdminTravelService;

class AdminTravelController extends Controller
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
        dump($this->data);
        return view('admin.pages.travel.index', $this->data);
    }

    public function data(Request $req)
    {
        if ($req->ajax() && $req->isMethod('post')) {
            $service = new AdminTravelService();
            return match ($req->pType) {
                'setSeq' => $service->setSeq($req),
            };
        }
    }
}

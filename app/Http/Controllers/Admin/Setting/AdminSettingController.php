<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
use App\Services\Admin\Setting\AdminSettingService;

class AdminSettingController extends AdminController
{
    //
    public function index()
    {
        $dataService = new AdminSettingService();
        $this->data['admins'] = $dataService->getList();
        return view('admin.pages.member.index', $this->data);
    }

    public function write()
    {
        $this->authorize('create', Admin::class);
        return view('admin.pages.member.write');
    }

    public function view(int $id)
    {
        $this->data['view'] = Admin::getData(['id' => $id]);

        if (!$this->data['view']) {
            RedirectRoute('admin.setting.member');
        }

        if ($this->data['view']->id === config('auth.admin')->id || $this->data['view']->auth === 'D') {
            RedirectRoute('admin.setting.member');
        }

        return view('admin.pages.member.view', $this->data);
    }

    public function data(Request $req)
    {
        if ($req->ajax() && $req->isMethod('POST')) {
            $dataService = new AdminSettingService();
            return match ($req->pType) {
                "checkUserId" => $dataService->checkUserId($req),
                "addAdmin" => $dataService->addAdmin($req),
            };
        }
    }
}

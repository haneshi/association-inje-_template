<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Admin\AdminController;
use App\Services\Admin\Auth\AdminAuthService;
use Illuminate\Http\Request;

class AdminAuthController extends AdminController
{
    public function login(Request $request) {
        if($request->ajax() && $request->isMethod('POST')) {
            return (new AdminAuthService())->login($request);
        }
        return view('admin.pages.auth.login');
    }
}

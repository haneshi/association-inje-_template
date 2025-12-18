<?php

namespace App\Http\Controllers\Admin\Pension;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Http\Request;

class AdminPensionController extends AdminController
{
    //
    public function index() {
        return view('admin.pages.pension.index');
    }
}

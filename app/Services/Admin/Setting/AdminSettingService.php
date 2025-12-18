<?php

namespace App\Services\Admin\Setting;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AdminService;
use Illuminate\Support\Facades\Hash;

/**
 * Class AdminSettingService
 * @package App\Services
 */
class AdminSettingService extends AdminService
{
    public function getList()
    {
        $query = Admin::orderByRaw('is_active desc, id asc');
        if (!config('auth.isDevel')) {
            if (!config('auth.isSuper')) {
                $query = $query->where('is_active', true);
            }
        }
        if (config('auth.isSuper') && !config('auth.isDevel')) {
            $query = $query->whereNot('id', config('auth.admin')->id);
        }
        $query = $query->whereNot('auth', 'D');
        return $query->get();
    }

    public function checkUserId(Request $req) {
        $data = $req->except('pType');
        $row = Admin::where('user_id', $data['value'])->withTrashed()->first();
        return $this->returnJsonData('hasUserId', (bool)$row);
    }

    public function addAdmin(Request $req)
    {
        /*
            관리자 등록 시 (DB 저장 잘 됨)
            프론트 쪽 서버에러 모달로 표현됨 응답은 success로 잘 가는데
            해결 : resources/views/admin/layouts/notification.blade.php 에 toast Alert html이 주석되어 있었음 .. ㅋㅋ
        */
        $data = $req->except(['pType', 'password_confirm']);
        $data['auth'] = isset($req->auth) ? "S" : "A";
        $data['is_active'] = true;
        $data['password'] = Hash::make($data['password']);
        try {
            return DB::transaction(function () use ($data, $req) {
                $row = Admin::create($data);
                if ($row) {
                    return $this->returnJsonData('toastAlert', [
                        'type' => 'success',
                        'delay' => 1000,
                        'delayMask' => true,
                        'content' => "관리자가 추가 되었습니다.",
                        'event' => [
                            'type' => 'replace',
                            'url' => route('admin.setting.member'),
                        ],
                    ]);
                }

                return $this->returnJsonData('modalAlert', [
                    'type' => 'error',
                    'title' => "관리자 추가 에러",
                    'content' => "관리자가 추가 되지 않았습니다."
                ]);
            });
        } catch (\Exception $e) {
            $adminLog = new Admin();
            $adminLog->setHistoryLog([
                'type' => 'error',
                'description' => "관리자 추가 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "관리자 추가 에러",
                'content' => "관리자가 추가 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }
}

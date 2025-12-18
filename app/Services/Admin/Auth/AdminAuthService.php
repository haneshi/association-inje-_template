<?php

namespace App\Services\Admin\Auth;

use App\Models\Admin;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use App\Services\Admin\AdminService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

/**
 * Class AdminAuthService
 * @package App\Services
 */
class AdminAuthService extends AdminService
{

    public function login(Request $req)
    {
        $data = $req->only(['user_id', 'password']);
        $row = Admin::where('user_id', $data['user_id'])->first();
        $queryData = $this->encryptLogInArray($data);

        if ($row) {
            if ($row->is_active) {
                if (Auth()->guard('admin')->attempt($data)) {
                    $row->setHistoryLog([
                        'type' => 'login',
                        'description' => "[{$row->user_id}, {$row->name}] 로그인 성공",
                        'queryData' => $this->json_encode($queryData),
                    ]);

                    $req->has("remember")
                        ? Cookie::queue('admin_remember', $row->user_id, 60 * 24 * 30)
                        : Cookie::queue('admin_remember', $row->user_id, -1);

                    $intendeUrl = session()->pull('url.intended', route('admin.home'));
                    return $this->returnJsonData('replace', $intendeUrl);
                }

                $emptyLog = new HistoryLog();
                $emptyLog->setHistoryLog([
                    'type' => 'login',
                    'description' => "[{$data['user_id']} ] 비밀번호가 틀렸습니다.",
                    'queryData' => $this->json_encode($queryData),
                ]);

                return $this->returnJsonData('modalAlert', [
                    'type' => 'warning',
                    'icon' => true,
                    'title' => '로그인 에러',
                    'content' => '비밀번호가 틀렸습니다.',
                    'event' => [
                        'type' => 'focus',
                        'selector' => '#password'
                    ],
                ]);
            } else {
                $adminLog = new Admin();
                $adminLog->setHistoryLog([
                    'type' => 'login',
                    'description' => "[{$row->user_id}, {$row->name}] 정지된 아이디",
                    'queryData' => $this->json_encode($queryData),
                ]);

                return $this->returnJsonData('modalAlert', [
                    'type' => 'warning',
                    'icon' => true,
                    'title' => "로그인 에러",
                    'content' => "<p>[ {$row->userid} ]는 정지된 아이디 입니다.</p><p>관리자에게 문의 하세요!</p>"
                ]);
            }
        }

        $adminLog = new Admin();
        $adminLog->setHistoryLog([
            'type' => 'login',
            'description' => "[ {$data['user_id']} ] 잘못된 로그인 아이디",
            'queryData' => $this->json_encode($queryData)
        ]);

        return $this->returnJsonData('modalAlert', [
            'type' => 'warning',
            'icon' => true,
            'title' => "로그인 에러",
            'content' => "아이디 [ {$data['user_id']} ]는 등록된 아이디가 아닙니다.",
            'event' => [
                'type' => 'focus',
                'selector' => '#user_id',
            ],
        ]);
    }

    public function logout()
    {
        Auth()->guard('admin')->logout();
    }

    public function setAccount(Request $req): array
    {
        $data = $req->except(['pType']);
        $row = Admin::find(config('auth.admin')->id);

        if (!$row) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "내 정보 수정",
                'content' => "다시 로그인 해주세요.",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        }

        $row->name = $data['name'];

        if ($row->save()) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'success',
                'title' => "내 정보 수정",
                'content' => "내 정보가 수정 되었습니다.",
            ]);
        }

        return $this->returnJsonData('modalAlert', [
            'type' => 'error',
            'title' => "내 정보 수정 에러",
            'content' => "내 정보가 수정 되지 않았습니다."
        ]);
    }

    public function setPassword(Request $req): array
    {
        $data = $req->except(['pType']);
        $row = Admin::find(config('auth.admin')->id);

        if (!$row) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "내 정보 수정",
                'content' => "다시 로그인 해주세요.",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        }

        if (!Hash::check($data['password_current'], $row->password)) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'warning',
                'title' => "현재 비밀번호 틀림",
                'content' => "현재 비밀번호가 틀립니다. 확인해 주세요!",
                'event' => [
                    'type' => 'focus',
                    'selector' => '#password_current'
                ],
            ]);
        }

        $row->password = Hash::make($data['password']);

        if ($row->save()) {
            $this->logout();

            return $this->returnJsonData('modalAlert', [
                'type' => 'success',
                'title' => "내 비밀번호 수정",
                'content' => "비밀번호가 수정 되었습니다. 다시 로그인해 주세요!",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        }

        return $this->returnJsonData('modalAlert', [
            'type' => 'error',
            'title' => "내 비밀번호 수정 에러",
            'content' => "내 비밀번호가 수정 되지 않았습니다."
        ]);
    }
}

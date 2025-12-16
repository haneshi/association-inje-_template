<?php

namespace App\Services\Admin\Auth;

use App\Models\Admin;
use App\Models\HistoryLog;
use Illuminate\Http\Request;
use App\Services\Admin\AdminService;
use Illuminate\Support\Facades\Cookie;

/**
 * Class AdminAuthService
 * @package App\Services
 */
class AdminAuthService extends AdminService
{

    public function login(Request $request)
    {
        $data = $request->only(['user_id', 'password']);
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

                    $request->has("remember")
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
                    // 'size' => 'sm',
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
                    // 'size' => 'sm',
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
            // 'size' => 'sm',
            'icon' => true,
            'title' => "로그인 에러",
            'content' => "아이디 [ {$data['user_id']} ]는 등록된 아이디가 아닙니다.",
            'event' => [
                'type' => 'focus',
                'selector' => '#user_id',
            ],
        ]);
    }
}

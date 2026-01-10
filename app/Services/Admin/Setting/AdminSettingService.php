<?php

namespace App\Services\Admin\Setting;

use App\Models\Admin;
use App\Models\Board;
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

    /**
     * ============================================
     *  (Admin Management)
     * ============================================
     */
    public function getAdminList()
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

    public function checkUserId(Request $req)
    {
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
                            'url' => route('admin.setting.manager'),
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

    public function setAdmin(Request $req)
    {
        $data = $req->except(['pType']);
        $row = Admin::find($data['id']);
        if (!$row) {
            return $this->returnJsonData('modal', [
                'type' => 'error',
                'title' => '관리자 정보 수정 에러',
                'content' => "존재하지 않은 관리자입니다.",
                'event' => [
                    'type' => 'reload',
                ]
            ]);
        }

        $row->name = $data['name'];
        $row->is_active = isset($data['is_active']) ? true : false;

        if ($row->save()) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'success',
                'title' => "관리자 정보 수정",
                'content' => "관리자 정보가 수정 되었습니다.",
                'event' => [
                    'type' => 'reload',
                ]
            ]);
        }
        return $this->returnJsonData('modalAlert', [
            'type' => 'error',
            'title' => '관리자 정보 수정 에러',
            'content' => '관리자 정보가 수정되지 않았습니다.',
            'event' => [
                'type' => 'reload',
            ]
        ]);
    }

    public function setPassword(Request $req)
    {
        $data = $req->except(['pType']);
        $row = Admin::find($data['id']);

        if (!$row) {
            return $this->returnJsonData('modal', [
                'type' => 'error',
                'title' => '관리자 비밀번호 수정 에러',
                'content' => "존재하지 않은 관리자입니다.",
                'event' => [
                    'type' => 'reload',
                ]
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
            return $this->returnJsonData('modalAlert', [
                'type' => 'success',
                'title' => "관리자 비밀번호 수정",
                'content' => "관리자 비밀번호가 수정 되었습니다.",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        }

        return $this->returnJsonData('modalAlert', [
            'type' => 'error',
            'title' => "관리자 비밀번호 수정 에러",
            'content' => "관리자 비밀번호가 수정 되지 않았습니다."
        ]);
    }

    /**
     * ============================================
     *  (Board Management)
     * ============================================
     */

    public function getBoardList()
    {
        $query = Board::orderByRaw('is_active desc');

        return $query->get();
    }

    public function addBoard(Request $req)
    {
        $data = $req->except(['pType']);
        $board = Board::getData(['board_name' => $data['board_name']]);
        if ($board) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => '게시판 추가 에러',
                'content' => '이미 사용중인 게시판 아이디 입니다.',
            ]);
        }
        $data['is_fixed'] = $req->boolean('is_fixed');
        $data['is_secret'] = $req->boolean('is_secret');
        $data['is_comment'] = $req->boolean('is_comment');
        $data['is_period'] = $req->boolean('is_period');
        $data['is_active'] = $req->boolean('is_active');
        if ($data['is_active'] === true) {
            $data['seq'] = Board::where('is_active', 1)->count() + 1;
        }
        DB::beginTransaction();
        try {
            $row = Board::create($data);
            if ($row) {
                DB::commit();
                $row->setHistoryLog([
                    'type' => 'create',
                    'description' => "게시판 추가",
                    'queryData' => $this->json_encode($data),
                    'rowData' => $this->json_encode($row)
                ], $this->user());

                return $this->returnJsonData('toastAlert', [
                    'type' => 'success',
                    'delay' => 1000,
                    'delayMask' => true,
                    'content' => "게시판이 추가 되었습니다.",
                    'event' => [
                        'type' => 'replace',
                        'url' => route('admin.setting.board'),
                    ],
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $boardLog = new Board();
            $boardLog->setHistoryLog([
                'type' => 'error',
                'description' => "게시판 추가 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "게시판 추가 에러",
                'content' => "게시판이 추가 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }

    public function setBoard(Request $req)
    {
        $data = $req->except(['pType']);
        $board = Board::find($data['id']);
        if (!$board) {
            return $this->returnJsonData('modalAlert', [
                'title' => "게시판 수정 에러",
                'content' => "삭제된 게시판 입니다.",
                'event' => [
                    'type' => 'replace',
                    'url' => route('admin.setting.board'),
                ],
            ]);
        }
        $data['is_fixed'] = $req->boolean('is_fixed');
        $data['is_secret'] = $req->boolean('is_secret');
        $data['is_comment'] = $req->boolean('is_comment');
        $data['is_period'] = $req->boolean('is_period');
        $data['is_active'] = $req->boolean('is_active');
        if ($data['is_active'] === true) {
            $data['seq'] = Board::where('is_active', 1)->count() + 1;
        }
        DB::beginTransaction();
        try {
            if ($board->update($data)) {
                DB::commit();
                $board->setHistoryLog([
                    'type' => 'create',
                    'description' => "게시판 수정",
                    'queryData' => $this->json_encode($data),
                    'rowData' => $this->json_encode($board)
                ], $this->user());

                return $this->returnJsonData('toastAlert', [
                    'type' => 'success',
                    'delay' => 1000,
                    'delayMask' => true,
                    'content' => "게시판이 수정 되었습니다.",
                    'event' => [
                        'type' => 'reload',
                    ],
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $boardLog = new Board();
            $boardLog->setHistoryLog([
                'type' => 'error',
                'description' => "게시판 수정 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "게시판 수정 에러",
                'content' => "게시판이 수정 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }
}

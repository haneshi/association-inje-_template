<?php

namespace App\Services\Admin\Pension;

use App\Helper\ImageUploadHelper;
use App\Models\Pension;
use App\Services\Admin\AdminService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminPensionService
 * @package App\Services
 */
class AdminPensionService extends AdminService
{
    public function getPaginate(array $arrData, int $paginate = 5)
    {
        $st = $arrData['paramData']['st'];
        $query = Pension::orderByRaw('is_active desc, seq asc');
        if ($st) {
            $query = $query->where(function ($q) use ($st) {
                $q->orWhere('name', 'LIKE', "%{$st}%");
            });
        }
        return $query->paginate($paginate);
    }

    public function addPension(Request $req)
    {

        DB::beginTransaction();

        try {
            $data = $req->except(['pType']);
            $data['is_active'] = $req->boolean('is_active');
            # dd($req->hasFile('images'), $req); true

            if ($data['is_active'] === true) {
                $data['seq'] = Pension::where('is_active', 1)->count() + 1;
            }

            $pension = Pension::create($data);

            if ($req->hasFile('images')) {
                $images = $req->file('images');
                $uploadedCount = 0;
                $failedCount = 0;

                foreach ($images as $index => $image) {
                    $seq = $index + 1;

                    $tempImage = ImageUploadHelper::upload(
                        $image,
                        'pension/' . $pension->id . '/main',
                        ['width' => 1920],
                        $seq
                    );

                    if ($tempImage && $pension->files()->create($tempImage)) {
                        $uploadedCount++;
                    } else {
                        $failedCount++;
                    }
                }

                if ($failedCount > 0 && $uploadedCount === 0) {
                    // 모든 이미지가 실패한 경우
                    throw new \Exception('모든 이미지 업로드에 실패했습니다.');
                }
            }

            DB::commit();

            return $this->returnJsonData('toastAlert', [
                'type' => 'success',
                'delay' => 1000,
                'delayMask' => true,
                'title' => '펜션 등록 성공',
                'event' => [
                    'type' => 'replace',
                    'url' => route('admin.pension'),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $pensionLog = new Pension();
            $pensionLog->setHistoryLog([
                'type' => 'error',
                'description' => "펜션 추가 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "펜션 추가 에러",
                'content' => "펜션이 추가 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }
}




{
    "error": "SQLSTATE[42S22]: Column not found: 1054 Unknown column 'images' in 'INSERT INTO' (Connection: mysql, SQL: insert into `pension` (`name`, `owner`, `tel`, `reservation_key`, `address_basic`, `address_detail`, `post`, `address_local`, `address_jibun`, `lat`, `lng`, `is_active`, `location`, `images`, `seq`, `updated_at`, `created_at`) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, location5, ?, 8, 2025-12-23 03:03:04, 2025-12-23 03:03:04))"
}

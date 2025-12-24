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

        // dd($req->file('images'));

        try {
            $data = $req->except(['pType', 'images']);
            $data['is_active'] = $req->boolean('is_active');

            if ($data['is_active'] === true) {
                $data['seq'] = Pension::where('is_active', 1)->count() + 1;
            }

            $pension = Pension::create($data);

            if ($req->hasFile('images')) {
                $images = $req->file('images');
                $imagesCount = count($images);
                foreach ($images as $image) {
                    $tempImage = ImageUploadHelper::upload(
                        $image,
                        'pension/' . $pension->id . '/main',
                        ['width' => 1920],
                        $imagesCount
                    );
                    if ($tempImage) {
                        if ($pension->files()->create($tempImage)) {
                            $imagesCount++;
                        }
                    }
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

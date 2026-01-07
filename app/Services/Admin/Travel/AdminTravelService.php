<?php

namespace App\Services\Admin\Travel;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Helper\ImageUploadHelper;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AdminService;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminTravelService
 * @package App\Services
 */
class AdminTravelService extends AdminService
{
    public function getData(array $where = []): Model|null
    {
        if (empty($where))
            return null;

        return Travel::where($where)->first();
    }
    public function getList(array $arrData)
    {
        $st = $arrData['paramData']['st'];
        $query = Travel::orderByRaw('is_active desc, seq asc');
        if ($st) {
            $query = $query->where(function ($q) use ($st) {
                $q->orWhere('name', 'LIKE', "%{$st}%");
            });
        }

        return $query->get();
    }

    public function addTravel(Request $req)
    {
        DB::beginTransaction();
        try {
            $data = $req->except(['pType', 'images']);
            $data['is_active'] = $req->boolean('is_active');
            if ($data['is_active'] === true) {
                $data['seq'] = Travel::where('is_active', 1)->count() + 1;
            }
            $travel = Travel::create($data);
            if ($req->hasFile('images')) {
                $images = $req->file('images');
                $imagesCount = count($images);

                foreach ($images as $image) {
                    $tempImage = ImageUploadHelper::upload(
                        $image,
                        'travel/' . $travel->id . '/',
                        ['width' => 1920],
                        $imagesCount
                    );

                    if ($tempImage) {
                        if ($travel->files()->create($tempImage)) {
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
                'title' => '관광지 등록 성공',
                'event' => [
                    'type' => 'replace',
                    'url' => route('admin.travel'),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $travelLog = new Travel();
            $travelLog->setHistoryLog([
                'type' => 'error',
                'description' => "관광지 추가 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "관광지 추가 에러",
                'content' => "관광지이 추가 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }

    public function setTravel(Request $req)
    {
        $travel = $this->getData(['id' => $req->id]);
        if(!$travel) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => '관광지 수정 에러',
                'content' => '존재하지 않은 관광지입니다.',
                'event' => [
                    'type' => 'replace',
                    'url' => route('amdin.travel'),
                ]
            ]);
        }

        DB::beginTransaction();
        try {
            $data = $req->except(['pType', 'images']);
            $data['is_active'] = $req->boolean('is_active');
            $origin = $travel->getOriginal();

            // 사용유무 처리 로직
            if ($origin['is_active'] == 1 && $data['is_active'] == 0) {
                $data['seq'] = 9999;
            } elseif ($origin['is_active'] == 0 && $data['is_active'] == 1) {
                $data['seq'] = Travel::active()->count() + 1;
            }

            if($req->hasFile('images')) {
                $images = $req->file('images');
                $imagesCount = count($images);

                foreach ($images as $image) {
                    $tempImage = ImageUploadHelper::upload(
                        $image,
                        'travel/' . $travel->id . '/',
                        ['width' => 1920],
                        $imagesCount
                    );

                    if ($tempImage) {
                        if ($travel->files()->create($tempImage)) {
                            $imagesCount++;
                        }
                    }
                }
            }

            if($travel->update($data)) {
                DB::commit();
                return $this->returnJsonData('toastAlert', [
                    'type' => 'success',
                    'delay' => 1000,
                    'delayMask' => true,
                    'content' => '관광지 정보가 수정되었습니다.',
                    'event' => [
                        'type' => 'reload',
                    ]
                ]);
            }

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "관광지 수정 에러",
                'content' => "관광지 정보가 수정 되지 않았습니다.",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $travelLog = new Travel();
            $travelLog->setHistoryLog([
                'type' => 'error',
                'description' => "관광지 수정 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "관광지 수정 에러",
                'content' => "관광지이 수정 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }





    ################# System Logic
    public function setSeq(Request $req): array
    {
        $data = $req->except(['pType']);
        $count = 1;
        foreach ($data['seqIdxes'] as $id) {
            Travel::where('id', $id)->update([
                'seq' => $count
            ]);
            $count++;
        }
        return $this->returnJsonData('toastAlert', [
            'type' => 'success',
            'delay' => 1000,
            'delayMask' => true,
            'title' => '순서가 변경 되었습니다.',
            'event' => [
                'type' => 'reload',
            ],
        ]);
    }
}

<?php

namespace App\Services\Admin\Pension;

use Exception;
use App\Models\Pension;
use Illuminate\Http\Request;
use App\Helper\ImageUploadHelper;
use App\Models\DataFile;
use App\Models\PensionRoom;
use Illuminate\Support\Facades\DB;
use App\Services\Admin\AdminService;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AdminPensionService
 * @package App\Services
 */
class AdminPensionService extends AdminService
{
    public function getData(array $where = []): Model|null
    {
        if (empty($where))
            return null;

        return Pension::where($where)->first();
    }

    public function getPension($pansion_id): Model
    {
        return Pension::find($pansion_id);
    }
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

    public function setPension(Request $req)
    {
        $data = $req->except(['pType', 'images']);
        $pension = $this->getData(['id' => $data['id']]);
        $data['is_active'] = $req->boolean('is_active');
        if (!$pension) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => '펜션 수정 에러',
                'content' => '존재하지 않은 펜션입니다.',
                'event' => [
                    'type' => 'replace',
                    'url' => route('amdin.pension'),
                ]
            ]);
        }
        DB::beginTransaction();
        try {
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
            $origin = $pension->getOriginal();

            // 사용유무 처리 로직
            if ($origin['is_active'] == 1 && $data['is_active'] == 0) {
                $data['seq'] = 9999;
            } elseif ($origin['is_active'] == 0 && $data['is_active'] == 1) {
                $data['seq'] = Pension::active()->count() + 1;
            }
            // 순서 변경로직
            $this->setSeq($pension, $data);
            if ($pension->update($data)) {
                DB::commit();
                return $this->returnJsonData('toastAlert', [
                    'type' => 'success',
                    'delay' => 1000,
                    'delayMask' => true,
                    'content' => '펜션 정보가 수정되었습니다.',
                    'event' => [
                        'type' => 'reload',
                    ]
                ]);
            }
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "펜션 수정 에러",
                'content' => "펜션 정보가 수정 되지 않았습니다.",
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $pensionLog = new Pension();
            $pensionLog->setHistoryLog([
                'type' => 'error',
                'description' => "펜션 수정 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "펜션 수정 에러",
                'content' => "펜션이 수정 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }

    public function setImagesSeq(Request $req)
    {
        $data = $req->except(['pType']);
        $count = 1;
        foreach ($data['seqIdxes'] as $id) {
            DataFile::where('id', $id)->update([
                'seq' => $count,
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

    public function deleteImages(Request $req)
    {
        $data = $req->only('id');
        $dataFile = DataFile::find($data['id']);
        if (!$dataFile) {
            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => '이미지 삭제에러',
                'content' => '이미 삭제된 이미지 입니다.',
                'event' => [
                    'type' => 'reload',
                ]
            ]);
        }

        $origin = $dataFile->getOriginal();
        if ($dataFile->delete()) {
            $this->deleteStorageData($origin['file_path']);
            return $this->returnJsonData('toastAlert', [
                'type' => 'success',
                'delay' => 1000,
                'delayMask' => true,
                'title' => '이미지 삭제 성공',
                'event' => [
                    'type' => 'reload',
                ],
            ]);
        }
        return $this->returnJsonData('modalAlert', [
            'type' => 'error',
            'title' => "이미지 삭제 에러",
            'content' => "이미지 삭제 되지 않았습니다."
        ]);
    }

    ###################### 객실 서비스 로직
    public function addRoom(Request $req)
    {
        DB::beginTransaction();
        try {
            $data = $req->except(['pType', 'images']);
            $data['amenities'] = json_decode($req->input('amenities'), true);
            $data['is_active'] = $req->boolean('is_active');
            $pension = $this->getPension($req->pension_id);
            if ($data['is_active'] === true) {
                $data['seq'] = PensionRoom::where('pension_id', $pension->id)
                    ->where('is_active', true)
                    ->count() + 1;
            }
            $room = PensionRoom::create($data);
            if ($req->hasFile('images')) {
                $images = $req->file('images');
                $imagesCount = count($images);
                foreach ($images as $image) {
                    $tempImage = ImageUploadHelper::upload(
                        $image,
                        'pension/' . $pension->id . '/room',
                        ['width' => 1920],
                        $imagesCount
                    );
                    if ($tempImage) {
                        if ($room->files()->create($tempImage)) {
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
                'title' => '객실 등록 성공',
                'event' => [
                    'type' => 'replace',
                    'url' => route('admin.pension'),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $roomLog = new Pension();
            $roomLog->setHistoryLog([
                'type' => 'error',
                'description' => "객실 추가 에러",
                'queryData' => $this->json_encode($data),
                'rowData' => JsonEncode(['error' => $e->getMessage()]),
            ], $this->user());

            return $this->returnJsonData('modalAlert', [
                'type' => 'error',
                'title' => "객실 추가 에러",
                'content' => "객실이 추가 되지 않았습니다. <br> 관리자에게 문의해 주세요!",
            ]);
        }
    }
}

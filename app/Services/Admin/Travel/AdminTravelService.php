<?php

namespace App\Services\Admin\Travel;

use App\Models\Travel;
use Illuminate\Http\Request;
use App\Services\Admin\AdminService;

/**
 * Class AdminTravelService
 * @package App\Services
 */
class AdminTravelService extends AdminService
{
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

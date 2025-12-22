<?php

namespace App\Services\Admin\Pension;

use App\Models\Pension;
use App\Services\Admin\AdminService;
use Illuminate\Http\Request;

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

    public function addPension(Request $req) {
        dd($req);
    }
}

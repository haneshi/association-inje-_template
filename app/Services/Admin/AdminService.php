<?php

namespace App\Services\Admin;

use App\Services\Service;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Class AdminService
 * @package App\Services
 */
class AdminService extends Service
{
    protected function guard(): Guard|StatefulGuard
    {
        return Auth::guard('admin');
    }

    protected function user(): ?Authenticatable
    {
        return $this->guard()->user();
    }

    protected function setSeq($row, array $data)
    {
        $oldSeq = $row->seq;
        $newSeq = $data['seq'] ?? $oldSeq;
        if ($oldSeq < $newSeq) {
            $row->where('is_active', true)
                ->where('id', '!=', $row->id)
                ->where('seq', '>', $row->seq)
                ->where('seq', '<=', $data['seq'])
                ->decrement('seq');
        } else {
            $row->where('is_active', true)
                ->where('id', '!=', $row->id)
                ->where('seq', '<', $row->seq)
                ->where('seq', '>=', $data['seq'])
                ->increment('seq');
        }
        $row->seq = $newSeq;
    }
}

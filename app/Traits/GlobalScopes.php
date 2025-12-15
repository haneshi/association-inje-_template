<?php

namespace App\Traits;

use App\Models\HistoryLog;
use Illuminate\Database\Eloquent\Model;

trait GlobalScopes
{
    ################ Common
    public static function getData(array $where = [], array $with = [])
    {
        if (empty($where)) {
            return null;
        }

        $query = static::query();

        if (!empty($with)) {
            $query->with($with);
        }

        return $query->where($where)->first();
    }

    public function setHistoryLog(array $logData, ?Model $auther = null) {
        $defaultData =[
            'type' => 'other',
            'description' => '',
            'queryData' => null,
            'rowData' => null,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        $logData = array_merge($defaultData, $logData);

        if($auther) {
            $logData['author_id'] = $auther->id;
            $logData['author_type'] = get_class($auther);
        }

        return $this->historyLogs()->create($logData);
    }

    public function historyLogs()
    {
        return $this->morphMany(HistoryLog::class, 'modelable');
    }
}

<?php

namespace App\Traits;

use App\Models\HistoryRecord as History;
use Illuminate\Support\Facades\DB;

trait HistoryRecord
{
    protected static function store($model, $action)
    {
        DB::beginTransaction();

        try {
            if (auth()->user()) {
                $target = get_class($model);
                $targetModel = new $target;

                History::create([
                    'feature' => class_basename($model),
                    'action' => $action,
                    'action_by' => auth()->user()->id,
                    'target' => $action === 'CREATE' ? null : $targetModel->findOrFail(request('id'))->toArray(),
                    'payload' => request()->toArray(),
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected static function bootHistoryRecord()
    {
        $self = new static();

        static::creating(function ($model) {
            self::store($model, 'CREATE');
        });

        static::updating(function ($model) {
            $target = $model->findOrFail(request('id'))->first()->toArray();
            self::store($model, 'UPDATE');
        });

        static::deleting(function ($model) use ($self) {
            if ($self->isSoftDeleteEnabled()) {
                self::store($model, 'DELETE');
            }
        });
    }
}

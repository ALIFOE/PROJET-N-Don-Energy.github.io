<?php

namespace App\Traits;

use App\Models\LogActivite;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('création', $model);
        });

        static::updated(function ($model) {
            self::logActivity('modification', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('suppression', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        LogActivite::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'table' => $model->getTable(),
            'id_table' => $model->id,
            'description' => self::getDescription($action, $model),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    protected static function getDescription($action, $model)
    {
        $modelName = class_basename($model);
        switch ($action) {
            case 'création':
                return "Création d'un(e) {$modelName}";
            case 'modification':
                return "Modification d'un(e) {$modelName}";
            case 'suppression':
                return "Suppression d'un(e) {$modelName}";
            default:
                return "Action sur un(e) {$modelName}";
        }
    }
}
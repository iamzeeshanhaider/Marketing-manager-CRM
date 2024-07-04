<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

trait LogsActivity
{
    /**
     * Handle model event
     */
    public static function bootLogsActivity()
    {
        if (auth()->check()) {
            /**
             * Data creating and updating event
             */
            static::saved(function (Model $model) {
                // create or update?
                if ($model->wasRecentlyCreated) {
                    static::storeLog($model, static::class, 'Created');
                } else {
                    if (!$model->getChanges()) {
                        return;
                    }
                    static::storeLog($model, static::class, 'Updated');
                }
            });

            /**
             * Data deleting event
             */
            static::deleted(function (Model $model) {
                static::storeLog($model, static::class, 'Deleted');
            });
        };
    }

    /**
     * Generate the model name
     * @param  Model  $model
     * @return string
     */
    public static function getTagName(Model $model)
    {
        return !empty($model->tagName) ? $model->tagName : Str::title(Str::snake(class_basename($model), ' '));
    }

    /**
     * Retrieve the current login user id
     * @return int|string|null
     */
    public static function activeUserId()
    {
        return Auth::guard(static::activeUserGuard())->id() ?? 1;
    }

    /**
     * Retrieve the current login user guard name
     * @return mixed|null
     */
    public static function activeUserGuard()
    {
        foreach (array_keys(config('auth.guards')) as $guard) {

            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }

    /**
     * Store model logs
     * @param $model
     * @param $modelPath
     * @param $action
     */
    public static function storeLog($model, $modelPath, $action)
    {

        $newValues = null;
        $oldValues = null;
        if ($action === 'Created') {
            $newValues = $model->getAttributes();
        } elseif ($action === 'Updated') {
            $newValues = $model->getChanges();
        }

        if ($action !== 'Created') {
            $oldValues = $model->getOriginal();
        }

        $log = new ActivityLog();
        $log->model_id = $model->id;
        $log->model_type = $modelPath;
        $log->user_id = static::activeUserId();
        $log->guard_name = static::activeUserGuard();
        $log->module_name = static::getTagName($model);
        $log->action = $action;
        $log->old_value = !empty($oldValues) ? json_encode($oldValues) : null;
        $log->new_value = !empty($newValues) ? json_encode($newValues) : null;
        $log->ip_address = request()->ip();
        $log->save();
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasUniqueCode
{
    /**
     * قم بتشغيل هذا الكود عند تهيئة النموذج.
     * سنستخدم حدث 'created' لضمان أن السجل لديه 'id' بالفعل.
     */
    protected static function bootHasUniqueCode(): void
    {
        static::created(function ($model) {

            if (empty($model->{$model->getCodeColumn()})) {

                $prefix = $model->getCodePrefix();
                $padding = $model->getCodePadding();
                $column = $model->getCodeColumn();


                $code = sprintf('%s%0' . $padding . 'd', $prefix, $model->id);

                DB::table($model->getTable())->where('id', $model->id)->update([$column => $code]);

                $model->{$column} = $code;
            }
        });
    }

    /**
     * يجب على كل نموذج تحديد اسم العمود الذي سيحمل الرمز.
     *
     * @return string
     */
    abstract protected function getCodeColumn(): string;

    /**
     * يجب على كل نموذج تحديد البادئة (Prefix) الخاصة به.
     *
     * @return string
     */
    abstract protected function getCodePrefix(): string;

    /**
     * يجب على كل نموذج تحديد طول الحشو بالأصفار.
     *
     * @return int
     */
    abstract protected function getCodePadding(): int;
}

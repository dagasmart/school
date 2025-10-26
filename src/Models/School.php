<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Support\Facades\Storage;

/**
 * 基础-学校表
 */
class School extends Model
{

	protected $table = 'biz_school';
    protected $primaryKey = 'id';

    protected $casts = [
        'region_info' => 'array',
        'register_time' => 'date',
    ];

    public $timestamps = false;

    public $hidden = []; //排除字段


    public function getSchoolLogoAttribute($value): ?string
    {
        return $value ? env('APP_URL') . $value : null;
    }

    public function setSchoolLogoAttribute($value): void
    {
        $logo = str_replace(env('APP_URL') . Storage::url(''), '', $value);
        $this->attributes['school_logo'] = Storage::url($logo);
    }

    public function sexOption(): array
    {
        return [['value'=>1, 'label'=>'男'], ['value'=>2, 'label'=>'女']];
    }

    public function school(): hasOne
    {
        return $this->hasOne(School::class, 'id', 'school_id')->select('id','school_name');
    }

}

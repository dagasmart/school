<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\hasOne;

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

    public function sexOption(): array
    {
        return [['value'=>1, 'label'=>'男'], ['value'=>2, 'label'=>'女']];
    }

    public function school(): hasOne
    {
        return $this->hasOne(School::class, 'id', 'school_id')->select('id','school_name');
    }

    public function class(): hasOne
    {
        return $this->hasOne(SchoolClasses::class, 'id', 'class_id')->select('id','name');
    }

}

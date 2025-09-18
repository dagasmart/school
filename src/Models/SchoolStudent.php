<?php

namespace DagaSmart\School\Models;


use DagaSmart\BizAdmin\Models\BizModel as Model;
use Illuminate\Database\Eloquent\Relations\hasOne;

/**
 * 基础-学生表
 */
class SchoolStudent extends Model
{

	protected $table = 'fa_school_student';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public $hidden = ['f_idcard2', 'f_code2']; //排除乱码字段

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
        return $this->hasOne(SchoolClass::class, 'id', 'class_id')->select('id','name');
    }

}

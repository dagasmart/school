<?php

namespace DagaSmart\School\Models;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\hasOne;

/**
 * 基础-学生表
 */
class Student extends Model
{
	protected $table = 'biz_student';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function sexOption(): array
    {
        return [['value'=>1, 'label'=>'男'], ['value'=>2, 'label'=>'女']];
    }

    public function school(): hasOne
    {
        return $this->hasOne(SchoolGradeClassesStudent::class)->with(['classes','grade','school']);
    }

//    public function classes(): belongsToMany
//    {
//        return $this->belongsToMany(Classes::class, SchoolGradeClassesStudent::class, 'student_id', 'classes_id')->select(['id','class_name']);
//    }

}

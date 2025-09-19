<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\hasMany;

/**
 * 基础-学生表
 */
class Teacher extends Model
{
	protected $table = 'biz_teacher';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function bind(): hasMany
    {
        return $this->hasMany(SchoolTeacher::class, 'teacher_id', 'id')
            ->with(['school' => function ($query) {
                $query->select('id','school_name');
            }]);
    }

    public function schoolData()
    {
        return School::query()->whereNull('deleted_at')->pluck('school_name','id');
    }


}

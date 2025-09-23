<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    /**
     * 手机号脱敏
     * @param $value
     * @return false|string
     */
    public function getMobileAttribute($value): false|string
    {
        return admin_sensitive($value, 3,5);
    }

    /**
     * 身份证号脱敏
     * @param $value
     * @return false|string
     */
    public function getIdCardAttribute($value): false|string
    {
        return admin_sensitive($value, 6,8);
    }

    public function school(): HasManyThrough
    {
        return $this->hasManyThrough(School::class, SchoolTeacher::class,
            'teacher_id',
            'id',
            'id',
            'school_id'
        )->select(admin_raw("id as value, school_name as label"));
    }

    public function schoolData()
    {
        return School::query()->whereNull('deleted_at')->pluck('school_name','id');
    }




}

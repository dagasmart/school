<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;


/**
 * 基础-学生表
 */
class Teacher extends Model
{
	protected $table = 'biz_teacher';
    protected $primaryKey = 'id';

    protected $casts = [
        'region_info' => 'array',
        'family' => 'array',
    ];

    public $timestamps = true;

    public function bind(): hasMany
    {
        return $this->hasMany(SchoolTeacher::class, 'teacher_id', 'id')
            ->with(['school' => function ($query) {
                $query->select('id','school_name');
            }]);
    }

    /**
     * 头像
     * @param $value
     * @return string|null
     */
    public function getAvatarAttribute($value): ?string
    {
        return $value ? env('APP_URL') . $value : null;
    }

    public function setAvatarAttribute($value): void
    {
        $avatar = str_replace(env('APP_URL') . Storage::url(''), '', $value);
        $this->attributes['avatar'] = Storage::url($avatar);
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

    public function setMobileAttribute($value): void
    {
        if ($value && !strpos($value, '*')) {
            $this->attributes['mobile'] = $value;
        }
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

    public function setIdCardAttribute($value): void
    {
        if ($value && !strpos($value, '*')) {
            $this->attributes['id_card'] = $value;
        }
    }


    public function schoolThrough(): HasManyThrough
    {
        return $this->hasManyThrough(School::class, SchoolTeacher::class,
            'teacher_id',
            'id',
            'id',
            'school_id'
        )->select(admin_raw("id as value, school_name as label"));
    }

    public function school(): HasMany
    {
        return $this->hasMany(SchoolTeacherJob::class,
            'teacher_id',
            'id'
        )->select(admin_raw("school_id,teacher_id,string_agg(job_id::varchar, ',') job_id,array_agg(job_id::varchar) job_ids,school_id as value"))->groupBy(['school_id', 'teacher_id']);
    }

    public function job(): HasOne
    {
        return $this->HasOne(SchoolTeacherJob::class,
            'teacher_id',
            'id'
            )
            ->select(admin_raw("teacher_id,string_agg(job_id::varchar, ',') job_id"))
            ->orderBy('job_id')
            ->groupBy(['teacher_id']);
    }

    public function schoolData(): Collection
    {
        return School::query()->whereNull('deleted_at')->pluck('school_name','id');
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, SchoolTeacherJob::class, 'teacher_id', 'job_id');
    }



}

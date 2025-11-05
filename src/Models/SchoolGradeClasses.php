<?php

namespace DagaSmart\School\Models;

use DagaSmart\BizAdmin\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学校-年级-班级关联表
 */
class SchoolGradeClasses extends Model
{
	protected $table = 'biz_school_grade_classes';

    public $timestamps = false;


    /**
     * 关联学校
     * @return void
     */
    protected static function booted(): void
    {
        static::addGlobalScope(ActiveScope::class, function ($query) {
            $query->whereHas('base');
        });
    }


    /**
     * 年级
     * @return HasOne
     */
    public function grade(): hasOne
    {
        return $this->hasOne(Grade::class, 'id', 'grade_id')->select(['id', 'grade_name']);
    }

    /**
     * 学校
     * @return HasOne
     */
    public function school(): hasOne
    {
        return $this->hasOne(School::class, 'id', 'school_id')->select(['id', 'school_name']);
    }


}

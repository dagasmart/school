<?php

namespace DagaSmart\School\Models;

use Illuminate\Support\Collection;

/**
 * 基础-学生表
 */
class SchoolClassroom extends Model
{

	protected $table = 'biz_school_classroom';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function schoolData(): Collection
    {
        return School::query()->whereNull('deleted_at')->pluck('school_name','id');
    }

}

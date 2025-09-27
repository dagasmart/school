<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;

/**
 * 基础-老师-职务表
 */
class SchoolTeacherJob extends Model
{
	protected $table = 'biz_school_teacher_job';

    public $timestamps = false;


}

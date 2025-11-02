<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学校-年级关联表
 */
class SchoolFacility extends Model
{
	protected $table = 'biz_school_facility';

    public $timestamps = false;


}

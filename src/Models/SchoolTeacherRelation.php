<?php

namespace DagaSmart\School\Models;


use DagaSmart\BizAdmin\Models\BizModel as Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学生表
 */
class SchoolTeacherRelation extends Model
{
	//use SoftDeletes;

	protected $table = 'fa_school_staff_relation';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function school(): HasOne
    {
        return $this->hasOne(School::class,  'id','school_id');
    }


}

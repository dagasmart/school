<?php

namespace Biz\School\Models;


use DagaSmart\BizAdmin\Models\BizModel as Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学生表
 */
class BizSchoolTeacherRelation extends Model
{
	//use SoftDeletes;

	protected $table = 'fa_school_staff_relation';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function school(): HasOne
    {
        return $this->hasOne(BizSchool::class,  'id','school_id');
    }


}

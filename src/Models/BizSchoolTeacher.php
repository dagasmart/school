<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

/**
 * 基础-学生表
 */
class BizSchoolTeacher extends Model
{
	protected $table = 'fa_school_staff';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function bind(): hasMany
    {
        return $this->hasMany(BizSchoolTeacherRelation::class, 'staff_id', 'id')
            ->with(['school' => function ($query) {
                $query->select('id','school_name');
            }]);
    }

    public function schoolData()
    {
        return BizSchool::query()->whereNull('deletetime')->pluck('school_name','id');
//        return BizSchool::query()
//            ->whereNull('deletetime')
//            ->select(['id as value','school_name as label'])
//            ->get();
    }


}

<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;

/**
 * 基础-学生表
 */
class BizSchoolClassRoom extends Model
{

	protected $table = 'fa_school_class';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function schoolData()
    {
        return BizSchool::query()->whereNull('deletetime')->pluck('school_name','id');
    }

}

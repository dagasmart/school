<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Support\Collection;

/**
 * 基础-学生表
 */
class BizSchoolGrade extends Model
{

	protected $table = 'fa_school_grade';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function school(): hasOne
    {
        return $this->hasOne(BizSchool::class, 'id', 'school_id')->select('id', 'school_name');
    }

    public function schoolData(): Collection
    {
        return BizSchool::query()->whereNull('deletetime')->pluck('school_name','id');
    }

}

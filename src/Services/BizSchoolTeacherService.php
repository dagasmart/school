<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchoolTeacher;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * 基础-学生表
 *
 * @method BizSchoolTeacher getModel()
 * @method BizSchoolTeacher|\Illuminate\Database\Query\Builder query()
 */
class BizSchoolTeacherService extends AdminService
{
	protected string $modelName = BizSchoolTeacher::class;

    public function listQuery()
    {
        return $this->query()->with(['bind' => function ($query) {
            $query->select('school_id','staff_id')->orderBy('school_id','asc');
        }]);
    }
}

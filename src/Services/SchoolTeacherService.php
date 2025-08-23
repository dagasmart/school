<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\SchoolTeacher;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;


/**
 * 基础-学生表
 *
 * @method SchoolTeacher getModel()
 * @method SchoolTeacher|Builder query()
 */
class SchoolTeacherService extends AdminService
{
	protected string $modelName = SchoolTeacher::class;

    public function listQuery()
    {
        return $this->query()->with(['bind' => function ($query) {
            $query->select('school_id','staff_id')->orderBy('school_id','asc');
        }]);
    }

    /**
     * 学校列表
     */
    public function schoolData()
    {
        return $this->getModel()->schoolData();
    }

}

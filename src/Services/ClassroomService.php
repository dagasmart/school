<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Classroom;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-学生表
 *
 * @method Classroom getModel()
 * @method Classroom|Builder query()
 */
class ClassroomService extends AdminService
{
	protected string $modelName = Classroom::class;

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

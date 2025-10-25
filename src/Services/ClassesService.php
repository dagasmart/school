<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Classes;
use DagaSmart\BizAdmin\Services\AdminService;
use DagaSmart\School\Models\Grade;
use DagaSmart\School\Models\School;
use DagaSmart\School\Models\SchoolGradeClasses;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-学生表
 *
 * @method Classes getModel()
 * @method Classes|Builder query()
 */
class ClassesService extends AdminService
{
	protected string $modelName = Classes::class;

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

    /**
     * 学校年级列表
     * @param int $school_id
     * @param $grade_id
     * @return array
     */
    public function SchoolGradeClasses(int $school_id, $grade_id): array
    {
        $classes_id = SchoolGradeClasses::query()
            ->where('school_id', $school_id)
            ->where('grade_id', $grade_id)
            ->pluck('classes_id');
        return Classes::query()
            ->whereIn('id', $classes_id)
            ->get(['id as value','class_name as label'])
            ->toArray();
    }

}

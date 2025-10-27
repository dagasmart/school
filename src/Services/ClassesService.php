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


    public function sortable($query): void
    {
        if (request()->orderBy && request()->orderDir) {
            $query->orderBy(request()->orderBy, request()->orderDir ?? 'asc');
        } else {
            $query->orderBy($this->primaryKey(), 'asc');
        }
    }


    /**
     * 学校列表
     */
    public function schoolData()
    {
        return (new StudentService)->getSchoolAll();
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
            ->pluck('classes_id')
            ->unique()
            ->filter()
            ->toArray();
        return Classes::query()
            ->whereIn('id', $classes_id)
            ->get(['id as value','classes_name as label'])
            ->toArray();
    }

}

<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Classes;
use DagaSmart\School\Models\Grade;
use DagaSmart\School\Models\School;
use DagaSmart\School\Models\Student;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Query\Builder;

/**
 * 基础-学生表
 *
 * @method Student getModel()
 * @method Student|Builder query()
 */
class StudentService extends AdminService
{
	protected string $modelName = Student::class;

    public function listQuery()
    {
        $request = request();
        $name = mb_convert_encoding($request->name, 'UTF-8', 'auto') ?? null;
        $school_name = $request->school_name ?? null;
        $school_id = $request->school_id ?? null;
        $gender = $request->gender ?? null;

        return $this->query()
            ->with([
                'school',
//                'classes' => function($query) {
//                    $query->with(['school']);
//                }
            ])
            ->whereNull('deleted_at')
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($name, function ($query) use ($name) {
                $query->where('student_name', 'like', "%{$name}%");
            })
            ->when($school_id, function($query) use ($school_id) {
                $query->whereHas('school', function($query) use ($school_id) {
                    $query->whereIn('id', explode(',', $school_id));
                });
            })
            ->when($school_name, function($query) use ($school_name) {
                $query->whereHas('school', function($query) use ($school_name) {
                    $query->where('school_name', 'like', "%{$school_name}%");
                });
            });

    }

    /**
     * 学校列表
     * @return array
     */
    public function getSchoolAll(): array
    {
        $model = new School;
        return $model->query()->whereNull('deleted_at')->get(['id as value','school_name as label'])->toArray();
    }

    /**
     * 年级列表
     * @return array
     */
    public function getGradeAll(): array
    {
        $model = new Grade;
        $data = $model->query()->get(['id as value','grade_name as label', 'grade_no as id', 'parent_id'])->toArray();
        return array2tree($data);
    }

    /**
     * 班级列表
     * @return array
     */
    public function getClassesAll(): array
    {
        $model = new Classes;
        return $model->query()->get(['id as value','class_name as label'])->toArray();
    }

}

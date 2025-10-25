<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Classes;
use DagaSmart\School\Models\Grade;
use DagaSmart\School\Models\School;
use DagaSmart\School\Models\SchoolGradeClassesStudent;
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


    public function sortable($query): void
    {
        if (request()->orderBy && request()->orderDir) {
            $query->orderBy(request()->orderBy, request()->orderDir ?? 'asc');
        } else {
            $query->orderBy($this->primaryKey(), 'asc');
        }
    }

    public function saving(&$data, $primaryKey = ''): void
    {
        $params = [
            'school_id' => $data['school_id'],
            'grade_id' => $data['grade_id'],
            'classes_id' => $data['classes_id'],
            'student_id' => $data['id']
        ];
        admin_transaction(function () use ($params) {
            SchoolGradeClassesStudent::query()->where('student_id', $params['student_id'])->delete();
            SchoolGradeClassesStudent::query()->insert($params);
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

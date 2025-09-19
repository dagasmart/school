<?php

namespace DagaSmart\School\Services;

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
            ->with(['school','class'])
            ->whereNull('deleted_at')
            ->when($gender, function ($query) use ($gender) {
                $query->where('gender', $gender);
            })
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
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

    public function getSchoolData(): array
    {
        $model = new School;
        return $model->query()->whereNull('deleted_at')->get(['id as value','school_name as label'])->toArray();
    }

}

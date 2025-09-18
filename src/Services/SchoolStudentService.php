<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\School;
use DagaSmart\School\Models\SchoolStudent;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Query\Builder;

/**
 * 基础-学生表
 *
 * @method SchoolStudent getModel()
 * @method SchoolStudent|Builder query()
 */
class SchoolStudentService extends AdminService
{
	protected string $modelName = SchoolStudent::class;

    public function listQuery()
    {
        $request = request();
        $name = mb_convert_encoding($request->name, 'UTF-8', 'auto') ?? null;
        $school_name = $request->school_name ?? null;
        $school_id = $request->school_id ?? null;
        $gender = $request->gender ?? null;

        return $this->query()
            ->with(['school','class'])
            ->whereNull('deletetime')
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
        return $model->query()->whereNull('deletetime')->get(['id as value','school_name as label'])->toArray();
    }

}

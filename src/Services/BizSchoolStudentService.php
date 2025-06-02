<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchoolStudent;
use DagaSmart\BizAdmin\Services\AdminService;

/**
 * 基础-学生表
 *
 * @method BizSchoolStudent getModel()
 * @method BizSchoolStudent|\Illuminate\Database\Query\Builder query()
 */
class BizSchoolStudentService extends AdminService
{
	protected string $modelName = BizSchoolStudent::class;

    public function listQuery()
    {
        $request = request();
        $name = mb_convert_encoding($request->name, 'UTF-8', 'auto') ?? null;
        $school_name = $request->school_name ?? null;

        return $this->query()
            ->with('school')
            ->when($name, function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($school_name, function($query) use ($school_name) {
                $query->whereHas('school', function($query) use ($school_name) {
                    $query->where('school_name', 'like', "%{$school_name}%");
                });
            });
    }

}

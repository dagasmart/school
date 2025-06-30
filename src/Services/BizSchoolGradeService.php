<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchoolGrade;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;


/**
 * 基础-学生表
 *
 * @method BizSchoolGrade getModel()
 * @method BizSchoolGrade|Builder query()
 */
class BizSchoolGradeService extends AdminService
{
	protected string $modelName = BizSchoolGrade::class;

    public function listQuery()
    {
        $request = request();
        $name = $request->name ?? null;
        $school_id = $request->school_id ?? null;

        return $this->query()->with('school')
            ->when($name, function ($query) use (&$name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($school_id, function($query) use (&$school_id) {
                $query->whereHas('school', function($query) use (&$school_id) {
                    $query->where('id', $school_id);
                });
            })
            ->whereNotNull('school_id')
            ->orderBy('school_id')
            ->orderBy('id');
    }

    /**
     * 学校列表
     */
    public function schoolData(): Collection
    {
        return $this->getModel()->schoolData();
    }

}

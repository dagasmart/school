<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Grade;
use DagaSmart\School\Models\School;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Query\Builder;

/**
 * 基础-学校表
 *
 * @method School getModel()
 * @method School|Builder query()
 */
class SchoolService extends AdminService
{
	protected string $modelName = School::class;


    public function addRelations($query, string $scene = 'list'): void
    {
        //$query->with('authorize');
    }

    public function sortable($query): void
    {
        if (request()->orderBy && request()->orderDir) {
            $query->orderBy(request()->orderBy, request()->orderDir ?? 'asc');
        } else {
            $query->orderBy($this->getModel()->getKeyName(), 'asc');
        }
    }

    public function saving(&$data, $primaryKey = ''): void
    {
        if (!empty($data['school_grade'])) {
            //学段年级
            $school_grade = explode(',', $data['school_grade']);
            //获取年级学段
            $parent = Grade::query()
                ->whereIn('id', $school_grade)
                ->distinct()
                ->pluck('parent_id')
                ->toArray();
            $data['school_grade'] = admin_sort(array_merge($parent, $school_grade), 'desc');
        }
        //地区代码
        $data['region'] = is_array($data['region']) ? $data['region']['code'] : $data['region'];
        //注册时间
        $data['register_time'] = strtotime($data['register_time']);
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

}

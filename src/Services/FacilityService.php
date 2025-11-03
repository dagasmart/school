<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Facility;
use DagaSmart\BizAdmin\Services\AdminService;
use DagaSmart\School\Models\SchoolGradeClasses;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-学生表
 *
 * @method Facility getModel()
 * @method Facility|Builder query()
 */
class FacilityService extends AdminService
{
	protected string $modelName = Facility::class;

    public function loadRelations($query): void
    {
        $query->with(['school','rel']);
    }

    public function sortable($query): void
    {
        if (request()->orderBy && request()->orderDir) {
            $query->orderBy(request()->orderBy, request()->orderDir ?? 'asc');
        } else {
            $query->orderBy($this->primaryKey(), 'asc');
        }
    }

    /**
     * 新增或修改后更新关联数据
     * @param $model
     * @param bool $isEdit
     * @return void
     */
    public function saved($model, $isEdit = false): void
    {
        parent::saved($model, $isEdit);
        $request = request()->all();
        $data = [
            'school_id' => $request['school_id'],
            'grade_id' => $request['grade_id'],
            'classes_id' => $model->id
        ];
        admin_transaction(function () use ($data) {
            if ($data['classes_id']) {
            SchoolGradeClasses::query()->where('classes_id', $data['classes_id'])->delete();
            }
            SchoolGradeClasses::query()->insert($data);
        });
    }

    /**
     * 学校列表
     */
    public function getSchoolAll(): array
    {
        return (new StudentService)->getSchoolAll();
    }

}

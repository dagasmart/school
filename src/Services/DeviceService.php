<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Device;
use DagaSmart\School\Models\SchoolFacilityDevice;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-设备服务类
 *
 * @method Device getModel()
 * @method Device|Builder query()
 */
class DeviceService extends AdminService
{
	protected string $modelName = Device::class;

    public function loadRelations($query): void
    {
        $query->with(['rel']);
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
            'facility_id' => $request['facility_id'],
            'device_id' => $model->id,
        ];
        admin_transaction(function () use ($data) {
            if ($data['device_id']) {
                SchoolFacilityDevice::query()->where($data)->delete();
            }
            SchoolFacilityDevice::query()->insert($data);
        });
    }

    /**
     * 学校列表
     */
    public function getSchoolAll(): array
    {
        return (new SchoolService)->query()
            ->select(['id as value', 'school_name as label', 'id'])
            ->get()
            ->toArray();
    }

    /**
     * 递归选择项
     * @return array
     */
    public function options(): array
    {
        $id = request()->id;
        $school_id = request()->school_id;
        $data = $this->query()->from('biz_facility as a')
            ->join('biz_school_facility as b','a.id','=','b.facility_id')
            ->select(['a.id as value', 'a.facility_name as label', 'a.id', 'a.parent_id'])
            ->when($school_id, function($query) use ($school_id) {
                $query->where('b.school_id', $school_id);
            })
            ->when($id, function($query) use ($id) {
                $query->where('b.facility_id', '<>', $id);
            })
            ->get()
            ->toArray();
        return array2tree($data);
    }

}

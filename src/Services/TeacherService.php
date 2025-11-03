<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Department;
use DagaSmart\School\Models\Job;
use DagaSmart\School\Models\School;
use DagaSmart\School\Models\Teacher;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-学生表
 *
 * @method Teacher getModel()
 * @method Teacher|Builder query()
 */
class TeacherService extends AdminService
{
	protected string $modelName = Teacher::class;

    public function loadRelations($query): void
    {
        $query->with(['school','rel','combo']);
    }

    public function searchable($query): void
    {
        parent::searchable($query);
        $query->whereHas('school', function (Builder $builder) {
            $school_id = request('school_id');
            $builder->when($school_id, function (Builder $builder) use (&$school_id) {
                if (!is_array($school_id)) {
                    $school_id = explode(',', $school_id);
                }
                $builder->whereIn('school_id', $school_id);
            });
            $department_id = request('department_id');
            $builder->when($department_id, function (Builder $builder) use (&$department_id) {
                if (!is_array($department_id)) {
                    $department_id = explode(',', $department_id);
                }
                $builder->whereIn('department_id', $department_id);
            });
            $job_id = request('job_id');
            $builder->when($job_id, function (Builder $builder) use (&$job_id) {
                if (!is_array($job_id)) {
                    $job_id = explode(',', $job_id);
                }
                $builder->whereIn('job_id', $job_id);
            });
        });
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
        if (is_repeat($data['combo'])) {
            admin_abort('学校信息12：部门或职务选项有重叠，请修改或删除');
        }
        //地区代码
        $region_id = $data['region_id'] ?? null;
        if ($region_id) {
            if (is_array($data['region_id'])) {
                $data['region_id'] = $data['region_id']['code'];
            }
        }
        //手机号码
        $mobile = $data['mobile'] ?? null;
        if ($mobile && strpos($mobile, '*')) {
            unset($data['mobile']);
        }
        //身份证号
        $id_card = $data['id_card'] ?? null;
        if ($id_card && strpos($id_card, '*')) {
            unset($data['id_card']);
        }
        //模块
        if (admin_current_module()) {
            $data['module'] = admin_current_module();
        }
        //商户
        if (admin_mer_id()) {
            $data['mer_id'] = admin_mer_id();
        }
    }

    /**
     * 更新数据
     */
    public function update($primaryKey, $data): bool
    {
        return admin_transaction(function () use ($primaryKey, $data) {
            $schoolJobs = [];
            if ($data['combo']) {
                if (is_repeat($data['combo'])) {
                    admin_abort('学校信息：部门或职务选项有重叠，请修改或删除');
                }
                array_walk($data['combo'], function ($item) use (&$schoolJobs) {
                    $school_id = $item['school_id'];
                    $department_id = $item['department_id'];
                    $teacher_id = $item['teacher_id'];
                    $jobs = explode(',', $item['job_id']);
                    array_walk($jobs, function ($value) use (&$schoolJobs, $school_id, $department_id, $teacher_id) {
                        $schoolJobs[] = [
                            'school_id' => $school_id,
                            'department_id' => $department_id,
                            'teacher_id' => $teacher_id,
                            'job_id' => $value
                        ];
                    });
                });
            }
            unset($data['school']);
            $model = $this->getModel()->query()->where(['id' => $data['id']])->first();
            //$model->jobs()->forceDelete();
            $model->jobs()->sync($schoolJobs);
            return parent::update($primaryKey, $data);
        });
    }

    /**
     * 学校列表
     */
    public function schoolData(): \Illuminate\Support\Collection
    {
        return $this->getModel()->schoolData();
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
     * 部门列表
     * @return array
     */
    public function getDepartmentAll(): array
    {
        $model = new Department;
        $res = $model->query()
            ->select(admin_raw('*, department_name as label, id as value'))
            ->orderBy('sort')
            ->get()
            ->toArray();
        return array2tree($res, 0);
    }

    /**
     * 职务列表
     */
    public function getJobAll(): array
    {
        $list = Job::query()
            ->select(admin_raw('*, job_name as label, id as value'))
            ->orderBy('sort')
            ->get()
            ->toArray();
        return array2tree($list, 0);
    }

}

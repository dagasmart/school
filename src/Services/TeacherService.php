<?php

namespace DagaSmart\School\Services;

use DagaSmart\School\Models\Job;
use DagaSmart\School\Models\School;
use DagaSmart\School\Models\Teacher;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;


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
        //Job::initialize();
        $query->with(['school','job']);
    }

    public function searchable($query): void
    {
        parent::searchable($query);
        $query->whereHas('school', function (Builder $builder) {
            $school = request('school');
            $builder->when($school, function (Builder $builder) use (&$school) {
                $builder->whereIn('school_id', explode(',', $school));
            });
            $job_id = request('job_id');
            $builder->when($job_id, function (Builder $builder) use (&$job_id) {
                $builder->whereIn('job_id', explode(',', $job_id));
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
        //地区代码
        $data['region_id'] = is_array($data['region_id']) ? $data['region_id']['code'] : $data['region_id'];
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
    }

    /**
     * 更新数据
     */
    public function update($primaryKey, $data): bool
    {
        return admin_transaction(function () use ($primaryKey, $data) {
            $schoolJobs = [];
            if ($data['school']) {
                array_walk($data['school'], function ($item) use (&$schoolJobs) {
                    $school_id = $item['school_id'];
                    $teacher_id = $item['teacher_id'];
                    $jobs = explode(',', $item['job_id']);
                    array_walk($jobs, function ($value) use (&$schoolJobs, $school_id, $teacher_id) {
                        $schoolJobs[] = [
                            'school_id' => $school_id,
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
     * 职务列表
     */
    public function jobOption(): array
    {
        $list = Job::query()
            ->select(admin_raw('*, label_name as label, id as value'))
            ->orderBy('sort')
            ->get()
            ->toArray();
        return array2tree($list, 0);
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
    
}

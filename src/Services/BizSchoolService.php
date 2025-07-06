<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchool;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Query\Builder;

/**
 * 基础-学校表
 *
 * @method BizSchool getModel()
 * @method BizSchool|Builder query()
 */
class BizSchoolService extends AdminService
{
	protected string $modelName = BizSchool::class;


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
        $data['region'] = is_array($data['region']) ? $data['region']['code'] : $data['region'];
        $data['register_time'] = strtotime($data['register_time']);
    }

}

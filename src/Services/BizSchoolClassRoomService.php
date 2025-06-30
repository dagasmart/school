<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchoolClassRoom;
use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;


/**
 * 基础-学生表
 *
 * @method BizSchoolClassRoom getModel()
 * @method BizSchoolClassRoom|Builder query()
 */
class BizSchoolClassRoomService extends AdminService
{
	protected string $modelName = BizSchoolClassRoom::class;

    public function listQuery()
    {
        return $this->query()->with(['bind' => function ($query) {
            $query->select('school_id','staff_id')->orderBy('school_id','asc');
        }]);
    }

    /**
     * 学校列表
     */
    public function schoolData()
    {
        return $this->getModel()->schoolData();
    }

}

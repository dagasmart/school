<?php

namespace DagaSmart\School\Services;

use DagaSmart\BizAdmin\Services\AdminService;
use Illuminate\Database\Eloquent\Builder;
use DagaSmart\School\Models\Stage;


/**
 * 基础-学校表
 *
 * @method Stage getModel()
 * @method Stage|Builder query()
 */
class StageService extends AdminService
{
	protected string $modelName = Stage::class;

    /**
     * 学校学段列表
     * @return array
     */
    public function getStageAll(): array
    {
        return $this->getModel()
            ->query()
            ->orderBy('sort')
            ->get(['id as value','stage_name as label', 'id', 'parent_id'])
            ->toArray();
    }

}

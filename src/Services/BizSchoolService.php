<?php

namespace Biz\School\Services;

use Biz\School\Models\BizSchool;
use DagaSmart\BizAdmin\Services\AdminService;

/**
 * 基础-学校表
 *
 * @method BizSchool getModel()
 * @method BizSchool|\Illuminate\Database\Query\Builder query()
 */
class BizSchoolService extends AdminService
{
	protected string $modelName = BizSchool::class;
}

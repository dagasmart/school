<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;

/**
 * 基础模型
 */
class BizSchoolNation extends Model
{

	protected $table = 'fa_nation';
    protected $primaryKey = 'id';

    public $timestamps = false;


}

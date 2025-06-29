<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;

/**
 * 基础-学生表
 */
class BizSchoolClass extends Model
{
	//use SoftDeletes;

	protected $table = 'fa_school_class';
    protected $primaryKey = 'id';

    public $timestamps = false;


}

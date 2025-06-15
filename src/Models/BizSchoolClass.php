<?php

namespace Biz\School\Models;


use DagaSmart\BizAdmin\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\hasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 基础-学生表
 */
class BizSchoolClass extends Model
{
	//use SoftDeletes;

    protected $connection = 'biz'; // 使用业务数据库连接

	protected $table = 'fa_school_class';
    protected $primaryKey = 'id';

    public $timestamps = false;


}

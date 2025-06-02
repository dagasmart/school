<?php

namespace Biz\School\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DagaSmart\BizAdmin\Models\BaseModel as Model;

/**
 * 基础-学校表
 */
class BizSchool extends Model
{
	//use SoftDeletes;

    protected $connection = 'biz'; // 使用业务数据库连接

	protected $table = 'fa_school';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setRegisterTimeAttribute($value): string
    {
        return date('Y-m-d', $value);
    }

}

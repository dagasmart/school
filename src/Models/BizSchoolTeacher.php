<?php

namespace Biz\School\Models;


use DagaSmart\BizAdmin\Models\BaseModel as Model;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 基础-学生表
 */
class BizSchoolTeacher extends Model
{
	//use SoftDeletes;

    protected $connection = 'biz'; // 使用业务数据库连接

	protected $table = 'fa_school_staff';
    protected $primaryKey = 'id';

    public $timestamps = false;

    public function bind(): hasMany
    {
        return $this->hasMany(BizSchoolTeacherRelation::class, 'staff_id', 'id')
            ->with(['school' => function ($query) {
                $query->select('id','school_name');
            }]);
    }


}

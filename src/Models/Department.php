<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学生表
 */
class Department extends Model
{

	protected $table = 'biz_department';
    protected $primaryKey = 'id';

    public $timestamps = true;


    public function rel(): hasOne
    {
        return $this->hasOne(SchoolGradeClasses::class)->with(['grade','school']);
    }

}

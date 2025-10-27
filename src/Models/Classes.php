<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * 基础-学生表
 */
class Classes extends Model
{

	protected $table = 'biz_classes';
    protected $primaryKey = 'id';

    public $timestamps = false;


    public function school(): hasOne
    {
        return $this->hasOne(SchoolGradeClasses::class)->with(['grade','school']);
    }

}

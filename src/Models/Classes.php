<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 基础-学生表
 */
class Classes extends Model
{

	protected $table = 'biz_classes';
    protected $primaryKey = 'id';

    public $timestamps = true;


    public function rel(): hasOne
    {
        return $this->hasOne(SchoolGradeClasses::class)->with(['grade','school']);
    }

    public function school(): HasOne
    {
        return $this->hasOne(SchoolGradeClasses::class,
            'classes_id',
            'id'
        );
    }


}

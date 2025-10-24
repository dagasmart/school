<?php

namespace DagaSmart\School\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * 基础-学生表
 */
class Grade extends Model
{

	protected $table = 'biz_grade';
    protected $primaryKey = 'id';

    public $timestamps = false;


}

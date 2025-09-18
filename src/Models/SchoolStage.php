<?php

namespace DagaSmart\School\Models;

use DagaSmart\BizAdmin\Models\BizModel as Model;

/**
 * 基础-学段模型
 */
class SchoolStage extends Model
{

	protected $table = 'fa_school_stage';
    protected $primaryKey = 'id';

    public $timestamps = false;

}

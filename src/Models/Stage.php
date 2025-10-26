<?php

namespace DagaSmart\School\Models;


/**
 * 基础-学段模型
 */
class Stage extends Model
{

	protected $table = 'biz_stage';
    protected $primaryKey = 'id';

    public $timestamps = false;

}

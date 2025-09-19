<?php

namespace DagaSmart\School\Models;


/**
 * 基础-学段模型
 */
class Stage extends Model
{

	protected $table = 'fa_stage';
    protected $primaryKey = 'id';

    public $timestamps = false;

}

<?php

namespace DagaSmart\School\Models;

use DagaSmart\BizAdmin\Models\BaseModel;
use DagaSmart\BizAdmin\Scopes\ActiveScope;

/**
 *基座模型
 */
class Model extends BaseModel
{

    protected $connection = 'school'; // 使用school数据库连接

    public function __construct()
    {
        if(!isset($this->connection)){
            $this->setConnection($this->connection);
        }
        parent::__construct();
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveScope('school'));
        parent::booted();
    }

}

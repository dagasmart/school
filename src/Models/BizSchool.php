<?php

namespace Biz\School\Models;

use DagaSmart\BizAdmin\Models\BusModel as Model;

/**
 * 基础-学校表
 */
class BizSchool extends Model
{

	protected $table = 'biz_school';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function setRegisterTimeAttribute($value): string
    {
        return date('Y-m-d', $value);
    }

    public function getSchoolLogoAttribute($value)
    {
        extract(parse_url($value));
        if (!isset($scheme)) {
            $app_url = config('app.url');
            if (mb_substr($app_url, 1, -1) == '/' || mb_substr($value, 0, 1) == '/') {
                $app_url .= 'storage';
            } else {
                $app_url .= '/storage/';
            }
            $value = $app_url . $value;
        }
        return $value;
    }

}

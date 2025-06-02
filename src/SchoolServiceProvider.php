<?php

namespace Biz\School;

use DagaSmart\BizAdmin\Renderers\TextControl;
use DagaSmart\BizAdmin\Extend\ServiceProvider;

class SchoolServiceProvider extends ServiceProvider
{

    protected $menu = [
        [
            'parent' => NULL,
            'title' => '基础维护',
            'url' => '/biz',
            'url_type' => 1,
            'icon' => 'carbon:calendar-settings',
        ],
        [
            'parent' => '基础维护',
            'title' => '学校管理',
            'url' => '/biz/school/index',
            'url_type' => 1,
            'icon' => 'teenyicons:school-outline',
        ],
        [
            'parent' => '基础维护',
            'title' => '老师管理',
            'url' => '/biz/school/teacher',
            'url_type' => 1,
            'icon' => 'la:chalkboard-teacher',
        ],
        [
            'parent' => '基础维护',
            'title' => '学生管理',
            'url' => '/biz/school/student',
            'url_type' => 1,
            'icon' => 'ph:student-light',
        ],
        [
            'parent' => '基础维护',
            'title' => '年级管理',
            'url' => '/biz/school/grade',
            'url_type' => 1,
            'icon' => 'eos-icons:package-upgrade-outlined',
        ],
        [
            'parent' => '基础维护',
            'title' => '班级管理',
            'url' => '/biz/school/classroom',
            'url_type' => 1,
            'icon' => 'simple-icons:googleclassroom',
        ],

    ];


	public function settingForm()
	{
	    return $this->baseSettingForm()->body([
            TextControl::make()->name('value')->label('Value')->required(true),
	    ]);
	}
}

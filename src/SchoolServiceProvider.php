<?php

namespace DagaSmart\School;

use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\TextControl;
use DagaSmart\BizAdmin\Extend\ServiceProvider;
use Exception;

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
            'title' => '班级管理',
            'url' => '/biz/school/classes',
            'url_type' => 1,
            'icon' => 'simple-icons:googleclassroom',
        ],

    ];


    /**
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        parent::register();

        /**加载路由**/
        parent::registerRoutes(__DIR__.'/Http/routes.php');
        /**加载语言包**/
        if ($lang = parent::getLangPath()) {
            $this->loadTranslationsFrom($lang, $this->getCode());
        }
    }


	public function settingForm(): Form
    {
	    return $this->baseSettingForm()->body([
            TextControl::make()->name('value')->label('Value')->required(),
	    ]);
	}
}

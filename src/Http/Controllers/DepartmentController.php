<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\School\Enums\Enum;
use DagaSmart\School\Services\DepartmentService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-部门表
 *
 * @property DepartmentService $service
 */
class DepartmentController extends AdminController
{
	protected string $serviceName = DepartmentService::class;

	public function list(): Page
    {
		return $this->baseList([]);
	}

	public function form($isEdit = false): Form
    {
		return $this->baseForm()->body();
	}

	public function detail(): Form
    {
		return $this->baseDetail()->body()->static();
	}


}

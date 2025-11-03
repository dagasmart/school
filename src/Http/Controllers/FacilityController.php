<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\School\Services\FacilityService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;


/**
 * 基础-班级表
 *
 * @property FacilityService $service
 */
class FacilityController extends AdminController
{
	protected string $serviceName = FacilityService::class;

	public function list(): Page
    {
		$crud = $this->baseCRUD()
			->filterTogglable(false)
			->headerToolbar([
				$this->createButton('dialog',250),
				...$this->baseHeaderToolBar()
			])
            ->autoGenerateFilter()
            ->affixHeader()
            ->columnsTogglable()
            ->footable(['expand' => 'first'])
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('rel.school.school_name', '学校')
                    ->searchable([
                        'name' => 'school_id',
                        'type' => 'select',
                        'multiple' => false,
                        'searchable' => true,
                        'options' => $this->service->getSchoolAll(),
                    ])
                    ->width(200),
                amis()->TableColumn('facility_name', '设施名称')->width(200),
                amis()->TableColumn('facility_desc','设施描述'),
                amis()->TableColumn('status', '状态')
                    ->set('type','status'),
                amis()->TableColumn('updated_at', '更新时间')->type('datetime')->sortable()->width(150),
                $this->rowActions('dialog',250)
                    ->set('align','center')
                    ->set('fixed','right')
                    ->set('width',150)
            ]);

		return $this->baseList($crud);
	}

	public function form($isEdit = false): Form
    {
		return $this->baseForm()->body([
            amis()->SelectControl('school_id', '学校')
                ->options($this->service->getSchoolAll())
                ->value('${rel.school.id}')
                ->searchable()
                ->clearable()
                ->required(),
            amis()->TextControl('facility_name', '设施名称')
                ->clearable()
                ->required(),
            amis()->SwitchControl('status','状态')
                ->onText('开启')
                ->offText('禁用')
                ->value(true),
		]);
	}

	public function detail(): Form
    {
		return $this->baseDetail()->body([
            amis()->StaticExactControl('id','ID')->visibleOn('${id}'),
            amis()->SelectControl('rel.school_id', '学校')
                ->options($this->service->getSchoolAll())
                ->searchable()
                ->clearable()
                ->required(),
            amis()->SelectControl('grade_id', '设施名称')
                ->clearable()
                ->required(),
		])->static();
	}


}

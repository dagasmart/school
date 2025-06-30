<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Services\BizSchoolGradeService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-学校表
 *
 * @property BizSchoolGradeService $service
 */
class BizSchoolGradeController extends AdminController
{
	protected string $serviceName = BizSchoolGradeService::class;

	public function list(): Page
    {
		$crud = $this->baseCRUD()
			->filterTogglable(false)
			->headerToolbar([
				$this->createButton('dialog','sm'),
				...$this->baseHeaderToolBar()
			])
            ->autoGenerateFilter()
            ->affixHeader()
            ->columnsTogglable()
            ->footable(['expand' => 'first'])
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('name', '年级名称')->searchable()->set('fixed','left'),
                amis()->TableColumn('school_id', '所属学校')->sortable()
                    ->searchable(['type'=>'select', 'searchable'=>true, 'options'=>$this->service->schoolData()])
                    //->breakpoint('*')
                    ->set('type','tpl')
                    ->tpl('${school.school_name}<font color=lightblue>[ID_${school_id}]</font>')
                    ->set('fixed','left'),
                $this->rowActions('dialog', 'sm')
                    ->set('align','center')
                    ->set('fixed','right')
                    ->set('width',150)
            ]);

		return $this->baseList($crud);
	}

	public function form($isEdit = false): Form
    {
		return $this->baseForm()->body([
            amis()->TextControl('id', 'ID')->visible($isEdit)->static($isEdit),
            amis()->TextControl('name', '年级名称'),
            amis()->SelectControl('school.school_name', '所属学校')
                ->options($this->service->schoolData())
                ->static($isEdit)
		]);
	}

	public function detail(): Form
    {
		return $this->baseDetail()->body([
			amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('name', '年级名称')->static(),
			amis()->TextControl('school_id', '学校id')->static(),
			amis()->TextControl('school.school_name', '所属学校')->static()

		]);
	}
}

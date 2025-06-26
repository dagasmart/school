<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Services\BizSchoolService;
use DagaSmart\BizAdmin\Controllers\AdminController;

/**
 * 基础-学校表
 *
 * @property BizSchoolService $service
 */
class BizSchoolController extends AdminController
{
	protected string $serviceName = BizSchoolService::class;

	public function list()
	{
		$crud = $this->baseCRUD()
			->filterTogglable()
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar()
			])
            ->filter($this->baseFilter()->body([
                amis()->TextControl('username', admin_trans('admin.username'))
                    ->size('md')
                    ->clearable()
                    ->placeholder(admin_trans('admin.admin_user.search_username')),
                amis()->TextControl('name', admin_trans('admin.admin_user.name'))
                    ->size('md')
                    ->clearable()
                    ->placeholder(admin_trans('admin.admin_user.search_name')),
                amis()->SelectControl('enabled', admin_trans('admin.extensions.card.status'))
                    ->size('md')
                    ->clearable()
                    ->options([
                        ['label' => admin_trans('admin.extensions.enable'), 'value' => 1],
                        ['label' => admin_trans('admin.extensions.disable'), 'value' => 0],
                    ]),
                amis()->DateRangeControl('created_at', admin_trans('admin.created_at'))
                    ->format('YYYY-MM-DD')
                    ->clearable(true)
            ]))
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('school_code', '学校代码')
                    ->sortable()
                    ->searchable()
                    ->set('fixed','left'),
                amis()->TableColumn('school_name', '学校名称')
                    ->searchable()
                    ->width(200)
                    ->set('fixed','left'),
                amis()->TableColumn('area_id', '所属地区id')
                    ->searchable(['name'=>'area_id','type'=>'input-city'])
                    ->quickEdit(['type'=>'input-city','value'=>'${area_id}'])
                    ->set('type','input-city')
                    ->set('static',true)
                    ->sortable(),
                amis()->TableColumn('contacts_mobile', '联系电话')->searchable(),
                amis()->TableColumn('contacts_email', '联系邮件')->searchable(),
                amis()->TableColumn('type', '学校类型'),
                amis()->TableColumn('map_address', '学校地址')->searchable()->width(200),
                amis()->TableColumn('location', '位置定位'),
                amis()->TableColumn('register_time', '注册日期')
                    ->width(120)
                    ->sortable()
                    ->quickEdit(['type'=>'input-date','value'=>'${register_time}']),
                amis()->TableColumn('credit_code', '信用代码'),
                amis()->TableColumn('legal_person', '学校法人'),
//                amis()->TableColumn('created_at', admin_trans('admin.created_at'))
//                    ->width(100)
//                    ->type('datetime')
//                    ->sortable(),
//                amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))
//                    ->width(100)
//                    ->type('datetime')
//                    ->sortable(),
                $this->rowActions('dialog')->width(200)->align('center')->set('fixed','right')
            ]);

		return $this->baseList($crud);
	}

	public function form($isEdit = false)
	{
		return $this->baseForm()->body([
			amis()->TextControl('school_code', '学校代码'),
			amis()->TextControl('school_name', '学校名称'),
			amis()->TextControl('school_logo', '学校标志'),
			amis()->TextControl('area_id', '所属地区id'),
			amis()->TextControl('contacts_mobile', '联系电话'),
			amis()->TextControl('contacts_email', '联系邮件'),
			amis()->TextControl('type', '学校类型'),
			amis()->TextControl('map_address', '学校地址'),
			amis()->TextControl('location', '位置定位'),
			amis()->TextControl('register_time', '注册日期'),
			amis()->TextControl('credit_code', '信用代码'),
			amis()->TextControl('legal_person', '学校法人'),
		]);
	}

	public function detail()
	{
		return $this->baseDetail()->body([
			amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('school_code', '学校代码')->static(),
			amis()->TextControl('school_name', '学校名称')->static(),
			amis()->TextControl('school_logo', '学校标志')->static(),
			amis()->TextControl('area_id', '所属地区id')->static(),
			amis()->TextControl('contacts_mobile', '联系电话')->static(),
			amis()->TextControl('contacts_email', '联系邮件')->static(),
			amis()->TextControl('type', '学校类型')->static(),
			amis()->TextControl('map_address', '学校地址')->static(),
			amis()->TextControl('location', '位置定位')->static(),
			amis()->TextControl('register_time', '注册日期')->static(),
			amis()->TextControl('credit_code', '信用代码')->static(),
			amis()->TextControl('legal_person', '学校法人')->static(),
//			amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
//			amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
		]);
	}
}

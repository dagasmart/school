<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Services\BizSchoolTeacherService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-学校表
 *
 * @property BizSchoolTeacherService $service
 */
class BizSchoolTeacherController extends AdminController
{
	protected string $serviceName = BizSchoolTeacherService::class;

	public function list(): Page
    {
		$crud = $this->baseCRUD()
			->filterTogglable(false)
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar()
			])
            //->autoFillHeight(true)
            ->autoGenerateFilter()
            ->affixHeader()
            ->columnsTogglable()
            ->footable(['expand' => 'first'])
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('name', '老师姓名')->sortable()->searchable()->set('fixed','left'),
                amis()->TableColumn('bind.school', '所属学校')
                    ->searchable()
                    ->set('fixed','left')
                    ->breakpoint('*'),
                amis()->TableColumn('duties','教师职务')->sortable(),
                amis()->TableColumn('staff_sn','教师编码')->sortable(),
                amis()->TableColumn('school_logo', '老师照片')->set('type','images'),
                amis()->TableColumn('id_number', '身份证号')->searchable(),
                amis()->TableColumn('mobile', '联系电话')->searchable(),
//                amis()->TableColumn('area_id', '所属地区id')
//                    ->searchable(['name'=>'area_id','type'=>'input-city'])
//                    ->quickEdit(['type'=>'input-city','value'=>'${area_id}'])
//                    ->set('type','input-city')
//                    ->set('static',true)
//                    ->sortable(),
                amis()->TableColumn('alipay_user_id', '支付宝刷脸账号')->searchable(),
                amis()->TableColumn('createtime', '创建时间'),
                amis()->TableColumn('createtime', '更新时间'),
                $this->rowActions('dialog')->width(100)->align('center')->set('fixed','right')
            ])
            ->affixRow([
                [
                    'type' => 'text',
                    'text' => '总计',
                    "colSpan" => 3,
                ],
                [
                    'type' => 'tpl',
                    "tpl" => '${rows|pick:mobile|sum}'
                ]
            ]);

		return $this->baseList($crud);
	}

	public function form($isEdit = false): Form
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

	public function detail(): Form
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

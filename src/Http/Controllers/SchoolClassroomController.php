<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\School\Enums\Enum;
use DagaSmart\School\Services\SchoolClassroomService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-班级表
 *
 * @property SchoolClassroomService $service
 */
class SchoolClassroomController extends AdminController
{
	protected string $serviceName = SchoolClassroomService::class;

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
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('name', '')->sortable()->searchable()->set('fixed','left'),
                amis()->TableColumn('school_name', '所属学校')
                    ->searchable(['type'=>'select', 'searchable'=>true, 'options'=>$this->service->schoolData()])
                    //->breakpoint('*')
                    ->set('type','tpl')
                    ->tpl('${bind[0].school.school_name}')
                    ->set('fixed','left'),
                amis()->TableColumn('duties','教师职务')->sortable(),
                amis()->TableColumn('staff_sn','教师编码')->sortable(),
                amis()->TableColumn('face_img', '老师照片')
                    ->set('src','${face_img}')
                    ->set('type','avatar')
                    ->set('fit','cover')
                    ->set('size',60)
                    ->set('onError','return true;')
                    ->set('onEvent', [
                        'click' => [
                            'actions' => [
                                [
                                    'actionType' => 'drawer',
                                    'drawer' => [
                                        'title' => false,
                                        'actions' => [],
                                        'closeOnEsc' => true, //esc键关闭
                                        'closeOnOutside' => true, //域外可关闭
                                        'showCloseButton' => false, //显示关闭
                                        'body' => [
                                            amis()->Image()
                                                ->src('${face_img}')
                                                ->defaultImage('/admin-assets/no-error.svg')
                                                ->width('100%')
                                                ->height('100%'),
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]),
                amis()->TableColumn('id_number', '身份证号')->searchable(),
                amis()->TableColumn('mobile', '联系电话')->searchable(),
                amis()->TableColumn('alipay_user_id', '支付宝刷脸账号')->searchable(),
                amis()->TableColumn('updatetime', '更新时间')->type('datetime')->width(150),
                $this->rowActions('dialog')
                    ->set('align','center')
                    ->set('fixed','right')
                    ->set('width',150)
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
			amis()->SelectControl('school_id', '选择学校')
                ->options($this->service->schoolData())
                ->searchable()
                ->required(),
			amis()->TreeSelectControl('grade_id', '选择年级')
                ->options(Enum::Grade)
                ->searchable()
                ->onlyLeaf()
                ->required(),
            amis()->TextControl('class_code', '班级编码'),
            amis()->TextControl('class_name', '班级名称'),
			amis()->TextControl('leader_teacher', '班主任'),
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

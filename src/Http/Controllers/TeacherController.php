<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\BizAdmin\Renderers\DialogAction;
use DagaSmart\School\Enums\Enum;
use DagaSmart\School\Services\TeacherService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-学校表
 *
 * @property TeacherService $service
 */
class TeacherController extends AdminController
{
	protected string $serviceName = TeacherService::class;

	public function list(): Page
    {
		$crud = $this->baseCRUD()
			->filterTogglable(false)
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar(),
                $this->importAction(admin_url('teacher/import')),
                $this->exportAction(),
			])
            ->autoGenerateFilter()
            ->affixHeader()
            ->columnsTogglable()
            ->footable(['expand' => 'first'])
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('teacher_name', '老师姓名')->sortable()->searchable()->set('fixed','left'),
                amis()->TableColumn('school', '所属学校')
                    ->searchable(['type'=>'select', 'searchable'=>true, 'options'=>$this->service->schoolData()])
                    //->breakpoint('*')
                    ->set('type','input-tag')
                    ->set('options',$this->service->schoolData())
                    ->set('fixed','left')
                    ->set('static', true),
                amis()->TableColumn('job.job_name','教师职务')->sortable(),
                amis()->TableColumn('staff_sn','教师编码')->searchable()->sortable(),
                amis()->TableColumn('id_card','身份证号')->searchable()->sortable(),
                amis()->TableColumn('avatar', '老师照片')
                    ->set('src','${avatar}')
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
                                        'title' => '预览',
                                        'actions' => [],
                                        'closeOnEsc' => true, //esc键关闭
                                        'closeOnOutside' => true, //域外可关闭
                                        'showCloseButton' => true, //显示关闭
                                        'body' => [
                                            amis()->Image()
                                                ->src('${avatar}')
                                                ->defaultImage('/admin-assets/no-error.svg')
                                                ->width('100%')
                                                ->height('100%'),
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]),
                amis()->TableColumn('mobile', '联系电话')->searchable(),
//                amis()->TableColumn('area_id', '所属地区id')
//                    ->searchable(['name'=>'area_id','type'=>'input-city'])
//                    ->quickEdit(['type'=>'input-city','value'=>'${area_id}'])
//                    ->set('type','input-city')
//                    ->set('static',true)
//                    ->sortable(),
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
        return $this->baseForm()->mode('horizontal')->tabs([

            // 基本信息
            amis()->Tab()->title('基本信息')->body([

                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->TextControl('teacher_name', '姓名'),
                        amis()->TextControl('teacher_sn', '教师编码'),
                        amis()->TextControl('id_card', '身份证号'),
                        amis()->TextControl('work_sn', '工号'),
                        amis()->RadiosControl('sex', '性别')
                            ->options(Enum::sex()),
                    ]),
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->ImageControl('avatar')
                            ->thumbRatio('1:1')
                            ->thumbMode('cover h-full rounded-md overflow-hidden')
                            ->className(['overflow-hidden'=>true, 'h-full'=>true])
                            ->imageClassName([
                                'w-52'=>true,
                                'h-64'=>true,
                                'overflow-hidden'=>true
                            ])
                            ->fixedSize()
                            ->fixedSizeClassName([
                                'w-52'=>true,
                                'h-64'=>true,
                                'overflow-hidden'=>true
                            ]),
                    ]),
                ]),
                amis()->TreeSelectControl('job', '职务')
                    ->options(Enum::job())
                    ->menuTpl('<div class="flex justify-between"><span>${label}</span><span class="ml-5 bg-gray-200 rounded p-1 text-xs text-white text-center w-full">${tag}</span></div>')
                    ->multiple()
                    ->onlyLeaf()
                    ->searchable(),
                amis()->SelectControl('school', '所属学校')
                    ->options($this->service->schoolData())
                    ->multiple()
                    ->searchable(),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->SelectControl('nation_id', '民族')
                        ->options(Enum::nation()),
                    amis()->SelectControl('work_status', '工作状态')
                        ->options(Enum::WorkStatus),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->SelectControl('full_teacher', '专职老师')
                        ->options(Enum::IsFull),
                    amis()->SelectControl('work_status', '工作状态')
                        ->options(Enum::WorkStatus),
                ]),
            ]),

            // 家庭情况
            amis()->Tab()->title('家庭情况')->body([
                amis()->InputCityControl('region_id', '所在地区')
                    ->searchable()
                    ->extractValue()
                    ->required(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->TextControl('address', '家庭住址'),
                    amis()->TextControl('mobile', '联系电话'),
                ]),
                amis()->TextControl('address_info', '详细地址')
                    ->value('${region.province} ${region.city} ${region.district} ${address}')->static(),

                amis()->TextControl('mobile', '家庭成员'),
            ]),

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
			//amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
			//amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
		]);
	}

    public function importAction($api=null): DialogAction
    {
        return amis()->DialogAction()->label('一键导入')->icon('fa fa-upload')->dialog(
            amis()->Dialog()->title('一键导入')->body(
                amis()->Form()->mode('normal')->api($api)->body([
                    amis()->FileControl()->name('file')->required()->drag(),
                ]),
            )->actions([])
        );
    }
}

<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Enums\Enum;
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
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('name', '老师姓名')->sortable()->searchable()->set('fixed','left'),
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

    public function form($isEdit = false)
    {
        return $this->baseForm()->mode('horizontal')->tabs([

            // 基本信息
            amis()->Tab()->title('基本信息')->body([

                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->TextControl('name', '姓名'),
                        amis()->TextControl('staff_sn', '教师编码'),
                        amis()->TextControl('id_number', '身份证号'),
                        amis()->SelectControl('school_id', '所属学校')
                            ->options($this->service->schoolData())
                            ->searchable(),
                        amis()->TextControl('work_sn', '工号'),
                    ]),
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->ImageControl('picture')
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
                            ])
                            ->crop([
                                'aspectRatio' => '0.81',
                            ]),
                    ]),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->SelectControl('gender', '性别')
                        ->options(Enum::sex()),
                    amis()->SelectControl('nation_id', '民族')
                        ->options(Enum::nation()),
                    amis()->SelectControl('work_status', '工作状态')
                        ->options(Enum::WorkStatus),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->TextControl('duties', '职务'),
                    amis()->SelectControl('full_teacher', '专职老师')
                        ->options(Enum::IsFull),
                    amis()->SelectControl('work_status', '工作状态')
                        ->options(Enum::WorkStatus),
                ]),
            ]),

            // 家庭情况
            amis()->Tab()->title('家庭情况')->body([
                amis()->InputCityControl('region', '所在地区')
                    ->searchable()
                    ->extractValue(false)
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
//			amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
//			amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
		]);
	}
}

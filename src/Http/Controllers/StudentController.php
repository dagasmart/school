<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\School\Services\StudentService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;

/**
 * 基础-学生表
 *
 * @property StudentService $service
 */
class StudentController extends AdminController
{
	protected string $serviceName = StudentService::class;

	public function list()
	{
		$crud = $this->baseCRUD()
			->filterTogglable(true)
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar()
			])
            ->filter($this->baseFilter()->body([
                amis()->SelectControl('school_id', '学校')
                    ->multiple()
                    ->searchable()
                    ->options($this->service->getSchoolData())
                    ->clearable()
                    ->placeholder('请选择学校...')
                    ->size('lg'),
                amis()->Divider(),
                amis()->TextControl('name', '学生姓名')
                    ->clearable()
                    ->placeholder('请输入学生姓名')
                    ->size('sm'),
                amis()->TextControl('id_number', '身份证号')
                    ->clearable()
                    ->placeholder('请输入学生身份证号')
                    ->size('md'),
            ]))
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->fixed('left'),
                amis()->TableColumn('name', '姓名')->searchable()->fixed('left'),
                amis()->TableColumn('student_code', '国网学籍号')->searchable(),
                amis()->TableColumn('school.school_name', '学校')
                    ->searchable([
                        'name' => 'school_id',
                        'type' => 'select',
                        'multiple' => true,
                        'searchable' => true,
                        'options' => $this->service->getSchoolData(),
                    ])
                    ->width(200),
                amis()->TableColumn('class.name', '班级')->width('100px'),
                amis()->TableColumn('face_img', '学生照片')
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
                amis()->TableColumn('gender', '性别')
                    ->searchable([
                        'name' => 'gender',
                        'type' => 'checkboxes',
                        'options' => $this->service->getModel()->sexOption(),
                    ])
                    ->set('type', 'checkboxes')
                    ->set('options', $this->service->getModel()->sexOption())
                    ->set('static', true),
//				amis()->TableColumn('nation_id', '民族'),
                amis()->TableColumn('status', '状态'),
                amis()->TableColumn('id_number', '身份证号')->searchable(),
				amis()->TableColumn('mobile', '电话')->searchable(),
//				amis()->TableColumn('parent_mobile', '家长电话'),
//				amis()->TableColumn('live_school', '是否住校1 是 2否'),
//				amis()->TableColumn('remark', '备注'),
//				amis()->TableColumn('hk', '户口类型')->sortable(),
//				amis()->TableColumn('face_img', '人脸识别照片'),
//				amis()->TableColumn('graduate_status', '是否毕业 1是 2否'),
//				amis()->TableColumn('food_card_expire', '饭卡过期 1是 2否'),
//				amis()->TableColumn('have_face_pay', '已开通刷脸支付 1是2否'),
//				amis()->TableColumn('have_pay_photo', '已采集支付照片 1是 2否'),
//				amis()->TableColumn('is_free', '是否免缴服务费 1是 2否'),
//				amis()->TableColumn('is_pay', '缴费状态 1已缴费 2未缴费'),
//				amis()->TableColumn('pay_status', '支付状态：1=正常，2=异常(有待支付订单) , 3禁用拉黑'),
//				amis()->TableColumn('pay_status_clock', '黑名单状态锁，防止定时任务刷新状态，0未锁定，1锁定')->sortable(),
//				amis()->TableColumn('pay_status_clock_time', '锁定时间')->sortable(),
//				amis()->TableColumn('enjoy_sponsor', '是否享受资助 1是 2否'),
//				amis()->TableColumn('sponsor_money', '资助金额'),
//				amis()->TableColumn('send_sponsor_type', '发放方式'),
//				amis()->TableColumn('send_sponsor_time', '发放时间')->sortable(),
//				amis()->TableColumn('school_face_pass_status', '校园一脸通行开通状态 OPEN 开通 CLOSE关闭'),
//				amis()->TableColumn('school_face_payment_status', '校园一脸通行刷脸支付开通状态 OPEN开通 CLOSE关闭'),
//				amis()->TableColumn('school_face_data', '校园一脸通行开通返回的数据'),
//				amis()->TableColumn('end_time', '服务费截止时间'),
//				amis()->TableColumn('ali_user_id', '刷脸用户id'),
//				amis()->TableColumn('alifacepaystatus', '开通刷脸支付 0未开通，1已开通'),
//				amis()->TableColumn('alifacepayopertime', '开通刷脸支付时间')->sortable(),
//				amis()->TableColumn('day_maxpay', '日消费限额'),
				amis()->TableColumn('non_payment_num', '未支付订单数量')->sortable(),
//				amis()->TableColumn('created_at', admin_trans('admin.created_at'))->type('datetime')->sortable(),
				amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))->type('datetime')->sortable(),
				$this->rowActions('dialog')
                    ->set('align','center')
                    ->set('fixed','right')
                    ->set('width',150)
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
                        amis()->TextControl('name', '姓名'),
                        amis()->TextControl('student_code', '国网学籍号'),
                        amis()->TextControl('id_number', '身份证号'),
                        amis()->TextControl('school_id', '学校id'),
                        amis()->TextControl('class_id', '班级id'),
                    ]),
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->ImageControl('picture', false)
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
                        ->options([
                            ['value' => 1, 'label' => '男'],
                            ['value' => 2, 'label' => '女'],
                        ]),
                    amis()->TextControl('nation_id', '民族id'),
                    amis()->SelectControl('status', '状态')
                        ->options([
                            ['value' => 1, 'label' => '正常'],
                            ['value' => 2, 'label' => '请假'],
                        ]),
                ]),
            ]),

//            // 基本信息
//            amis()->Tab()->title(admin_trans('admin.code_generators.base_info'))->body([
//                amis()->GroupControl()->mode('normal')->body([
//                    amis()->TextControl('mobile', '电话'),
//                    amis()->TextControl('parent_mobile', '家长电话'),
//                    amis()->TextControl('live_school', '是否住校1 是 2否'),
//                    amis()->TextControl('remark', '备注'),
//                    amis()->TextControl('hk', '户口类型'),
//                    amis()->TextControl('face_img', '人脸识别照片'),
//                    amis()->TextControl('graduate_status', '是否毕业 1是 2否'),
//                    amis()->TextControl('food_card_expire', '饭卡过期 1是 2否'),
//                    amis()->TextControl('have_face_pay', '已开通刷脸支付 1是2否'),
//                    amis()->TextControl('have_pay_photo', '已采集支付照片 1是 2否'),
//                    amis()->TextControl('is_free', '是否免缴服务费 1是 2否'),
//                ]),
//            ]),
//
//            // 基本信息
//            amis()->Tab()->title(admin_trans('admin.code_generators.base_info'))->body([
//                amis()->GroupControl()->mode('normal')->body([
//                    amis()->TextControl('is_pay', '缴费状态 1已缴费 2未缴费'),
//                    amis()->TextControl('pay_status', '支付状态：1=正常，2=异常(有待支付订单) , 3禁用拉黑'),
//                    amis()->TextControl('pay_status_clock', '黑名单状态锁，防止定时任务刷新状态，0未锁定，1锁定'),
//                    amis()->TextControl('pay_status_clock_time', '锁定时间'),
//                    amis()->TextControl('enjoy_sponsor', '是否享受资助 1是 2否'),
//                    amis()->TextControl('sponsor_money', '资助金额'),
//                    amis()->TextControl('send_sponsor_type', '发放方式'),
//                    amis()->TextControl('send_sponsor_time', '发放时间'),
//                    amis()->TextControl('school_face_pass_status', '校园一脸通行开通状态 OPEN 开通 CLOSE关闭'),
//                    amis()->TextControl('school_face_payment_status', '校园一脸通行刷脸支付开通状态 OPEN开通 CLOSE关闭'),
//                    amis()->TextControl('school_face_data', '校园一脸通行开通返回的数据'),
//                    amis()->TextControl('end_time', '服务费截止时间'),
//                    amis()->TextControl('ali_user_id', '刷脸用户id'),
//                    amis()->TextControl('alifacepaystatus', '开通刷脸支付 0未开通，1已开通'),
//                    amis()->TextControl('alifacepayopertime', '开通刷脸支付时间'),
//                    amis()->TextControl('day_maxpay', '日消费限额'),
//                    amis()->TextControl('non_payment_num', '未支付订单数量'),
//                ]),
//            ]),

		]);
	}

	public function detail()
	{
		return $this->baseDetail()->body([
			amis()->TextControl('id', 'ID')->static(),
			amis()->TextControl('student_code', '国网学籍号')->static(),
			amis()->TextControl('class_id', '班级id')->static(),
			amis()->TextControl('school_id', '学校id')->static(),
			amis()->TextControl('name', '姓名')->static(),
			amis()->TextControl('picture', '头像')->static(),
			amis()->TextControl('gender', '性别1男 2女')->static(),
			amis()->TextControl('nation_id', '民族id')->static(),
			amis()->TextControl('status', '状态1 正常 2请假')->static(),
			amis()->TextControl('id_number', '身份证号')->static(),
			amis()->TextControl('mobile', '电话')->static(),
			amis()->TextControl('parent_mobile', '家长电话')->static(),
			amis()->TextControl('live_school', '是否住校1 是 2否')->static(),
			amis()->TextControl('remark', '备注')->static(),
			amis()->TextControl('hk', '户口类型')->static(),
			amis()->TextControl('face_img', '人脸识别照片')->static(),
			amis()->TextControl('graduate_status', '是否毕业 1是 2否')->static(),
			amis()->TextControl('food_card_expire', '饭卡过期 1是 2否')->static(),
			amis()->TextControl('have_face_pay', '已开通刷脸支付 1是2否')->static(),
			amis()->TextControl('have_pay_photo', '已采集支付照片 1是 2否')->static(),
			amis()->TextControl('is_free', '是否免缴服务费 1是 2否')->static(),
			amis()->TextControl('is_pay', '缴费状态 1已缴费 2未缴费')->static(),
			amis()->TextControl('pay_status', '支付状态：1=正常，2=异常(有待支付订单) , 3禁用拉黑')->static(),
			amis()->TextControl('pay_status_clock', '黑名单状态锁，防止定时任务刷新状态，0未锁定，1锁定')->static(),
			amis()->TextControl('pay_status_clock_time', '锁定时间')->static(),
			amis()->TextControl('enjoy_sponsor', '是否享受资助 1是 2否')->static(),
			amis()->TextControl('sponsor_money', '资助金额')->static(),
			amis()->TextControl('send_sponsor_type', '发放方式')->static(),
			amis()->TextControl('send_sponsor_time', '发放时间')->static(),
			amis()->TextControl('school_face_pass_status', '校园一脸通行开通状态 OPEN 开通 CLOSE关闭')->static(),
			amis()->TextControl('school_face_payment_status', '校园一脸通行刷脸支付开通状态 OPEN开通 CLOSE关闭')->static(),
			amis()->TextControl('school_face_data', '校园一脸通行开通返回的数据')->static(),
			amis()->TextControl('end_time', '服务费截止时间')->static(),
			amis()->TextControl('ali_user_id', '刷脸用户id')->static(),
			amis()->TextControl('alifacepaystatus', '开通刷脸支付 0未开通，1已开通')->static(),
			amis()->TextControl('alifacepayopertime', '开通刷脸支付时间')->static(),
			amis()->TextControl('day_maxpay', '日消费限额')->static(),
			amis()->TextControl('non_payment_num', '未支付订单数量')->static(),
			amis()->TextControl('created_at', admin_trans('admin.created_at'))->static(),
			amis()->TextControl('updated_at', admin_trans('admin.updated_at'))->static(),
		]);
	}
}

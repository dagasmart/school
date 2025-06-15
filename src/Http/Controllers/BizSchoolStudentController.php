<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Services\BizSchoolStudentService;
use DagaSmart\BizAdmin\Controllers\AdminController;

/**
 * 基础-学生表
 *
 * @property BizSchoolStudentService $service
 */
class BizSchoolStudentController extends AdminController
{
	protected string $serviceName = BizSchoolStudentService::class;

	public function list()
	{
		$crud = $this->baseCRUD()
			->filterTogglable(true)
			->headerToolbar([
				$this->createButton('dialog', 'lg'),
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
                amis()->TableColumn('picture', '头像')
                    ->set('type','avatar')
                    ->set('src','${picture}')
                    ->set('thumbMode','cover')
                    ->set('enlargeAble',true),
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
//				amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))->type('datetime')->sortable(),
				$this->rowActions('dialog', 'lg')->fixed('right')->width(150)
			]);

		return $this->baseList($crud);
	}

	public function form($isEdit = false)
	{
		return $this->baseForm()->body([
			amis()->TextControl('student_code', '国网学籍号'),
			amis()->TextControl('class_id', '班级id'),
			amis()->TextControl('school_id', '学校id'),
			amis()->TextControl('name', '姓名'),
			amis()->TextControl('picture', '头像'),
			amis()->TextControl('gender', '性别1男 2女'),
			amis()->TextControl('nation_id', '民族id'),
			amis()->TextControl('status', '状态1 正常 2请假'),
			amis()->TextControl('id_number', '身份证号'),
			amis()->TextControl('mobile', '电话'),
			amis()->TextControl('parent_mobile', '家长电话'),
			amis()->TextControl('live_school', '是否住校1 是 2否'),
			amis()->TextControl('remark', '备注'),
			amis()->TextControl('hk', '户口类型'),
			amis()->TextControl('face_img', '人脸识别照片'),
			amis()->TextControl('graduate_status', '是否毕业 1是 2否'),
			amis()->TextControl('food_card_expire', '饭卡过期 1是 2否'),
			amis()->TextControl('have_face_pay', '已开通刷脸支付 1是2否'),
			amis()->TextControl('have_pay_photo', '已采集支付照片 1是 2否'),
			amis()->TextControl('is_free', '是否免缴服务费 1是 2否'),
			amis()->TextControl('is_pay', '缴费状态 1已缴费 2未缴费'),
			amis()->TextControl('pay_status', '支付状态：1=正常，2=异常(有待支付订单) , 3禁用拉黑'),
			amis()->TextControl('pay_status_clock', '黑名单状态锁，防止定时任务刷新状态，0未锁定，1锁定'),
			amis()->TextControl('pay_status_clock_time', '锁定时间'),
			amis()->TextControl('enjoy_sponsor', '是否享受资助 1是 2否'),
			amis()->TextControl('sponsor_money', '资助金额'),
			amis()->TextControl('send_sponsor_type', '发放方式'),
			amis()->TextControl('send_sponsor_time', '发放时间'),
			amis()->TextControl('school_face_pass_status', '校园一脸通行开通状态 OPEN 开通 CLOSE关闭'),
			amis()->TextControl('school_face_payment_status', '校园一脸通行刷脸支付开通状态 OPEN开通 CLOSE关闭'),
			amis()->TextControl('school_face_data', '校园一脸通行开通返回的数据'),
			amis()->TextControl('end_time', '服务费截止时间'),
			amis()->TextControl('ali_user_id', '刷脸用户id'),
			amis()->TextControl('alifacepaystatus', '开通刷脸支付 0未开通，1已开通'),
			amis()->TextControl('alifacepayopertime', '开通刷脸支付时间'),
			amis()->TextControl('day_maxpay', '日消费限额'),
			amis()->TextControl('non_payment_num', '未支付订单数量'),
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

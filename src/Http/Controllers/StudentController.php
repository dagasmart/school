<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\BizAdmin\Renderers\DialogAction;
use DagaSmart\School\Enums\Enum;
use DagaSmart\School\Services\StudentService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
			->filterTogglable()
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar(),
                $this->importAction(admin_url('student/import')),
                $this->exportAction(),
                $this->dictForm(),
			])
            ->filter($this->baseFilter()->body([
                amis()->SelectControl('school_id', '学校')
                    ->multiple()
                    ->searchable()
                    ->options($this->service->getSchoolAll())
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
                amis()->TableColumn('student_name', '姓名')->searchable()->fixed('left'),
                amis()->TableColumn('student_code', '国网学籍号')->searchable(),
                amis()->TableColumn('school.school.school_name', '学校')
                    ->searchable([
                        'name' => 'school_id',
                        'type' => 'select',
                        'multiple' => true,
                        'searchable' => true,
                        'options' => $this->service->getSchoolAll(),
                    ])
                    ->width(200),
                amis()->TableColumn('school.grade.grade_name', '年级')->width(100),
                amis()->TableColumn('school.classes.class_name', '班级')->width(100),
                amis()->TableColumn('avatar', '学生照片')
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
                                        'title' => false,
                                        'actions' => [],
                                        'closeOnEsc' => true, //esc键关闭
                                        'closeOnOutside' => true, //域外可关闭
                                        'showCloseButton' => false, //显示关闭
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
                amis()->TableColumn('sex', '性别')
                    ->searchable([
                        'name' => 'sex',
                        'type' => 'checkboxes',
                        'options' => $this->service->getModel()->sexOption(),
                    ])
                    ->set('type', 'checkboxes')
                    ->set('options', $this->service->getModel()->sexOption())
                    ->set('static', true),
                amis()->TableColumn('status', '状态'),
                amis()->TableColumn('id_card', '身份证号')->searchable(),
				amis()->TableColumn('mobile', '电话')->searchable(),
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
                        amis()->TextControl('student_name', '姓名')->required(),
                        amis()->TextControl('id_card', '身份证号')
                            ->required(),
                        amis()->HiddenControl('student_code', '国网学籍号')->value('G${id_number}'),
                        amis()->SelectControl('school_id', '学校')
                            ->options($this->service->getSchoolAll())
                            ->value('${school.school.id}')
                            ->searchable()
                            ->required(),
                        amis()->SelectControl('grade_id', '年级')
                            //->options($this->service->getGradeAll())
                            ->source(admin_url('biz/school/${school_id||0}/grade'))
                            ->selectMode('group')
                            ->searchable()
                            ->value('${school.grade.id}')
                            ->disabledOn('${!school_id}')
                            ->required(),
                        amis()->SelectControl('classes_id', '班级')
                            //->options($this->service->getClassesAll())
                            ->source(admin_url('biz/school/${school_id||0}/grade/${grade_id||0}/classes'))
                            ->selectMode('group')
                            ->searchable()
                            ->value('${school.classes.id}')
                            ->disabledOn('${!grade_id}')
                            ->required(),
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
                            ])
                            ->crop([
                             'aspectRatio' => '0.81',
                            ]),
                    ]),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->SelectControl('gender', '性别')
                        ->options(Enum::sex())
                        ->value(3)
                        ->required(),
                    amis()->SelectControl('nation_id', '民族')
                        ->options(Enum::nation())
                        ->value(1)
                        ->required(),
                    amis()->SelectControl('status', '状态')
                        ->options(Enum::StudentState)
                        ->value(1)
                        ->required(),
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
		return $this->baseDetail()->mode('horizontal')->tabs([

            // 基本信息
            amis()->Tab()->title('基本信息')->body([
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->TextControl('student_name', '姓名')->required(),
                        amis()->TextControl('id_card', '身份证号')
                            ->required(),
                        amis()->HiddenControl('student_code', '国网学籍号')->value('G${id_number}'),
                        amis()->SelectControl('school.school.id', '学校')
                            ->options($this->service->getSchoolAll())
                            ->searchable()
                            ->required(),
                        amis()->SelectControl('school.grade.id', '年级')
                            //->options($this->service->getGradeAll())
                            ->source(admin_url('biz/school/${school.school.id||0}/grade'))
                            ->selectMode('group')
                            ->searchable()
                            ->disabledOn('${!school.school.id}')
                            ->required(),
                        amis()->SelectControl('school.classes.id', '班级')
                            //->options($this->service->getClassesAll())
                            ->source(admin_url('biz/school/${school.school.id||0}/grade/${school.grade.id||0}/classes'))
                            ->selectMode('group')
                            ->searchable()
                            ->disabledOn('${!school.grade.id}')
                            ->required(),
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
                            ])
                            ->crop([
                                'aspectRatio' => '0.81',
                            ]),
                    ]),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->SelectControl('gender', '性别')
                        ->options(Enum::sex())
                        ->value(3)
                        ->required(),
                    amis()->SelectControl('nation_id', '民族')
                        ->options(Enum::nation())
                        ->value(1)
                        ->required(),
                    amis()->SelectControl('status', '状态')
                        ->options(Enum::StudentState)
                        ->value(1)
                        ->required(),
                ]),
            ]),
            //其他
		])->static();
	}

    public function importAction($api=null): DialogAction
    {
        return amis()->DialogAction()->label('一键导入')->icon('fa fa-upload')->dialog(
            amis()->Dialog()->title('一键导入-学生')->body([
                amis()->Action()
                    ->label('演示模板')
                    ->level('light')
                    ->icon('fa fa-wpforms')
                    ->className('float-right')
                    ->actionType('saveAs')
                    ->api(Storage::url('template/student.csv')),
                amis()->Divider()->color('transparent'),
                amis()->Form()->mode('normal')->api($api)->body([
                    amis()->FileControl()
                        ->name('file')
                        ->label('限制只能上传csv文件')
                        ->accept('.csv')
                        ->receiver('school/student/import')
                        ->required()
                        ->drag()
                        ->onEvent([
                            'remove' => [
                                'actions' => [
                                    [
                                        'actionType' => 'ajax',
                                        'api' => [
                                            'url' => 'school/common/remove',
                                            'method' => 'post',
                                            'data' => [
                                                'path' => '${event.data.value}'
                                            ],
                                            'silent' => true
                                        ]
                                    ]
                                ]
                            ]
                        ]),
                ]),
            ])->actions([])
        );
    }

    public function import(): JsonResponse|JsonResource
    {
        // 验证文件是否存在且不为空
        if (request()->hasFile('file') && request()->file('file')->isValid()) {
            $file = request()->file('file');
            $filename = str_replace('.', '', microtime(true)) . $file->getClientOriginalName(); // 使用时间戳和原始名称作为文件名
            $path = $file->storeAs('files', $filename, 'public'); // 存储到 public 磁盘的 uploads 目录下
            return $this->response()->success(['value' => $path], '文件上传成功！'); // 返回成功消息
        } else {
            return $this->response()->fail('文件上传失败！');
        }
    }

    public function dictForm(): DialogAction
    {
        $form = $this->baseForm()->api($this->getStorePath())->data([
            'enabled' => true,
            'sort'    => 0,
        ])->body([
            amis()->TextControl()->name('value')->label()->clearable()->required()->maxLength(255),
            amis()->TextControl()->name('key')->label()->clearable()->required()->maxLength(255),
            amis()->SwitchControl()->name('enabled')->label(),
        ]);

        $createButton = amis()->DialogAction()
            ->dialog(amis()->Dialog()->title(__('admin.create'))->body($form))
            ->label(__('admin.create'))
            ->icon('fa fa-add')
            ->level('primary');

        $editForm = (clone $form)->api($this->getUpdatePath('$id'))->initApi($this->getEditGetDataPath('$id'));

        $editButton = amis()->DialogAction()
            ->dialog(amis()->Dialog()->title(__('admin.edit'))->body($editForm))
            ->label(__('admin.edit'))
            ->icon('fa-regular fa-pen-to-square')
            ->level('link');

        return amis()->DialogAction()->label('班级管理')->dialog(
            amis()->Dialog()->title('班级管理')->size('lg')->actions([])->body(
                amis()->CRUDTable()
                    ->perPage(10)
                    ->affixHeader(false)
                    ->filterTogglable()
                    ->filterDefaultVisible(false)
                    ->bulkActions([$this->bulkDeleteButton()])
                    ->perPageAvailable([10, 20, 30, 50, 100, 200])
                    ->footerToolbar(['switch-per-page', 'statistics', 'pagination'])
                    ->api($this->getListGetDataPath() . '&_type=1')
                    ->headerToolbar([
                        $createButton,
                        'bulkActions',
                        amis('reload')->set('align','right'),
                        amis('filter-toggler')->set('align','right'),
                    ])
                    ->filter(
                        $this->baseFilter()->data(['_type' => 1])->body([
                            amis()->TextControl()->name('type_key')->label()->clearable()->size('md'),
                            amis()->TextControl()->name('type_value')->label()->clearable()->size('md'),
                            amis()->SelectControl()
                                ->name('type_enabled')
                                ->label()
                                ->size('md')
                                ->clearable()
                                ->options([
                                    '1' => '是',
                                    '0' => '否',
                                ]),
                        ])
                    )
                    ->columns([
                        amis()->TableColumn()->name('value')->label(),
                        amis()->TableColumn()->name('key')->label(),
                        amis()->TableColumn()
                            ->name('enabled')
                            ->label()
                            ->type('status'),
                        amis()->Operation()->label(__('admin.actions'))->buttons([
                            $editButton,
                            $this->rowDeleteButton(),
                        ])->set('width', 150),
                    ])
            )
        );
    }


}

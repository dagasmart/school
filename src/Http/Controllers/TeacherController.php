<?php

namespace DagaSmart\School\Http\Controllers;

use DagaSmart\BizAdmin\Renderers\DialogAction;
use DagaSmart\School\Enums\Enum;
use DagaSmart\School\Services\TeacherService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\error;
use function Swow\Utils\success;

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
                    //->set('value', '${ school }')
                    ->set('fixed','left')
                    ->set('static', true),
                amis()->TableColumn('job_id', '教师职务')
                    ->searchable(['type'=>'tree-select', 'multiple'=>true, 'searchable'=>true, 'options'=>$this->service->jobOption()])
                    ->set('type', 'input-tree')
                    ->set('options', $this->service->jobOption())
                    ->set('multiple', true)
                    ->set('value', '${ job.job_id }')
                    ->set('width', 150)
                    ->set('static', true),
                amis()->TableColumn('teacher_no','教师编码')->searchable()->sortable(),
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
                amis()->TableColumn('updated_at', '更新时间')->type('datetime')->width(150),
                $this->rowActions('dialog')
                    ->set('align','center')
                    ->set('fixed','right')
                    ->set('width',150)
            ])
            ->affixRow([
//                [
//                    'type' => 'text',
//                    'text' => '总计',
//                    "colSpan" => 3,
//                ],
//                [
//                    'type' => 'tpl',
//                    "tpl" => '${rows|pick:mobile|sum}'
//                ]
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
            // 学校信息
            amis()->Tab()->title('学校信息')->body([
                amis()->ComboControl('school', false)->items([
                    amis()->SelectControl('school_id', '学校${index+1}')
                        ->options($this->service->schoolData())->required(),
                    amis()->HiddenControl('teacher_id')->value('${id}'),
                    amis()->TreeSelectControl('job_id', '职务')
                        ->options($this->service->jobOption())
                        ->menuTpl('<div class="flex justify-between"><span style="color: var(--button-link-default-font-color);">${label}</span><span class="ml-2 rounded p-1 text-xs text-gray-500 text-center w-full">${tag}</span></div>')
                        ->multiple()
                        ->maxTagCount(5)
                        ->onlyChildren()
                        ->searchable()
                        ->required(),
                ])
                ->className('border-gray-100 border-dashed')
                ->mode('horizontal')
                ->multiLine(false)
                ->multiple()
                ->strictMode(false)
                ->removable()
                ->required(),
            ]),
            // 家庭情况
            amis()->Tab()->title('家庭情况')->body([
                amis()->InputCityControl('region_id', '所在地区')
                    ->searchable()
                    ->extractValue(false)
                    ->required()
                    ->onEvent([
                        'change' => [
                            'actions' => [
                                [
                                    'actionType'  => 'setValue',
                                    'componentId' => 'form_region_info',
                                    'args'        => [
                                        'value' => '${value}'
                                    ],
                                ],
                            ],
                        ],
                    ]),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->TextControl('address', '家庭住址'),
                    amis()->TextControl('mobile', '联系电话'),
                ]),
                amis()->HiddenControl('region_info', '地区信息')->id('form_region_info'),
                amis()->TextControl('address_info', '详细地址')
                    ->value('${region_info.province} ${region_info.city} ${region_info.district} ${address}')
                    ->static(),
                amis()->Divider()->title('家庭成员')->titlePosition('left'),
                amis()->ComboControl('family', false)->items([
                    amis()->TextControl('family_name', '${index+1}.姓名')
                        ->clearable()
                        ->required(),
                    amis()->SelectControl('family_ties', '关系')
                        ->options(Enum::family())
                        ->clearable()
                        ->required(),
                    amis()->TextControl('family_mobile','电话')->clearable(),
                ])
                ->className('border-gray-100 border-dashed')
                ->mode('horizontal')
                ->multiLine(false)
                ->multiple()
                ->strictMode(false)
                ->removable(),
            ]),
        ])->onEvent([
            'submitSucc' => [
                'actions' => [
                    [
                        'actionType' => 'custom',
                        'script' => 'window.$owl.refreshAmisPage();'
                    ],
                ]
            ]
        ]);
    }

	public function detail(): Form
    {
		return $this->baseDetail()->mode('horizontal')->tabs([
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
            amis()->Tab()->title('学校信息')->body([
                amis()->ComboControl('school', false)->items([
                    amis()->SelectControl('school_id', '学校${index+1}')
                        ->options($this->service->schoolData())->required(),
                    amis()->HiddenControl('teacher_id')->value('${id}'),
                    amis()->TreeSelectControl('job_id', '职务')
                        ->options($this->service->jobOption())
                        ->menuTpl('<div class="flex justify-between"><span style="color: var(--button-link-default-font-color);">${label}</span><span class="ml-2 rounded p-1 text-xs text-gray-500 text-center w-full">${tag}</span></div>')
                        ->multiple()
                        ->maxTagCount(5)
                        ->onlyChildren()
                        ->searchable()
                        ->required(),
                ])
                    ->className('border-gray-100 border-dashed')
                    ->mode('horizontal')
                    ->multiLine(false)
                    ->multiple()
                    ->strictMode(false)
                    ->removable()
                    ->required(),
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
        ])->static();
	}

    public function importAction($api=null): DialogAction
    {
        return amis()->DialogAction()->label('一键导入')->icon('fa fa-upload')->dialog(
            amis()->Dialog()->title('一键导入-老师')->body([
                amis()->Action()
                    ->label('演示模板')
                    ->level('light')
                    ->icon('fa fa-wpforms')
                    ->className('float-right')
                    ->actionType('saveAs')
                    ->api(Storage::url('template/teacher.csv')),
                amis()->Divider()->color('transparent'),
                amis()->Form()->mode('normal')->api($api)->body([
                    amis()->FileControl()
                        ->name('file')
                        ->label('限制只能上传csv文件')
                        ->accept('.csv')
                        ->receiver('school/teacher/import')
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
            $filename = time() . $file->getClientOriginalName(); // 使用时间戳和原始名称作为文件名
            $path = $file->storeAs('files', $filename, 'public'); // 存储到 public 磁盘的 uploads 目录下

            $data = fastexcel()->import(public_storage_path($path));

            print_r($data->toArray());die;

            return $this->response()->success(['value' => $path], '文件上传成功！'); // 返回成功消息
        } else {
            return $this->response()->fail('文件上传失败！');
        }
    }

}

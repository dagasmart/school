<?php

namespace Biz\School\Http\Controllers;

use Biz\School\Enums\Enum;
use Biz\School\Services\BizSchoolService;
use DagaSmart\BizAdmin\Controllers\AdminController;
use DagaSmart\BizAdmin\Renderers\Form;
use DagaSmart\BizAdmin\Renderers\Page;

/**
 * 基础-学校表
 *
 * @property BizSchoolService $service
 */
class BizSchoolController extends AdminController
{
	protected string $serviceName = BizSchoolService::class;

	public function list(): Page
    {
		$crud = $this->baseCRUD()
			->filterTogglable()
			->headerToolbar([
				$this->createButton('dialog'),
				...$this->baseHeaderToolBar()
			])
            ->filter($this->baseFilter()->body([
                amis()->TextControl('school_code', '学校代码')
                    ->size('md')
                    ->clearable()
                    ->placeholder('学校代码'),
                amis()->TextControl('school_name', '学校名称')
                    ->size('md')
                    ->clearable()
                    ->placeholder('学校名称'),
                amis()->SelectControl('school_nature', '学校性质')
                    ->options(Enum::Nature)
                    ->clearable(),
                amis()->SelectControl('school_type', '办学类型')
                    ->options(Enum::Type)
                    ->clearable(),
                amis()->Divider(),
                amis()->DateRangeControl('register_time', '注册登记')
                    ->format('YYYY-MM-DD')
                    ->clearValueOnHidden()
            ]))
            ->autoFillHeight(true)
            ->columns([
                amis()->TableColumn('id', 'ID')->sortable()->set('fixed','left'),
                amis()->TableColumn('school_name', '学校名称')
                    ->searchable()
                    ->width(280)
                    ->set('fixed','left'),
                amis()->TableColumn('school_code', '学校代码'),
                amis()->TableColumn('school_type', '办学类型')
                    ->searchable(['name' => 'school_type', 'type' => 'select', 'options' => Enum::Type])
                    ->set('type', 'select')
                    ->set('options', Enum::Type)
                    ->set('static', true),
                amis()->TableColumn('school_nature', '学校性质')
                    ->searchable(['name' => 'school_nature', 'type' => 'select', 'options' => Enum::Nature])
                    ->set('type', 'select')
                    ->set('options', Enum::Nature)
                    ->set('static', true),
                amis()->TableColumn('region', '所属地区')
                    ->searchable(['name'=>'region','type'=>'input-city'])
                    ->set('type','input-city')
                    ->set('static',true)
                    ->set('width', 200)
                    ->sortable(),
                amis()->TableColumn('school_address', '学校地址')
                    ->searchable()
                    ->set('width', 200),
                amis()->TableColumn('location', '位置定位'),
                amis()->TableColumn('register_time', '注册日期')
                    ->width(120)
                    ->sortable()
                    ->quickEdit(['type'=>'input-date','value'=>'${register_time}']),
                amis()->TableColumn('credit_code', '信用代码'),
                amis()->TableColumn('legal_person', '学校法人'),
                amis()->TableColumn('contacts_mobile', '联系电话')->searchable(),
                amis()->TableColumn('contacts_email', '联系邮件')->searchable(),
                amis()->TableColumn('updated_at', admin_trans('admin.updated_at'))
                    ->width(100)
                    ->type('datetime')
                    ->sortable(),
                $this->rowActions('dialog')
                    ->set('width', 200)
                    ->set('align', 'center')
                    ->set('fixed', 'right')
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
                        amis()->TextControl('school_name', '学校名称'),
                        amis()->TextControl('school_code', '学校代码'),
                        amis()->SelectControl('school_nature', '学校性质')
                            ->options(Enum::Nature),
                        amis()->SelectControl('school_type', '办学类型')
                            ->options(Enum::Type),
                        amis()->TextControl('register_time', '注册日期'),
                    ]),

                    amis()->GroupControl()->direction('vertical')->body([
                        amis()->ImageControl('school_logo')
                            ->thumbRatio('4:3')
                            ->thumbMode('cover h-full rounded-md overflow-hidden')
                            ->className(['overflow-hidden'=>true, 'h-full'=>true])
                            ->imageClassName([
                                'w-80'=>true,
                                'h-60'=>true,
                                'overflow-hidden'=>true
                            ])
                            ->fixedSize()
                            ->fixedSizeClassName([
                                'w-80'=>true,
                                'h-60'=>true,
                                'overflow-hidden'=>true
                            ])
                            ->crop([
                                'aspectRatio' => '1.3',
                            ]),
                    ]),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->direction('horizontal')->body([
                    amis()->TextControl('credit_code', '信用代码'),
                    amis()->TextControl('legal_person', '学校法人'),
                ]),
                amis()->Divider(),
                amis()->GroupControl()->mode('horizontal')->body([
                    amis()->TextControl('contacts_mobile', '联系电话'),
                    amis()->TextControl('contacts_email', '联系邮件'),
                ]),
                amis()->Divider(),
                amis()->InputCityControl('region', '所在地区')
                    ->searchable()
                    ->extractValue(false)
                    ->required(),
                amis()->TextControl('school_address', '学校地址'),
                amis()->TextControl('school_address_info', '详细地址')
                    ->value('${region.province} ${region.city} ${region.district} ${school_address}')
                    ->static(),
            ]),
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

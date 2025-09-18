<?php

namespace Biz\School\Enums;

use DagaSmart\BizAdmin\Enums\Enum as Enums;

enum Enum
{

    public const array WorkStatus = [
        ['value' => 0, 'label' => '未知'],
        ['value' => 1, 'label' => '正常'],
        ['value' => 2, 'label' => '事病假'],
        ['value' => 3, 'label' => '停职'],
        ['value' => 4, 'label' => '离职'],
    ];

    public const array IsFull = [
        ['value' => 1, 'label' => '是'],
        ['value' => 2, 'label' => '否'],
    ];

    public const array State = [
        ['value' => 0, 'label' => '否'],
        ['value' => 1, 'label' => '是'],
    ];

    public const array Nature = [
        ['label' => '公办学校‌',    'value' => 1],
        ['label' => '民办学校‌',    'value' => 2],
        ['label' => '独立学院‌',    'value' => 3],
        ['label' => '‌中外办学',    'value' => 4],
        ['label' => '私立学校‌',    'value' => 5],
    ];

    public const array Type = [
        ['label' => '九年义务教育', 'value' => 1],
        ['label' => '十二年一贯制', 'value' => 2],
    ];

    public const array Stage = [
        ['label' => '幼儿园',  'value' => 1],
        ['label' => '小学',   'value' => 2],
        ['label' => '初级中学','value' => 3],
        ['label' => '高级中学','value' => 4],
        ['label' => '职业高中','value' => 5],
        ['label' => '九年一贯制','value' => 6],
        ['label' => '十二年一贯制','value' => 7],
        ['label' => '全龄一贯制','value' => 8],
    ];

    public static function sex(): array
    {
        return Enums::sex();
    }

    public static function nation(): array
    {
        return Enums::nation();
    }




}

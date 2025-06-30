<?php

namespace Biz\School\Enums;

use DagaSmart\BizAdmin\Enums\Enum as BaseEnum;


enum Enum
{
    public const array Nature = [
        ['label' => '公办学校',  'value' => 1],
        ['label' => '民办学校',  'value' => 2],
        ['label' => '私立学校',  'value' => 3],
        ['label' => '‌独立学院',  'value' => 4],
        ['label' => '成人教育',  'value' => 5],
        ['label' => '中外合作',  'value' => 6],
    ];

    public const array Type = [
        ['label' => '幼儿园',  'value' => 1],
        ['label' => '小学',  'value' => 2],
        ['label' => '初级中学','value' => 3],
        ['label' => '高级中学','value' => 4],
        ['label' => '职业高中','value' => 5],
        ['label' => '九年一贯制','value' => 6],
        ['label' => '十二年一贯制','value' => 7],
        ['label' => '十五年一贯制','value' => 8],

    ];

    public function nation(): array
    {
        return BaseEnum::nation();
    }

    public function sex(): array
    {
        return BaseEnum::sex();
    }
}

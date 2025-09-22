<?php

namespace DagaSmart\School\Enums;

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


    public const array Mode = [
        ['label' => '幼儿园',  'value' => 10],
        ['label' => '小学',   'value' => 20],
        ['label' => '初级中学','value' => 30],
        ['label' => '高级中学','value' => 40],
        ['label' => '九年一贯制','value' => 50],
        ['label' => '十二年一贯制','value' => 60],
        ['label' => '高职五年一贯制','value' => 80],
        ['label' => '大学','value' => 90],
    ];
    public const array Grade = [
        ['label' => '幼儿园',  'value' => 10, 'children' => [
                ['label' => '小班', 'value' => 11],
                ['label' => '中班', 'value' => 12],
                ['label' => '大班', 'value' => 13],
            ]
        ],
        ['label' => '小学',   'value' => 20, 'children' => [
                ['label' => '小学一年级', 'value' => 21],
                ['label' => '小学二年级', 'value' => 22],
                ['label' => '小学三年级', 'value' => 23],
                ['label' => '小学四年级', 'value' => 24],
                ['label' => '小学五年级', 'value' => 25],
                ['label' => '小学六年级', 'value' => 26],
            ]
        ],
        ['label' => '初级中学', 'value' => 30, 'children' => [
                ['label' => '初中一年级', 'value' => 31],
                ['label' => '初中二年级', 'value' => 32],
                ['label' => '初中三年级', 'value' => 33],
            ]
        ],
        ['label' => '高级中学', 'value' => 40, 'children' => [
                ['label' => '高中一年级', 'value' => 41],
                ['label' => '高中二年级', 'value' => 42],
                ['label' => '高中三年级', 'value' => 43],
            ]
        ],
        ['label' => '大学', 'value' => 90, 'children' => [
                ['label' => '大学一年级', 'value' => 91],
                ['label' => '大学二年级', 'value' => 92],
                ['label' => '大学三年级', 'value' => 93],
                ['label' => '大学四年级', 'value' => 94],
                ['label' => '大学五年级', 'value' => 95],
            ]
        ]
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

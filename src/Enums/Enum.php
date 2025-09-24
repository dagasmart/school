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

    public const array JOB = [
        ['label' => '行政类', 'tag' => '主要负责学校日常运营的管理工作', 'children' =>
            [
                ['label' => '校长',  'value' => 100, 'tag' => '全面负责学校的行政和党建工作'],
                ['label' => '党支部书记',  'value' => 101, 'tag' => '协助校长处理日常事务，并负责党支部的日常工作'],
                ['label' => '教学副校长',  'value' => 102, 'tag' => '主管学校的教育教学工作'],
                ['label' => '科研副校长',  'value' => 103, 'tag' => '负责教育科研工作'],
                ['label' => '德育副校长',  'value' => 104, 'tag' => '主管学生的思想政治工作'],
                ['label' => '行政副校长',  'value' => 105, 'tag' => '负责学校后勤和安全管理工作'],
                ['label' => '工会主席',  'value' => 106, 'tag' => '主持工会的各项工作'],
                ['label' => '办公室主任',  'value' => 107, 'tag' => '协助校长处理学校的日常行政事务'],
                ['label' => '团委书记',  'value' => 108, 'tag' => '负责学校团组织的各项工作'],
                ['label' => '人事处长',  'value' => 109, 'tag' => '负责师资引进和教师考核工作'],
                ['label' => '财务处长',  'value' => 110, 'tag' => '负责师资引进和教师考核工作'],
                ['label' => '教导处主任',  'value' => 111, 'tag' => '主管教育教学工作'],
                ['label' => '教导处副主任',  'value' => 112, 'tag' => '分别负责语文、数学和综合学科的教学工作'],
                ['label' => '德育处主任',  'value' => 113, 'tag' => '主管班主任和学生思想政治工作'],
                ['label' => '德育副主任',  'value' => 114, 'tag' => '负责少先队工作'],
                ['label' => '总务主任',  'value' => 115, 'tag' => '主管学校后勤工作'],
                ['label' => '总务副主任',  'value' => 116, 'tag' => '负责学校财务管理工作'],
                ['label' => '教科室主任',  'value' => 117, 'tag' => '主管学校的教科研工作'],
                ['label' => '教科室副主任',  'value' => 118, 'tag' => '负责学校的课程建设'],
            ]
        ],
        ['label' => '教学类', 'tag' => '主要负责教育教学工作', 'children' =>
            [
                ['label' => '教务主任',  'value' => 200, 'tag' => '全面负责学校的行政和党建工作'],
                ['label' => '教研组长',  'value' => 201, 'tag' => '协助校长处理日常事务，并负责党支部的日常工作'],
                ['label' => '年级组长',  'value' => 202, 'tag' => '主管学校的教育教学工作'],
                ['label' => '班主任',  'value' => 203, 'tag' => '负责教育科研工作'],
                ['label' => '任课教师',  'value' => 204, 'tag' => '主管学生的思想政治工作'],
            ]
        ],
        ['label' => '科研类', 'tag' => '主要从事科学研究工作', 'children' =>
            [
                ['label' => '研究所长',  'value' => 300, 'tag' => '全面负责学校的行政和党建工作'],
                ['label' => '实验室主任',  'value' => 301, 'tag' => '协助校长处理日常事务，并负责党支部的日常工作'],
                ['label' => '课题组组长',  'value' => 302, 'tag' => '主管学校的教育教学工作'],
                ['label' => '科研助理',  'value' => 303, 'tag' => '负责教育科研工作'],
            ]
        ],
        ['label' => '教辅类', 'tag' => '为教学和科研提供辅助支持', 'children' =>
            [
                ['label' => '图书馆长',  'value' => 400, 'tag' => '全面负责学校的行政和党建工作'],
                ['label' => '阅览室管理员',  'value' => 401, 'tag' => '协助校长处理日常事务，并负责党支部的日常工作'],
                ['label' => '实验室管理员',  'value' => 402, 'tag' => '主管学校的教育教学工作'],
                ['label' => '资料室管理员',  'value' => 403, 'tag' => '负责教育科研工作'],
            ]
        ],
        ['label' => '工勤类', 'tag' => '为学校的正常运转提供必要的支持服务', 'children' =>
            [
                ['label' => '校医',  'value' => 500, 'tag' => '全面负责学校的行政和党建工作'],
                ['label' => '心理咨询师',  'value' => 501, 'tag' => '协助校长处理日常事务，并负责党支部的日常工作'],
                ['label' => '网络管理员',  'value' => 502, 'tag' => '主管学校的教育教学工作'],
                ['label' => '保安',  'value' => 503, 'tag' => '负责教育科研工作'],
                ['label' => '保洁员',  'value' => 504, 'tag' => '主管学生的思想政治工作'],
            ]
        ],
    ];

    public static function job(): array
    {
        return Enum::JOB;
    }

    public static function sex(): array
    {
        return Enums::sex();
    }

    public static function nation(): array
    {
        return Enums::nation();
    }




}

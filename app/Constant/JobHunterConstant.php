<?php
declare(strict_types=1);

namespace App\Constant;

class JobHunterConstant
{
    const
        SEARCH_JOB_STATUS = [
        ['code' => 0, 'text' => '请选择职业状态'],
        ['code' => 1, 'text' => '离职-随时到岗'],
        ['code' => 2, 'text' => '在职-暂不考虑'],
        ['code' => 3, 'text' => '在职-考虑机会'],
        ['code' => 4, 'text' => '在职-月内到岗']
    ];
}



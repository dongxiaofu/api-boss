<?php
declare(strict_types=1);

namespace App\Constant;

class JobConstant
{
//    // 工作经验：0.不限;1.在校生;2.应届生;3.1年以内;4.1-3年;5.3-5年;6.5-10年;7.10年以上
//    const EXPERIENCE = ['不限', '在校生', '应届生', '1年以内', '1-3年', '3-5年', '5-10年', '10年以上',];
//    // 学历：0.不限;1.初中及以下;2.中专/中技;3.高中;4.大专;5.本科;6.硕士;7.博士;
//    const DEGREE = ['不限', '初中及以下', '中专/中技', '高中', '大专', '本科', '硕士', '博士',];
//    // 薪水范围：0.不限;1.3K以下;2.3-5K;3.5-10K;4.10-15K;5.15-20K;6.20-30K;7.30-50K;8.50K以上
//    const SALARY = ['不限', '3K以下', '3-5K', '5-10K', '10-15K', '15-20K', '20-30K', '30-50K', '50K以上',];
//    const FINANCING_STAGE = ['不限', '未融资', '天使轮', 'A轮', 'B轮', 'C轮', 'D轮及以上', '已上市', '不需要融资',];
//    const COMPANY_SCALE = ['不限', '0-20人', '20-99人', '100-499人', '500-999人', '1000-9999人', '10000人以上',];
    // 薪水范围：0.不限;1.3K以下;2.3-5K;3.5-10K;4.10-15K;5.15-20K;6.20-30K;7.30-50K;8.50K以上
    const SALARY = [['code' => 0, 'name' => '不限'], ['code' => 1, 'name' => '3K以下'], ['code' => 2, 'name' => '3-5K'], ['code' => 3, 'name' => '5-10K'], ['code' => 4, 'name' => '10-15K'], ['code' => 5, 'name' => '15-20K'], ['code' => 6, 'name' => '20-30K'], ['code' => 7, 'name' => '30-50K'], ['code' => 8, 'name' => '50K以上'],];
    // 工作经验：0.不限;1.在校生;2.应届生;3.1年以内;4.1-3年;5.3-5年;6.5-10年;7.10年以上
    const EXPERIENCE = [['code' => 0, 'name' => '不限'], ['code' => 1, 'name' => '在校生'], ['code' => 2, 'name' => '应届生'], ['code' => 3, 'name' => '1年以内'], ['code' => 4, 'name' => '1-3年'], ['code' => 5, 'name' => '3-5年'], ['code' => 6, 'name' => '5-10年'], ['code' => 7, 'name' => '10年以上'],];
    const FINANCING_STAGE = [['code' => 0, 'name' => '不限'], ['code' => 1, 'name' => '未融资'], ['code' => 2, 'name' => '天使轮'], ['code' => 3, 'name' => 'A轮'], ['code' => 4, 'name' => 'B轮'], ['code' => 5, 'name' => 'C轮'], ['code' => 6, 'name' => 'D轮及以上'], ['code' => 7, 'name' => '已上市'], ['code' => 8, 'name' => '不需要融资'],];
    const COMPANY_SCALE = [['code' => 0, 'name' => '不限'], ['code' => 1, 'name' => '0-20人'], ['code' => 2, 'name' => '20-99人'], ['code' => 3, 'name' => '100-499人'], ['code' => 4, 'name' => '500-999人'], ['code' => 5, 'name' => '1000-9999人'], ['code' => 6, 'name' => '10000人以上'],];
    // 学历：0.不限;1.初中及以下;2.中专/中技;3.高中;4.大专;5.本科;6.硕士;7.博士;
    const DEGREE = [['code' => 0, 'name' => '不限'], ['code' => 1, 'name' => '初中及以下'], ['code' => 2, 'name' => '中专/中技'], ['code' => 3, 'name' => '高中'], ['code' => 4, 'name' => '大专'], ['code' => 5, 'name' => '本科'], ['code' => 6, 'name' => '硕士'], ['code' => 7, 'name' => '博士'],];


    const
        SEARCH_FILTER_CONFIG = [
        'experience' => self::EXPERIENCE,
        'degree' => self::DEGREE,
        'salary' => self::SALARY,
        'financing_stage' => self::FINANCING_STAGE,
        'company_scale' => self::COMPANY_SCALE,
        'industry' => [['code' => 1, 'name' => '电子商务'], ['code' => 2, 'name' => '游戏'], ['code' => 3, 'name' => '媒体'], ['code' => 4, 'name' => '广告营销'], ['code' => 5, 'name' => '数据服务'], ['code' => 6, 'name' => '医疗健康'], ['code' => 7, 'name' => '生活服务'], ['code' => 8, 'name' => 'O2O'], ['code' => 9, 'name' => '旅游'], ['code' => 10, 'name' => '分类信息'], ['code' => 11, 'name' => '音乐/视频/阅读'], ['code' => 12, 'name' => '在线教育'], ['code' => 13, 'name' => '社交网络'], ['code' => 14, 'name' => '人力资源服务'], ['code' => 15, 'name' => '企业服务'], ['code' => 16, 'name' => '信息安全'], ['code' => 17, 'name' => '智能硬件'], ['code' => 18, 'name' => '移动互联网'], ['code' => 19, 'name' => '互联网'], ['code' => 20, 'name' => '计算机软件'], ['code' => 21, 'name' => '通信/网络设备'], ['code' => 22, 'name' => '广告/公关/会展'], ['code' => 23, 'name' => '互联网金融'], ['code' => 24, 'name' => '物流/仓储'], ['code' => 25, 'name' => '贸易/进出口'], ['code' => 26, 'name' => '咨询'], ['code' => 27, 'name' => '工程施工'], ['code' => 28, 'name' => '汽车生产'], ['code' => 29, 'name' => '其他行业'],],
    ];
}

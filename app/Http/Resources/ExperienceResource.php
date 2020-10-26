<?php

namespace App\Http\Resources;

use App\Constant\JobHunterConstant;
use App\Model\JobHunter;
use App\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ExperienceResource extends JsonResource
{
    /**
     * 指示是否应保留资源的集合键。
     *
     * @var bool
     */
    public $preserveKeys = true;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->resource['start_time'] = date('Y-m', $this->resource['start_time']);
        $this->resource['end_time'] = date('Y-m', $this->resource['end_time']);

        return parent::toArray($request);
    }
}
//experience3: {
//    id: 1,
//                    user_id: 23,
//                    company_name: 'AB3',
//                    department: 'c',
//                    position_type: 'd',
//                    tags: ['后端开发', 'PHP', '系统架构'],
//                    industry: 'w',
//                    position_name: 'w',
//                    working_hours: '3',
//                    job_content: '1.游戏接口编写和接口性能优化\n' +
//    '2.数据统计功能开发\n' +
//    '3.微信登录、微信支付、微信打款开发\n' +
//    '4.代码版本控制与发布\n' +
//    '5.参与服务器日常维护\n' +
//    '6.指导初级工程师开发',
//                    performance: '1.使用docker在单机上建立压测环境，使用XHProf查找PHP接口性能瓶颈，优化php-fpm.conf\n' +
//    '配置项，使部分接口的压测速度由23秒下降至1秒。\n' +
//    '2.使用 “shell + 软链接” 简化项目发布过程，使代码发布与回退非常平滑和快速。\n' +
//    '3. 建立微信openId和用户ID的映射表，来加快用户数据的查询速度。\n' +
//    '4. 使用简单的SQL语句，通过在PHP中处理业务逻辑拼装数据，完成复杂的统计需求。\n' +
//    '5. Test。'
//                },


<?php

namespace App\Http\Resources;

use App\Service\JobService;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * 指示是否应保留资源的集合键。
     *
     * @var bool
     */
    public $preserveKeys = true;

    public function __construct($resource)
    {
        if (is_null($resource)) {
            $this->resource = [];
        } else {
            parent::__construct($resource);
        }
    }


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
//        return [
//            'job_id' => $this->job_id,
//            'name' => $this->name,
//            'email' => $this->email,
//            'start' => $this->created_at,
//            'updated_at' => $this->updated_at,
//        ];
        $this->resource['stage'] = JobService::getFinacingStageText($this->resource['stage']);
        $this->resource['scale'] = JobService::getCompanyScaleText($this->resource['scale']);
        $this->resource['industry'] = JobService::getIndustryText($this->resource['industry']);

        return parent::toArray($request);
    }
}

//introduce: '太子家居创始于1999年。在昆明起步，始终致力于打造具有国际视野的民族品牌。发展至今，公司在成都天邛工业园已拥有1300余亩家居智造基地，总部员工近3000人，1000多家专卖店遍布全国。多年来，太子家居全心投入创造舒适居家生活，以更加开阔的眼界，时刻洞悉未来“家”的趋势。现',
//                        business: '太子家居有限公司',
//                        address: '中国上海',
//                        logo: 'http://127.0.0.1:8080/static/JobDetail/company-log.png',
//                        name: '北燕',
//                        stage: '已上市',
//                        scale: '10000人以上',
//                        industry: '互联网',
//                        net: 'http://chugang.net'

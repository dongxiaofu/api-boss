<?php

namespace App\Http\Resources;

use App\Model\Company;
use App\Model\Employee;
use App\Service\DistrictService;
use App\Service\JobService;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * 指示是否应保留资源的集合键。
     *
     * @var bool
     */
    public $preserveKeys = true;
    public static $wrap = null;

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
//            'update_time' => $this->updated_at->toDateString(), // carbon
//            'company' => new CompanyResource(Company::find($this->company_id)),
//        ];
        $company = new CompanyResource(Company::find($this->company_id));
//        $request->offsetSet('company', $company);
        $this->resource['job_status'] = $this->status;
        unset($this->resource['status']);
        $this->resource['company'] = $company;
        $employee = new EmployeeResource(Employee::find($this->employee_id));
        $this->resource['employee'] = $employee;
        unset($this->resource['employee_id']);
        $this->resource['update_time'] = $this->updated_at->toDateString();
        unset($this->resource['updated_at']);
        $this->resource['degree'] = JobService::getDegreeText($this->resource['degree']);

        $this->resource['experience'] = JobService::getExperienceText($this->resource['experience']);
        $this->resource['salary'] = JobService::getSalaryText($this->resource['salary']);
//        $this->resource['degree'] = JobService::getDegreeText($this->resource['degree']);
//        $this->resource['degree'] = JobService::getDegreeText($this->resource['degree']);

        $this->resource['city'] = DistrictService::getNameBy($this->resource['city_code']);
        $this->resource['area'] = DistrictService::getNameBy($this->resource['area_code']);


        unset($this->resource['company_id']);

        return parent::toArray($request);
    }
}
//
//    job_id: 4,
//    job_status: '招聘中',
//    title: 'C++工程师 初中级4',
//    salary: '7-10K',
//    salary_num: '13薪',
//    city: '武汉',
//    experience: '1-3年',
//    degree: '本科',
//    benefits: ['全屋定制设计', ' 装饰装修', ' 设计师', ' 整套施工图'],
//    employee: {name: '孙悟空', title: '创意总监', status: '刚刚在线', avatar: '头像'},
//    job_description: "岗位职责：\n" +
//    "\n" +
//    "1、负责和客户沟通设计方案、促成签单。\n" +
//    "\n" +
//    "2、量房、效果图及整套施工图的绘制工作。\n" +
//    "\n" +
//    "3、维护和客户之间的关系，挖掘潜在客户，促进公司与客户的长期合作。\n" +
//    "\n" +
//    "4、完成部门经理及公司领导交办的其他相关性工作。\n" +
//    "\n" +
//    "任职资格：\n" +
//    "\n" +
//    "1、室内设计、环境艺术设计等相关专业。\n" +
//    "\n" +
//    "2、二年以上家装设计师工作经验，独立完成设计项目。\n" +
//    "\n" +
//    "3、熟练运用绘图软件，有较强的实际操作能力。\n" +
//    "\n" +
//    "岗位福利：\n" +
//    "\n" +
//    "1.每个月到店客流保证300户以上.\n" +
//    "\n" +
//    "2.上班时间9:00-18:00午休2个小时。\n" +
//    "\n" +
//    "3.公司所有岗位提供住宿。\n" +
//    "\n" +
//    "4.每月举办员工生日会、部门团建、销冠奖励",
//    company: {
//    introduce: '太子家居创始于1999年。在昆明起步，始终致力于打造具有国际视野的民族品牌。发展至今，公司在成都天邛工业园已拥有1300余亩家居智造基地，总部员工近3000人，1000多家专卖店遍布全国。多年来，太子家居全心投入创造舒适居家生活，以更加开阔的眼界，时刻洞悉未来“家”的趋势。现',
//    business: '太子家居有限公司',
//    address: '中国上海',
//    logo: 'http://127.0.0.1:8080/static/JobDetail/company-log.png',
//    name: '北燕',
//    stage: '已上市',
//    scale: '10000人以上',
//    industry: '互联网',
//    net: 'http://chugang.net'
//
//    },
//    update_time: '2020-09-09'

<?php

namespace App\Http\Resources;

use App\Constant\JobConstant;
use App\Constant\JobHunterConstant;
use App\Model\JobHunter;
use App\Model\User;
use Illuminate\Http\Resources\Json\JsonResource;

class JobHunterResource extends JsonResource
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
        $user = User::find($this->user_id);
        $this->resource['name'] = $user->name;
        $this->resource['gender'] = $user->gender;
        $birthday = $user->birthday;
        $this->resource['birthday_year'] = date('Y', $birthday);
        $this->resource['birthday_month'] = date('m', $birthday);
        $this->resource['job_search_status'] = JobHunter::getConfigItem(
            $this->job_search_status, JobHunterConstant::SEARCH_JOB_STATUS);
        $this->resource['degree'] = JobHunter::getConfigItem(
            $this->degree, JobConstant::DEGREE
        );
        $this->resource['experience'] = JobHunter::getConfigItem(
            $this->experience, JobConstant::EXPERIENCE
        );
        return parent::toArray($request);
    }
}

//
//user_profile: {
//    user_id: '',
//                    name: '姓名2',
//                    job_search_status: 1,
//                    gender: 2,
//                    birthday: '',
//                    birthday_year: '0',
//                    birthday_month: '0',
//                    telephone: '-',
//                    wechat: '-',
//                    email: '-',
//                    identity: '',
//                    degree: '-',
//                    experience: '-'
//                },


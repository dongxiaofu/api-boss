<?php
declare(strict_types=1);

namespace App\Service;


use App\Constant\CommonConstant;
use App\Constant\JobConstant;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobPageCollection;
use App\Http\Resources\JobResource;
use App\Model\Job;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class JobService
{
    public function getList(array $params)
    {
//            "key_word":"gggggg","industry_code":"11","experience_code":"1","degree_code":"1","salary_code":"1","stage_code":"1","scale_code":"1"}
        $city = $params['city_code'] ?? 0;
        $area = $params['area_code'] ?? 0;
        $experience = $params['experience_code'] ?? 0;
        $degree = $params['degree_code'] ?? 0;
        $salary = $params['salary_code'] ?? 0;

        $stage = $params['stage_code'] ?? 0;
        $scale = $params['scale_code'] ?? 0;
        $industry = $params['industry_code'] ?? 0;

        $page = $params['page'] ?? 0;


        $jobs = DB::table('job')->where('job_id', '!=', 0);
        $city && $jobs = $jobs->where('city_code', intval($city));
        $area && $jobs = $jobs->where('area_code', intval($area));
        $experience && $jobs->where('experience', intval($experience));
        $degree && $jobs = $jobs->where('degree', intval($degree));
        $salary && $jobs = $jobs->where('salary', intval($salary));

        $jobs = $jobs->leftJoin('company', 'job.company_id', '=', 'company.company_id');
        $stage && $jobs = $jobs->where('company.stage', intval($stage));
        $scale && $jobs = $jobs->where('company.scale', intval($scale));
        $industry && $jobs = $jobs->where('company.industry', intval($industry));

        $currentPage = $page;
        $pageSize = 2;
        $start = ($currentPage - 1) * $pageSize;
        $jobs = $jobs->offset($start)->limit($pageSize)->get();


        $collection = new Collection();
        foreach ($jobs as $k => $v) {
            $attributes = get_object_vars($v);
            $job = new Job($attributes);
            $collection->add($job);
        }

        $paginator = new Paginator($collection, 2, 1);

        return new JobPageCollection($paginator);

    }

    public function getRelatedList(int $limit = CommonConstant::PAGE_SIZE)
    {
        $list = Job::take($limit)
//            ->where('job_id' , '<', 0)
            ->get();
        return new JobCollection($list);
//        $list = $this->formatList($list);
//        return $list;
    }


    public function getRecommendList(int $limit = CommonConstant::PAGE_SIZE)
    {
        $list = Job::take($limit)
            ->get();
        return new JobCollection($list);

//        foreach ($list as $job) {
//            $job->company;
//            if (is_null($job->company)) {
//                $job->company = [];
//            }
//        }
//
//        return $list;
    }

    public function getById(int $id)
    {

        return new JobResource(Job::find($id));
        $job = Job::find($id);
        if (!is_null($job)) {
            $job->company;
        }
        return $job;
    }

    private
    static function getJobConfig(int $code, string $key)
    {
        $text = '';
        $stageCollection = JobConstant::SEARCH_FILTER_CONFIG[$key];
        foreach ($stageCollection as $stage) {
            if ($code == $stage['code']) {
                $text = $stage['name'];
            }
        }
        return $text;
    }

    public
    static function getFinacingStageText(int $code)
    {
        return self::getJobConfig($code, 'financing_stage');
    }

    public
    static function getExperienceText(int $code)
    {
        return self::getJobConfig($code, 'experience');
    }

    public
    static function getDegreeText(int $code)
    {
        return self::getJobConfig($code, 'degree');
    }


    public
    static function getSalaryText(int $code)
    {
        return self::getJobConfig($code, 'salary');
    }

    public
    static function getCompanyScaleText(int $code)
    {
        return self::getJobConfig($code, 'company_scale');
    }

    public
    static function getIndustryText(int $code)
    {
        return self::getJobConfig($code, 'industry');
    }

}

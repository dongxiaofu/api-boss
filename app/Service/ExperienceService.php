<?php
declare(strict_types=1);

namespace App\Service;


use App\Constant\CommonConstant;
use App\Constant\JobConstant;
use App\Http\Resources\ExperienceCollection;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobPageCollection;
use App\Http\Resources\JobResource;
use App\Model\Experience;
use App\Model\Job;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class ExperienceService
{
    public function getList(int $userId)
    {
        return new ExperienceCollection(Experience::where('user_id', $userId)->get());
    }

    // 保存工作经验，保存与新增能否都用save方法？尝试使用
    public function save(array $params)
    {
        $id = $params['id'] ?? 0;
        // 有id是update，无id是create
        if (empty($id)) {
            unset($params['id']);
        }
        // 工作经历时间段
        $workingMonthStart = $params['working_month_start'] ?? 0;
        $workingYearStart = $params['working_year_start'] ?? 0;
        $workingMonthEnd = $params['working_month_end'] ?? 0;
        $workingYearEnd = $params['working_year_end'] ?? 0;
        $start = strtotime($workingYearStart . '-' . $workingMonthStart);
        $end = strtotime($workingYearEnd . '-' . $workingMonthEnd);
        $params['start_time'] = $start;
        $params['end_time'] = $end;
        unset($params['working_month_start'], $params['working_year_start'],
            $params['working_month_end'], $params['working_year_end']);

        // 工作经历标签，例如：架构,测试,后端
        $tagArr = $params['tags'] ?? [];
        if (!empty($tags)) {
            $tags = implode(',', $tagArr);
            $params['tags'] = $tags;
        } else {
            unset($params['tags']);
        }

        $experience = new Experience();
        if ($id == 0) {
            $experience = $experience->newInstance($params, false);
        } else {
            $experience = $experience->newInstance($params, true);
        }
        return $experience->save();
//
//
//
//
//        $experience = Experience::find($id);
//        if (is_null($experience)) {
//            return true;
//        }
//        foreach ($params as $k => $v) {
//            $experience->$k = $v;
//        }

    }

    // 删除用户工作经验
    public function delete(int $experienceId)
    {
        return Experience::find($experienceId)->delete();
    }
}

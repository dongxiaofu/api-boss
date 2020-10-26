<?php
declare(strict_types=1);

namespace App\Service;


use App\Constant\CommonConstant;
use App\Constant\JobConstant;
use App\Http\Resources\JobCollection;
use App\Http\Resources\JobHunterResource;
use App\Http\Resources\JobPageCollection;
use App\Http\Resources\JobResource;
use App\Model\Job;
use App\Model\JobHunter;
use App\Model\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class JobHunterService
{
    public function getByUserId(int $userId)
    {
        $jobHunter = new JobHunterResource(JobHunter::where('user_id', $userId)->first());
        return $jobHunter;
    }

    public function save($params)
    {
        $userId = (int)($params['user_id'] ?? 0);
        if (empty($userId)) {
            return;
        }
        $jobHunter = JobHunter::where('user_id', $userId)->first();
        if (isset($params['weixin'])) $jobHunter->weixin = $params['weixin'];
        if (isset($params['degree'])) $jobHunter->degree = $params['degree'];
        if (isset($params['experience'])) $jobHunter->experience = $params['experience'];
        if (isset($params['email'])) $jobHunter->email = $params['email'];
        if (isset($params['telephone'])) $jobHunter->telephone = $params['telephone'];
        if (isset($params['job_search_status'])) $jobHunter->job_search_status = $params['job_search_status'];
        $jobHunter->updated_at = date('Y-m-d H:i:s');

        $user = User::find($userId);
        if (isset($params['gender'])) $user->gender = $params['gender'];
        if (isset($params['name'])) $user->name = $params['name'] . 'c';
        if (isset($params['birthday'])) $user->birthday = strtotime($params['birthday']);
        $user->updated_at = date('Y-m-d H:i:s');
        // 使用事务
        DB::transaction(function () use ($jobHunter, $user) {
            // 当数据无变化时，save方法不会执行。由于使用了事务，只要一个方法没有执行，有变化的数据也不会被保存。
            // 为了防止这种情况发生，我手动更新updated_at
            // 调试挺慢的
            $jobHunter->save();
            $user->save();
        });
    }

    public function saveAdvantage(int $userId, string $advantage)
    {
        $jobHunter = JobHunter::where('user_id', $userId)->first();
        $bool = false;
        if ($jobHunter != null) {
            $jobHunter->advantage = $advantage;
            $bool = $jobHunter->save();
        }
        return $bool;
    }

}

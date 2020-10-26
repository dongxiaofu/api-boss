<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Extend\Pinyin\Pignyin;
use App\Model\District;
use Illuminate\Console\Command;

class ConvertToPinyin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert-too-pinyin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '转为拼音';

    /**
     * 方便查找日志
     * @var string
     */
    protected $logTag = 'convert-too-pinyin';


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->convertDistrictNameToPinyin();
//        $testStr2 = $this->testStr;
//        $filter = new FilterRepeatedComments();
//        // 往过滤器中添加数据
//        $this->info('开始插入数据');
//        for ($i = 0; $i < 90000; $i++) {
//            $str = $this->generateStr();
//            $this->info('=======插入' . $str);
//            $filter->add($str);
//            if ($i % 13 == 0) {
//                $tmp = array_shift($this->testStr);
//                $tmp && $this->info('====插入======' . $tmp);
//                $tmp && $filter->add($tmp);
//            }
//        }
//        $this->info('插入数据结束');
//
//        // 往过滤器中添加重复数据
//        foreach ($testStr2 as $str2) {
//            $res = $filter->exists($str2);
//            if ($res) {
//                $msg = sprintf('%s已经存在', $str2);
//            } else {
//                $msg = '';
//            }
//            \Log::debug(sprintf($msg));
//        }
//
//        $this->info('over');
    }

    private function convertDistrictNameToPinyin()
    {
        District::chunk(200, function ($districts) {
            foreach ($districts as $district) {
                $districtName = $district->district_name;
                $shortPinyin = $this->getShortPinyin($districtName);
                $shortPinyin = strtoupper($shortPinyin);
                $firstLetter = $shortPinyin[0];
                if (ord($firstLetter) < 65 || ord($firstLetter) > 90) {
                    continue;
                }
                $this->info($firstLetter);
                $district->first_letter = $firstLetter;
                $district->save();
            }
        });
        $this->info('over');
    }

    private function getShortPinyin($name)
    {
        $shortPinyin = Pinyin::getShortPinyin($name);
        return $shortPinyin;
    }
}

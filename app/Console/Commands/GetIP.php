<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Model\Customer;
use App\Model\Session;
use App\Model\User;
use App\Model\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use itbdw\Ip\IpLocation;

class GetIP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-ip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取IP';

    /**
     * 方便查找日志
     * @var string
     */
    protected $logTag = 'ip';

    protected $requestCollection;


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->requestCollection = new Collection();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $ip = "222.244.189.233";

        $qqwry_filepath = $path = base_path('vendor/itbdw/ip-database/src/qqwry.dat');
        echo json_encode(IpLocation::getLocation($ip, $qqwry_filepath), JSON_UNESCAPED_UNICODE) . "\n";

//直接用附带的版本
        echo json_encode(IpLocation::getLocation($ip), JSON_UNESCAPED_UNICODE) . "\n";
    }

}


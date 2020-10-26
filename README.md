# boss-api
仿写boss直聘的API项目

安装框架

composer global about

composer create-project --prefer-dist laravel/laravel boss-zhipin

boss-zhipin 是项目名称，即项目根目录，需不存在。

# 框架代码

框架代码行数：875,842。恐怖。看源码，要看到猴年马月。

# 运行laravel项目

访问：http://boss.api-cg.com/

建立配置文件 .env.example，内容见文件 /Users/cg/data/www/boss-api/.env.example 。

执行  composer run post-root-package-install，效果等同 cp .env.example .env。

执行 php artisan key:generate，生成key，解决下面的报错：

[2020-09-27 10:02:23] production.ERROR: No application encryption key has been specified. {"exception":"[object] (RuntimeException(code: 0): No application encryption key has been specified. at /Users/cg/data/www/boss-api/vendor/laravel/framework/src/Illuminate/Encryption/EncryptionServiceProvider.php:80)

访问本站点时，出现500页面，调试思路：

看到500，查看nginx的acess_log和error_log，还有PHP的log。不过，php自身的错误日志，我一般没有配置。看PHP log，一般是
看php框架的log。


laravel的用法：

php artisan make:controller Api/CityController

php artisan make:middleware Jwt

php artisan make:model Model/Job

php artisan event:generate

命令行用法：

1.新建命令行文件：app/Console/Commands/ConvertToPinyin.php

2.在 app/Console/Kernel.php 中注册方法：

protected $commands = [
    ConvertToPinyin::class
];

3.查看能使用的命令 php artisan list

Available commands:
  clear-compiled       Remove the compiled class file
  convert-too-pinyin   转为拼音

convert-too-pinyin 是 app/Console/Commands/ConvertToPinyin.php 在 protected $signature = 'convert-too-pinyin'; 配置的。

4.使用命令

php artisan convert-too-pinyin

laravel避免一次性取出多条数据的方法

Flight::chunk(200, function ($flights) {
    foreach ($flights as $flight) {
        //
    }
});

汉字转拼音使用的类库是

app/Extend/Pinyin/Pinyin.php。

还不错，1000多地区，仅有五个不能正确转换。

启动了那么多次mysql，却没有留下可靠的能用的文档。

部分文件没有提交到git










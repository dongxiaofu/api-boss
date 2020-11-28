<?php
declare(strict_types=1);

namespace App\Service;


class Utils
{
    public static const IMAGE_PATH = '/Users/cg/data/www/cg/html/ws/pic';

    public static function base64_image_content($base64_image_content, $path)
    {
        //匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            $childPath = date('Ymd', time()) . "/";
            $new_file = $path . "/" . $childPath;
            if (!file_exists($new_file)) {
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $filename = time() . ".{$type}";
            $new_file = $new_file . $filename;
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                return $childPath . '/' . $filename;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

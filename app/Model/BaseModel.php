<?php
declare(strict_types=1);

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    //根据model class获取表名 表名中包含_，用驼峰自动转换
    public function getTable()
    {
        return $this->table ? $this->table : $this->getClassName();
    }


    private function getClassName()
    {
        $className = get_class($this);
//        $neddle = <<<EOF
//\
//EOF;
        $neddle = "\\";
        $pos = strrpos($className, $neddle);
        $modelName = substr($className, $pos + 1);
        $baseName = strtolower($modelName);
        return $baseName;
    }

    public function fill(array $attributes)
    {
        $totallyGuarded = $this->totallyGuarded();

        foreach ($this->fillableFromArray($attributes) as $key => $value) {
            $key = $this->removeTableFromKey($key);
            $this->setAttribute($key, $value);
        }

        return $this;
    }

    public static function getConfigItem(string $code, $config)
    {
        foreach ($config as $item) {
            $key = (string)$item['code'] ?? -1;
            if ($key == $code) {
                return $item;
            }
        }
        return [];
    }
}

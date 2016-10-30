<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-08-18
 * Time: 14:26
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Redis;

class RedisController extends Controller {

    private $myHashKey = "myHash";
    private $myHash = [
        "a1"=> 1,
        "a2"=> 2,
        "a3"=> 3,
    ];

    public function index() {
        echo <<<EOF1
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<meta charset="utf-8">
</head>
<body style='font-size:18px;'>
EOF1;
        echo "<pre>";
        echo "<a href=\"".url('setRedis')."\" target=\"_blank\">设置Redis</a>.\n";
        echo "<a href=\"".url('delRedis')."\" target=\"_blank\">删除Redis key(parameter_two)</a>.\n";
        echo "<a href=\"".url('getRedis')."\" target=\"_blank\">从Redis取值</a>.\n";
    }
    public function setRedis() {
        echo <<<EOF1
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<meta charset="utf-8">
</head>
<body style='font-size:18px;'>
EOF1;
        echo "<pre>";
        Cache::forever("parameter_one", time());
        Cache::forever("parameter_two", time());
        echo "设置parameter_one=当前时间.\n";
        echo "设置parameter_two=当前时间.\n";

        Redis::hmset($this->myHashKey, $this->myHash);
        echo "设置哈希key:myHash.\n";
    }


    public function getRedis() {
        echo <<<EOF1
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<meta charset="utf-8">
</head>
<body style='font-size:18px;'>
EOF1;
        echo "<pre>";
        echo "取值：\n";
        echo "parameter_one=".Cache::get("parameter_one")."<hr>\n";
        echo "parameter_two=".Cache::get("parameter_two")."<hr>\n";
        $pars = [$this->myHashKey];
        $pars = array_merge($pars, array_keys($this->myHash));
        $result = call_user_func_array('Redis::hmget', $pars);
        echo "HashKey:".$this->myHashKey."\n";
        print_r($result);
    }
    public function delRedis() {
        echo <<<EOF1
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>

<meta charset="utf-8">
</head>
<body style='font-size:18px;'>
EOF1;
        echo "<pre>";
        echo "删除key:parameter_two\n";
//        Redis::del("laravel:parameter_two");
        Cache::forget("parameter_two");
        echo "也可采取原生redis删除命令，Redis::del(\"laravel:parameter_two\"),需要注意的是，laravel在操作redis时，加入了前辍，具体控制在\config\cache.php,'prefix' => 'laravel'\n";
    }

}

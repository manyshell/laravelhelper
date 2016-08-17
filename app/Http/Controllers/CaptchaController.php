<?php
/**
 * 验证码
 * Created by Hzg
 * Date: 2015-08-27
 * Time: 上午10:06
 */

namespace App\Http\Controllers;

use Session;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Encryption;
use Illuminate\Cookie;

class CaptchaController extends Controller {

    public function index() {
        echo <<<Eof
<!DOCTYPE html>
<html lang="en">
<meta charset='utf-8'>
<head>
<title>captcha test</title>
</head>
<body>
    <img src="/captcha/captcha">
</body>
</html>
Eof;
    }

    /**
     * 输出验证码
     */
    public function captcha()
    {
        $builder = new CaptchaBuilder('123456');
        $builder->build();
        $phrase = $builder->getPhrase();
        Session::put('admin_captcha', $phrase);
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        $builder->output();
    }
}

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
        $captcha2 = file_get_contents(url('/captcha/captcha2'));
        echo <<<Eof
<!DOCTYPE html>
<html lang="en">
<meta charset='utf-8'>
<head>
<title>captcha test</title>
</head>
<body>
    <pre>
    <div style="background:green;height:300px;font-size:20px;">
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
    今天很残酷，明天更残酷，后天会很美好，但绝大多数人都死在明天晚上。
        <div style="position: absolute;top: 40px;left: 30px;color:#fff;font-size:18px;">
            1、输出自定的验证码
            <img src="/captcha/captcha">
            2、输出随机验证码，自定义尺寸
            <img src="/captcha/captcha1" style="margin-left:20px;">
            3、输出内联图片的验证码
            <img src="{$captcha2}">
        </div>
    </div>
    1、验证码文字尺寸:
    CaptchaBuilder.php[326行]
    \$size = \$width / \$length - \$this->rand(0, 3) - 1;
    可固定size大小，如size=12;
    2、将验证码图片保存到服务器
    \$builder->save('out.jpg');
    可以在public目录下，看到out.jpg文件
    3、内联图片
    \$builder->inline();
</body>
</html>
Eof;
    }

    /**
     * 输出验证码
     */
    public function captcha()
    {
        $builder = new CaptchaBuilder('1234');
        $builder->build();
        $builder->isOCRReadable();
        $phrase = $builder->getPhrase();
        Session::put('captcha', $phrase);
//        header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type: image/jpeg");
        $builder->output($quality = 90);
    }

    /**
     * 自定义尺寸
     */
    public function captcha1()
    {
        $builder = new CaptchaBuilder();
        $builder->build($width = 100, $height = 25, $font = null);
        $phrase = $builder->getPhrase();
        Session::put('captcha1', $phrase);
        //header("Cache-Control: no-cache, must-revalidate");
        header("Content-Type: image/jpeg");
        $builder->save(public_path('temp/out.jpg'));
        $builder->output($quality = 20);
    }

    /**
     * 内联图片
     */
    public function captcha2()
    {
        $builder = new CaptchaBuilder();
        $builder->build();
        echo $builder->inline();
    }
}

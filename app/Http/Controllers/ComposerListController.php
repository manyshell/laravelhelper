<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-08-17
 * Time: 17:04
 */

namespace App\Http\Controllers;

class ComposerListController extends Controller {

    /**
     * Composer模块说明
     */
    public function index() {
        echo <<<EOF1
            <!DOCTYPE html>
            <html lang="en">
            <meta charset='utf-8'>
            <head>
            <title>Composer模块说明</title>
            </head>
            <body>
EOF1;
        echo "<table border='1' style='border: dotted 1px rebeccapurple;width: 100%;padding:10px;font-size:18px;'>";
        echo "<tr>";
        echo "<td>\"gregwar/captcha\": \"dev-master\"</td>";
        echo "<td>验证码</td>";
        echo "<tr>";
        echo "</table>";
        echo <<<EOF2
            </body>
            </html>
EOF2;
    }

}

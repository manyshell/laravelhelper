<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-08-17
 * Time: 17:04
 */

namespace App\Http\Controllers;

use Category;

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
        echo "<td>\"gregwar/captcha\": \"dev-master\",</td>";
        echo "<td><a href='".url('captcha')."' target='_blank'>验证码</a></td>";
        echo "<tr>";
        echo "<tr>";
        echo "<td>\"predis/predis\": \"^1.0\",</td>";
        echo "<td><a href='".url('redis')."' target='_blank'>redis</a></td>";
        echo "<tr>";
        echo "</table>";
        echo <<<EOF2
            </body>
            </html>
EOF2;
    }

    public function test() {
        $node = Category::create([
            'name' => 'Foo',

            'children' => [
                [
                    'name' => 'Bar',

                    'children' => [
                        [ 'name' => 'Baz' ],
                    ],
                ],
            ],
        ]);
        print_r($node);
    }

}

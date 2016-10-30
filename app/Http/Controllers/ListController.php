<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-08-17
 * Time: 17:04
 */

namespace App\Http\Controllers;

use Category;

class ListController extends Controller {

    /**
     * Composer模块说明
     */
    public function index() {
        return view('list');
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

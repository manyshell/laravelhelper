<?php
/**
 *
 * Created by Hzg.
 * Date: 2016-11-17
 * Time: 4:07
 */

namespace App\Http\Controllers;

class DatetimeController extends Controller
{

    public function index()
    {
        return view('daycountdown');
    }
}

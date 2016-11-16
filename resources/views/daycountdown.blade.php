@extends('layouts.master')

@section('title', '日期倒计时')

@section('head')
@stop

@section('body')
    <div class="container">
        <div class="jumbotron">
            <h1><span id="end_time"></span></h1>
        </div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        require(['jquery','moment'], function($, moment) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var endTime = moment("2016-12-31 23:59:59", "YYYY-MM-DD HH:mm:ss");
            var servTime = moment("{{ date("Y-m-d H:i:s",time()) }}", "YYYY-MM-DD HH:mm:ss");
            var nowTime = moment();
            var subSec = parseInt(servTime.diff(nowTime, 'seconds', true), 10);
            var overTime;
            function durationTime() {
                nowTime = moment().add(subSec, 'seconds');
                var endSeconds = parseInt(endTime.diff(nowTime, 'seconds', true), 10);
                var overTime = moment.duration(endSeconds, 'seconds');
                if (overTime > 0) {
                    var countdown = setTimeout(function () {
                        var outStr = "距9月3日还剩 ";
                        outStr += overTime.days() + " 天 " + overTime.hours() + " 时 " + overTime.minutes() + " 分 " + overTime.seconds() + " 秒";
                        outStr += " 。";
                        $("#end_time").text(outStr);
                        durationTime();
                    }, 1000);
                } else {
                    $("#end_time").text("时间到，你懂的。");
                }
            }
            durationTime();
        });
    </script>
@stop
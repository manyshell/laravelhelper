@extends('layouts.master')

@section('title', 'LIST')

@section('head')
    {{--<link rel="stylesheet" type="text/css" href="/components/bootstrap/css/bootstrap.css?{{ mt_rand(1000,9999) }}">--}}

@stop

@section('body')
    <div class="container">
        <div class="row clearfix">
            <div class="col-md-12 column">
                <table class="table table-striped">
                    <caption style="text-align: center;font-size: 20px;">Composer模块介绍</caption>
                    <thead>
                    <tr>
                        <th>
                            编号
                        </th>
                        <th>
                            功能
                        </th>
                        <th>
                            Require
                        </th>
                        <th>
                            详情
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <span class="listNo"></span>
                        </td>
                        <td>
                            验证码
                        </td>
                        <td>
                            "gregwar/captcha": "1.*",
                        </td>
                        <td>
                            <a href='{{url('captcha')}}' target='_blank'>查看</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="listNo"></span>
                        </td>
                        <td>
                            Redis
                        </td>
                        <td>
                            "predis/predis": "^1.0",
                        </td>
                        <td>
                            <a href='{{url('redis')}}' target='_blank'>查看</a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="listNo"></span>
                        </td>
                        <td>
                            无限分类
                        </td>
                        <td>
                            "kalnoy/nestedset": "4.*"
                        </td>
                        <td>
                            <a href='{{url('nestedset')}}' target='_blank'>查看</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('script')
<script type="text/javascript">
    require(['jquery','bootstrap'], function($) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var No = 0;
        $(".listNo").each(function(){
            No++;
            $(this).text(No);
        });
    });
</script>
@stop
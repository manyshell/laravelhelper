(function (window, $) {
    var msgBox = function (opt) {
        var self = this;
        this.title = opt.title || '温馨提示';
        this.content = opt.content || 'load...';
        this.width = opt.width || 300;
        this.height = opt.height || 180; //弃用
        this.isMask = opt.isMask || false;
        this.ok = opt.ok || false;

        this.dom = document.createElement('div');
        this.dom.style.position = 'absolute';
        this.dom.style.top = '0px';
        this.dom.style.left = '0px';

        //遮罩层
        if (this.isMask) {
            this.mask = '<div style="position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 100;background: #000;opacity: 0.5;filter: alpha(opacity=50);"></div>';
            this.dom.innerHTML = this.mask;
        }
        //
        if (typeof this.content == 'object') {
            this.content = this.content.html();
        }

        this.init();

        document.addEventListener('touchmove', touchmove);

        //监听窗口重设
        $(window).resize(function(){
            self._resize();
        });
    };

    msgBox.prototype = {
        init: function () {
            this.getWindowSize();
            var posWidth = this.windowWidth / 2 - this.width / 2 + 'px',
                posHeight = this.windowHeight / 2 - this.height / 2 + 'px',
                dom = this.dom,
                innerDiv,
                html = '';

            innerDiv = document.createElement('div');
            innerDiv.style.position = 'fixed';
            innerDiv.style.zIndex = '200';
            innerDiv.style.left = posWidth;
            innerDiv.style.top  = posHeight;
            innerDiv.style.width = this.width+'px';
            innerDiv.className = 'prompt_box px';

                //html+= '<span class="close" style="position: absolute;padding: 2px; top: 0; right: 2px; cursor: pointer;">X</span>';
                html+= '<div class="t" style="height:auto; padding: 0 15px 15px 15px;"><p>'+this.title+'</p><p>'+this.content+'</p></div>';
                //html+= '<input type="button" class="btn form-control btnn hiwrap" value="确定">';

            //按钮组
            var btn = document.createElement('input');
            btn.type = 'button';
            btn.className = 'btn form-control btnn hiwrap';
            btn.value = '确定';
            innerDiv.innerHTML = html;
            innerDiv.appendChild(btn);

            btn.onclick = function () {
                $(dom).remove();
                document.removeEventListener("touchmove", touchmove);
            };

            //有回调按钮
            if(this.ok){
                btn.style.width = '50%';
                var okBtn = btn.cloneNode(true);
                btn.style.borderRight = '1px solid #9e9e9e';
                okBtn.onclick = this.ok;

                btn.value= '取消';
                innerDiv.appendChild(okBtn);
            }

            dom.appendChild(innerDiv);
            document.body.appendChild(dom);
        },
        getWindowSize: function () {
            // 获取窗口宽度
            if (window.innerWidth)
                this.windowWidth = window.innerWidth;
            else if ((document.body) && (document.body.clientWidth))
                this.windowWidth = document.body.clientWidth;
            // 获取窗口高度
            if (window.innerHeight)
                this.windowHeight = window.innerHeight;
            else if ((document.body) && (document.body.clientHeight))
                this.windowHeight = document.body.clientHeight;
            return this;
        },
        close: function () {
            this.dom.remove();
        },
        _resize: function() {
            this.getWindowSize();
            var posWidth  = this.windowWidth / 2 - this.width / 2 + 'px',
                posHeight = this.windowHeight / 2 - this.height / 2 + 'px';

            this.dom.getElementsByClassName('prompt_box')[0].style.left = posWidth;
            this.dom.getElementsByClassName('prompt_box')[0].style.top  = posHeight;
        }
    };

    window.touchmove = function (event) {
        //判断条件,条件成立才阻止背景页面滚动,其他情况不会再影响到页面滚动
        event.preventDefault();
    };

    window.msgBox = msgBox;

    /**
     * 封装对话框函数
     * @param content
     * @returns {msgBox}
     */
    var showErr = function(content){
        //return new msgBox({content:content, isMask:true});
        return swalBox({msg:content,buttonNumber:1});
    };
    window.showErr = showErr;

    /**
     * 有回调按钮的对话框
     * @param content
     * @param callback 回调函数
     * @returns {msgBox}
     */
    var confirmBox = function(content, callback){
        return new msgBox({content:content, isMask:true, ok:callback});
        //swalBox({msg:content,buttonNumber:2,confirmEvent:callback});
    };
    window.confirmBox = confirmBox;


    window.checkPID = function (code) {
        var city={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江 ",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北 ",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏 ",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外 "};
        var tip = "";
        var pass = true;

        if(!code || !/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[0-2])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i.test(code)) {
            tip = "身份证号格式错误";
            pass = false;
        } else if(!city[code.substr(0,2)]) {
            tip = "地址编码错误";
            pass = false;
        } else {
            //18位身份证需要验证最后一位校验位
            if(code.length == 18) {
                code = code.split('');
                //∑(ai×Wi)(mod 11)
                //加权因子
                var factor = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2 ];
                //校验位
                var parity = [ 1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2 ];
                var sum = 0;
                var ai = 0;
                var wi = 0;
                for (var i = 0; i < 17; i++) {
                    ai = code[i];
                    wi = factor[i];
                    sum += ai * wi;
                }
                //var last = parity[sum % 11];
                if(parity[sum % 11] != code[17]){
                    tip = "校验位错误";
                    pass = false;
                }
            }
        }
        if(!pass) showErr("身份证号码不正确");
        return pass;
    };

    window.checkPhone = function (code) {
        if(!/^1[34578]\d{9}$/.test(code)) {
            showErr("您输入的手机号码不正确");
            return false;
        } else {
            return true;
        }
    };

    window.checkRealName = function (code) {
        // \u4e00-\u9fa5 中文正则
        if(!/^.{1,24}$/.test(code)) {
            showErr("持卡人姓名只能是1-24个字符");
            return false;
        } else {
            return true;
        }
    };

    window.checkCardNo = function (code) {
        if(!/^[0-2](\d{14}|\d{9})$/.test(code)) {
            showErr("您输入的卡号不正确");
            return false;
        } else {
            return true;
        }
    };

    window.checkVerifyCode = function (code) {
        if(!/^\d{6}$/.test(code)) {
            showErr("验证码必须是6位数字");
            return false;
        } else {
            return true;
        }
    };

    window.checkPwd = function (code) {
        if(!/^\d{6}$/.test(code)) {
            showErr("密码只允许输入6位数字,请重新输入");
            return false;
        } else {
            return true;
        }
    };

    window.localStorage.setItem("swalBusy", (new Date().getTime()-1000));
    var swalBox = function(p){
        var swalBusy = window.localStorage.getItem("swalBusy");
        var nowTime = new Date().getTime();
        if (nowTime - swalBusy > 700) {
            window.localStorage.setItem("swalBusy", new Date().getTime());
            document.addEventListener('touchmove', touchmove);
            if (p.buttonNumber == 1) {
                if (p.extend == 1) {
                    var msg = "";
                    msg += "<div class=\"oilalert_wrap\" style='width:100%;margin-top: 13px;'>";
                    msg += "<div class=\"oilalert_red\"></div>";
                    msg += "<div class=\"oilalert_content\">";
                    msg += "<div class=\"oilalert_left\">¥<br/><span>储油券</span></div>";
                    msg += "<div class=\"oilalert_middle\">" + p.msg_money + "</div>";
                    msg += "<div class=\"oilalert_right\">有效期<br/>" + p.msg_deadline + "</div>";
                    msg += "</div>";
                    msg += "</div>";
                    if (p.msg_isBind == 1) {
                        msg += "<div style='padding: 20px 18px 0px 18px;text-align: center;font-size: 1.4rem;color: #999;'>";
                        msg += "该卡已添加到国通石油APP用户账户中，您可以通过APP查看相应的储油券";
                        msg += "</div>";
                    }
                    swal({
                            title: "储油券添加成功",
                            text: msg,
                            html: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: true,
                            confirmButtonText: "确定",
                            confirmButtonColor: "#ff6600",
                            closeOnConfirm: false,
                            customClass: 'gt_oil_certificate'
                        }, function (isConfirm) {
                            if (isConfirm) {
                                swal.close();
                                document.removeEventListener("touchmove", touchmove);
                                var delayTime;
                                delayTime = setTimeout(function () {
                                    if (p.confirmEvent) {
                                        eval(p.confirmEvent);
                                    }
                                }, 600);
                            }
                        }
                    );
                } else if (p.extend == 2) {
                    var msg = "";
                    var detail = p.detail.split("_");
                    msg += "<div style='width:100%;-webkit-text-size-adjust: none;'>";
                    msg += "<div style='padding:4px 0;font-size: 1.6rem;line-height: 16px;color:#333;'>"+detail[0]+"</div>";
                    msg += "<div style='padding:4px 0 15px 0;font-size: 1.4rem;line-height: 14px;color:#8e8e93;border-bottom: 1px dashed #ccc;'>交易时间："+detail[1]+"</div>";
                    msg += "<div style='padding:14px 0 4px 0;font-size: 1.4rem;line-height: 14px;' class='li_blue'>交易金额："+detail[2]+"元</div>";
                    msg += "<div style='padding:4px 0 4px 0;font-size: 1.4rem;line-height: 14px;color:#555;'>当日油价："+detail[3]+"元/升</div>";
                    msg += "<div style='padding:4px 0 10px 0;font-size: 1.4rem;line-height: 14px;color:#555;'>加油升数："+detail[4]+"升</div>";
                    msg += "<div style='padding:10px 0 4px 0;font-size: 1.4rem;line-height: 14px;color:#555;'>储油均价："+detail[5]+"元/升</div>";
                    msg += "<div style='padding:4px 0 4px 0;font-size: 1.4rem;line-height: 14px;color:#555;'>结算单价："+detail[6]+"元/升</div>";
                    if (detail[9] != 0) {
                        msg += "<div style='padding:4px 0 4px 0;font-size: 1.4rem;line-height: 14px;color:#555;'>抵扣金额：" + detail[7] + "元 (" + detail[9] + ")</div>";
                    }
                    msg += "<div style='padding:4px 0 4px 0;font-size: 1.4rem;line-height: 14px;' class='li_orange'>实付金额："+detail[8]+"元</div>";
                    msg += "</div>";
                    swal({
                            title: "交易详情",
                            text: msg,
                            html: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: true,
                            confirmButtonText: " ",
                            confirmButtonColor: "#ffffff",
                            closeOnConfirm: false,
                            customClass: 'gt_bill'
                        }, function (isConfirm) {
                            if (isConfirm) {
                                swal.close();
                                document.removeEventListener("touchmove", touchmove);
                                var delayTime;
                                delayTime = setTimeout(function () {
                                    if (p.confirmEvent) {
                                        eval(p.confirmEvent);
                                    }
                                }, 600);
                            }
                        }
                    );
                } else {
                    swal({
                            title: "温馨提示",
                            text: p.msg,
                            html: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: true,
                            confirmButtonText: "确定",
                            confirmButtonColor: "#d8ddde",
                            closeOnConfirm: false,
                            customClass: 'gt_single_button'
                        }, function(isConfirm){
                            if (isConfirm) {
                                swal.close();
                                document.removeEventListener("touchmove", touchmove);
                                var delayTime;
                                delayTime = setTimeout(function() {
                                    if (p.confirmEvent) {
                                        eval(p.confirmEvent);
                                    }
                                }, 600);
                            }
                        }
                    );
                }
                var customClass = "gt_single_button";
                // $(".sweet-alert."+customClass+" .sa-button-container").css("background-color","rgba(216, 221, 222,0)");
                $(".sweet-alert."+customClass+" .sa-confirm-button-container").css("width","100%");
                // $(".sweet-alert."+customClass+" button.confirm").css("left","0px");
            } else if (p.buttonNumber == 2) {
                var type = "";
                var inputValue = "";
                var customClass = "gt_two_button";
                if (p.type) {
                    customClass = "gt_two_button_input";
                    swal({
                        title: "温馨提示",
                        text: p.msg,
                        html: true,
                        type: p.type,
                        inputValue : p.inputValue,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: true,
                        confirmButtonText: "确定",
                        confirmButtonColor: "#d8ddde",
                        showCancelButton: true,
                        cancelButtonText: "取消",
                        closeOnCancel: false,
                        closeOnConfirm: false,
                        customClass: customClass
                    }, function(inputValue){
                        if (inputValue == false) {
                            if (inputValue.length == 0) {
                                swalBox({
                                    "buttonNumber" : "2",
                                    "msg"          : "<span style='color:red;'>请输入您的储油卡卡号</span>",
                                    "type"         : p.type,
                                    "inputValue"   : inputValue,
                                    "align"        : "center",
                                    "confirmEvent" : p.confirmEvent
                                });
                            } else {
                                swal.close();
                                document.removeEventListener("touchmove", touchmove);
                                var delayTime;
                                delayTime = setTimeout(function() {
                                    if (p.cancelEvent) {
                                        eval(p.cancelEvent);
                                    }
                                }, 600);
                            }
                        } else {
                            //console.log("val():"+inputValue);
                            if (p.confirmEvent) {
                                if(typeof(p.confirmEvent) == "function"){
                                    p.confirmEvent(inputValue);
                                } else {
                                    eval(p.confirmEvent);
                                }
                            }
                        }
                    });
                } else {
                    swal({
                        title: "温馨提示",
                        text: p.msg,
                        html: true,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: true,
                        confirmButtonText: "确定",
                        confirmButtonColor: "#d8ddde",
                        showCancelButton: true,
                        cancelButtonText: "取消",
                        closeOnCancel: false,
                        closeOnConfirm: false,
                        customClass: customClass
                    }, function(isConfirm){
                        if (isConfirm) {
                            swal.close();
                            document.removeEventListener("touchmove", touchmove);
                            var delayTime;
                            delayTime = setTimeout(function() {
                                if (p.confirmEvent) {
                                    eval(p.confirmEvent);
                                }
                            }, 600);
                        } else {
                            swal.close();
                            document.removeEventListener("touchmove", touchmove);
                            var delayTime;
                            delayTime = setTimeout(function() {
                                if (p.cancelEvent) {
                                    eval(p.cancelEvent);
                                }
                            }, 600);
                        }
                    });
                }
                if (p.type) {
                    $(".sweet-alert."+customClass+" input").attr("pattern", "[0-9]*");
                    $(".sweet-alert."+customClass+" input").attr("maxlength", "15");
                }
                if (p.align) {
                    $(".sweet-alert."+customClass+" p").css("text-align", p.align);
                }
                $(".sweet-alert."+customClass+" .sa-confirm-button-container").css("width","50%");
                $(".sweet-alert."+customClass+" button.confirm").css("left","-5px");
            } else if (p.buttonNumber == "noButton") {
                if (p.timeout) {
                    window.localStorage.setItem("swalBusy", new Date().getTime()+p.timeout);
                    swal({
                            title: p.msg,
                            html: true,
                            timer: p.timeout,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            customClass: 'gt_no_button'
                        }, function(){
                            $(".sweet-alert.gt_no_button").css("display", "none");
                            $(".sweet-alert.gt_no_button").css("width", "auto");
                            swal.close();
                            document.removeEventListener("touchmove", touchmove);
                            var delayTime;
                            delayTime = setTimeout(function() {
                                if (p.confirmEvent) {
                                    eval(p.confirmEvent);
                                }
                            }, 600);
                        }
                    );
                    if (p.width) {
                        $(".sweet-alert.gt_no_button").css("width", p.width);
                    } else {
                        $(".sweet-alert.gt_no_button").css("width", "140px");
                    }
                } else {
                    swal({
                            title: p.msg,
                            html: true,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            customClass: 'gt_no_button'
                        }
                    );
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    };
    window.swalBox = swalBox;
}(window, jQuery));

$(function(){
    $(".call_96566").click(function(){
        window.location.href = "tel:96566";
    });
});

<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <title>信息变更</title>
</head>
<body>
<!--办理须知-->
<div class="content ">
    <div class="content_box">
        <div class="ill_head">
            <div class="illegal">
                <div class="ill_tit driver ill_current">驾驶人信息变更</div>
            </div>
            <div class="illegal">
                <div class="ill_tit car">机动车信息变更</div>
            </div>
        </div>
        <div class="ill_query first">
            <div class="query_box jsr" style="display: block" >
                <div class="adap_range1">
                    <div>办理须知</div>
                    <div>1、驾驶人信息变更业务只能办理驾驶人的联系电话和邮寄地址的变更。<br/>
                        2、如需申请变更其他信息（如变更身份证号码、姓名等）请前往无锡车管所咨询办理(咨询电话:96122、81188228)。<br/>
                        3、<请驾驶人如实填写相关信></请驾驶人如实填写相关信>息。凡提供虚假信息，需承担相应法律后果。</div>
                </div>
                <a id="tiao1" href="javascript:;">确定</a>
            </div>
            <div class="query_box jdc"  style="display:none;">
                <div class="adap_range1">
                    <div>办理须知</div>
                    <div>1、机动车信息变更业务只能办理机动车所有人的联系电话和邮寄地址的变更。<br>
                        2、如需申请变更其他信息（如变更身份证号码、姓名等）请前往无锡车管所咨询办理（咨询电话：96122,81188228）。<br>
                        3、请机动车所有人如实填写相关信息。凡提供虚假信息，需承担相应法律后果。
                    </div>
                </div>
                <a id="tiao" href="javascript:;">确定</a>
            </div>
        </div>

        <div class="ill_query first">
            <div class="query_box change_jsr" style="display: none">
                <div class="car_style">
                    <div>驾驶证号</div>
                    <input type="text" name="drivernum" id="car_number1" placeholder="请输入驾驶证号" />
                </div>
                <div class="car_style">
                    <div>证芯编号<br><a class="where">(在哪里)</a></div>
                    <input type="text" name="driverpapers" placeholder="请输入证芯编号后六位或档案编号后六位" id="car_fa1" />
                </div>
                <a id="query1">下一步</a>
            </div>
            <div class="query_box change_jdc" style="display: none">
                <div class="car_style">
                    <div>车辆类型</div>
                    <select name="chcartype">
                        <option value="02">小型车辆</option>
                        <option value="01">大型车辆</option>
                    </select>
                </div>
                <div class="car_style">
                    <div>车牌号码</div>
                    <input type="text" name="chcarnum" id="car_number" value="苏BG"/>
                </div>
                <div class="car_style">
                    <div>身份编号</div>
                    <input type="text" name="chcarpapers" placeholder="请输入身份证后六位或机构代码证前六位" id="car_fa" />
                </div>
                <a id="query">下一步</a>
            </div>
        </div>
    </div>

<script>
    var process = false;
    $("#loading-fs").hide();
    $(function(){
        $('.driver').click(function(){
            $('.ill_tit').removeClass('ill_current');
            $(this).addClass('ill_current');
            $('.jdc').hide();
            $('.change_jsr').hide();
            $('.change_jdc').hide();
            $('.gg_driver').hide();
            $('.gg_drivercar').hide();
            $('.jsr').show();
            $('.first').show();
        });
        $('.car').click(function(){
            $('.ill_tit').removeClass('ill_current');
            $(this).addClass('ill_current');
            $('.change_jsr').hide();
            $('.change_jdc').hide();
            $('.gg_driver').hide();
            $('.gg_drivercar').hide();
            $('.jsr').hide();
            $('.jdc').show();
            $('.first').show();
        });

        $('#tiao1').click(function(){
            $('.jsr').hide();
            $('.change_jsr').show();
        });
        $('#tiao').click(function(){
            $('.jdc').hide();
            $('.change_jdc').show();
        });

        //驾驶人数据
        $('#query1').click(function(){
            if(process)return false;
            process = true;
            var drivernum = $("[name = 'drivernum']").val();
            var driverpapers = $("[name = 'driverpapers']").val();
            reg = /^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i;
            if (drivernum == ''){
                alert('驾驶证编号不能为空！');return false;
            }
            if (!reg.test(drivernum)){
                alert('驾驶证编号不正确！');return false;
            }
            if (driverpapers == ''){
                alert('证芯编号不能为空！');return false;
            }
            if (driverpapers.length != 6){
                alert('证芯编号输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('drivermanage')?>",{'drivernum':drivernum,'driverpapers':driverpapers},function(data){
                //console.log(data);
                $("#loading-fs").hide();
                if(data.State == 0){
                    $('.first').hide();
                    $('.change').hide();
                    $('.gg_drivercar').hide();
                    $('.gg_driver').show();
                    $(".drname").text(data.XM);
                    $(".draddr").text(data.LXDZ);
                    $(".drphone").text(data.SJHM);
                    $(".drmobile").text(data.LXDH);
                }else if(data.State == 1){
                    alert('查询失败！');
                }else if(data.State == 2){
                    alert('空记录！');
                }else if(data.State == 3){
                    alert('未授权！');
                }else if(data.State == 4){
                    alert('无权限访问！');
                }else if(data.State == 5){
                    alert('非法参数！');
                }
                //0：成功、1：失败、2：空记录、3：未授权、4：无权限访问、5：非法参数
                //window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'subdata','block'=>'driver'))?>&drivernum="+drivernum+"&driverpapers="+driverpapers;
                process = false;
            },'JSON');
        });
        //机动车信息
        $('#query').click(function(){
            if(process)return false;
            process = true;
            var chcartype = $("[name = 'chcartype']").val();
            var chcarnum = $("[name = 'chcarnum']").val();
            var chcarpapers = $("[name = 'chcarpapers']").val();
            reg=/^苏B[0-9A-Z]{5}$/;
            if (!reg.test(chcarnum)){
                alert('请正确输入车牌号');return false;
            }
            if (chcarpapers == ''){
                alert('证件号码不能为空！');return false;
            }
            if(chcarpapers.length != 6){
                alert('证件号码输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('carmanage')?>",{'chcartype':chcartype,'chcarnum':chcarnum,'chcarpapers':chcarpapers},function(data){
                //console.log(data);
                $("#loading-fs").hide();
                if (data.State == 0){
                    $('.first').hide();
                    $('.change').hide();
                    $('.gg_drivercar').hide();
                    $('.gg_driver').show();
                    $(".drname").text(data.XM);
                    $(".draddr").text(data.LXDZ);
                    $(".drphone").text(data.SJHM);
                    $(".drmobile").text(data.LXDH);
                    //window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'subdata','block'=>'car'))?>&chcartype="+chcartype+"&chcarnum="+chcarnum+"&chcarpapers="+chcarpapers;
                }
                process = false;
            },'JSON');

        });

    });
</script>
<!--信息-驾驶人--车辆-->
        <div class="gg_driver" style="display: none;">
            <div class="info_change">
                <div class="change_box">
                    <div class="infor_box">
                        <div class="infor1">
                            <p>姓&nbsp;&nbsp;名:</p>
                            <p class="drname"></p>
                        </div>
                        <div class="infor1">
                            <p>固定电话:</p>
                            <p class="drmobile"></p>
                        </div>
                        <div class="infor1">
                            <p>移动电话:</p>
                            <p class="drphone"></p>
                        </div>
                        <div class="infor2">
                            <p>邮寄地址:</p>
                            <p class="draddr"></p>
                        </div>

                    </div>
                </div>
                <p class="infor_care">注：以上信息仅供核对使用</p>
                <div class="infor_btn">
                    <button class="myupdate">我要修改</button>
                    <button class="wuupdate">无需修改</button>
                </div>
            </div>
        </div>

    <script>
        var process = false;
        $("#loading-fs").hide();
        $(function(){
            $('.myupdate').click(function(){
                $('.first').hide();
                $('.change').hide();
                $('.gg_driver').hide();
                $('.gg_drivercar').show();
            });
            $('.wuupdate').click(function(){
                location.reload();
            });
        });
    </script>
<!--信息更改-驾驶人-机动车-->
        <div class="content_box gg_drivercar" style="display: none">
            <div class="infor_change1">
                <p class="change1_care">如无需修改项目，请勿填写</p>
                <div class="change1_box">
                    <div class="change1">
                        <p class="number">新固定电话号码:<span>例如：0510-68818588</span></p>
                        <input id="number" type="number" placeholder="请输入您的新固定电话号码" name="lxdh">
                        <p class="number1">新移动电话号码:</p>
                        <input id="number1" type="number" placeholder="请输入您的新移动电话号码" name="sjhm">
                        <p class="add_p">新邮寄地址:</p>
                        <div class="address_box">
                            <p>江苏省</p>
                            <select name="city">
                                <option value="无锡市">无锡市</option>
                                <option value="江阴市">江阴市</option>
                                <option value="宜兴">宜兴</option>
                            </select>
                            <input type="text" name="district">
                            <p>区</p>
                        </div>
                        <input id="address" type="text" placeholder="请填写详细街道门牌地址" name="address">
                        <p class="change1_tip">如不在无锡地区居住请将驾驶证转至居住地车管所</p>
                    </div>
                </div>
                <button id="modify">我要修改</button>
            </div>
        </div>

    <script>
        var process = false;
        $("#loading-fs").hide();
        $(function(){
            $('#modify').click(function(){
                if(process)return false;
                process = true;
                //驾驶证变更
                var drivernum = $("[name = 'drivernum']").val();
                var driverpapers = $("[name = 'driverpapers']").val();
                //车辆变更
                var chcartype = $("[name = 'chcartype']").val();
                var chcarnum = $("[name = 'chcarnum']").val();
                var chcarpapers = $("[name = 'chcarpapers']").val();
                //公共参数
                var lxdh = $("[name = 'lxdh']").val();
                var sjhm = $("[name = 'sjhm']").val();
                var lsdz = $("[name = 'city']").val()+$("[name = 'district']").val()+$("[name = 'address']").val();
                if(drivernum && driverpapers){
                    //alert(1);
                    $.post("<?php  echo $this->createMobileUrl('drpost')?>",{'drivernum':drivernum,'driverpapers':driverpapers,'lxdh':lxdh,'sjhm':sjhm,'lsdz':lsdz},function(data){
                        $("#loading-fs").hide();
                        if (data.State == 0){
                            alert('ok'+data.ZXBH);
                            $('.content').hide();
                            $('.succeed').show();
                        }else if(data.State == 1){
                            alert('失败'+data.ZXBH);
                        }
                        process = false;
                    },'JSON');
                }
                if(chcartype && chcarnum && chcarpapers){
                    $.post("<?php  echo $this->createMobileUrl('carpost')?>",{'chcartype':chcartype,'chcarnum':chcarnum,'chcarpapers':chcarpapers,'lxdh':lxdh,'sjhm':sjhm,'lsdz':lsdz},function(data){
                        $("#loading-fs").hide();
                        if (data.State == 0){
                            alert('ok'+data.HPHM);
                            $('.content').hide();
                            $('.succeed').show();
                        }else if(data.State == 1){
                            alert('失败'+data.HPHM);
                        }
                        process = false;
                    },'JSON');
                }
            });
        });
    </script>



<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
</div>
<div class="content_box succeed" style="display: none">
    <div class="appeal1_box">
        <i class="appeal_suc">&#xe60d;</i>
        <div>您的变更信息已经收到，我们将为您尽快处理。</div>
    </div>
    <a id="appeal_back" href="<?php  echo $this->createMobileUrl('infochange',array('op' => 'notice'))?>">返回</a>
</div>
</body>
</html>

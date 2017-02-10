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
<?php  if($op == 'notice') { ?>
<div class="content">
    <div class="content_box">
        <div class="ill_head">
            <div class="illegal">
                <div class="ill_tit driver <?php  if($block == 'driver') { ?>ill_current<?php  } ?>">驾驶人信息变更</div>
            </div>
            <div class="illegal">
                <div class="ill_tit car <?php  if($block == 'car') { ?>ill_current<?php  } ?>">机动车信息变更</div>
            </div>
        </div>
        <div class="ill_query">
            <?php  if($block == 'driver') { ?>
            <div class="query_box" >
                <div class="adap_range1">
                    <div>办理须知</div>
                    <div>1、驾驶人信息变更业务只能办理驾驶人的联系电话和邮寄地址的变更。<br/>
                        2、如需申请变更其他信息（如变更身份证号码、姓名等）请前往无锡车管所咨询办理(咨询电话:96122、81188228)。<br/>
                        3、<请驾驶人如实填写相关信></请驾驶人如实填写相关信>息。凡提供虚假信息，需承担相应法律后果。</div>
                </div>
                <a id="query1" href="<?php  echo $this->createMobileUrl('infochange',array('op'=>'change','block'=>'driver'))?>">确定</a>
            </div>
            <?php  } ?>
            <?php  if($block == 'car') { ?>
            <div class="query_box">
                <div class="adap_range1">
                    <div>办理须知</div>
                    <div>1、机动车信息变更业务只能办理机动车所有人的联系电话和邮寄地址的变更。<br>
                        2、如需申请变更其他信息（如变更身份证号码、姓名等）请前往无锡车管所咨询办理（咨询电话：96122,81188228）。<br>
                        3、请机动车所有人如实填写相关信息。凡提供虚假信息，需承担相应法律后果。
                    </div>
                </div>
                <a id="query" href="<?php  echo $this->createMobileUrl('infochange',array('op'=>'change','block'=>'car'))?>">确定</a>
            </div>
            <?php  } ?>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.driver').click(function(){
            window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'notice','block'=>'driver'))?>";
        });
        $('.car').click(function(){
            window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'notice','block'=>'car'))?>";
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'change') { ?>
<div class="content">
    <div class="content_box">
        <div class="ill_head">
            <div class="illegal">
                <div class="ill_tit driver <?php  if($block == 'driver') { ?>ill_current<?php  } ?>">驾驶人信息变更</div>
            </div>
            <div class="illegal">
                <div class="ill_tit car <?php  if($block == 'car') { ?>ill_current<?php  } ?>">机动车信息变更</div>
            </div>
        </div>
        <div class="ill_query">
            <?php  if($block == 'driver') { ?>
            <div class="query_box" >
                <div class="car_style">
                    <div>驾驶证号</div>
                    <input type="text" name="drivernum" id="car_number1" placeholder="请输入驾驶证号" >
                </div>
                <div class="car_style">
                    <div>证芯编号<br><a class="where">(在哪里)</a></div>
                    <input type="text" name="driverpapers" placeholder="请输入证芯编号后六位或档案编号后六位" id="car_fa1">
                </div>
                <a id="query1">下一步</a>
            </div>
            <?php  } ?>
            <?php  if($block == 'car') { ?>
            <div class="query_box">
                <div class="car_style">
                    <div>车辆类型</div>
                    <select name="chcartype">
                        <option value="1">小型车辆</option>
                        <option value="2">大型车辆</option>
                    </select>
                </div>
                <div class="car_style">
                    <div>车牌号码</div>
                    <input type="text" name="chcarnum" id="car_number" value="苏B">
                </div>
                <div class="car_style">
                    <div>证芯编号</div>
                    <input type="text" name="chcarpapers" placeholder="请输入身份证后六位或机构代码证前六位" id="car_fa" onFocus="this.placeholder=''" onBlur="this.placeholder='请输入身份证后六位或机构代码证前六位'">
                </div>
                <a id="query">下一步</a>
            </div>
            <?php  } ?>
        </div>
    </div>
</div>
<script>
    var process = false;
    $("#loading-fs").hide();
    $(function(){
        //驾驶人数据
        $('#query1').click(function(){
            if(process)return false;
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
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'subdata','block'=>'driver'))?>&drivernum="+drivernum+"&driverpapers="+driverpapers;
                }
            });
            process = true;

        });
        //机动车信息
        $('#query').click(function(){
            if(process)return false;
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
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'subdata','block'=>'car'))?>&chcartype="+chcartype+"&chcarnum="+chcarnum+"&chcarpapers="+chcarpapers;
                }
            });
            process = true;
        });
        $('.driver').click(function(){
            window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'change','block'=>'driver'))?>";
        });
        $('.car').click(function(){
            window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op' => 'change','block'=>'car'))?>";
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'subdata') { ?>
<!--信息更改-驾驶人-->
    <?php  if($block == 'driver') { ?>
    <div class="content">
        <div class="content_box">
            <form>
            <div class="driv_box">
                <div class="driv_info">
                    <div class="info_icon"><i>&#xe6ea;</i></div>
                    <div>姓名</div>
                    <input type="text" name="drname" placeholder="请输入姓名" onfocus="this.placeholder=''" onblur="this.placeholder='请输入姓名'">
                </div>
                <div class="driv_info">
                    <div class="info_icon1"><i>&#xe6ff;</i></div>
                    <div>身份证号</div>
                    <input type="text" name="drcard" placeholder="请输入身份证号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入身份证号'">
                </div>
                <div class="driv_info">
                    <div class="info_icon2"><i>&#xe6bc;</i></div>
                    <div>档案编号</div>
                    <input type="text" name="drnum" placeholder="请输入档案编号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入档案编号'">
                </div>
                <div class="driv_info">
                    <div class="info_icon3"><i>&#xe602;</i></div>
                    <div>联系地址</div>
                    <input type="text" name="draddr" placeholder="请输入地址" onfocus="this.placeholder=''" onblur="this.placeholder='请输入地址'">
                </div>

                <div class="driv_info">
                    <div class="info_icon4"><i>&#xe61b;</i></div>
                    <div>手机号码</div>
                    <input type="text" name="drphone" placeholder="请输入手机号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入手机号'">
                </div>
                <input type="hidden" value="<?php  echo $drivernum;?>" name="drivernum">
                <input type="hidden" value="<?php  echo $driverpapers;?>" name="driverpapers">
                <a id="submit">提交</a>
            </div>
            </form>
        </div>
    </div>
    <script>
        var process = false;
        $("#loading-fs").hide();
        $(function(){
            $('#submit').click(function(){
                if(process)return false;
                var drivernum = $("[name = 'drivernum']").val();
                var driverpapers = $("[name = 'driverpapers']").val();
                var drname = $("[name = 'drname']").val();
                var drcard = $("[name = 'drcard']").val();
                var drnum =  $("[name = 'drnum']").val();
                var draddr =  $("[name = 'draddr']").val();
                var drphone =  $("[name = 'drphone']").val();
                if (drname == ''){
                    alert('请填写您姓名！');return false;
                }
                if (drcard == ''){
                    alert('请填写您的身份证号！');return false;
                }
                var reg = /^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i;
                if(!reg.test(drcard)){
                    alert('身份证号不合法！');
                }
                if (drnum == ''){
                    alert('请填写档案编号！');return false;
                }
                if (draddr == ''){
                    alert('请填写您的联系地址！');return false;
                }
                if (drphone == ''){
                    alert('请填写您的手机号码！');return false;
                }
                var pattern = /^1[34578]\d{9}$/;
                if (!pattern.test(drphone)){
                    alert('手机号码有误！');return false;
                }
                var drdata = $('form').serialize();
                $("#loading-fs").show();
                $.post("<?php  echo $this->createMobileUrl('drpost')?>&" + drdata,function(data){
                    if (data == 200){
                        window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op'=>'success'))?>";
                    }
                });
                process = true;
            });
        });
    </script>
    <?php  } ?>
<!--信息更改-机动车-->
    <?php  if($block == 'car') { ?>
    <div class="content">
        <div class="content_box">
            <form>
            <div class="driv_box">
                <div class="driv_info">
                    <div class="info_icon"><i>&#xe6ea;</i></div>
                    <div>姓名</div>
                    <input type="text" name="carname" placeholder="请输入姓名" onfocus="this.placeholder=''" onblur="this.placeholder='请输入姓名'">
                </div>
                <div class="driv_info">
                    <div class="info_icon1"><i>&#xe6ff;</i></div>
                    <div>车牌号码</div>
                    <input type="text" name="carnum" value="苏B">
                </div>
                <div class="driv_info">
                    <div class="info_icon2"><i>&#xe627;</i></div>
                    <div>号牌种类</div>
                    <select name="cartype">
                        <option value="1">小型汽车</option>
                        <option value="2">大型汽车</option>
                    </select>
                </div>
                <div class="driv_info">
                    <div class="info_icon3"><i>&#xe602;</i></div>
                    <div>联系地址</div>
                    <input type="text" name="caraddr" placeholder="请输入地址" onfocus="this.placeholder=''" onblur="this.placeholder='请输入地址'">
                </div>

                <div class="driv_info">
                    <div class="info_icon4"><i>&#xe61b;</i></div>
                    <div>手机号码</div>
                    <input type="text" name="carphone" placeholder="请输入手机号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入手机号'">
                </div>
                <input type="hidden" value="<?php  echo $chcartype;?>" name="chcartype">
                <input type="hidden" value="<?php  echo $chcarnum;?>" name="chcarnum">
                <input type="hidden" value="<?php  echo $chcarpapers;?>" name="chcarpapers">
                <a id="submit">提交</a>
            </div>
            </form>
        </div>
    </div>
    <script>
        var process = false;
        $("#loading-fs").hide();
        $(function(){
            $('#submit').click(function(){
                if(process)return false;
                var chcartype = $("[name = 'chcartype']").val();
                var chcarnum = $("[name = 'chcarnum']").val();
                var chcarpapers = $("[name = 'chcarpapers']").val();
                var carname = $("[name = 'carname']").val();
                var carnum = $("[name = 'carnum']").val();
                var caraddr = $("[name = 'caraddr']").val();
                var carphone = $("[name = 'carphone']").val();
                if (carname == ''){
                    alert('请填写您姓名！');return false;
                }
                if (carnum == ''){
                    alert('请填写你的车牌号码！');return false;
                }
                reg=/^苏B[0-9A-Z]{5}$/;
                if (!reg.test(carnum)){
                    alert('请正确输入车牌号');return false;
                }
                if (caraddr == ''){
                    alert('请填写您的联系地址！');return false;
                }
                if (carphone == ''){
                    alert('请填写您的手机号码！');return false;
                }
                var pattern = /^1[34578]\d{9}$/;
                if (!pattern.test(carphone)){
                    alert('手机号码有误！');return false;
                }
                var cardata = $('form').serialize();
                $("#loading-fs").show();
                $.post("<?php  echo $this->createMobileUrl('carpost')?>&" + cardata,function(data){
                    if (data == 200){
                        window.location.href = "<?php  echo $this->createMobileUrl('infochange',array('op'=>'success'))?>";
                    }
                });
                process = true;
            });
        });
    </script>
    <?php  } ?>
<?php  } ?>
<?php  if($op == 'success') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>您的变更信息已经收到，我们将为您尽快处理。</div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('infochange',array('op' => 'notice'))?>">返回</a>
    </div>
</div>
<?php  } ?>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
</body>
</html>

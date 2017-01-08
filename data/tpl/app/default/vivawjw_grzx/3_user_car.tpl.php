<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <title>添加绑定车辆</title>
</head>
<body>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('wxsdk', TEMPLATE_INCLUDEPATH)) : (include template('wxsdk', TEMPLATE_INCLUDEPATH));?>
<?php  if($op == 'wx_car') { ?>
<div class="content">
    <div class="content_box">
        <div class="bind_car">添加绑定车辆</div>
        <div class="query_box" style="margin-top:5%;">
            <div class="car_style">
                <div>车辆类型</div>
                <select name="wx_type">
                    <option value="1">小型车辆</option>
                    <option value="2">大型车辆</option>
                </select>
            </div>
            <div class="car_style">
                <div>车牌号码</div>
                <input type="text" name="wx_car_num" id="car_number1" style="font-size:1rem;" value="苏B">
            </div>
            <div class="car_style">
                <div>发动机号<br><a class="where">(在哪里)</a></div>
                <input type="text" placeholder="请输入发动机号后六位" id="car_fa1" name="wx_car_engine">
            </div>
        </div>
        <a id="query1">立即绑定</a>
        <div class="bind">温馨提示：非苏B车辆点击<a href="<?php  echo $this->createMobileUrl('user_car',array('op'=>'wd_car'))?>">这里绑定</a></div>
    </div>
</div>
<script>
    var process = false;
    $(function(){
        $('#query1').click(function(){
            if(process)return false;
            var wx_type = $("[name = 'wx_type']").val();
            var wx_car_num = $("[name = 'wx_car_num']").val();
            var wx_car_engine = $("[name = 'wx_car_engine']").val();
            reg=/^苏B[0-9A-Z]{5}$/;
            if (!reg.test(wx_car_num)){
                alert('请正确输入车牌号');return false;
            }
            if(wx_car_engine.length != 6){
                alert('发动机号输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('user_car',array('op'=>'wx_car_post'))?>",{wx_type:wx_type,wx_car_num:wx_car_num,wx_car_engine:wx_car_engine},function(data){
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('user_car',array('op'=> 'sub_car'))?>";
                }else{
                    alert('绑定失败！');
                }
            });
            $("#loading-fs").hide();
            process = true;
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'wd_car') { ?>
<div class="content">
    <div class="content_box">
        <div class="bind_car">添加绑定外地车辆</div>
        <div class="query_box" style="margin-top:5%;">
            <div class="car_style">
                <div>车辆类型</div>
                <select name="wd_type">
                    <option value="1">小型车辆</option>
                    <option value="2">大型车辆</option>
                </select>
            </div>

            <div class="car_style">
                <div>车牌号码</div>
                <input type="text" name="wd_car_num" id="car_number1" style="font-size:1rem;">
            </div>
            <div class="car_style">
                <div>发动机号<br><a class="where">(在哪里)</a></div>
                <input type="text" placeholder="请输入发动机号后六位" id="car_fa1" name="wd_car_engine">
            </div>

        </div>
        <a id="query1">立即绑定</a>
        <div class="bind">温馨提示：苏B车辆点击<a href="<?php  echo $this->createMobileUrl('user_car',array('op'=>'wx_car'))?>">这里绑定</a></div>
    </div>
</div>
<script>
    var process = false;
    $(function(){
        $('#query1').click(function(){
            if(process)return false;
            var wd_type = $("[name = 'wd_type']").val();
            var wd_car_num = $("[name = 'wd_car_num']").val();
            var wd_car_engine = $("[name = 'wd_car_engine']").val();
            reg=/^[京津沪渝冀豫云辽黑湘皖鲁新苏浙赣鄂桂甘晋蒙陕吉闽贵粤青藏川宁琼使领]{1}[A-Z]{1}[A-Z0-9]{4}[A-Z0-9挂学警港澳]{1}$/;
            if (!reg.test(wd_car_num)){
                alert('请正确输入车牌号');return false;
            }
            if(wd_car_engine.length != 6){
                alert('发动机号输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('user_car',array('op'=>'wd_car_post'))?>",{wd_type:wd_type,wd_car_num:wd_car_num,wd_car_engine:wd_car_engine},function(data){
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('user_car',array('op'=> 'sub_car'))?>";
                }else{
                    alert('绑定失败！');
                }
            });
            $("#loading-fs").hide();
            process = true;
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'sub_car') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>我们将尽快审核，请在24小时之后到个人中心，我的车辆中查看审核结果！</div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('user_car',array('op'=>'success_car'))?>">返回</a>
    </div>
</div>
<?php  } ?>
<?php  if($op == 'success_car') { ?>
<div class="content">
    <div class="content_box">
        <div class="query_box" style="margin-top:15%;">
            <div class="person_num1">
                <div>车牌号码</div>
                <input type="text" name="number"  placeholder="<?php  if($typecar['distinction'] == 1) { ?><?php  echo $typecar['wx_car_num'];?><?php  } else { ?><?php  echo $typecar['wd_car_num'];?><?php  } ?>" disabled="disabled">
            </div>
            <div class="person_num1">
                <div>状态</div>
                <input type="text" placeholder="<?php  if($typecar['status'] == 2) { ?>注册申请中<?php  } else if($typecar['status'] == 1) { ?>注册成功<?php  } else { ?>注册失败<?php  } ?>"  disabled="disabled">
            </div>

        </div>
        <div class="person_btn">
            <div class="btn_box">
                <a class="del_bound_car">解除绑定</a>
                <a href="javascript:history.go(-1);">返回</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.del_bound_car').click(function(){
            if(confirm('解除绑定驾驶证后，您将无法收到驾驶证违法提醒、驾驶证到期等提醒通知。\n 确定解除绑定吗？')){
                window.location.href = "<?php  echo $this->createMobileUrl('user_car',array('op'=>'del_bound_car','id'=>$typecar['id']))?>";
            }
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'del_bound_car') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>解除绑定<?php  echo $info;?></div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('user_car',array('op'=>'wx_car'))?>">返回</a>
    </div>
</div>
<?php  } ?>
<script>
    wx.ready(function(){
        $('.where').click(function(){
            wx.previewImage({
                current: '<?php echo S_URL;?>img/help2_0.jpg', // 当前显示图片的http链接
                urls: ['<?php echo S_URL;?>img/help2_0.jpg'] // 需要预览的图片http链接列表
            });
        });

    });
</script>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
</body>
</html>

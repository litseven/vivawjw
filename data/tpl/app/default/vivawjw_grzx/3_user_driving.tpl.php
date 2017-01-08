<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>添加绑定驾驶证</title>
</head>
<body>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('wxsdk', TEMPLATE_INCLUDEPATH)) : (include template('wxsdk', TEMPLATE_INCLUDEPATH));?>
<?php  if($op == 'wx_driving') { ?>
<div class="content">
    <div class="content_box">
        <div class="bind_car">添加绑定本地驾驶证</div>
        <form>
        <div class="query_box" style="margin-top:5%;">
            <div class="car_style">
                <div>驾驶证号</div>
                <input type="text" name="wx_driv_num" id="car_number1" placeholder="请输入驾驶证号">
            </div>
            <div class="car_style">
                <div>档案编号<br><a class="where">(在哪里)</a></div>
                <input type="text" placeholder="请输入档案编号后六位" id="car_fa1" name="wx_driv_record">
            </div>
        </div>
        </form>
        <a id="query1">下一步</a>
        <div class="bind">温馨提示：外地驾驶证点击<a href="<?php  echo $this->createMobileUrl('user_driving',array('op'=>'wd_driving'))?>">这里绑定</a></div>
    </div>
</div>
<script>
    var process = false;
    $(function () {
        $('#query1').click(function () {
            if(process)return false;
            var wx_driv_num = $("[name = 'wx_driv_num']").val();
            var wx_driv_record = $("[name = 'wx_driv_record']").val();
            reg = /^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i;
            if(wx_driv_num == ''){
                alert('驾驶证号不能为空！');return false;
            }
            if (!reg.test(wx_driv_num)){
                alert('驾驶证号不正确！');return false;
            }
            if(wx_driv_record.length != 6){
                alert('档案编号输入有误！');return false;
            }
            var drivdata = $('form').serialize();
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('user_driving',array('op'=>'wx_drivpost'))?>&" + drivdata,function (data) {
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('user_driving',array('op'=>'sub_driving'))?>";
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
<?php  if($op == 'wd_driving') { ?>
<div class="content">
    <div class="content_box">
        <div class="bind_car">添加绑定外地驾驶证</div>
        <form>
            <div class="query_box" style="margin-top:5%;">
                <div class="car_style">
                    <div>驾驶证号</div>
                    <input type="text" name="wd_driv_num" id="car_number1" placeholder="请输入驾驶证号">
                </div>
                <div class="car_style">
                    <div>档案编号<br><a class="where">(在哪里)</a></div>
                    <input type="text" placeholder="请输入档案编号后六位" id="car_fa1" name="wd_driv_record">
                </div>
            </div>
        </form>
        <a id="query1">下一步</a>
        <div class="bind">温馨提示：无锡驾驶证点击<a href="<?php  echo $this->createMobileUrl('user_driving',array('op'=>'wx_driving'))?>">这里绑定</a></div>
    </div>
</div>
<script>
    var process = false;
    $(function () {
        $('#query1').click(function () {
            if(process)return false;
            var wd_driv_num = $("[name = 'wd_driv_num']").val();
            var wd_driv_record = $("[name = 'wd_driv_record']").val();
            if(wd_driv_num == ''){
                alert('驾驶证号不能为空！');return false;
            }
            if(wd_driv_record.length != 6){
                alert('档案编号输入有误！');return false;
            }
            var drivdata = $('form').serialize();
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('user_driving',array('op'=>'wd_drivpost'))?>&" + drivdata,function (data) {
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('user_driving',array('op'=>'sub_driving'))?>";
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
<?php  if($op == 'sub_driving') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>我们将尽快审核，请在24小时之后到个人中心，我的驾驶证中查看审核结果！</div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('user_driving')?>">返回</a>
    </div>
</div>
<?php  } ?>
<?php  if($op == 'success_driving') { ?>
<div class="content">
    <div class="content_box">
        <div class="query_box" style="margin-top:15%;">
            <div class="person_num1" style="border: none">
                <div>驾驶证号</div>
                <input type="text"  placeholder="<?php  if($typedriving['distinction'] == 1) { ?><?php  echo $typedriving['wx_driv_num'];?><?php  } else { ?><?php  echo $typedriving['wd_driv_num'];?><?php  } ?>" disabled="disabled"/>
            </div>
            <div class="person_num1" style="height: 25px;line-height: 15px;">
                <a href="" style="color:#2ecc71; font-size:14px ">(点击查看驾驶证违法信息)</a>
            </div>
            <div class="person_num1">
                <div>状态</div>
                <input type="text" placeholder="<?php  if($typedriving['status'] == 2) { ?>注册申请中<?php  } else if($typedriving['status'] == 1) { ?>注册成功<?php  } else { ?>注册失败<?php  } ?>"  disabled="disabled">
            </div>

        </div>
        <div class="person_btn">
            <div class="btn_box">
                <a class="del_bound_dr">解除绑定</a>
                <a href="javascript:history.go(-1);">返回</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('.del_bound_dr').click(function(){
            if(confirm('解除绑定驾驶证后，您将无法收到驾驶证违法提醒、驾驶证到期等提醒通知。\n 确定解除绑定吗？')){
                window.location.href = "<?php  echo $this->createMobileUrl('user_driving',array('op'=>'del_bound_dr','id'=>$typedriving['id']))?>";
            }
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'del_bound_dr') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>解除绑定<?php  echo $info;?></div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('user_driving',array('op'=>'wx_driving'))?>">返回</a>
    </div>
</div>
<?php  } ?>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
<script>
    wx.ready(function(){
        $('.where').click(function(){
            wx.previewImage({
                current: '<?php echo S_URL;?>img/help_0.jpg', // 当前显示图片的http链接
                urls: ['<?php echo S_URL;?>img/help_0.jpg'] // 需要预览的图片http链接列表
            });
        });

    });
</script>
</body>
</html>
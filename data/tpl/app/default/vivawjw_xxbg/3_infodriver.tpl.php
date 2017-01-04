<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <title>驾驶人信息变更</title>
</head>
<body>
<?php  if($op == 'list') { ?>
<div class="content">
    <div class="content_box">
        <div class="driv_box">
            <div class="driv_info">
                <div class="info_icon"><i>&#xe6ea;</i></div>
                <div>姓名</div>
                <input type="text" placeholder="请输入姓名" onfocus="this.placeholder=''" onblur="this.placeholder='请输入姓名'">
            </div>
            <div class="driv_info">
                <div class="info_icon1"><i>&#xe6ff;</i></div>
                <div>身份证号</div>
                <input type="text" placeholder="请输入身份证号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入身份证号'">
            </div>
            <div class="driv_info">
                <div class="info_icon2"><i>&#xe6bc;</i></div>
                <div>档案编号</div>
                <input type="text" placeholder="请输入档案编号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入档案编号'">
            </div>
            <div class="driv_info">
                <div class="info_icon3"><i>&#xe602;</i></div>
                <div>联系地址</div>
                <input type="text" placeholder="请输入地址" onfocus="this.placeholder=''" onblur="this.placeholder='请输入地址'">
            </div>

            <div class="driv_info">
                <div class="info_icon4"><i>&#xe61b;</i></div>
                <div>手机号码</div>
                <input type="text" placeholder="请输入手机号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入手机号'">
            </div>
            <a id="submit">提交</a>

        </div>
    </div>
</div>
<?php  } ?>
</body>
</html>

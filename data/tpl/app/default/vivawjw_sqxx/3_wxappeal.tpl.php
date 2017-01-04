<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/index.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <title>申诉信息填写</title>
</head>
<body>
<?php  if($op == 'post') { ?>
<div class="content">
    <div class="content_box">
        <form>
        <div class="appeal_box">
            <div class="appeal_name">
                <div>姓名:</div>
                <input type="text" name="appeal_name" placeholder="请输入您的姓名" onFocus="this.placeholder=''" onBlur="this.placeholder='请输入您的姓名'">
            </div>
            <div class="appeal_phone">
                <div>联系方式:</div>
                <input type="text" name="appeal_phone" placeholder="请输入您的手机号" onFocus="this.placeholder=''" onBlur="this.placeholder='请输入您的手机号'">
            </div>
            <div class="appeal_why">
                <div>申诉原因:</div>
                <textarea placeholder="请输入申诉原因(100字)" name="appeal_why" onFocus="this.placeholder=''" onBlur="this.placeholder='请输入申诉原因(100字)'"></textarea>
            </div>
        </div>
            <a id="appeal_sub">提交</a>
        </form>
    </div>
</div>
<?php  } ?>
<?php  if($op == 'success') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>您的申诉信息我们已经收到，将尽快为您处理。</div>
        </div>
        <a id="appeal_back" href="<?php  echo $this->createMobileUrl('wxappeal')?>">返回</a>
    </div>
</div>
<?php  } ?>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
</body>
<script>
    var process = false;
    $("#loading-fs").hide();
    $(function(){
        $('#appeal_sub').click(function(){
            if(process)return false;
            var appeal_name = $("[name = 'appeal_name']").val();
            var appeal_phone = $("[name = 'appeal_phone']").val();
            var appeal_why = $("[name = 'appeal_why']").val();
            if (appeal_name == ''){
                alert('请留下您的名字！');return false;
            }
            if (appeal_phone == ''){
                alert('手机号不能为空！');return false;
            }
            if(!(/^1[34578]\d{9}$/.test(appeal_phone))){
                alert("手机号码有误，请重填");return false;
            }
            if (appeal_why == ''){
                alert('请填写申诉原因,方便我们帮到您！');return false;
            }
            if (appeal_why.length > 100){
                alert('您字数过多，我看不过来，请在100字符之内！');return false;
            }
            var data = $('form').serialize();
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('appealpost')?>&" + data,function(data){
                window.location.href = "<?php  echo $this->createMobileUrl('wxappeal',array('op'=>'success'))?>";
            });
            process = true;
        });
    });
</script>
</html>
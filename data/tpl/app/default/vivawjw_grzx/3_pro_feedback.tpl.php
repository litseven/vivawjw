<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="http://imgcdn.yimiaoxiao.com/jquery.min.js"></script>
    <title>产品反馈</title>
</head>
<body>
<?php  if($op == 'feedback') { ?>
<div class="content">
    <div class="content_box">
        <div class="feedback">
            <div class="feed_head">
                <div>请选择反馈类型</div>
                <select name="feedtype">
                    <option value="1">功能意见</option>
                    <option value="2">页面设计</option>
                    <option value="3">用户体验</option>
                </select>
            </div>
            <div class="feed_fg"></div>
            <div class="feed_sugg">
                <textarea placeholder="请输入产品意见，我们将不断优化" name="content"></textarea>
                <input type="text" placeholder="请输入您的手机号/邮箱(选填)" name="phone">
            </div>
            <a id="feed_sub">提交</a>
        </div>
    </div>
</div>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
<script>
    var process = false;
    $(function(){
        $('#feed_sub').click(function () {
            if(process)return false;
            var feedtype = $("[name = 'feedtype']").val();
            var content = $("[name = 'content']").val();
            var phone = $("[name = 'phone']").val();
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('pro_feedback',array('op'=>'feedpost'))?>",{feedtype:feedtype,content:content,phone:phone},function (data) {
                if (data == 200){
                    window.location.href = "<?php  echo $this->createMobileUrl('pro_feedback',array('op'=>'end'))?>";
                }else{
                    alert('提交失败！');
                }
            });
            $("#loading-fs").hide();
            process = true;
        });
    });
    
</script>
<?php  } ?>
<?php  if($op == 'end') { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe60d;</i>
            <div>您上传的结果我们已经收到，将尽快为您处理。</div>
        </div>
        <a id="appeal_back" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<?php  } ?>
</body>
</html>
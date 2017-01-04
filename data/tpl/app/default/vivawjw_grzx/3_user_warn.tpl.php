<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <title>我的车驾告知</title>
</head>
<body style="background-color:#eeeeee;">
<?php  if($op == 'list') { ?>
<div class="content">
    <div class="content_box">
        <div class="person_box">
            <?php  if(is_array($warnlist)) { foreach($warnlist as $k => $item) { ?>
            <div class="remind1">
                <div><?php  echo $item['title'];?></div>
                <div class="checkbox">
                    <input type="checkbox"  id="checkbox_1_<?php  echo $item['id'];?>" class="check" data-key="<?php  echo $item['title_key'];?>" name="check" <?php  if($item['status']) { ?>checked<?php  } ?>>
                    <label for="checkbox_1_<?php  echo $item['id'];?>"></label>
                </div>
            </div>
            <?php  } } ?>
            <input type="hidden" value="<?php  echo $uid;?>" name="uid">
        </div>
        <a id="remind_back" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<?php  } ?>
<script>
    $(function(){
        $(".check").click(function(){
            var title_key = $(this).data('key');
            var on_off = $(this).prop('checked');
            var uid = $("[name = 'uid']").val();
            if(on_off){
                on_off = 1;
            }
            else{
                on_off = 0;
            }
            $.post("<?php  echo $this->createMobileUrl('user_warn',array('op'=>'warnpost'))?>",{title_key:title_key,on_off:on_off,uid:uid},function(data){

            });
        });
    });
</script>
</body>
</html>

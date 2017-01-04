<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <title>我的申诉</title>
</head>
<body>
<?php  if($wfdata) { ?>
<div class="my_ss_list">
    <?php  if(is_array($wfdata)) { foreach($wfdata as $item) { ?>
    <ul>
        <li>
            <span>申诉事件</span>
            <span><?php  echo $item['appeal_why'];?></span>
        </li>
        <li>
            <span>申诉时间</span>
            <span><?php  echo date('Y-m-d',$item['time'])?></span>
        </li>
        <li>
            <span>申诉状态</span>
            <span><?php  if($item['status'] == 2) { ?>等待审核<?php  } else if($item['status'] == 1) { ?>审核通过<?php  } else { ?>审核未通过<?php  } ?></span>
        </li>
        <li></li>
    </ul>
    <?php  } } ?>
    <a href="javascript:history.go(-1);">返回</a>
</div>
<?php  } else { ?>
<div class="content">
    <div class="content_box">
        <div class="appeal1_box">
            <i class="appeal_suc">&#xe8ae;</i>
            <div>您还没有申诉记录。</div>
        </div>
        <a id="appeal_back" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<?php  } ?>
</body>
</html>

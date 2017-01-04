<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <title>我的快处</title>
</head>
<body>
<?php  if($kcdata) { ?>
<div class="my_ss_list">
    <?php  if(is_array($kcdata)) { foreach($kcdata as $item) { ?>
    <ul>
        <li>
            <span>报警记录号</span>
            <span><?php  echo $item['recordnum'];?></span>
        </li>
        <li>
            <span>事故时间</span>
            <span><?php  echo date('Y-m-d',$item['acctime'])?></span>
        </li>
        <li>
            <span>事故快处状态</span>
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
            <div>您还没有快处记录。</div>
        </div>
        <a id="appeal_back" href="javascript:history.go(-1);">返回</a>
    </div>
</div>
<?php  } ?>
</body>
</html>

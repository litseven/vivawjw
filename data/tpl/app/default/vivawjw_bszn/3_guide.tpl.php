<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
    <script src="http://imgcdn.yimiaoxiao.com/jquery.min.js"></script>
    <title>办事指南</title>
</head>
<?php  echo htmlspecialchars_decode($data['content'])?>
<body style="background-color:#eeeeee;">
<?php  if($op == 'list') { ?>
<div class="content">
    <div class="content_box">
        <div class="search_box">
            <div class="search">
                <input type="text" placeholder="请输入关键词" id="search">
                <button class="seek"><i>&#xe601;</i></button>
            </div>
            <div class="search_tip" >
                <span>热门搜索:</span>
                <span>
                    <?php  if(is_array($keylist)) { foreach($keylist as $val) { ?>
                    <a href="<?php  echo $this->createMobileUrl('guide',array('op' => 'key','hotkey' =>$val['hotkey']))?>" style="text-decoration: none;color: #000;"><?php  echo $val['hotkey'];?></a>
                    <?php  } } ?>
                </span>
            </div>
        </div>
        <div class="guild">
            <div class="guild_box">
                <?php  if(is_array($data)) { foreach($data as $k => $item) { ?>
                <a class="guild1" href="<?php  echo $this->createMobileUrl('guide',array('op' => 'title','id' =>$item['id']))?>" style="color: #555;">
                    <div class="icon<?php  echo $item['id'];?>"><i><?php  echo $item['ioc'];?></i></div>
                    <div><?php  echo $item['title'];?></div>
                    <i class="icon11">&#xe622;</i>
                </a>
                <?php  } } ?>

            </div>
        </div>

    </div>
</div>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>正在查询，请稍后 ...</span></div>
</div>
<script>
    var process = false;
    $(function(){
        $('.seek').click(function(){
            if(process)return false;
            var search = $('#search').val();
            $("#loading-fs").show();
            window.location.href = "<?php  echo $this->createMobileUrl('guide',array('op' => 'key'))?>&hotkey=" + search;
            process = true;
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'key') { ?>
<div class="content">
    <div class="content_box">
        <div class="guild1_box">
            <?php  if($keylist) { ?>
            <?php  if(is_array($keylist)) { foreach($keylist as $val) { ?>
            <a href="<?php  echo $this->createMobileUrl('guide',array('op' => 'content','id' =>$val['id']))?>" style="color: #555;">
                <div class="question1">
                    <div class="ques"><?php  echo $val['title'];?></div>
                    <i>&#xe626;</i>
                </div>
            </a>
            <?php  } } ?>
            <?php  } else { ?>
                <div style="text-align: center;margin-top: 50px">没有查到你要找的信息</div>
            <?php  } ?>
        </div>
    </div>
</div>
<?php  } ?>
<?php  if($op == 'title') { ?>
<div class="content">
    <div class="content_box">
        <div class="guild1_box">
            <?php  if(is_array($title)) { foreach($title as $item) { ?>
            <a href="<?php  echo $this->createMobileUrl('guide',array('op' => 'content','id' =>$item['id']))?>" style="color: #555;">
                <div class="question1">
                    <div class="ques"><?php  echo $item['title'];?></div>
                    <i>&#xe626;</i>
                </div>
            </a>
            <?php  } } ?>
            <!--<div class="question2">
                <div class="ques2">变更登记<span>(地址、姓名[名称]、身份证明名称/号码、联系方式在车辆管理所管辖区内变更)</span></div>
                <i>&#xe626;</i>
            </div>
            </div>-->
        </div>
    </div>
</div>
<?php  } ?>
<?php  if($op == 'content') { ?>
<div class="content">
    <div class="content_box">
        <div class="license_head">
            <div><?php  echo $content['title'];?></div>
            <div><?php  echo date('Y-m-d',$content['time'])?></div>
        </div>
        <div class="lice_con">
            <div class="license_box">
                <?php  echo htmlspecialchars_decode($content['content'])?>
            </div>
        </div>
        <a id="back" href="<?php  echo $this->createMobileUrl('guide',array('op' => 'list'))?>">返回</a>
    </div>
</div>
<?php  } ?>
</body>
</html>

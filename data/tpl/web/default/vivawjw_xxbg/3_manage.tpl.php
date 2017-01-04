<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op == 'driver') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'driver'))?>">驾驶人列表</a></li>
    <li<?php  if($op == 'car') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'car'))?>">机动车信息</a></li>
    <?php  if($op == 'drinfo') { ?><li class="active"><a>驾驶人信息</a></li><?php  } ?>
    <?php  if($op == 'carinfo') { ?><li class="active"><a>机动车信息</a></li><?php  } ?>
</ul>
<?php  if($op == 'driver') { ?>
<!--列表-->
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">姓名</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($drlist)) { foreach($drlist as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['drname'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['drtime'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>drinfo,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
                            <a href="javascript:;" class="btn btn-danger btn-sm" data-id="<?php  echo $item['id'];?>"  title="点击删除">删除</a>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php  echo $pager;?>
    </form>
</div>
<script>
    $(function(){
        /*删除*/
        $('.btn-danger').click(function(){
            var id = $(this).attr('data-id');
            if(confirm('确定要删除吗？')){
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'driverdel'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'driver'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'car') { ?>
<!--列表-->
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">姓名</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($carlist)) { foreach($carlist as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['carname'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['cartime'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>carinfo,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
                            <a href="javascript:;" class="btn btn-danger btn-sm" data-id="<?php  echo $item['id'];?>"  title="点击删除">删除</a>
                        </td>
                    </tr>
                    <?php  } } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php  echo $pager;?>
    </form>
</div>
<script>
    /*删除*/
    $(function(){
        $('.btn-danger').click(function(){
            var id = $(this).attr('data-id');
            if(confirm('确定要删除吗？')){
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'cardel'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'car'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'drinfo') { ?>
<div class="panel panel-default">
    <div class="panel-heading">驾驶人信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['drname'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">身份证号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['drcard'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">档案编号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['drnum'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系地址:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['draddr'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['drphone'];?></p>
            </div>
        </div> <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-d-m H:i:s',$drinfo['drtime'])?></p>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($drinfo['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$drinfo['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($drinfo['status'] == 2) { ?>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                    <input type="radio" value="1" name="status" onClick="$('#failreason').hide();" checked> 受理
                </label>
                <label class="radio-inline">
                    <input type="radio" value="3" name="status" onClick="$('#failreason').show();"> 驳回
                </label>
            </div>
            <?php  } else { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  if($drinfo['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($drinfo['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $drinfo['id'];?>" name="id">
            <?php  if($drinfo['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $drinfo['turndown'];?></p>
            </div>
            <?php  } else { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <textarea class="form-control" name="turndown"><?php  echo $drinfo['turndown'];?></textarea>
            </div>
            <?php  } ?>
        </div>
    </div>
</div>
<a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
<?php  if($drinfo['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('drturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'driver'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }

        });
    });
</script>
<?php  } ?>
<?php  if($op == 'carinfo') { ?>
<div class="panel panel-default">
    <div class="panel-heading">机动车信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['carname'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">车牌号码:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['carnum'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">好牌类型:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['cartype'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系地址:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['caraddr'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">手机号码:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['carphone'];?></p>
            </div>
        </div> <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
        <div class="col-sm-9 col-xs-9 col-md-9">
            <p class="form-control-static"><?php  echo date('Y-d-m H:i:s',$carinfo['cartime'])?></p>
        </div>
    </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($carinfo['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$carinfo['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($carinfo['status'] == 2) { ?>
            <div class="col-sm-9 col-xs-12">
                <label class="radio-inline">
                    <input type="radio" value="1" name="status" onClick="$('#failreason').hide();" checked> 受理
                </label>
                <label class="radio-inline">
                    <input type="radio" value="3" name="status" onClick="$('#failreason').show();"> 驳回
                </label>
            </div>
            <?php  } else { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  if($carinfo['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($carinfo['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $carinfo['id'];?>" name="id">
            <?php  if($carinfo['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $carinfo['turndown'];?></p>
            </div>
            <?php  } else { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <textarea class="form-control" name="turndown"></textarea>
            </div>
            <?php  } ?>
        </div>
    </div>
</div>
<a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
<?php  if($carinfo['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('carturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'car'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
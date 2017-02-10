<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op == 'wx_car') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wx_car'))?>">无锡绑定车辆</a></li>
    <li<?php  if($op == 'wx_driving') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wx_driving'))?>">无锡绑定驾驶证</a></li>
    <li<?php  if($op == 'wd_car') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wd_car'))?>">外地绑定车辆</a></li>
    <li<?php  if($op == 'wd_driving') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wd_driving'))?>">外地绑定驾驶证</a></li>
    <li<?php  if($op == 'pro_feedback') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'pro_feedback'))?>">产品反馈</a></li>

    <?php  if($op == 'wx_car_info') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wx_car_info'))?>">无锡绑定车辆信息</a></li><?php  } ?>
    <?php  if($op == 'wx_driv_info') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wx_driv_info'))?>">无锡绑定驾驶证信息</a></li><?php  } ?>
    <?php  if($op == 'wd_car_info') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wd_car_info'))?>">外地绑定车辆信息</a></li><?php  } ?>
    <?php  if($op == 'wd_driv_info') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wd_driv_info'))?>">外地绑定驾驶证信息</a></li><?php  } ?>
    <?php  if($op == 'feedback_info') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'feedback_info'))?>">产品反馈信息</a></li><?php  } ?>
</ul>
<!--无锡绑定车辆列表-->
<?php  if($op == 'wx_car') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">车牌号码</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($wxlist)) { foreach($wxlist as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['wx_car_num'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>wx_car_info,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'car_del'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wx_car'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<!--外地绑定车辆列表-->
<?php  if($op == 'wd_car') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">车牌号码</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($wdlist)) { foreach($wdlist as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['wd_car_num'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>wd_car_info,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'car_del'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wd_car'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<!--无锡绑定车辆信息-->
<?php  if($op == 'wx_car_info') { ?>
<div class="panel panel-default">
    <div class="panel-heading">驾驶人信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">车辆型号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  if($wxone['wx_type'] == 1) { ?>小型车<?php  } else if($wxone['wx_type'] == 2) { ?>大型车<?php  } ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">车牌号码:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxone['wx_car_num'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">发动机号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxone['wx_car_engine'];?></p>
            </div>
        </div>
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
        <div class="col-sm-9 col-xs-9 col-md-9">
            <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wxone['time'])?></p>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($wxone['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wxone['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($wxone['status'] == 2) { ?>
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
                <p class="form-control-static"><?php  if($wxone['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($wxone['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $wxone['id'];?>" name="id">
            <?php  if($wxone['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxone['turndown'];?></p>
            </div>
            <?php  } else { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <textarea class="form-control" name="turndown"><?php  echo $wxone['turndown'];?></textarea>
            </div>
            <?php  } ?>
        </div>
    </div>
</div>
<a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
<?php  if($wxone['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('wxturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wx_car'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }

        });
    });
</script>
<?php  } ?>
<!--外地绑定车辆信息-->
<?php  if($op == 'wd_car_info') { ?>
<div class="panel panel-default">
    <div class="panel-heading">机动车信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">车辆类型:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  if($wdone['wd_type'] == 1) { ?>小型车<?php  } else if($wdone['wd_type'] == 2) { ?>大型车<?php  } ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">车牌号码:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wdone['wd_car_num'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">发动机号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wdone['wd_car_engine'];?></p>
            </div>
        </div>
        <div class="form-group">
        <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
        <div class="col-sm-9 col-xs-9 col-md-9">
            <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wdone['time'])?></p>
        </div>
    </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($wdone['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wdone['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($wdone['status'] == 2) { ?>
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
                <p class="form-control-static"><?php  if($wdone['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($wdone['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $wdone['id'];?>" name="id">
            <?php  if($wdone['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wdone['turndown'];?></p>
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
<?php  if($wdone['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('wdturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wd_car'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }
        });
    });
</script>
<?php  } ?>






<!--无锡绑定驾驶证列表-->
<?php  if($op == 'wx_driving') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">驾驶证号</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($wxdriving)) { foreach($wxdriving as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['wx_driv_num'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>wx_driv_info,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'dir_del'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wx_driving'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<!--无锡绑定驾驶证信息-->
<?php  if($op == 'wx_driv_info') { ?>
<div class="panel panel-default">
    <div class="panel-heading">驾驶证信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驾驶证号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxdriving['wx_driv_num'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">档案编号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxdriving['wx_driv_record'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wxdriving['time'])?></p>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($wxdriving['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wxdriving['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($wxdriving['status'] == 2) { ?>
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
                <p class="form-control-static"><?php  if($wxdriving['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($wxdriving['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $wxdriving['id'];?>" name="id">
            <?php  if($wxdriving['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wxdriving['turndown'];?></p>
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
<?php  if($wxdriving['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('wxdrturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wx_driving'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }
        });
    });
</script>
<?php  } ?>

<!--无锡绑定驾驶证列表-->
<?php  if($op == 'wd_driving') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">驾驶证号</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($wddriving)) { foreach($wddriving as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['wd_driv_num'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>'wd_driv_info','id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'dir_del'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wd_driving'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<!--无锡绑定驾驶证信息-->
<?php  if($op == 'wd_driv_info') { ?>
<div class="panel panel-default">
    <div class="panel-heading">驾驶证信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驾驶证号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wddriving['wd_driv_num'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">档案编号:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wddriving['wd_driv_record'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wddriving['time'])?></p>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($wddriving['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$wddriving['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($wddriving['status'] == 2) { ?>
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
                <p class="form-control-static"><?php  if($wddriving['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($wddriving['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $wddriving['id'];?>" name="id">
            <?php  if($wddriving['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $wddriving['turndown'];?></p>
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
<?php  if($wddriving['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('wddrturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'wd_driving'))?>";
                    }else{
                        alert('修改失败');
                    }
                });
            }
        });
    });
</script>
<?php  } ?>

<!--产品反馈列表-->
<?php  if($op == 'pro_feedback') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;text-align: center">序号</th>
                        <th style="width:30%;text-align: center">反馈类型</th>
                        <th style="width:15%;text-align: center">提交时间</th>
                        <th style="width:15%;text-align: center">状态</th>
                        <th style="width:15%;text-align: center">受理时间</th>
                        <th style="width:20%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($feeddata)) { foreach($feeddata as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  if($item['feedtype'] == 1) { ?>功能意见<?php  } else if($item['feedtype'] == 2) { ?>页面设计<?php  } else { ?>用户体验<?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>'feedback_info','id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'feed_del'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'pro_feedback'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<!--产品反馈信息-->
<?php  if($op == 'feedback_info') { ?>
<div class="panel panel-default">
    <div class="panel-heading">产品反馈信息</div>
    <div class="panel-body">
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">返回类型:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  if($feedone['feedtype'] == 1) { ?>功能意见<?php  } else if($feedone['feedtype'] == 2) { ?>页面设计<?php  } else { ?>用户体验<?php  } ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">反馈内容:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $feedone['content'];?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">时间:</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$feedone['time'])?></p>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">受理操作</div>
    <div class="panel-body" style="padding-bottom: 0;">
        <div class="form-group">
            <?php  if($feedone['status'] != 2) { ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$feedone['sittime'])?></p>
            </div>
            <?php  } ?>
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
            <?php  if($feedone['status'] == 2) { ?>
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
                <p class="form-control-static"><?php  if($feedone['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
            </div>
            <?php  } ?>
        </div>
    </div>
    <div class="panel-body" style="padding-top: 0;">
        <div class="form-group" id="failreason" style="display:<?php  if($feedone['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
            <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
            <input type="hidden" value="<?php  echo $feedone['id'];?>" name="id">
            <?php  if($feedone['status']==3) { ?>
            <div class="col-sm-9 col-xs-9 col-md-9">
                <p class="form-control-static"><?php  echo $feedone['turndown'];?></p>
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
<?php  if($feedone['status'] == 2) { ?><button class="btn btn-success" style="margin-left: 20px">确认修改</button><?php  } ?>
<script>
    $(function(){
        $('.btn-success').click(function(){
            var status = $("[name = 'status']:checked").val();
            var turndown = $("[name = 'turndown']").val();
            var id = $("[name = 'id']").val();
            if (confirm('您确定要修改吗？')){
                $.post("<?php  echo $this->createWebUrl('feedturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                    if (data == 200){
                        alert('修改成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'pro_feedback'))?>";
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
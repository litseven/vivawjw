<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op == 'list') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>">申诉信息</a></li>
    <?php  if($op == 'view') { ?><li class="active"><a href="javascript:;">信息详情</a></li><?php  } ?>
</ul>
<?php  if($op == 'list') { ?>
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
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['appeal_name'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] == 2) { ?><span style="color:orange;">未受理</span><?php  } else if($item['status'] == 1) { ?> <span style="color:green;">通过</span><?php  } else { ?> <span style="color:red;">驳回</span><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  if($item['status'] != 2) { ?><?php  echo date('Y-m-d H:i:s',$item['sittime'])?><?php  } ?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>view,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
        $('.btn').hover(function(){
            $(this).tooltip('show');
        },function(){
            $(this).tooltip('hide');
        });
        /*删除*/
        $('.btn-danger').click(function(){
            var id = $(this).attr('data-id');
            if(confirm('确定要删除吗？')){
                $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'sub_delete'))?>",{'id':id},function(data){
                    if (data == 200){
                        alert('删除成功！');
                        window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                    }
                });
            }
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'view') { ?>
<div class="main">
    <div class="panel panel-default">
        <div class="panel-heading">申诉信息详情</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">姓名:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['appeal_name'];?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">联系方式:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['appeal_phone'];?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">提交时间:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$dataone['time'])?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">情况说明:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['appeal_why'];?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">违法信息</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">违法时间:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$dataone['wftime'])?></p>
                </div>
            </div>

            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">违法地点:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['wfaddr'];?>1</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">违法行为:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['wfcontent'];?>2</p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">罚款金额:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['wfmoney'];?>3</p>
                </div>
            </div>
            <!--<div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">违法图片:</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><img src="<?php  echo $dataone['wfimage'];?>" alt="违法图片"></p>
                </div>
            </div>-->
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">受理操作</div>
        <div class="panel-body" style="padding-bottom: 0;">
            <div class="form-group">
                <?php  if($dataone['status'] != 2) { ?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">受理时间</label>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$dataone['sittime'])?></p>
                </div>
                <?php  } ?>
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">是否受理</label>
                <?php  if($dataone['status'] == 2) { ?>
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
                    <p class="form-control-static"><?php  if($dataone['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
                </div>
                <?php  } ?>
            </div>
        </div>
        <div class="panel-body" style="padding-top: 0;">
            <div class="form-group" id="failreason" style="display:<?php  if($dataone['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
                <label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
                <input type="hidden" value="<?php  echo $dataone['id'];?>" name="id">
                <?php  if($dataone['status']==3) { ?>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <p class="form-control-static"><?php  echo $dataone['turndown'];?></p>
                </div>
                <?php  } else { ?>
                <div class="col-sm-9 col-xs-9 col-md-9">
                    <textarea class="form-control" name="turndown"><?php  echo $dataone['turndown'];?></textarea>
                </div>
                <?php  } ?>
            </div>
        </div>
    </div>
    <a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
    <span data-id="<?php  echo $dataone['id'];?>" class="btn btn-danger sub_delete" style="margin:0 20px"  title="点击删除">删除信息</span>
    <?php  if($dataone['status'] == 2) { ?><button class="btn btn-success" title="点击修改">确认修改</button><?php  } ?>
    <script>
        $(function(){
            $('.btn-success').click(function(){
                var status = $("[name = 'status']:checked").val();
                var turndown = $("[name = 'turndown']").val();
                var id = $("[name = 'id']").val();
                if (confirm('您确定要修改吗？')){
                    $.post("<?php  echo $this->createWebUrl('ssturndown')?>",{id:id,turndown:turndown,status:status},function(data){
                        if (data == 200){
                            alert('修改成功！');
                            window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                        }else{
                            alert('修改失败');
                        }
                    });
                }

            });
            //删除
            $('.sub_delete').click(function(){
                var id = $(this).attr('data-id');
                if (confirm('您确定要删除吗？')){
                    $.post("<?php  echo $this->createWebUrl('manage',array('op'=>'sub_delete'))?>",{'id':id},function(data){
                        if (data == 200){
                            alert('删除成功！');
                            window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                        }
                    });
                }
            });
        });

    </script>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
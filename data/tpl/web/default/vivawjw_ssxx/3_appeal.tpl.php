<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op == 'list') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('appeal',array('op'=>'list'))?>">申诉信息</a></li>
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
                        <th style="width:15%;text-align: center">姓名</th>
                        <th style="width:15%;text-align: center">电话</th>
                        <th style="width:55%;text-align: center">申请原因</th>
                        <th style="width:25%;text-align: center">时间</th>
                        <th style="width:25%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['appeal_name'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['appeal_phone'];?></td>
                        <td style="vertical-align:middle;text-align: center"  title="<?php  echo $item['appeal_why'];?>"><?php  echo $item['appeal_why'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y/m/d H:i:s',$item['time'])?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <a href="<?php  echo $this->createWebUrl('appeal',array('op'=>view,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
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
    });
</script>
<?php  } ?>
<?php  if($op == 'view') { ?>
<div class="main">
        <div class="panel panel-default">
            <div class="panel-heading">申诉信息详情</div>
            <div class="panel-body">
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
            <div class="panel-heading">申请人信息</div>
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
            </div>
        </div>
        <div class="form-group" style="padding-bottom:100px;margin-left: 2px">
            <a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
            <span data-id="<?php  echo $dataone['id'];?>" class="btn btn-danger sub_delete"  title="点击删除">删除信息</span>
        </div>
    <script>
        $(function(){
            //删除
            $('.sub_delete').click(function(){
                var id = $(this).attr('data-id');
                if (confirm('您确定要删除吗？')){
                    $.post("<?php  echo $this->createWebUrl('appeal',array('op'=>'sub_delete'))?>",{'id':id},function(data){
                            if (data == 200){
                                alert('删除成功！');
                                window.location.href = "<?php  echo $this->createWebUrl('appeal',array('op'=>'list'))?>";
                            }
                    });
                }
            });
        });
    </script>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
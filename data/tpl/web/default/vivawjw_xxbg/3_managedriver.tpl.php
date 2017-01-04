<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op == 'list') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>">办事指南列表</a></li>
    <li<?php  if($op == 'add') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'add'))?>">办事指南添加</a></li>
    <li<?php  if($op == 'catalog') { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'catalog'))?>">添加根目录</a></li>
    <?php  if($op == 'view') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'view'))?>">办事指南编辑</a></li><?php  } ?>
    <?php  if($op == 'recatalog') { ?><li class="active"><a href="<?php  echo $this->createWebUrl('manage',array('op'=>'view'))?>">根目录编辑</a></li><?php  } ?>
</ul>
<?php  if($op == 'list') { ?>
<!--列表-->
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:10%;text-align: center">序号</th>
                        <th style="width:10%;text-align: center">父级</th>
                        <th style="width:40%;text-align: center">标题</th>
                        <th style="width:25%;text-align: center">时间</th>
                        <th style="width:25%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  if($item['fid'] == 0) { ?>顶级<?php  } else { ?><?php  echo $item['fid'];?><?php  } ?></td>
                        <td style="vertical-align:middle;text-align: center"><?php  echo $item['title'];?></td>
                        <td style="vertical-align:middle;text-align: center" ><?php  echo date('Y-m-d',$item['time'])?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <?php  if($item['fid'] == 0) { ?>
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>recatalog,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
                            <?php  } else { ?>
                            <a href="<?php  echo $this->createWebUrl('manage',array('op'=>view,'id'=>$item['id']))?>" class="btn btn-default btn-sm"  title="点击查看">查看</a>
                            <?php  } ?>
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
                $.post("<?php  echo $this->createWebUrl('guidedelete')?>",{'id':id},function(data){
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
<!--办事编辑-->
<div class="clearfix add_page" >
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-5 col-xs-12">
                            <select name="fid" class="form-control">
                                <?php  if(is_array($fatherid)) { foreach($fatherid as $item) { ?>
                                <?php  if($dataone['fid'] == $item['id']) { ?>
                                <option value="<?php  echo $item['id'];?>"><?php  echo $item['title'];?></option>
                                <?php  } ?>
                                <?php  } } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">标题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title" value="<?php  echo $dataone['title'];?>" >
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">时间：</label>
                        <div class="col-sm-5 col-xs-12">
                            <input type="date" class="form-control" name="time" value="<?php  echo date('Y-m-d',$dataone['time'])?>">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">内容：</label>
                        <div class="col-sm-9 col-xs-12">
                            <?php  echo tpl_ueditor('content',$dataone['content']);?>
                        </div>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="form-group" style="padding-bottom:100px;margin-left: 2px">
                <a href="<?php  echo $this->createWebUrl('manage',array('op' => 'list'))?>" class="btn btn-primary">返回列表</a>
                <input type="button"  class="btn btn-success sub_up"  title="点击修改" value="点击修改"/>
                <input type="hidden" value="<?php  echo $dataone['id'];?>" name="id">
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $('.sub_up').click(function(){
            var id = $("[name = 'id']").val();
            var fid = $("[name = 'fid']").val();
            var title = $("[name = 'title']").val();
            var time = $("[name = 'time']").val();
            var content = $("[name = 'content']").val();
            $.post("<?php  echo $this->createWebUrl('review')?>", {'id' : id, 'content' : content ,'title' : title ,'fid' : fid,'time':time},function(data){
                if (data == 200){
                    alert('修改成功！');
                    window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                }
            });
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'add') { ?>
<!--添加-->
<div class="clearfix add_page" >
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-5 col-xs-12">
                            <select name="fid" class="form-control">
                                <?php  if(is_array($father)) { foreach($father as $item) { ?>
                                <option value="<?php  echo $item['id'];?>"><?php  echo $item['title'];?></option>
                                <?php  } } ?>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">标题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">时间：</label>
                        <div class="col-sm-5 col-xs-12">
                            <input type="date" class="form-control" name="time">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">内容：</label>
                        <div class="col-sm-9 col-xs-12">
                            <?php  echo tpl_ueditor('content');?>
                        </div>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="form-group" style="padding-bottom:100px;margin-left: 2px">
                <a href="<?php  echo $this->createWebUrl('manage',array('op' => 'list'))?>" class="btn btn-primary">返回列表</a>
                <input type="button"  class="btn btn-success sub_up"  title="点击提交" value="点击提交"/>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $('.sub_up').click(function(){
            var time = $("[name = 'time']").val();
            var id = $("[name = 'id']").val();
            var fid = $("[name = 'fid']").val();
            var title = $("[name = 'title']").val();
            var content = $("[name = 'content']").val();
            if (title == ''){
                alert('标题不能为空！');return false;
            }
            if (content == ''){
                alert('内容不能为空！');return false;
            }
            $.post("<?php  echo $this->createWebUrl('addguide')?>",{'id' : id, 'content' : content ,'title' : title ,'fid' : fid,'time':time},function(data){
                if (data == 200){
                    alert('添加成功！');
                    window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                }
            });
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'catalog') { ?>
<!--添加根目录-->
<div class="clearfix add_page" >
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加根目录
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-2 col-xs-12">
                            <select name="fid" class="form-control">
                                <option value="0">顶级</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">时间：</label>
                        <div class="col-sm-5 col-xs-12">
                            <input type="date" class="form-control" name="time">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">标题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title">
                        </div>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="form-group" style="padding-bottom:100px;margin-left: 2px">
                <a href="<?php  echo $this->createWebUrl('manage',array('op' => 'list'))?>" class="btn btn-primary">返回列表</a>
                <input type="button"  class="btn btn-success sub_up"  title="点击提交" value="点击提交"/>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $('.sub_up').click(function(){
            var title  = $("[name = 'title']").val();
            if (title == ''){
                alert('标题不能为空！');return false;
            }
            var data = $('form').serialize();
            $.post("<?php  echo $this->createWebUrl('catalog',array('op'=>'catalog'))?>&"+ data,function(data){
                if (data == 200){
                    alert('添加成功！');
                    window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                }
            });
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'recatalog') { ?>
<!--修改根目录-->
<div class="clearfix add_page" >
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    编辑根目录
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-2 col-xs-12">
                            <select name="fid" class="form-control">
                                <option value="0">顶级</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">时间：</label>
                        <div class="col-sm-5 col-xs-12">
                            <input type="date" class="form-control" name="time" value="<?php  echo date('Y-m-d',$fatherone['time'])?>">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">标题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title"  value="<?php  echo $fatherone['title'];?>">
                        </div>
                        <span class="help-block"></span>
                    </div>
                </div>
            </div>
            <div class="form-group" style="padding-bottom:100px;margin-left: 2px">
                <a href="<?php  echo $this->createWebUrl('manage',array('op' => 'list'))?>" class="btn btn-primary">返回列表</a>
                <input type="hidden" name="id" value="<?php  echo $fatherone['id'];?>">
                <input type="button"  class="btn btn-success sub_up"  title="点击修改" value="点击修改"/>
            </div>
        </form>
    </div>
</div>
<script>
    $(function(){
        $('.sub_up').click(function(){
            var id =  $("[name = 'id']").val();
            var title  = $("[name = 'title']").val();
            if (title == ''){
                alert('标题不能为空！');return false;
            }
            var data = $('form').serialize();
            $.post("<?php  echo $this->createWebUrl('recatalog')?>&"+ data,{'id':id},function(data){
                if (data == 200){
                    alert('修改成功！');
                    window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list'))?>";
                }
            });
        });
    });
</script>
<?php  } ?>
<script>
    $(function(){
        $('.btn').hover(function(){
            $(this).tooltip('show');
        },function(){
            $(this).tooltip('hide');
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li<?php  if($op=='list') { ?> class="active" <?php  } ?> class="lists"><a href="javascript:;">问卷列表</a></li>
    <li<?php  if($op=='add') { ?> class="active" <?php  } ?> class="add"><a href="javascript:;">添加问卷</a></li>
    <?php  if($op == 'compile') { ?><li class="active" class="add"><a href="javascript:;">编辑问卷</a></li><?php  } ?>
</ul>
<script>
    $(function(){
        $('.nav .lists ').click(function(){
            window.location.href="<?php  echo $this->createWebUrl('sit', array('op' => 'list'))?>";
        });
        $('.nav .add ').click(function(){
            window.location.href="<?php  echo $this->createWebUrl('sit', array('op' => 'add'))?>";
        });
    });
</script>
<?php  if($op == 'list') { ?>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;">题号</th>
                        <th style="width:65%;">题目</th>
                        <th style="width:25%;text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;font-weight: bold"><?php  echo $item['title'];?></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <?php  if($item['is_show'] == 0) { ?>
                           <!-- <span class="btn-warning btn-sm btn status-toggle" title="点击修改" id="<?php  echo $item['id'];?>" is_show="1">隐藏</span>&nbsp;&nbsp;-->
                            <?php  } else { ?>
                           <!-- <span class="btn-success btn-sm btn status-toggle" title="点击修改" id="<?php  echo $item['id'];?>" is_show="0">显示</span>&nbsp;&nbsp;-->
                            <?php  } ?>
                           <!-- <span  class="btn btn-sm btn-primary sub_compile" data-id="<?php  echo $item['id'];?>" title="点击编辑">编辑</span>&nbsp;&nbsp;-->
                            <span  class="btn btn-sm btn-danger sub_delete" data-id="<?php  echo $item['id'];?>" title="点击删除">删除</span>
                        </td>
                    </tr>
                    <tr>
                        <td>选项：</td>
                        <td colspan="2">
                            <?php  $val =  unserialize($item['key'])?>
                            <?php  if(is_array($val)) { foreach($val as $k => $v) { ?>
                            <?php  echo $k+1?>:<?php  echo $v;?>&nbsp;&nbsp;
                            <?php  } } ?>
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
        //是否显示
        $('.status-toggle').click(function(){
            var id = $(this).attr('id');
            var is_show = $(this).attr('is_show');
            $.post("<?php  echo $this->createWebUrl('sit',array('op'=>'is_show'))?>",{'id':id,'is_show':is_show},function(data){
                if (data == 200){
                    if(is_show == 1){
                        $("#update_"+id+" .status-toggle").text('显示').removeClass('btn-warning').addClass('btn-success').attr("is_show","0");
                    }
                    else{
                        $("#update_"+id+" .status-toggle").text('隐藏').removeClass('btn-success').addClass('btn-warning').attr("is_show","1");
                    }
                }
            });
        });
        //删除
        $('.sub_delete').click(function(){
            var id = $(this).attr('data-id');
            if (confirm('确认要删除吗？')) {
                $.post("<?php  echo $this->createWebUrl('sit',array('op'=>'sub_delete'))?>", {'id': id}, function (data) {
                    if (data == 200) {
                        window.location.href="<?php  echo $this->createWebUrl('sit', array('op' => 'list'))?>";
                    }
                });
            }
        });
        //编辑
        $('.sub_compile').click(function(){
            var id = $(this).attr('data-id');
            window.location.href="<?php  echo $this->createWebUrl('sit', array('op' => 'compile'))?>&id="+id;
        });
    });
</script>
<?php  } ?>
<?php  if($op == 'add') { ?>
<div class="clearfix add_page" >
    <!--<div class="alert alert-danger">
        请谨慎修改积分行为参数
    </div>-->
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加问卷
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">序号：</label>
                        <div class="col-sm-1 col-xs-12">
                            <input type="text" class="form-control" name="id">
                        </div>
                        <span class="help-block">前台要显示的题号,题号要连续</span>
                    </div>
                    <!--<div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">区域：</label>
                        <div class="col-sm-1 col-xs-12">
                            <select name="currency_addr" class="form-control">
                                <option value="玄武区">玄武区</option>
                                <option value="建邺区">建邺区</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-2 col-xs-12">
                            <select name="type" class="form-control">
                                <?php  if(is_array($credits)) { foreach($credits as $key => $item) { ?>
                                <option <?php  if($creditbehaviors['currency']==$key) { ?>  selected <?php  } ?> value="<?php  echo $key;?>"><?php  echo $item['title'];?></option>
                                <?php  } } ?>
                                <option value="radio">单选</option>
                                <option value="checked">多选</option>
                                <option value="select">下拉</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">问题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title">
                        </div>
                        <button type="button" class="btn btn-default" id="add_input">添加选项</button>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">选项1：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control xuan" name="xuan[]">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <input type="button" id="sub" class="btn btn-primary col-lg-1" name="submit" value="提交" />
            </div>
        </form>
    </div>
</div>
<?php  } ?>
<script>
    $(function(){
        //添加表单
        var i = 2;
        $("#add_input").click(function(){
            $(".panel-body").append("<div class='form-group'><label class='col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label'>选项" + i + "：</label> <div class='col-sm-9 col-xs-12'> <input type='text' class='form-control xuan' name='xuan[]'></div> </div>");
            i++;
        });
        //提交数据
        $('#sub').click(function(){
            var data = $('form').serialize();
            $.post("<?php  echo $this->createWebUrl('postadd')?>&"+data,function(data){
               if (data.stat == 200){
                    alert('提交成功');
                   window.location.href = "<?php  echo $this->createWebUrl('sit',array('op'=>'list'))?>";
               }
            },'JSON');
        });
    });

</script>
<?php  if($op == 'compile') { ?>
<div class="clearfix add_page" >
    <div class="form-horizontal form">
        <form>
            <div class="panel panel-default">
                <div class="panel-heading">
                    添加问卷
                </div>
                <div class="panel-body">
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">序号：</label>
                        <div class="col-sm-1 col-xs-12">
                            <input type="text" class="form-control" name="id" value="<?php  echo $item['id'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">区域：</label>
                        <div class="col-sm-1 col-xs-12">
                            <input type="text" class="form-control" name="address" value="<?php  echo $item['address'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">类型：</label>
                        <div class="col-sm-1 col-xs-12">
                            <select name="type" class="form-control" disabled="disabled">
                                <option value="radio">单选</option>
                                <option value="checked">多选</option>
                                <option value="text">文本</option>
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">问题：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="title" value="<?php  echo $item['title'];?>">
                        </div>
                    </div>
                    <?php  if(is_array($item['key'])) { foreach($item['key'] as $k => $it) { ?>
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label">选项<?php  echo $k+1?>：</label>
                        <div class="col-sm-9 col-xs-12">
                            <input type="text" class="form-control xuan" name="xuan[]" value="<?php  echo $it;?>">
                        </div>
                    </div>
                    <?php  } } ?>
                </div>
                <?php  } } ?>
            </div>
            <div class="form-group col-sm-12">
                <input type="button" id="sub_up" class="btn btn-primary col-lg-1" name="submit" value="提交" />
            </div>
        </form>
    </div>
</div>
<?php  } ?>
<script>
    $(function(){
        //提交数据
        $('#sub_up').click(function(){
            var data = $('form').serialize();
            $.post("<?php  echo $this->createWebUrl('postup')?>&"+data,function(data){
                if (data.stat == 200){
                    alert('提交成功');
                    window.location.href = "<?php  echo $this->createWebUrl('sit',array('op'=>'list'))?>";
                }
            },'JSON');
        });
    });

</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
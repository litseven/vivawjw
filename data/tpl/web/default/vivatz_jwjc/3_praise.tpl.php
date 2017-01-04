<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<ul class="nav nav-tabs">
    <li class="active"  class="lists"><a href="javascript:;">用户问卷列表</a></li>

</ul>
<div class="list_page">
    <form action="" method="post" id="form1">
        <div class="panel panel-default">
            <div class="panel-body table-responsive">
                <table class="table table-hover">
                    <thead class="navbar-inner">
                    <tr>
                        <th style="width:5%;">序号</th>
                        <th>民警姓名</th>
                        <th>民警编号</th>
                        <th>车牌</th>
                        <th>民警单位</th>
                        <th>下属单位</th>
                        <th>表扬人</th>
                        <th>电话</th>
                        <th>情况叙述</th>
                        <th style="text-align: center">时间</th>
                        <th style="text-align: center">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(is_array($list)) { foreach($list as $item) { ?>
                    <tr>
                        <td style="vertical-align:middle;"><?php  echo $item['id'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_name'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_num'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_car_num'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_unit'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_next_unit'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_to_name'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_to_num'];?></td>
                        <td style="vertical-align:middle;"><?php  echo $item['pr_comment'];?></td>
                        <td style="vertical-align:middle;"><span class="time" title="<?php  echo date('Y/m/d H:i:s',$item['pr_time'])?>"><?php  echo date('Y/m/d H:i:s',$item['pr_time'])?>
                        </span></td>
                        <td id="update_<?php  echo $item['id'];?>" style="text-align: center">
                            <span  class="btn btn-sm btn-danger sub_delete" data-id="<?php  echo $item['id'];?>" title="点击删除">删除</span>
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
        $('.btn ,.time').hover(function(){
            $(this).tooltip('show');
        },function(){
            $(this).tooltip('hide');
        });
        //删除
        $('.sub_delete').click(function(){
            var id = $(this).attr('data-id');
            if (confirm('确认要删除吗？')) {
                $.post("<?php  echo $this->createWebUrl('praise',array('op'=>'pr_delete'))?>", {'id': id}, function (data) {
                    if (data == 200) {
                        window.location.href="<?php  echo $this->createWebUrl('praise')?>";
                    }
                });
            }
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
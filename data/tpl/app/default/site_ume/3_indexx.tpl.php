<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
    <div>
        <input type="text" name="address"  placeholder="请输入要查询的地点 必填">
        <input type="tel" name="range" placeholder="请输入要查询的范围 单位：/米 默认：1000">
        <input type="tel" name="num" class="num"  placeholder="请输入查询结果的数量 默认：3">
        <input type="submit" value="提交" id="sub" style="margin: 0 10%; width: 80%;">
    </div>


    <?php  echo tpl_app_form_field_avatar('avatar');?>

<div class="mui-input-row">
    <label>自定义选项</label>
    <input class="js-user-options" type="text" value="" readonly="" placeholder="请选择">
</div>
<script type="text/javascript">
    $(".js-user-options").on("tap", function(){
        var options = {data: [
            {"text":"测试1","value":"ceshi1"},
            {"text":"测试2","value":"ceshi2"}
        ]};
        var $this = $(this);
        util.poppicker(options, function(items){
            $this.val(items[0].value);
        });
    });
</script>
<script>
    $(function(){
        /*$data = $('form').serialize();
        window.location.href = "<?php  echo $this->createMobileUrl('postgb')?>&"+$data;*/
        $('#sub').click(function(){
            var address = $("[name=address]").val();
            var range = $("[name=range]").val();
            var num = $(".num").val();
            $.post("<?php  echo $this->createMobileUrl('postgb')?>",{address:address,range:range,num:num},function(data){
                    if (data.status == 200){
                        alert('设置成功，关闭页面，请发送位置！');
                    }else{
                        alert('error');
                    }
            },'JSON');
        });
    });
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>

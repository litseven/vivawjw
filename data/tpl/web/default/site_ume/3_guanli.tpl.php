<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<!--<script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
<input type="text" name="addr" class="addr">地点<br>
<input type="text" name="range" class="range">范围<br>
<input type="text" name="num" class="num">数量
<input type="submit" value="提交" id="sub">-->
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
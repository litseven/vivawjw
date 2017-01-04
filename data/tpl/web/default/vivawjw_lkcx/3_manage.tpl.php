<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<?php  if($op == 'list') { ?>
<div class="main" >
    <style>
		.order-nav {margin: 0;width: 100%;min-width: 800px;}
	.order-nav > li > a {display: block;}
	.order-nav-tabs {background: #EEE;}
	.order-nav-tabs > li {line-height: 40px;float: left;list-style: none;display: block;text-align: -webkit-match-parent;}
	.order-nav-tabs > li > a {font-size: 14px;color: #666;height: 40px;line-height: 40px;padding: 0 10px;margin: 0;border: 1px solid transparent;border-bottom-width: 0px;-webkit-border-radius: 0;-moz-border-radius: 0;border-radius: 0;}
	.order-nav-tabs > li > a, .order-nav-tabs > li > a:focus {border-radius: 0 !important;background-color: #f9f9f9;color: #999;margin-right: -1px;position: relative;z-index: 11;border-color: #c5d0dc;text-decoration: none;}
	.order-nav-tabs >li >a:hover {background-color: #FFF;}
	.order-nav-tabs > li.active > a, .order-nav-tabs > li.active > a:hover, .order-nav-tabs > li.active > a:focus {color: #576373;border-color: #c5d0dc;border-top: 2px solid #4c8fbd;border-bottom-color: transparent;background-color: #FFF;z-index: 12;margin-top: -1px;box-shadow: 0 -2px 3px 0 rgba(0, 0, 0, 0.15);}
</style>
<ul class="order-nav order-nav-tabs" style="background:none;float: left;margin-left: 0px;padding-left: 0px;border-bottom:1px #c5d0dc solid;">
	<li<?php  if($status == 2) { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage', array('status' => 2))?>">待通过</a></li>
	<li<?php  if($status == 1) { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage', array('status' => 1))?>">已通过</a></li>
	<li<?php  if($status == 3) { ?> class="active"<?php  } ?>><a href="<?php  echo $this->createWebUrl('manage', array('status' => 3))?>">驳回</a></li>
</ul>
		<div class="panel panel-default">
			<div class="panel-body table-responsive">
				<table class="table table-hover">
					<thead class="navbar-inner">
						<tr>
							<th style="width:50px">编号</th>
							<th style="text-align: center">路况说明</th>
							<th style="text-align: center">提交时间</th>
							<th style="text-align: center">受理时间</th>
							<th style="width:100px; text-align:center;">操作</th>
						</tr>
					</thead>
                    <style>.status_0{color:#FF9900;}.status_1{color:green;}.status_2{color:red;}</style>
					<tbody>
						<?php  if(is_array($list)) { foreach($list as $item) { ?>
						<tr style="text-align: center">
							<td><?php  echo $item['id'];?></td>
							<td><?php  echo $item['pictinfo'];?></td>
							<td><?php  echo date('Y-m-d H:i:s',$item['time'])?></td>
							<td><?php  if($item['status'] == 2) { ?>
								<span class="require"> 未受理</span>
								<?php  } else { ?>
								<?php  echo date('Y-m-d H:i:s',$item['sittime'])?>
								<?php  } ?>
							</td>
							<td style="text-align:right;">
								<a href="<?php  echo $this->createWebUrl('manage', array('op' => 'view', 'id' => $item['id']))?>" class="btn btn-default btn-sm" title="编辑" data-toggle="tooltip" data-placement="top"><i class="fa fa-eye"> </i> 查看</a>
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
<?php  } else if($op == 'view') { ?>
<script>
require(['../../../addons/PUBLIC/template/resource/js/lightbox.min.js'],function(){

		});
</script>
<style>
	.thumbscien img{width:18%;margin-right:2%}
	.thumbscien img:last-child{margin:0}
	input:focus::-webkit-input-placeholder,textarea:focus::-webkit-input-placeholder { color:transparent; }
    </style>
<link rel="stylesheet" href="<?php echo MODULE_URL;?>template/resource/css/lightbox.min.css">
<div class="main">
		<input type="hidden" name="c" value="site">
		<input type="hidden" name="a" value="entry">
		<input type="hidden" name="m" value="vivawjwtakefoto">
		<input type="hidden" name="do" value="wzlist"/>
		<input type="hidden" name="op" value="status"/>
		<input type="hidden" name="id" value="<?php  echo $info['id'];?>"/>
    <div class="panel panel-default">
		<div class="panel-heading">事故快处理详情</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">提交时间:</label>
				<div class="col-sm-9 col-xs-9 col-md-9">
					<p class="form-control-static"><?php  echo date('Y-m-d H:i:s',$info['time'])?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label">路况说明:</label>
				<div class="col-sm-9 col-xs-9 col-md-9">
					<p class="form-control-static"><?php  echo $info['pictinfo'];?></p>
				</div>
			</div>
		</div>
	</div>

    <div class="panel panel-default">
		<div class="panel-heading">资料预览</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label ">照片</label>
				<div class="col-sm-9 col-xs-9 col-md-9 thumbscien">
					<?php  if(is_array($v['imgdir'])) { foreach($v['imgdir'] as $foto) { ?>
					<a href="<?php  echo tomedia($foto);?>" data-lightbox="roadtrip"><img src="<?php  echo tomedia($foto);?>" ></a>
					<?php  } } ?>
				</div>
			</div>
			<?php  if($v['videodir']) { ?>
			<div class="form-group">
				<label class="col-xs-12 col-sm-3 col-md-2 control-label ">视频</label>
				<div class="col-sm-9 col-xs-9 col-md-9 thumbscien">
					<?php  if(is_array($v['videodir'])) { foreach($v['videodir'] as $video) { ?>
					<video src="<?php  echo tomedia($video);?>" controls preload height="300" width="400">您的浏览器不支持 video 标签。</video>
					<?php  } } ?>
				</div>
			</div>
			<?php  } ?>
		</div>
	</div>
		<form>
			<div class="panel panel-default">
				<div class="panel-heading">操作</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">是否通过</label>
                        <?php  if($info['status'] == 2) { ?>
						<div class="col-sm-9 col-xs-12">
							<label class="radio-inline">
								<input type="radio" value="1" name="status" onClick="$('#failreason').hide();" checked> 通过
							</label>
							<label class="radio-inline">
								<input type="radio" value="3" name="status" onClick="$('#failreason').show();"> 驳回
							</label>
						</div>
                        <?php  } else { ?>
						<div class="col-sm-9 col-xs-9 col-md-9">
							<p class="form-control-static"><?php  if($info['status']==1) { ?>通过<?php  } else { ?>驳回<?php  } ?></p>
						</div>
						<?php  } ?>
					</div>
				</div>
				<div class="panel-body">
                	<div class="form-group" id="failreason" style="display:<?php  if($info['status']==3) { ?> <?php  } else { ?>none<?php  } ?>">
						<label class="col-xs-12 col-sm-3 col-md-2 control-label">驳回原因</label>
                        <?php  if($info['status']==3) { ?>
                        <div class="col-sm-9 col-xs-9 col-md-9">
								<p class="form-control-static"><?php  echo $info['turndown'];?></p>
							</div>
                        <?php  } else { ?>
						<div class="col-sm-9 col-xs-9 col-md-9">
							<textarea class="form-control" name="failreason"><?php  echo $info['failreason'];?></textarea>
						</div>
                        <?php  } ?>
					</div>
				</div>
			</div>
			<div class="form-group" style="padding-bottom:100px">
				<div class="col-sm-9 col-xs-9 col-md-9">
                	<?php  if($info['status'] == 2) { ?>
					<a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
					<a href="javascript:;" class="btn btn-success scien postbtn"  style="margin-left:50px;display:<?php  if($info['status']==2) { ?> <?php  } else { ?>none<?php  } ?>">确定操作</a>
                    <?php  } else { ?>
					<a href="javascript:history.go(-1);" class="btn btn-primary">返回列表</a>
                    <?php  } ?>
				</div>
			</div>
    	</form>
    <script>
		$(function(){
			$(".postbtn").click(function(){
				var status = $("[name = 'status']:checked").val();
				var failreason = $("[name = 'failreason']").val();
				var id = $("[name = 'id']").val();
				if (status == 3){
					if (failreason == ''){
						alert('请添加驳回信息！');return false;
					}
				}
				if (confirm('确定修改状态?')){
					$.post("<?php  echo $this->createWebUrl('manage',array('op'=>'status'))?>",{id:id,status:status,failreason:failreason},function(data){
						if(data == 200){
							window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list','status'=>1))?>";
						}
						if(data == 300){
							window.location.href = "<?php  echo $this->createWebUrl('manage',array('op'=>'list','status'=>3))?>";
						}
					});
				}
			});
		});
	</script>
</div>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
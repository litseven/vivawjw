<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
class vivawjw_xxbgModuleSite extends WeModuleSite
{
	/*
	 * 入口
	 */
	public function doMobileInfochange(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'notice';
		$block =trim($_GPC['block'])? trim($_GPC['block']): 'driver';
		$show =trim($_GPC['show'])? trim($_GPC['show']): 'driver';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		//驾驶证号、证芯编号
		$drivernum = trim($_GPC['drivernum']);
		$driverpapers = trim($_GPC['driverpapers']);

		//车辆类型车牌、证芯编号
		$chcartype = trim($_GPC['chcartype']);
		$chcarnum = trim($_GPC['chcarnum']);
		$chcarpapers = trim($_GPC['chcarpapers']);
		include $this->template('infochange');
	}
	//驾驶人接受数据验证
	public function doMobileDrivermanage(){
		global $_W,$_GPC;
		$drivernum = trim($_GPC['drivernum']);
		$driverpapers = trim($_GPC['driverpapers']);

		//提交接口对比数据

		echo 200;exit;
	}
	//机动车接受数据验证
	public function doMobileCarmanage(){
		global $_W,$_GPC;
		$chcartype = $_GPC['chcartype'];
		$chcarnum = trim($_GPC['chcarnum']);
		$chcarpapers = trim($_GPC['chcarpapers']);

		//提交接口对比数据

		echo 200;exit;
	}
	//变更驾驶人信息
	public function doMobileDrpost(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = trim($_W['member']['uid']);
		$data['drname'] = trim($_GPC['drname']);
		$data['drcard'] = trim($_GPC['drcard']);
		$data['drnum'] = trim($_GPC['drnum']);
		$data['draddr'] = trim($_GPC['draddr']);
		$data['drphone'] = trim($_GPC['drphone']);
		$data['drtime'] = time();
		$data['drivernum'] = trim($_GPC['drivernum']);
		$data['driverpapers'] = trim($_GPC['driverpapers']);
		$drdata = pdo_insert('vivawjw_change_driver',$data);
		if ($drdata){
			echo 200;exit;
		}

	}
	//变更机动车信息
	public function doMobileCarpost(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = trim(23);
		$data['carname'] = trim($_GPC['carname']);
		$data['cartype'] = trim($_GPC['cartype']);
		$data['carnum'] = trim($_GPC['carnum']);
		$data['caraddr'] = trim($_GPC['caraddr']);
		$data['carphone'] = trim($_GPC['carphone']);
		$data['cartime'] = time();
		$data['chcartype'] = trim($_GPC['chcartype']);
		$data['chcarnum']= trim($_GPC['chcarnum']);
		$data['chcarpapers'] = trim($_GPC['chcarpapers']);
		$cardata = pdo_insert('vivawjw_change_car',$data);
		if ($cardata){
			echo 200;eixt;
		}
	}




	/*
	 * 后台管理
	 */
	public function doWebManage(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'driver';
		//驾驶人列表
		if ($op == 'driver'){
			//获取页码
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_change_driver').' WHERE uniacid = :uniacid ',array(':uniacid' => $_W['uniacid']));
			$drlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_change_driver').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
			$pager =pagination($total, $pindex, $psize);
		}
		//驾驶人信息
		if($op == 'drinfo'){
			$id = $_GPC['id'];
			$drinfo = pdo_fetch('SELECT * FROM '.tablename('vivawjw_change_driver').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		}
		//机动车列表
		if ($op == 'car'){
			//获取页码
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_change_car').' WHERE uniacid = :uniacid ',array(':uniacid' => $_W['uniacid']));
			$carlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_change_car').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
			$pager =pagination($total, $pindex, $psize);
		}
		//机动车信息
		if($op == 'carinfo'){
			$id = $_GPC['id'];
			$carinfo = pdo_fetch('SELECT * FROM '.tablename('vivawjw_change_car').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
		}
		//删除驾驶人信息
		if ($op == 'driverdel'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_change_driver',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		//删除机动车信息
		if ($op == 'cardel'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_change_car',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('manage');
	}
	//驾驶人信息驳回
	public function doWebDrturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_change_driver',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}
	//机动车信息驳回
	public function doWebCarturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$cardata = pdo_update('vivawjw_change_car',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($cardata){
			echo 200;exit;
		}
	}

}
?> 
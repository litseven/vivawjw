<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
class vivawjw_grzxModuleSite extends WeModuleSite
{
	//入口
	//我的车驾告知
	public function doMobileUser_warn(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		if ($op == 'list'){
			//所有推送的消息
			$warnlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_warn').' WHERE uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
			//不接收推送的消息存入库
			$warnstatus = pdo_fetchall('SELECT title_key FROM '.tablename('vivawjw_user_warn_status').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
			//将不接收推送的消息转成一维数组
			foreach ($warnstatus as $k => $v){
				$warnstatus[$k] = $v['title_key'];
			}
			//比对所有推送的消息
			foreach ($warnlist as &$v){
				if (in_array($v['title_key'],$warnstatus)){
					//有数据显示关闭
					$v['status'] = 0;
				}else{
					//没有数据显示打开
					$v['status'] = 1;
				}
			}
		}
		if ($op == 'warnpost'){
			//选择打开删除数据
			if ($_GPC['on_off']){
				$uid = $_GPC['uid'];
				$title_key = $_GPC['title_key'];
				$uniacid =  $_W['uniacid'];
				$delstatus = pdo_delete('vivawjw_user_warn_status',array('uniacid'=>$uniacid,'uid'=>$uid,'title_key'=>$title_key));
			}else{
				//选择关闭添加数据
				$data['uniacid'] = $_W['uniacid'];
				$data['title_key'] = $_GPC['title_key'];
				$data['uid'] = $_GPC['uid'];
				$instatus = pdo_insert('vivawjw_user_warn_status',$data);
			}

		}

		include $this->template('user_warn');
	}

	//我的车辆
	public function doMobileUser_car(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$data['uid'] = 12;
		$uid = $data['uid'];
		$showdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND uniacid=:uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		if (!empty($showdata)){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_car';
			$typecar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND uniacid=:uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		}else{
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';

		}
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		//无锡绑定车辆
		if ($op == 'wx_car_post'){
			$data['wx_type'] = $_GPC['wx_type'];
			$data['wx_car_num'] = $_GPC['wx_car_num'];
			$data['wx_car_engine'] = $_GPC['wx_car_engine'];
			$data['distinction'] = 1;
			$wxdata = pdo_insert('vivawjw_user_bound_car',$data);
			if($wxdata){
				echo 200;exit;
			}
		}
		//外地绑定车辆
		if ($op == 'wd_car_post'){
			$data['wd_type'] = $_GPC['wd_type'];
			$data['wd_car_num'] = $_GPC['wd_car_num'];
			$data['wd_car_engine'] = $_GPC['wd_car_engine'];
			$data['distinction'] = 0;
			$wxdata = pdo_insert('vivawjw_user_bound_car',$data);
			if($wxdata){
				echo 200;exit;
			}
		}
		//解除绑定车辆
		if ($op == 'del_bound_car'){
			$id = $_GPC['id'];
			$delbound = pdo_delete('vivawjw_user_bound_car',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($delbound){
				$info = '成功';
			}else{
				$info = '失败';
			}
		}
		include $this->template('user_car');
	}
	//我的驾驶证
	public function doMobileUser_driving(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$data['uid'] = 8;
		$uid = $data['uid'];
		$showdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		if (!empty($showdata)){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
			$typedriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		}else{
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';

		}
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		//无锡添加驾驶证
		if ($op == 'wx_drivpost'){
			$data['wx_driv_num'] = $_GPC['wx_driv_num'];
			$data['wx_driv_record'] = $_GPC['wx_driv_record'];
			$data['distinction'] = 1;
			$wxdata = pdo_insert('vivawjw_user_bound_driving',$data);
			if ($wxdata){
				echo 200;exit;
			}
		}
		//外地添加驾驶证
		if ($op == 'wd_drivpost'){
			$data['wd_driv_num'] = $_GPC['wd_driv_num'];
			$data['wd_driv_record'] = $_GPC['wd_driv_record'];
			$data['distinction'] = 0;
			$wddata = pdo_insert('vivawjw_user_bound_driving',$data);
			if ($wddata){
				echo 200;exit;
			}
		}
		//解除绑定驾驶证
		if ($op == 'del_bound_dr'){
			$id = $_GPC['id'];
			$delbound = pdo_delete('vivawjw_user_bound_driving',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($delbound){
				$info = '成功';
			}else{
				$info = '失败';
			}
		}

		include $this->template('user_driving');
	}
	//产品反馈
	public function doMobilePro_feedback(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'feedback';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$data['uid'] = 2;
		if ($op == 'feedpost'){
			$data['uniacid'] = $_W['uniacid'];
			$data['feedtype'] = $_GPC['feedtype'];
			$data['content'] = $_GPC['content'];
			$data['phone'] =$_GPC['phone'];
			$data['time'] = time();
			$feeddata = pdo_insert('vivawjw_user_feedback',$data);
			if ($feeddata){
				echo 200;exit;
			}
		}
		include $this->template('pro_feedback');
	}

	//我的申诉
	public function doMobileUser_complain(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$uid = $data['uid'] = 4;
		$wfdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		include $this->template('user_complain');
	}

	//我的快处
	public function doMobileUser_dispose(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		$kcdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_sgkc').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		include $this->template('user_dispose');
	}

	//我的路况
	public function doMobileUser_road(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;

		include $this->template('user_road');
	}

	//我的在线学习
	public function doMobileUser_study(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;

		include $this->template('user_study');
	}











	//后台管理
	public function doWebManage(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
		$uniacid = $_W['uniacid'];
		//无锡绑定车辆列表
		if ($op == 'wx_car'){
			$distinction = 1;
			$wxlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//无锡绑定车辆信息
		if ($op == 'wx_car_info'){
			$id = $_GPC['id'];
			$wxone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id = :id ',array(':id'=>$id));
		}
		//外地绑定车辆列表
		if ($op == 'wd_car'){
			$distinction = 0;
			$wdlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//外地绑定车辆信息
		if ($op == 'wd_car_info'){
			$id = $_GPC['id'];
			$wdone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id = :id ',array(':id'=>$id));
		}
		//删除无锡绑定车辆
		if ($op == 'car_del'){
			$id = $_GPC['id'];
			$wxcardel = pdo_delete('vivawjw_user_bound_car',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($wxcardel){
				echo 200;exit;
			}
		}


		//无锡绑定驾驶证列表
		if ($op == 'wx_driving'){
			$distinction = 1;
			$wxdriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//无锡绑定驾驶证信息
		if ($op == 'wx_driv_info'){
			$id = $_GPC['id'];
			$wxdriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
		}

		//外地绑定驾驶证列表
		if ($op == 'wd_driving'){
			$distinction = 0;
			$wddriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//外地绑定驾驶证信息
		if ($op == 'wd_driv_info'){
			$id = $_GPC['id'];
			$wddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
		}
		//删除无锡绑定车辆
		if ($op == 'dir_del'){
			$id = $_GPC['id'];
			$wxcardel = pdo_delete('vivawjw_user_bound_driving',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($wxcardel){
				echo 200;exit;
			}
		}

		//产品反馈
		if ($op == 'pro_feedback'){
			$feeddata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
		}
		//产品反馈信息
		if ($op == 'feedback_info'){
			$id = $_GPC['id'];
			$feedone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE id = :id ',array(':id'=>$id));
		}


















		include $this->template('manage');
	}
	//无锡车辆受理
	public function doWebWxturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_user_bound_car',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}
	//无锡驾驶证受理
	public function doWebWxdrturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_user_bound_driving',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}

	//外地车辆受理
	public function doWebWdturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_user_bound_car',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}
	//外地驾驶证受理
	public function doWebWddrturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_user_bound_driving',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}
	//外地驾驶证受理
	public function doWeBfeedturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_user_feedback',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}

}
?> 
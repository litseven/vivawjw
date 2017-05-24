<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_xxbg/template/resource/');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_xxbg/template/resource/');
class vivawjw_xxbgModuleSite extends WeModuleSite
{
	/*
	 * 入口
	 */
	public function doMobileInfochange(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'notice';
		$block =trim($_GPC['block'])? trim($_GPC['block']): 'driver';
		$show =trim($_GPC['show'])? trim($_GPC['show']): 'driver';

		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');
		include $this->template('infochange');
	}
	//驾驶证信息查询验证
	public function doMobileDrivermanage(){
		global $_W,$_GPC;
		$drivernum = trim(strtoupper($_GPC['drivernum']));
		$driverpapers = trim(strtoupper($_GPC['driverpapers']));

		//提交接口对比数据
		$data = $this->wxapi('JSZXXCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$drivernum,$driverpapers);
		echo json_encode($data);
	}

	//机动车接受数据验证
	public function doMobileCarmanage(){
		global $_W,$_GPC;
		$chcartype = $_GPC['chcartype'];
		$chcarnum = trim(strtoupper($_GPC['chcarnum']));
		$chcarpapers = trim(strtoupper($_GPC['chcarpapers']));

		//提交接口对比数据
		$data = $this->wxapi('CLXXCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$chcartype,$chcarnum,$chcarpapers);
		echo json_encode($data);
	}

	//变更驾驶人信息
	public function doMobileDrpost(){
		global $_W,$_GPC;
		$drivernum = trim(strtoupper($_GPC['drivernum']));
		$driverpapers = trim(strtoupper($_GPC['driverpapers']));
		$lxdh = $_GPC['lxdh'];
		$sjhm = $_GPC['sjhm'];
		$lsdz = $_GPC['lsdz'];
		libxml_disable_entity_loader(false);
		$opts = array(
			'ssl'   => array(
				'verify_peer'          => false
			),
			'https' => array(
				'curl_verify_ssl_peer'  => false,
				'curl_verify_ssl_host'  => false
			)
		);
		$streamContext = stream_context_create($opts);
		try {
			$url = 'http://192.168.11.51/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			$data =  $c->JSZXXBG('C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$drivernum,$driverpapers,$lsdz,$lxdh,$sjhm);
			echo json_encode($data);
		} catch (SOAPFault $e) {
			echo "<script>alert('系统繁忙，请稍后再试！')</script>";
		}
		//提交接口对比数据
		//$data = $this->wxapi('JSZXXBG','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$drivernum,$driverpapers,$lsdz,$lxdh,$sjhm);
		//$data = $this->wxapi('JSZXXBG','C81DD8605F0531F0B6C717D07A8979F4','WXZHCS','340304198211110012','062105','qqqq','','15812345678');
	}




	//变更机动车信息
	public function doMobileCarpost(){
		global $_W,$_GPC;
		$chcartype = $_GPC['chcartype'];
		$chcarnum = trim(strtoupper($_GPC['chcarnum']));
		$chcarpapers = trim(strtoupper($_GPC['chcarpapers']));
		$lxdh = $_GPC['lxdh'];
		$sjhm = $_GPC['sjhm'];
		$lsdz = $_GPC['lsdz'];
		//提交接口对比数据
		libxml_disable_entity_loader(false);
		$opts = array(
			'ssl'   => array(
				'verify_peer'          => false
			),
			'https' => array(
				'curl_verify_ssl_peer'  => false,
				'curl_verify_ssl_host'  => false
			)
		);
		$streamContext = stream_context_create($opts);
		try {
			$url = 'http://192.168.11.51/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			$data = $c->CLXXBG('C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$chcartype,$chcarnum,$chcarpapers,'',$lsdz,$lxdh,$sjhm);
			echo json_encode($data);
		} catch (SOAPFault $e) {
			echo "<script>alert('系统繁忙，请稍后再试！')</script>";
		}

	}


//接口
	public function wxapi($api,$sign,$wx,$typt,$carnum,$engnum){
		libxml_disable_entity_loader(false);
		$opts = array(
			'ssl'   => array(
				'verify_peer'          => false
			),
			'https' => array(
				'curl_verify_ssl_peer'  => false,
				'curl_verify_ssl_host'  => false
			)
		);
		$streamContext = stream_context_create($opts);
		try {
			$url = 'http://192.168.11.51/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			//echo '<pre>';
			//var_dump($c->register('wxzhcs','wxzhcs123456'));
			//C81DD8605F0531F0B6C717D07A8979F4
			return $c->$api($sign,$wx,$typt,$carnum,$engnum);

		} catch (SOAPFault $e) {
			echo "<script>alert('系统繁忙，请稍后再试！')</script>";
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
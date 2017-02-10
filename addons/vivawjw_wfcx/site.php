<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/'.$_GET['m'].'/template/resource/');
class vivawjw_wfcxModuleSite extends WeModuleSite
{
	/*
	 * 入口
	 */
	public function doMobileIllegal(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$userid = $_W['member']['uid'];
		//var_dump($_W['member']);
		include $this->template('illegal');
	}
	//申请信息
	public function doMobileWxappeal(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'post';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$userid = $_W['member']['uid'];

		include $this->template('wxappeal');
	}
	//申请信息处理
	public function doMobileAppealpost(){
		global $_W,$_GPC;
		$data['appeal_name'] = $_GPC['appeal_name'];
		$data['appeal_phone'] = $_GPC['appeal_phone'];
		$data['appeal_why'] = $_GPC['appeal_why'];
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = $_GPC['userid'];
		$data['time'] = time();

		$data['wfcjjg'] = $_GPC['wfcjjg'];
		$data['wftime'] = strtotime($_GPC['wftime']);
		$data['wfaddr'] = $_GPC['wfaddr'];
		$data['wfcontent'] = $_GPC['wfcontent'];
		$data['wfjkxh'] = $_GPC['wfjkxh'];
		$data['wfclbj'] = $_GPC['wfclbj'];
		$data['wfrksj'] = strtotime($_GPC['wfrksj']);
		$data['wffkjf'] = $_GPC['wffkjf'];
		$data['wfmoney'] = $_GPC['wfmoney'];
		$data['wfcph'] = $_GPC['wfcph'];
		if(!empty($data['appeal_name']) && !empty($data['appeal_phone']) && !empty($data['appeal_why'])){
			$insert_data = pdo_insert('vivawjw_wfcx',$data);
			if ($insert_data){
				echo 200;
			}
		}
	}

	//车辆违法查询接口
	public function doMobileApipostcl(){
		global $_W,$_GPC;
		$data = $this->wxapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$_GPC['cartype'],$_GPC['carnum'],$_GPC['enginenum']);
		echo json_encode($data);
	}
	//驾驶人违法查询
	public function doMobileApipostjsr(){
		global $_W,$_GPC;
		$data = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$_GPC['drnunum'],$_GPC['filenum']);
		echo json_encode($data);
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
			$url = 'http://192.168.11.51:5028/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			//echo '<pre>';
			//var_dump($c->register('wxzhcs','wxzhcs123456'));
			//C81DD8605F0531F0B6C717D07A8979F4
			return $c->$api($sign,$wx,$typt,$carnum,$engnum);

		} catch (SOAPFault $e) {
			print_r($e);
		}
	}


	/*
	 * 后台管理
	 */
	//后台
	//申请信息管理
	public function doWebManage(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		if($op == 'view'){
			$dataone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid AND id = :id',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		}
		//删除数据
		if ($op == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_wfcx',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('manage');
	}
	//申诉信息驳回
	public function doWebSsturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_wfcx',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}

}
?> 
<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://wx.vivatech.cn/addons/'.$_GET['m'].'/template/resource');
class BookwallModuleSite extends WeModuleSite
{
	public function doMobileEnter(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$user = $_W['fans'];

		include $this->template('enter');   
		} 

	public function doMobileBigscreen(){
		global $_W,$_GPC;
		include $this->template('bigscreen');   
		}
	
	public function doMobileGetlastest(){
		global $_W,$_GPC;
		$lastestid = pdo_fetchcolumn("select id from ".tablename('scien_wall_content')." where uniacid = :uniacid and allowed = 1 order by id desc limit 1",array(":uniacid"=>$_W['uniacid']));
		exit(json_encode(array('lastestid'=>$lastestid)));	
		}
	
	public function doMobileBigdata(){
		global $_W,$_GPC;
		$lists = pdo_fetchall("select * from ".tablename('scien_wall_content')." where uniacid = :uniacid and allowed = 1 order by id desc LIMIT 5",array(":uniacid"=>$_W['uniacid']));
		exit(json_encode($lists));	
		}

	public function doMobileBigdatarandom(){
		global $_W,$_GPC;
		$lists = pdo_fetchall("select * from ".tablename('scien_wall_content')." where uniacid = :uniacid and allowed = 1 order by RAND() LIMIT 2",array(":uniacid"=>$_W['uniacid']));
		exit(json_encode($lists));	
		}


	public function doMobilePostgb(){
		global $_W,$_GPC;
		checkauth();
		$data['realname'] = $_GPC['rn'];
		$data['avatar'] = $_GPC['avatar'];
		$data['gb'] = $_GPC['gc'];
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = $_W['member']['uid'];
		$data['addtime'] = date("Y-m-d H:i:s",time());
		//print_r($data);
		//留言限制关闭
		//$isexit = pdo_fetchcolumn("select count(*) as count from ".tablename('scien_wall_content')." where uniacid = :uniacid and uid = :uid",array(":uniacid"=>$_W['uniacid'],":uid"=>$_W['member']['uid']));
		//if($isexit)exit(json_encode(array('status'=>500,'_s'=>$isexit)));
		pdo_insert('scien_wall_content',$data);
		echo json_encode(array('status'=>200));
		}

	public function doMobileGblist(){
		global $_W,$_GPC;
		$page = isset($_GPC['p'])?$_GPC['p']:1;
		$pindex =max(1, $page);
		$psize =6;
		
		$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('scien_wall_content'). " where uniacid = :uniacid and allowed = 1 ",array(':uniacid' => $_W['uniacid']));
		$total = ceil($total/$psize);
		$lists = pdo_fetchall("select * from ".tablename('scien_wall_content')." where uniacid = :uniacid and allowed = 1 order by hit desc,id desc LIMIT ".($pindex - 1)* $psize.','.$psize,array(":uniacid"=>$_W['uniacid']));
		foreach($lists as &$v){
			$v['ishit'] = pdo_fetchcolumn("select count(*) from ".tablename('scien_wall_hits')." where uniacid = :uniacid and touid = :touid and fromuid = :fromuid",array(":uniacid"=>$_W['uniacid'],":touid"=>$v['uid'],":fromuid"=>$_W['member']['uid']));
			}
		if($_GPC['datetype']=='json')exit(json_encode($lists));	
		//print_r($lists);	
		include $this->template('gblist');   
		}

	public function doMobileHit(){
		global $_W,$_GPC;
		$data['fromuid'] = $_W['member']['uid'];
		$data['touid'] = $_GPC['touid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['hittime'] = time();
		$isexti = pdo_fetchcolumn("select count(*) from ".tablename('scien_wall_hits')." where uniacid = :uniacid and touid = :touid and fromuid = :fromuid",array(":uniacid"=>$_W['uniacid'],":touid"=>$data['touid'],":fromuid"=>$data['fromuid']));
		if(!$isexti){
			pdo_update('scien_wall_content','hit=hit+1',array("uniacid"=>$_W['uniacid'],"uid"=>$data['touid']));
			pdo_insert('scien_wall_hits',$data);
			echo json_encode(array('status'=>200));
			}
		else{
			echo json_encode(array('status'=>500));
			}	
		}


public function doWebGuestbook(){
global $_W, $_GPC;
$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
if($op == 'list'){
	$condition =' uniacid = :aid ';
	$params[':aid'] =$_W['uniacid'];
	if(!empty($_GPC['cid'])){
	$condition .= " AND cid = :cid";
	$params[':cid'] =intval($_GPC['cid']);
	}
$pindex =max(1, intval($_GPC['page']));
$psize =20;
$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('scien_wall_content'). ' WHERE ' . $condition, $params);
$lists =pdo_fetchall('SELECT * FROM ' . tablename('scien_wall_content'). ' WHERE ' . $condition . ' ORDER BY id desc LIMIT '.($pindex - 1)* $psize.','.$psize, $params);
$pager =pagination($total, $pindex, $psize);
}
elseif($op == 'display_switch'){
$id =intval($_GPC['id']);
$display =intval($_GPC['display']);
$state =pdo_update('scien_wall_content', array('allowed' => $display), array('uniacid' => $_W['uniacid'], 'id' => $id));
if($state !== false){exit('success');}exit("error");
}	

			include $this->template('guestbook');   
		}


	}
?> 
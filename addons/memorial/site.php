<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://lab.scienmedia.com/addons/'.$_GET['m'].'/template/resource/');
//define('S_URL', 'http://192.168.31.220/pros/addons/'.$_GET['m'].'/template/resource/');
class memorialModuleSite extends WeModuleSite
{
	//首页
	public function doMobileEnter(){
		global $_W,$_GPC;
		$count = pdo_fetch('SELECT count FROM '.tablename('memorial_num'));
		if ($count){
			$num = $count['count']+1;
			$updata = pdo_update('memorial_num',array('count'=>$num));
		}
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$user = $_W['fans'];
		//var_dump($user);
		include $this->template('enter');   
		}
	//接受留言并储存留言
	public function doMobilePostday(){
		global $_W, $_GPC;
		$data['uid'] = $_W['fans']['uid'];
		$data['uniacid'] =$_W['uniacid'];
		$data['name'] = $_GPC['name'];
		$data['content'] = $_GPC['content'];
		$data['time'] = time();
		$data = pdo_insert('memorial_day',$data);
		if ($data){
			echo json_encode(array('status'=>200));
		}

	}
	//查询留言
	public function doMobileSeldata(){
		global $_W,$_GPC;
		$tname = tablename('memorial_day');
		$uniacid = $_W['uniacid'];
		$numAll = pdo_fetchcolumn('SELECT COUNT(*) FROM '.$tname.' WHERE status = 1');
		$data = pdo_fetchall('SELECT name,content FROM '.$tname.' WHERE status = 1');
		$num = rand(0,$numAll-1);
		echo json_encode($data[$num]);
		/*echo '<pre>';
		var_dump($data[$num]);
		echo '<pre>';*/
	}

	//后台显示留言
	public function doWebGuestbook(){
		global $_W, $_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if($op == 'list'){
			$condition =' uniacid = :aid ';
			$params[':aid'] =$_W['uniacid'];
			if(!empty($_GPC['uid'])){
				$condition .= " AND uid = :uid";
				$params[':uid'] =intval($_GPC['uid']);
			}
			//echo '<pre>';
			//var_dump($params);
			//echo '<pre>';
		$pindex =max(1, intval($_GPC['page']));
		$psize =20;
		$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('memorial_day'). ' WHERE ' . $condition, $params);
		$lists =pdo_fetchall('SELECT * FROM ' . tablename('memorial_day'). ' WHERE ' . $condition . ' ORDER BY id desc LIMIT '.($pindex - 1)* $psize.','.$psize, $params);
		$pager =pagination($total, $pindex, $psize);

		}
		elseif($op == 'display_switch'){
			$id =intval($_GPC['id']);
			$display =intval($_GPC['display']);
			$state =pdo_update('memorial_day', array('status' => $display), array('uniacid' => $_W['uniacid'], 'id' => $id));
			if($state !== false){exit('success');}exit("error");
		}

		include $this->template('guestbook');
	}


}
?> 
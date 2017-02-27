<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_bszn/template/resource/');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_bszn/template/resource/');
class vivawjw_bsznModuleSite extends WeModuleSite
{
	//入口
	public function doMobileGuide(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';


		$data = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND status = :status AND fid =:fid ORDER BY id asc',array(':uniacid'=>$_W['uniacid'],':status'=>1,':fid'=>0	));
		$keylist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_hotkey').' WHERE uniacid = :uniacid AND status= :status',array(':uniacid'=>$_W['uniacid'],':status'=>1));
		if ($op == 'title'){
			$fid = $_GPC['id'];
			$title = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND status = :status AND fid =:fid',array(':uniacid'=>$_W['uniacid'],':status'=>1,':fid'=>$fid));
		}
		if ($op == 'content'){
			$id = $_GPC['id'];
			$content = pdo_fetch('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND status = :status AND id =:id',array(':uniacid'=>$_W['uniacid'],':status'=>1,':id'=>$id));
		}
		if ($op == 'key'){
			$hotkey = '"%'.$_GPC['hotkey'].'%"';
			$keylist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE fid <> 0 AND title LIKE '.$hotkey);

		}
		include $this->template('guide');
	}

	//后台管理
	public function doWebManage(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid  ORDER BY fid,id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		//编辑
		if($op == 'view'){
			$dataone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
			$fatherid = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND fid = :fid',array(':uniacid' => $_W['uniacid'],':fid' =>0));
		}
		//删除数据
		if ($op == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_bszn_guide',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		//查找父级目录
		if ($op == 'add'){
			$father = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND fid = :fid',array(':uniacid' => $_W['uniacid'],':fid' =>0));
		}
		//编辑根目录
		if ($op == 'recatalog'){
			$id = $_GPC['id'];
			$fatherone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_bszn_guide').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid' => $_W['uniacid'],':id' =>$id));
			//var_dump($fatherone);
		}
		//搜索关键字
		if ($op == 'hot_key_list'){
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_bszn_hotkey').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
			$keylist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_bszn_hotkey').' WHERE uniacid = :uniacid  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
			$pager =pagination($total, $pindex, $psize);
		}
		include $this->template('manage');
	}
	//添加热门关键字
	public function doWebKeypost(){
		global $_W,$_GPC;
		$data['hotkey'] = $_GPC['hotkey'];
		$data['uniacid'] = $_W['uniacid'];
		$keypost = pdo_insert('vivawjw_bszn_hotkey',$data);
		if ($keypost){
			echo 200;exit;
		}

	}
	//关键字显示隐藏
	public function doWebHotkeystatus(){
		global $_W,$_GPC;
		$id =intval($_GPC['id']);
		$display =intval($_GPC['display']);
		$state =pdo_update('vivawjw_bszn_hotkey', array('status' => $display), array('uniacid' => $_W['uniacid'], 'id' => $id));
		if($state !== false){exit('success');}exit("error");
	}
	//删除关键字
	public function doWebKeydelete(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$data = pdo_delete('vivawjw_bszn_hotkey',array('id'=>$id,'uniacid'=>$_W['uniacid']));
		if ($data){
			echo 200;exit;
		}
	}
	//添加跟目录
	public function doWebCatalog(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['fid'] = $_GPC['fid'];
		$data['title'] = $_GPC['title'];
		$data['time'] = strtotime($_GPC['time']);
		$catalog = pdo_insert('vivawjw_bszn_guide',$data);
		if ($catalog){
			echo 200;exit;
		}
	}
	//编辑跟目录
	public function doWebRecatalog(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$data['uniacid'] = $_W['uniacid'];
		$data['fid'] = $_GPC['fid'];
		$data['title'] = $_GPC['title'];
		$data['time'] = strtotime($_GPC['time']);
		$recatalog = pdo_update('vivawjw_bszn_guide',$data,array('id' =>$id));
		if ($recatalog){
			echo 200;exit;
		}
	}
	//添加办事信息
	public function doWebAddguide(){
		global $_W,$_GPC;
		$data['fid'] = $_GPC['fid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['title'] = $_GPC['title'];
		$data['content'] = $_GPC['content'];
		$data['time'] = strtotime($_GPC['time']);
		$addguide = pdo_insert('vivawjw_bszn_guide',$data);
		if ($addguide){
			echo 200;exit;
		}
	}
	//编辑办事信息
	public function doWebReview(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$data['uniacid'] = $_W['uniacid'];
		$data['fid'] = $_GPC['fid'];
		$data['title'] = $_GPC['title'];
		$data['content'] = $_GPC['content'];
		$data['time'] = strtotime($_GPC['time']);
		$review = pdo_update('vivawjw_bszn_guide',$data,array('id' =>$id));
		if ($review){
			echo 200;exit;
		}
	}
	//删除
	public function doWebGuidedelete(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$gd = pdo_delete('vivawjw_bszn_guide',array('id'=>$id,'uniacid'=>$_W['uniacid']));
		if ($gd){
			echo 200;exit;
		}
	}
	//显示隐藏
	public function doWebStatus(){
		global $_W,$_GPC;
		$id =intval($_GPC['id']);
		$display =intval($_GPC['display']);
		$state =pdo_update('vivawjw_bszn_guide', array('status' => $display), array('uniacid' => $_W['uniacid'], 'id' => $id));
		if($state !== false){exit('success');}exit("error");
	}

}
?> 
<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
//define('S_URL', 'http://lab.scienmedia.com/addons/'.$_GET['m'].'/template/resource/');
define('S_URL', 'http://localhost/pros/addons/'.$_GET['m'].'/template/resource/');
class research_questionModuleSite extends WeModuleSite
{
	//前台
	//南通问卷首页
	public function doMobileEnter(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'page1';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$user = $_W['fans'];
		//var_dump($user);
		$dataList = pdo_fetchall('SELECT * FROM '.tablename('research_question').' WHERE uniacid = :uniacid and is_show = 1 ORDER BY id asc',array(':uniacid'=>$_W['uniacid']));
		$dataNum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('research_question').' WHERE uniacid = :uniacid and is_show = 1 ',array(':uniacid'=>$_W['uniacid']));
		foreach($dataList as &$value){
			$value['key'] = unserialize($value['key']);
		}
		include $this->template('enter');   
	}

	//南通问卷提交的数据
	public function	doMobilePostdata(){
		global $_W,$_GPC;
		$dataNum = $_GPC['dataNum'];
		for ($i = 1; $i <= $dataNum; $i++){
			//echo $i.'---'. $_GPC['question_'.$i].'<br/>';
			$question = $_GPC['question_'.$i];
			//判断不能有空选项
			if ($question == ''){
				echo 500;exit;
			}
		}
		for ($i = 1; $i <= $dataNum; $i++){
			if (is_array($_GPC['question_'.$i])){
				$_GPC['question_'.$i] = implode(',',$_GPC['question_'.$i]);
			}
			$data = pdo_insert('research_result',array('uniacid'=>$_W['uniacid'],'userid'=>$_W['fans']['uid'],'t_id'=>$i,'key'=>$_GPC['question_'.$i],'time'=>time()));
		}

		if(!empty($_GPC['comment'])){
			$data = pdo_insert('research_opinion',array('uniacid'=>$_W['uniacid'],'userid'=>$_W['fans']['uid'],'opinion'=>$_GPC['comment'],'time'=>time()));
		}
		if ($data){
			echo 200;exit;
		}
	}

	//通州资料提交
	public function	doMobilePosttong(){
		global $_W,$_GPC;
		$dataNum = $_GPC['dataNum'];
		for ($i = 1; $i <= $dataNum; $i++){
			//echo $i.'---'. $_GPC['question_'.$i].'<br/>';
			$question = $_GPC['question_'.$i];
			//判断不能有空选项
			if ($question == ''){
				echo 500;exit;
			}
		}
		$dataNum = $_GPC['dataNum'];
		for ($i = 1; $i <= $dataNum; $i++){
			//echo $i.'---'. $_GPC['question_'.$i].'<br/>';
			if (is_array($_GPC['question_'.$i])){
				$_GPC['question_'.$i] = implode(',',$_GPC['question_'.$i]);
			}
			$data = pdo_insert('tongzhou_discussion',array('uniacid'=>$_W['uniacid'],'userid'=>$_W['fans']['uid'],'t_id'=>$i,'key'=>$_GPC['question_'.$i],'time'=>time()));
		}
		if ($data){
			echo 200;exit;
		}
	}

	//通州首页
	public function doMobileTongzhou(){
		global $_W,$_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'page1';

		$dataList = pdo_fetchall('SELECT * FROM '.tablename('tongzhou_question').' WHERE uniacid = :uniacid and is_show = 1 ORDER BY id asc',array(':uniacid'=>$_W['uniacid']));
		$dataNum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tongzhou_question').' WHERE uniacid = :uniacid and is_show = 1 ',array(':uniacid'=>$_W['uniacid']));
		foreach($dataList as &$value){
			$value['key'] = unserialize($value['key']);
		}

		include $this->template('tongzhou');
	}
	//接收通州表扬信息
	public function doMobilePrpost(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['pr_name'] = $_GPC['pr_name'];
		$data['pr_num'] = $_GPC['pr_num'];
		$data['pr_car_num'] = $_GPC['pr_car_num'];
		$data['pr_next_unit'] = $_GPC['pr_next_unit'];
		$data['pr_to_name'] = $_GPC['pr_to_name'];
		$data['pr_to_num'] = $_GPC['pr_to_num'];
		$data['pr_comment'] = $_GPC['pr_comment'];
		$data['pr_time'] = time();

		$prdata = pdo_insert('tongzhou_praise',$data);
		if ($prdata){
			echo 200;
		}
	}
	//接收通州投诉信息
	public function doMobileCopost(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['co_type'] = $_GPC['co_type'];
		$data['co_name'] = $_GPC['co_name'];
		$data['co_num'] = $_GPC['co_num'];
		$data['co_car_num'] = $_GPC['co_car_num'];
		$data['co_next_unit'] = $_GPC['co_next_unit'];
		$data['co_to_name'] = $_GPC['co_to_name'];
		$data['co_to_num'] = $_GPC['co_to_num'];
		$data['co_comment'] = $_GPC['co_comment'];
		$data['co_time'] = time();
		$codata = pdo_insert('tongzhou_complain',$data);
		if ($codata){
			echo 200;
		}
	}








	//后台

	//南通问卷后台提交问题
	public function doWebPostadd(){
		global $_W,$_GPC;
		$data['id'] = $_GPC['id'];
		$data['uniacid'] = $_W['uniacid'];
		$data['type'] = $_GPC['type'];
		$data['address'] = $_GPC['address'];
		$data['title'] = $_GPC['title'];
		$data['key'] = serialize($_GPC['xuan']);
		$data = pdo_insert('research_question',$data);
        if ($data){
            echo json_encode(array('stat'=>200));exit;
        }

	}
	//南通问卷后台修改
	public function doWebPostup(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		//$data['type'] = $_GPC['type'];
		$data['address'] = $_GPC['address'];
		$data['title'] = $_GPC['title'];
		$data['key'] = serialize($_GPC['xuan']);
		$data = pdo_update('research_question',$data,array('id'=>$id,'uniacid'=>$_W['uniacid']));
		if ($data){
			echo 200;exit;
		}
	}
	//南通问卷管理
	public function doWebSit(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('research_question').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('research_question').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		/*foreach ($list as &$v){
			$v['key'] = unserialize($v['key']);
		}*/
		/*echo '<pre>';
		var_dump($list);
		echo '<pre>';*/
		//修改显示
		if ($op == 'is_show'){
			$id = $_GPC['id'];
			$is_show = $_GPC['is_show'];
			$data = pdo_update('research_question',array('is_show'=>$is_show),array('uniacid'=>$_W['uniacid'],'id'=>$id));
			if ($data){
				echo 200;exit;
			}
		}
		//删除数据
		if ($op == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('research_question',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		//编辑数据
		if ($op == 'compile'){
			$id = $_GPC['id'];
			$list = pdo_fetchall('SELECT * FROM '.tablename('research_question').' WHERE uniacid = '.$_W['uniacid'].' and id = '.$id);
			foreach($list as &$value){
					$value['key'] = unserialize($value['key']);
			}
			//var_dump($list);
		}

		include $this->template('sit');
	}

	//南通问卷调查
	public function doWebAdmin(){
		global $_W,$_GPC;
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('research_result').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('research_result').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		//var_dump($v);
		//删除数据
		if ($_GPC['op'] == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('research_result',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('admin');
	}

	//南通问卷意见
	public function doWebOpinion(){
		global $_W,$_GPC;
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('research_opinion').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('research_opinion').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);

		//删除数据
		if ($_GPC['op'] == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('research_opinion',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('opinion');
	}

	//通州表扬管理
	public function doWebPraise(){
		global $_W,$_GPC;
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tongzhou_praise').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('tongzhou_praise').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);


		//删除数据
		if ($_GPC['op'] == 'pr_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('tongzhou_praise',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('praise');
	}

	//通州投诉调查
	public function doWebComplain(){
		global $_W,$_GPC;
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tongzhou_complain').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('tongzhou_complain').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);

		//删除数据
		if ($_GPC['op'] == 'co_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('tongzhou_complain',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}

		include $this->template('complain');
	}

	//通州资料
	public function doWebDiscussion(){
		global $_W,$_GPC;
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tongzhou_discussion').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('tongzhou_discussion').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));


		$pager =pagination($total, $pindex, $psize);

		//删除数据
		if ($_GPC['op'] == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('tongzhou_discussion',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}

		include $this->template('discussion');
	}

	//通州评议管理
	public function doWebDiscsit(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('tongzhou_question').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('tongzhou_question').' WHERE uniacid = :uniacid  ORDER BY id asc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);

		//修改显示
		if ($op == 'is_show'){
			$id = $_GPC['id'];
			$is_show = $_GPC['is_show'];
			$data = pdo_update('tongzhou_question',array('is_show'=>$is_show),array('uniacid'=>$_W['uniacid'],'id'=>$id));
			if ($data){
				echo 200;exit;
			}
		}

		//删除数据
		if ($op == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('tongzhou_question',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}

		include $this->template('discsit');
	}
	//通州资料后台提交问题
	public function doWebPosttz(){
		global $_W,$_GPC;
		$data['id'] = $_GPC['id'];
		$data['uniacid'] = $_W['uniacid'];
		$data['type'] = $_GPC['type'];
		$data['title'] = $_GPC['title'];
		$data['key'] = serialize($_GPC['xuan']);
		$data = pdo_insert('tongzhou_question',$data);
		if ($data){
			echo 200;
		}

	}
}
?> 
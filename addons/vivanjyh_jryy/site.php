<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', '/addons/vivanjyh_jryy/template/resource/');
class Vivanjyh_jryyModuleSite extends WeModuleSite
{

	//借权获取粉丝信息
	protected $profile;
	//借权获取用户信息（uid）
	protected $member;
	public function __construct()
	{
		global $_W, $_GPC;
		if (!defined('IN_SYS')) {
			load()->func('addons');
			$this->profile = getFansInfo($_W['openid']);
			$this->member = getMember($_W['openid']);
		}
	}
	//$this->member['uid'];

	//入口
	public function doMobileBank(){
		global $_W,$_GPC;
		load()->func('tpl');
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$id = $_GPC['id'];
		$data = pdo_fetch('SELECT * FROM '.tablename('viva_jryy').' WHERE uniacid = :uniacid AND status = :status AND id =:id',array(':uniacid'=>$_W['uniacid'],':status'=>1,':id'=>$id));
		if ($data['status'] == 1){$dataone = $data;}
		$title = $_W['current_module']['config']['title'];
		$footer = $_W['current_module']['config']['footer'];
		$logoimg = $_W['attachurl'].$_W['current_module']['config']['logoimg'];
		$bgimg = $_W['attachurl'].$_W['current_module']['config']['bgimg'];
		$zhihang = $_W['current_module']['config']['zhihang'];
		$zhihang = str_replace('，', ',', $zhihang); //替换全角空格为半角
		$zhihang = explode(",",$zhihang);
		include $this->template('bank');
	}
	/*public function doMobileSms() {
		load()->func('communication');
		$smsUri = "https://sms.yunpian.com/v2/sms/batch_send.json";
		$postarray = array(
			"apikey"=>"687ede36e02cfc2e44c8e636ee8c22a3",
			"mobile"=>"17312230681",
			//"mobile"=>"18862801582,13771471058",
			"text"=>"【维瓦互动】您收到一条来自“南京银行文章1”的预约信息，姓名:张三电话:13813131333留言:呵呵哈哈，请尽快回复处理回T退订"
		);
		$sr = ihttp_post($smsUri,$postarray);
		var_dump($sr);
	}*/
	//提交留言
	public function doMobileMessagepost(){
		global $_W,$_GPC;
		load()->func('communication');
		load()->model('utility');
		$_W['current_module']['config']['jifen'] = $_W['current_module']['config']['jifen'] ? $_W['current_module']['config']['jifen'] : 25;
		//$data['uid'] = 2;
		$data['uid'] = $this->member['uid'];
		if(!$data['uid']){
			echo 300;exit;
		}
		$data['uniacid'] = trim($_W['uniacid']);
		$data['name'] = trim($_GPC['name']);
		$data['phone'] = trim($_GPC['phone']);
		$data['content'] = trim($_GPC['content']);
		$data['article'] = trim($_GPC['title']);
		$data['zhihang'] = trim($_GPC['zhihang']);
		$data['time'] = time();
//		var_dump(code_verify($_W['uniacid'], trim($_GPC['phone']), trim($_GPC['code'])));exit;
		if (!code_verify($_W['uniacid'], trim($_GPC['phone']), trim($_GPC['code'])) && trim($_GPC['code'])) {
			echo 100;exit;
		}

		$res = pdo_insert('viva_jryy_message',$data);
		if($res){
			$reditData = pdo_fetchall('SELECT * FROM '.tablename('mc_credits_record').' WHERE remark = :remark AND uid = :uid',array(':uid'=>$this->member['uid'],':remark'=>'南京银行北京分行预约成功增加积分'. $data['phone']));
			if(!$reditData){
				mc_credit_update($this->member['uid'], 'credit1', $_W['current_module']['config']['jifen'], array($this->member['uid'], '南京银行北京分行预约成功增加积分' . $data['phone']));
			}
			echo 200;
			$smsUri = "https://sms.yunpian.com/v2/sms/batch_send.json";
			$postarray = array(
				"apikey"=>"687ede36e02cfc2e44c8e636ee8c22a	3",
				"mobile" => $_W['current_module']['config']['mobile'],
				"text"=>"【维瓦互动】您收到一条来自“".$_GPC['title']."”的预约信息，姓名:".$data['name']."电话:".$data['phone']."留言:".$data['content']."，请尽快回复处理回T退订"
			);
			$sr = ihttp_post($smsUri,$postarray);
			//exit(json_encode($sr));
		}
	}

	//验证码
	public function doMobileCode(){
		global $_W,$_GPC;
		load()->func('communication');
		$phone = trim($_GPC['phone']);
		$code = '';
		for ($i=0;$i<4;$i++){
			$code .= mt_rand(0, 9);
		}
		$smsUri = "https://sms.yunpian.com/v2/sms/batch_send.json";
		$postarray = array(
			"apikey"=>"2cdb5bfbe05e7eff45fb33e74c8a7e74",
			"mobile" => $phone,
			"text" =>"【维瓦互动】您好，本次确认码为 " . $code
		);
		$sr = ihttp_post($smsUri,$postarray);
		$sr['content'] = json_decode($sr['content']);
		if ($sr['content']->data[0]->msg == '发送成功') {
			$data = array(
				'uniacid' => trim($_W['uniacid']),
				'receiver' => trim($_GPC['phone']),
				'verifycode' => trim($code),
				'createtime' => time()
			);
			pdo_insert('uni_verifycode', $data);
			exit(json_encode(array('status' => 1, 'info' => '发送成功')));
		} else {
			exit(json_encode(array('status' => 0, 'info' => '发送失败')));
		}
	}

	//文章后台管理
	public function doWebManage(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('viva_jryy').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('viva_jryy').' WHERE uniacid = :uniacid  ORDER BY id DESC LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		//编辑
		if($op == 'view'){
			$dataone = pdo_fetch('SELECT * FROM '.tablename('viva_jryy').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
		}
		$url_show = murl('entry', array('m' => 'vivanjyh_jryy', 'do' => 'bank','id'=>1), true, true);
		include $this->template('manage');
	}

	//文章添加
	public function doWebPostadd(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['title'] = trim($_GPC['title']);
		$data['author'] = trim($_GPC['author']);
		$data['content'] = trim($_GPC['content']);
		$data['time'] = time();
		$data['sharetitle'] = trim($_GPC['sharetitle']);
		$data['sharedes'] = trim($_GPC['sharedes']);
		$data['shareimg'] = trim($_GPC['shareimg']);
		$data['shareurl'] = trim($_GPC['shareurl']);
		$data['status'] = 0;
		$data = pdo_insert('viva_jryy',$data);
		if($data){
			echo 200;exit;
		}

	}
	//文章修改
	public function doWebReview(){
		global $_W,$_GPC;
		global $_W,$_GPC;
		$id = trim($_GPC['id']);
		$data['uniacid'] = $_W['uniacid'];
		$data['title'] = trim($_GPC['title']);
		$data['author'] = trim($_GPC['author']);
		$data['content'] = trim($_GPC['content']);
		$data['time'] = time();
		$data['sharetitle'] = trim($_GPC['sharetitle']);
		$data['sharedes'] = trim($_GPC['sharedes']);
		$data['shareimg'] = trim($_GPC['shareimg']);
		$data['shareurl'] = trim($_GPC['shareurl']);
		$data = pdo_update('viva_jryy',$data,array('id'=>$id));
		if($data){
			echo 200;exit;
		}
	}

	//留言列表
	public function doWebAdmin(){
		global $_W,$_GPC;
		//$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		load()->func('communication');
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('viva_jryy_message').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('viva_jryy_message').' WHERE uniacid = :uniacid  ORDER BY id DESC LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		foreach ($list as $k => $v){
			$url = "https://www.baifubao.com/callback?cmd=1059&callback=phone&phone=" . $v['phone'];
			$info=ihttp_post($url);
			$json = json_decode(str_replace('/*fgg_again*/phone(', '', substr($info['content'], 0, strlen($info['content']) - 1 )));
			$list[$k]['isp'] = $json->data->area_operator;
//			echo '<pre>';
//			print_r(json_decode(str_replace('/*fgg_again*/phone(', '', substr($info['content'], 0, strlen($info['content']) - 1 ))));
		}

		/*if($op == 'view'){
			$id = $_GPC['id'];
			$dataone = pdo_fetch('SELECT * FROM '.tablename('viva_jryy_message').' WHERE uniacid = :uniacid AND id = :id',array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
		}*/

		include $this->template('admin');
	}
	//删除
	public function doWebGuidedelete(){
		global $_W,$_GPC;
		$op = trim($_GPC['op']);
		if($op == 'manage'){
			$id = $_GPC['id'];
			$gd = pdo_delete('viva_jryy',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($gd){
				echo 200;exit;
			}
		}
		if($op == 'admin'){
			$id = $_GPC['id'];
			$gd = pdo_delete('viva_jryy_message',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($gd){
				echo 200;exit;
			}
		}

	}
	//显示隐藏
	public function doWebStatus(){
		global $_W,$_GPC;
		$id =intval($_GPC['id']);
		$display =intval($_GPC['display']);
		$state =pdo_update('viva_jryy', array('status' => $display), array('uniacid' => $_W['uniacid'], 'id' => $id));
		if($state !== false){exit(json_encode('success'));}exit(json_encode("error"));
	}

	/**
	 * 数据导出 Excel 文件
	 */
	public function doWebExport() {
		include IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
		$list = pdo_fetchall('SELECT * FROM ' . tablename('viva_jryy_message'));
		foreach ($list as $k => $v) {
			$data[$k]['id'] = $v['id'];
			$data[$k]['name'] = $v['name'];
			$data[$k]['phone'] = $v['phone'];
			$data[$k]['content'] = $v['content'];
			$data[$k]['article'] = $v['article'];
			$data[$k]['zhihang'] = $v['zhihang'];
			$data[$k]['time'] = date('Y-m-d',$v['time']);
		}
		$excel = new PHPExcel();
		$letter = array('A', 'B', 'C', 'D','E','F','G');
		$tableheader = array('序号', '姓名', '手机号码', '留言','预约文章','支行','时间');
		for($i = 0; $i < count($tableheader); $i++) {
			$excel->getActiveSheet()->setCellValue($letter[$i] . '1', $tableheader[$i]);
		}
		for ($i = 0; $i < count($data); $i++) {
			$j = 0;
			foreach ($data[$i] as $k => $v) {
				$excel->getActiveSheet()->setCellValue($letter[$j] . ($i + 2), $v);
				$j++;
			}
		}
		$write = new PHPExcel_Writer_Excel5($excel);
		ob_end_clean();//清除缓冲区,避免乱码
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . date('Y-m-d',time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$write->save('php://output');
	}

}
?>




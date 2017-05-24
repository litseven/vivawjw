<?php
/**
 * 南京银行南京分行个人中心模块微站定义
 *
 * @author 
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/addons/viva_njyh_njfh_ucenter/resource/');

class Viva_njyh_njfh_ucenterModuleSite extends WeModuleSite {

	public $member;
	public $profile;

	public function __construct() {
		global $_W, $_GPC;
		load()->func('addons');
		$this->profile = getFansInfo($_W['openid']);
		$this->member = getMember($_W['openid'], '');
	}

	public function doMobileEntry() {
		//这个操作被定义用来呈现 功能封面
		global $_W, $_GPC;
		load()->model('mc');
        load()->func('tpl');
//		if (empty($this->profile['nickname'])) {
//		}
		if (!$_W['fans']['openid']) {
			exit('Access Denied');
		}
			mc_oauth_userinfo();
//		$info = mc_fetch($this->member['uid'], array('gender', 'age', 'occupation', 'resideprovince', 'residecity', 'residedist', 'mobile', 'address', 'interest'));
        $info = pdo_fetch('SELECT gender, age, occupation, resideprovince, residecity, residedist, mobile, address FROM ' . tablename('mc_members') . ' WHERE uid=:uid', array(':uid' => $this->member['uid']));
		$info['age'] = pdo_fetchcolumn('SELECT age FROM ' . tablename('mc_members') . ' WHERE uid=:uid', array(':uid' => $this->member['uid']));
		foreach ($info as $k => $v) {
			if ($v) {
				$arr[$k] = $v;
			}
		}
		unset($arr['uid']);
		$percent = ceil(count($arr) / 8 * 100);
		include $this->template('ucenter');
	}

	public function doMobileInfo() {
		global $_W, $_GPC;
		$return['status'] = 0;
		if (!$this->member) {
			$return['info'] = 'Access Denied';
			exit(json_encode());
		}
		if (empty($this->profile['nickname'])) {
			$return['info'] = 'Access Denied';
			exit(json_encode($return));
		}
		load()->model('utility');
		if (!code_verify($_W['uniacid'], trim($_GPC['data']['mobile']), trim($_GPC['verifycode'])) && trim($_GPC['verifycode'])) {
			$return['info'] = '验证码错误';
			exit(json_encode($return));
		}
		// load()->model('mc');
		// $res = mc_update($this->member['uid'], $_GPC['data']);

		$res = pdo_update('mc_members', $_GPC['data'], array('uid' => $this->member['uid']));
		if ($res) {
			$return['status'] = 1;
		} else {
			$return['info'] = '登录失败！';
		}
		exit(json_encode($return));
	}

	/**
	 * 发送验证码
	 */
	public function doMobileSms() {
		global $_W, $_GPC;
		load()->func('communication');
        for ($i = 0; $i < 6; $i++) { 
        	$code .= mt_rand(0, 9);
        }
		$smsUri = "https://sms.yunpian.com/v2/sms/batch_send.json";
		$postarray = array(
			"apikey" => "2cdb5bfbe05e7eff45fb33e74c8a7e74",
			"mobile" => $_GPC['phone'],
			"text" => "【悦读有礼】您好，本次确认码为 " . $code
			);
		$sr = ihttp_post($smsUri, $postarray);
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
}
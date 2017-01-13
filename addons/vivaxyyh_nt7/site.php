<?php
/**
 * 兴业银行十六周年模块微站定义
 *
 * @author 张意飞
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/addons/vivaxyyh_nt7/resource/');
class Vivaxyyh_nt7ModuleSite extends WeModuleSite {

	public $was_message;
	public $info;
	public function __construct() {
		global $_W, $_GPC;
		$this->was_message = pdo_fetch('SELECT * FROM ' . tablename('xy_message') . ' WHERE openid=:openid', array(':openid' => $_W['member']['uid']));
		$this->info = pdo_fetch('SELECT * FROM ' . tablename('xy_bank') . ' WHERE id=:id', array(':id' => $this->was_message['staffid']));
		$this->info['days'] = floor((time() - strtotime($this->info['entrytime'])) / 86400);
		$years = date('Y-m-d', time()) - $this->info['entrytime'];
		if ($years < 3) {
			$this->info['level'] = '新手级';
		} elseif ($years >= 3 && $years < 10) {
			$this->info['level'] = '达人级';
		} elseif ($years >= 10 && $years < 15) {
			$this->info['level'] = '资深级';
		} else {
			$this->info['level'] = '元老级';
		}
	}

	public function doMobileSql() {
		//$message = pdo_query('TRUNCATE ims_xy_message');
		//$hit = pdo_query('TRUNCATE ims_xy_hit');
		//pdo_insert('xy_bank', array('number' => '9568998', 'name' => '宋苏婷', 'entrytime' => '2005-05-05'));
		//pdo_insert('xy_bank', array('number' => '9568997', 'name' => '宋苏婷', 'entrytime' => '1992-05-05'));
		//pdo_insert('xy_bank', array('number' => '9568999', 'name' => '宋苏婷', 'entrytime' => '2015-05-05'));
		pdo_update('xy_bank', array('name' => '测试一'), array('number' => '9568997'));
		pdo_update('xy_bank', array('name' => '测试二'), array('number' => '9568998'));
		pdo_update('xy_bank', array('name' => '测试三'), array('number' => '9568999'));
		//$res = pdo_fetchall('SELECT * FROM ' . tablename('xy_message'));
// 		var_dump($message . ' ' . $hit);
	}

	public function doMobileEntry() {
		// 这个操作被定义用来呈现 功能封面
		global $_W, $_GPC;
		
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		
		$was_message = pdo_fetch('SELECT * FROM ' . tablename('xy_message') . ' WHERE openid=:openid', array(':openid' => $_W['member']['uid']));
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		// if ($was_message && empty($_GPC['page']) && $op == 'index') {
		// 	header('Location:' . $this->createMobileUrl('image', array('op' => 'share', 'id' => $was_message['staffid'])));
		// }
		if ($op == 'check') {
			$res = pdo_fetch('SELECT * FROM ' . tablename('xy_bank') . ' WHERE number=:number', array(':number' => trim($_GPC['number'])));
			if ($res) {
				exit(json_encode(array('status' => 1, 'id' => $res['id'])));
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}
		include $this->template('enter');
	}

	/**
	 * 生成图片
	 */
	public function doMobileImage() {
		global $_W, $_GPC;
		
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		load()->func('tpl');
		$id = intval($_GPC['id']);
		$info = pdo_fetch('SELECT * FROM ' . tablename('xy_bank') . ' WHERE id=:id', array('id' => $id));
		$days = floor((time() - strtotime($info['entrytime'])) / 86400);
		if ($op == 'submit') {
			$data = array(
				'uniacid' => $_W['uniacid'],
				'openid' => $_W['member']['uid'],
				'staffid' => $id,
				'avatar' => trim($_GPC['avatar']),
				'content' => trim($_GPC['content']),
				'time' => time()
				);
			$was_message = pdo_fetch('SELECT * FROM ' . tablename('xy_message') . ' WHERE staffid=:id', array(':id' => $id));
			if (!$was_message) {
				$res = pdo_insert('xy_message', $data);
				if ($res) {
					exit(json_encode(array('status' => 1)));
				} else {
					exit(json_encode(array('status' => 0)));
				}
			} else {
				$update = array(
					'avatar' => $data['avatar'],
					'content' => $data['content'],
					'time' => time()
					);
				$res = pdo_update('xy_message', $update, array('staffid' => $info['id']));
				if ($res) {
					exit(json_encode(array('status' => 1)));
				} else {
					exit(json_encode(array('status' => 0)));
				}
			}
		}
		if (in_array($op, array('choose', 'share', 'show'))) {
			$info['message'] = pdo_fetch('SELECT * FROM ' . tablename('xy_message') . ' WHERE staffid=:staffid', array(':staffid' => $id));
			$years = date('Y-m-d', time()) - $info['entrytime'];
			$was_hit = pdo_fetch('SELECT * FROM ' . tablename('xy_hit') . ' WHERE uniacid = :uniacid AND fromuid = :fromuid AND msgid = :msgid', array(':uniacid' => $_W['uniacid'], ':fromuid' => $_W['member']['uid'], ':msgid' => $info['message']['id']));
			if ($years < 3) {
				$level = 'new';
			} elseif ($years >= 3 && $years < 10) {
				$level = 'master';
			} elseif ($years >= 10 && $years < 15) {
				$level = 'senior';
			} else {
				$level = 'elder';
			}
		}
		if ($op == 'addtemp') {
			$lasttemp = pdo_fetchcolumn('SELECT temp FROM ' . tablename('xy_message') . ' WHERE staffid=:staffid', array(':staffid' => $id));
			if ($lasttemp == intval($_GPC['temp'])) {
				exit(json_encode(array('status' => 1)));
			}
			$res = pdo_update('xy_message', array('temp' => intval($_GPC['temp'])), array('staffid' => $id));
			if ($res) {
				exit(json_encode(array('status' => 1)));
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}
		include $this->template('image');
	}

	/**
	 * 点赞
	 */
	public function doMobileHit() {
		global $_W, $_GPC;
		$msgid = pdo_fetchcolumn('SELECT id FROM ' . tablename('xy_message') . ' WHERE staffid=:staffid', array(':staffid' => intval($_GPC['id'])));
		$data = array(
			'uniacid' => $_W['uniacid'],
			'fromuid' => $_W['member']['uid'],
			'msgid' => $msgid,
			'time' => time()
			);
		$was_hit = pdo_fetch('SELECT * FROM ' . tablename('xy_hit') . ' WHERE uniacid = :uniacid AND fromuid = :fromuid AND msgid = :msgid', array(':uniacid' => $data['uniacid'], ':fromuid' => $data['fromuid'], ':msgid' => $data['msgid']));
		if (!$was_hit && $_W['member']['uid']) {
			pdo_update('xy_message', 'hit=hit+1', array('id' => $data['msgid'], 'uniacid' => $_W['uniacid']));
			pdo_insert('xy_hit', $data);
			exit(json_encode(array('status' => 1)));
		} else {
			exit(json_encode(array('status' => 0)));
		}
	}

	/**
	 * 显示图片
	 */
	public function doMobileShow() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		$info = pdo_fetch('SELECT * FROM ' . tablename('xy_bank') . ' WHERE id=:id', array(':id' => $id));
		$info['message'] = pdo_fetch('SELECT * FROM ' . tablename('xy_message') . ' WHERE staffid=:id', array('id' => $id));
		$days = floor((time() - strtotime($info['entrytime'])) / 86400);
		$years = date('Y-m-d', time()) - $info['entrytime'];
		if ($years < 3) {
			$level = 'new';
		} elseif ($years >= 3 && $years < 10) {
			$level = 'master';
		} elseif ($years >= 10 && $years < 15) {
			$level = 'senior';
		} else {
			$level = 'elder';
		}
		$bg_path = dirname(__FILE__) . '/resource/template/bg-' . intval($info['message']['temp']) . '.jpg';
		$level_path = dirname(__FILE__) . '/resource/template/' . $level . '.png';
		$url = $_W['siteroot'] . 'index.php?i=2&c=entry&op=share&id=' . $id . '&do=image&m=viva_xyyh';
		$info['message']['content'] = str_replace("\n", ' ', $info['message']['content']);
		$this->createImg($bg_path, $info['message']['avatar'], $level_path, $url, $days, $info['name'], $info['message']['content']);
	}

	/**
	 * 生成图片
	 * @param  String $bg_path    模板图片地址
	 * @param  String $head_path  头像地址
	 * @param  String $level_path 级别对应的图片地址
	 * @param  String $qrcode_url 生成二维码的地址
	 * @param  int    $days       天数
	 * @param  String $name       姓名
	 * @param  String $content 	  留言内容
	 * @return image              返回图片
	 */
	public function createImg($bg_path, $head_path, $level_path, $qrcode_url, $days, $name, $content) {
		load()->func('communication');
		// $token = WeAccount::token(WeAccount::TYPE_WEIXIN);
		// $url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token={$token}";
		// $send = array();
		// $send['action'] = 'long2short';
		// $send['long_url'] = $qrcode_url;
		// $response = ihttp_request($url, json_encode($send));
		// $result = @json_decode($response['content'], true);
		// $shorturl = $result['short_url'];
		$shorturl = urlencode($qrcode_url);
		$qrcode_path = 'http://pan.baidu.com/share/qrcode?w=143&h=143&url=' . $shorturl;
		$src_path = dirname(__FILE__) . '/resource/template/content.png';
		$fontttf = dirname(__FILE__) . '/resource/template/SourceHanSansCN-Bold.ttf';
		$contentttf = dirname(__FILE__) . '/resource/template/SourceHanSansCN-Regular.ttf';
		// 创建背景图
		$bg = imagecreatefromjpeg($bg_path);
		$src = imagecreatefrompng($src_path);
		// 头像
		list($w, $h) = getimagesize($head_path);
        $headsrc = imagecreatefromstring(file_get_contents($head_path));  
        $head = imagecreatetruecolor($w, $h);  
        imagealphablending($head, false);  
        $transparent = imagecolorallocatealpha($head, 0, 0, 0, 127);  
        $r = $w / 2;
        for($x = 0; $x < $w; $x++)
            for($y = 0; $y < $h; $y++){  
                $c = imagecolorat($headsrc,$x,$y);  
                $_x = $x - $w / 2;  
                $_y = $y - $h / 2;
                if((($_x * $_x) + ($_y * $_y)) < ($r * $r)){  
                    imagesetpixel($head, $x, $y, $c);  
                }else{
                    imagesetpixel($head, $x, $y, $transparent);  
                }
            }  
        imagesavealpha($head, true);
		// 二维码
		$qrcode = imagecreatefrompng($qrcode_path);
		// 追梦人级别
		$level = imagecreatefrompng($level_path);
		list($src_w, $src_h) = getimagesize($src_path);
		// 生成图片
		imagecopy($bg, $src, 0, 0, 0, 0, $src_w, $src_h);
		imagecopyresampled($bg, $head, 200, 96, 0, 0, 180, 180, $w, $h);
		imagecopy($bg, $qrcode, 226, 673, 8, 9, 126, 126);
		imagecopy($bg, $level, (($src_w - 208) / 2), 525, 0, 0, 208, 130);
		// 字体颜色
		$color = imagecolorallocate($bg, 2, 66, 154);
		// 追梦人名称
		imagettftext($bg, 36, 0, $this->center($src_w, 36, $fontttf, $name), 410, $color, $fontttf, $name);
		// 天数
		imagettftext($bg, 34, 0, (340 + $this->center(136, 34, $fontttf, $days)), 455, $color, $fontttf, $days);
		// 留言内容
		if (mb_strlen($content) < 66) {
			imagettftext($bg, 18, 0, $this->center($src_w, 18, $contentttf, $content), 320, $color, $contentttf, $content);
		} else {
			$content1 = mb_substr($content, 0, 22, 'utf-8');
			$content2 = mb_substr($content, 22, 10, 'utf-8');
			imagettftext($bg, 18, 0, $this->center($src_w, 18, $contentttf, $content1), 320, $color, $contentttf, $content1);
			imagettftext($bg, 18, 0, $this->center($src_w, 18, $contentttf, $content2), 350, $color, $contentttf, $content2);
		}
		imagesavealpha($bg , true);
		header("content-type: image/png");
		imagejpeg($bg);
		imagedestroy($bg);

		// 输出图像，png 格式
		header('Cache-Control: private, max-age=0, no-store, no-cache, must-revalidate');
	    header('Cache-Control: post-check=0, pre-check=0', false);		
	    header('Pragma: no-cache');
	    header("content-type: image/png");
		imagepng($img);
		imagedestroy($img);
	}

	/**
	 * 文字居中
	 * @param  int $boxsize 文字所处的盒子大小
	 * @param  int $fontsize 字体大小
	 * @param  String $fontttf  字体
	 * @param  String $word     要居中的文字
	 * @return int           返回文字居中左边margin
	 */
	public function center($boxsize, $fontsize, $fontttf, $word) {
		$tbox = imagettfbbox($fontsize, 0, $fontttf, $word);
		$w = ($tbox[2] - $tbox[0]);
		$margin = ($boxsize - $w) / 2;
		return $margin;
	}

	public function doWebEntry() {
		global $_W, $_GPC;
		$op = trim($_GPC['op']) ? trim($_GPC['op']) : 'index';
		$pindex = max(1, intval($_GPC['page']));
		$psize = '15';
		$total = pdo_fetchcolumn('SELECT * FROM ' . tablename('xy_message') . ' ORDER BY time DESC');
		$list = pdo_fetchall('SELECT * FROM ' . tablename('xy_message') . ' ORDER BY time DESC LIMIT ' . ($pindex - 1) * $psize . ', ' . $psize);
		foreach ($list as $k => $v) {
			$list[$k]['info'] = pdo_fetch('SELECT * FROM ' . tablename('xy_bank') . ' WHERE id=:id', array(':id' => $v['staffid']));
		}
		$page = pagination($total, $pindex, $psize);
		if ($op == 'del') {
			$id = intval($_GPC['id']);
			$res = pdo_delete('xy_message', array('id' => $id));
			if ($res) {
				pdo_delete('xy_hit', array('msgid' => $id));
				exit(json_encode(array('status' => 1)));
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}
		include $this->template('list');
	}

}
<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_grzx/template/resource/');
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
		//$uid = $data['uid'];
		if ($op == 'list'){
			//所有推送的消息
			$warnlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_warn').' ORDER BY state asc');
			//不接收推送的消息存入库
			$warnstatus = pdo_fetchall('SELECT title_key FROM '.tablename('vivawjw_user_warn_status').' WHERE uid = :uid',array(':uid'=>$uid));
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
		//车辆类型
		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');

		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $data['uid'];
		//1.判断新表有无数据
		$bdcarnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$_W['member']['uid'],':bound'=>1,':uniacid'=>$_W['uniacid']));
		//如果没有绑定显示绑定页，如果有两条绑定显示成功页
		if($bdcarnum){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_car';
		}else{
			/*$r = new Redis();
			$v = $r->connect('r-bp1fd9985c4f7234.redis.rds.aliyuncs.com',6379);
			$res = $r->auth('0qEFuasxAaqbT1c3');
			$oldUser = $r->get("Users_".$_W['openid']);
			$oldUser = json_decode($oldUser,true);*/
			$openId = $_W['openid'];
			$oldData = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_old_users').' WHERE FOpenId=:FOpenId AND FStatus=:FStatus AND FAttentionType=:FAttentionType',array(':FOpenId'=>$openId,':FStatus'=>1,':FAttentionType'=>122));
			//如果有绑定数据导入新表
			if($oldData){
				foreach ($oldData as $value){
					//老数据0为本地1为外地
					if($value['FIsLocal'] == 1){
						if($value['FIsApply'] == 2){
							$new['status'] = 0;//绑定成功
						}elseif($value['FIsApply'] == -1){
							$new['status'] = 1;//绑定失败
						}elseif($value['FIsApply'] == 1 || $value['FIsApply'] == 0){
							$new['status'] = 2;//申请中
						}
						$new['uid'] = $_W['member']['uid'];
						$new['uniacid'] = $_W['uniacid'];
						$new['distinction'] = 0;//0为外地
						//$new['status'] = 0;//绑定状态
						$new['bound'] = 1;//绑定
						$new['time'] = strtotime($value['FUpdateTime']);
						$new['wd_type'] =  $value['FCarType'];//类型
						$new['wd_car_num'] = $value['FCarNumber'];//车牌
						$new['wd_car_engine'] = $value['FEngine'];//发动机号
						$new['issel'] = 1;
						pdo_insert('vivawjw_user_bound_car',$new);

					}else{
						$new['uid'] = $_W['member']['uid'];
						$new['uniacid'] = $_W['uniacid'];
						$new['distinction'] = 1;//新数据1为无锡
						$new['status'] = 0;//绑定状态
						$new['bound'] = 1;//绑定成功
						$new['time'] = strtotime($value['FUpdateTime']);
						$new['wx_type'] =  $value['FCarType'];//类型
						$new['wx_car_num'] = $value['FCarNumber'];//车牌
						$new['wx_car_engine'] = $value['FEngine'];//发动机号
						$new['issel'] = 1;
						pdo_insert('vivawjw_user_bound_car',$new);
					}
				}
			}else{
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
			}
		}
		if($op == 'success_car'){
			/*--------有效期和强制报废期------------*/
			/*foreach ($typecar as $key => $value){
				$data = $this->wxwfapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$value['wx_type'],$value['wx_car_num'],$value['wx_car_engine']);
				$data = $this->object2array($data);
				$value['YXQZ'] = $data['YXQZ'];
				$value['QZBFQZ'] = $data['QZBFQZ'];
				$value['ZT'] = $data['ZT'];
			}*/

			//绑定车辆的数量；一个微信号最多绑定两辆车
			$bdcarnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));
			//查询外地绑定车辆状态
			/*$wddatakey = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND distinction=:distinction AND bound=:bound',array(':uid'=>$uid,':distinction'=>0,':bound'=>1));
			$wddataapi = $this->wxapi('WDCLZCSQCX','C81DD8605F0531F0B6C717D07A8979F4');
			$wddataapi = $this->object2array($wddataapi);
			//echo $wddatakey['wd_car_num'];//绑定的外地车牌号码
			if($wddataapi['State'] == 0){
				foreach ($wddataapi['WDCLZCSQCXResult'] as $key => $value){
					$arr[$key]['WXH'] = $value['WXH'];
					$arr[$key]['HPHM'] = $value['HPHM'];
				}
				foreach ($arr as $k => $v) {
					//$wddatakey['wd_car_num']
					if (strpos($v['HPHM'],$wddatakey['wd_car_num']) !== false) {
						//var_dump($v);
						pdo_update('vivawjw_user_bound_car',array('status'=>$v['ZCZT']),array('uid'=>$uid,'distinction'=>0,'wd_car_num'=>$v['HPHM']));
					}
				}
				//测试用（访问一次更新数据库中数据，访问第二次更新页面显示状态）

			}else{

			}*/
			foreach ($typecar as $key => &$value){
				$data = $this->wxwfapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$value['wx_type'],$value['wx_car_num'],$value['wx_car_engine']);
				$data = $this->object2array($data);
				$value['YXQZ'] = $data['YXQZ'];
				$value['QZBFQZ'] = $data['QZBFQZ'];
				switch ($data['ZT']) {
					case 'A':
						$zt = '正常';
						break;
					case 'B':
						$zt = '转出';
						break;
					case 'C':
						$zt = '被盗抢';
						break;
					case 'D':
						$zt = '停驶';
						break;
					case 'E':
						$zt = '注销';
						break;
					case 'G':
						$zt = '违法未处理';
						break;
					case 'H':
						$zt = '海关监管';
						break;
					case 'I':
						$zt = '事故未处理';
						break;
					case 'J':
						$zt = '嫌疑车';
						break;
					case 'K':
						$zt = '查封';
						break;
					case 'L':
						$zt = '扣留';
						break;
					case 'M':
						$zt = '强制注销';
						break;
					case 'N':
						$zt = '事故逃逸';
						break;
					case 'O':
						$zt = '锁定';
						break;
					default:
						$zt = '正常';
						break;
				}
				$value['ZT'] = $zt;
			}
		}
		//无锡绑定车辆
		if ($op == 'wx_car_post'){
			//判断用户最多绑定两辆车
			$wxcargnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));
			if($wxcargnum >= 2){
				echo 100;exit;
			}else{
				//判断是否被绑定
				$seldata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND wx_car_num=:wx_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>1,':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>1,':uniacid'=>$_w['uniacid']));
				if($seldata){
					echo 400;exit;
				}
			}
			$wxcar = $this->wxwfapi('CLBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['wx_type']),trim(strtoupper($_GPC['wx_car_num'])),trim($_GPC['wx_car_engine']));
			$wxcar = $this->object2array($wxcar);
			//绑定失败State=1失败；0成功
			if($wxcar['State']==0){
				//绑定保存到数据库中
				$data['wx_type'] = trim($_GPC['wx_type']);
				$data['wx_car_num'] = trim(strtoupper($_GPC['wx_car_num']));
				$data['wx_car_engine'] = trim($_GPC['wx_car_engine']);
				$data['status'] = 0;
				$data['distinction'] = 1;//1为无锡0为外地
				$data['bound'] = 1;//1为绑定0没绑定
				$data['issel'] = 1;
				$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wx_car_num=:wx_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>1,':uid'=>$uid,':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>0,':uniacid'=>$_W['uniacid']));


				// 接口绑定成功后，直接删除以往记录！

				pdo_delete('vivawjw_user_bound_car',array("uniacid"=>$_W['uniacid'],"wx_car_num"=>trim($_GPC['wx_car_num'])));


				$wxdata = pdo_insert('vivawjw_user_bound_car',$data);
				if($wxdata){
					echo json_encode($wxcar);exit;
				}


				//同一uid绑定过
//				if($boundcar){
//					$wxdata = pdo_update('vivawjw_user_bound_car',array('issel'=>0,'bound'=>1,'status'=>$data['status']),array('uid'=>$boundcar['uid'],'wx_car_num'=>$boundcar['wx_car_num']));
//					if($wxdata){
//						echo json_encode($wxcar);exit;
//					}
//				}else{
//					$wxdata = pdo_insert('vivawjw_user_bound_car',$data);
//					if($wxdata){
//						echo json_encode($wxcar);exit;
//					}
//				}

			}else{
				echo 300;exit;
			}
		}
		//外地绑定车辆
		if ($op == 'wd_car_post'){
			//判断是否被绑定
			$wdcargnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));
			if($wdcargnum >= 2){
				echo 100;exit;
			}else{
				$seldata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND wd_car_num=:wd_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>0,':wd_car_num'=>trim($_GPC['wd_car_num']),':bound'=>1,':uniacid'=>$_W['uniacid']));
				if($seldata){
					echo 400;exit;
				}
			}
			$wdcar = $this->wxapi('WDCLZCSQ','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['wd_type']),trim(strtoupper($_GPC['wd_car_num'])),trim($_GPC['wd_car_engine']));
			$wdcar = $this->object2array($wdcar);
			//绑定失败State=1失败；0成功
			if($wdcar['State'] == 0){
				$data['wd_type'] = trim($_GPC['wd_type']);
				$data['wd_car_num'] = trim($_GPC['wd_car_num']);
				$data['wd_car_engine'] = trim($_GPC['wd_car_engine']);
				$data['status'] = 2;//2为申请中；0 为申请成功;1为申请失败
				$data['distinction'] = 0;//1为无锡0为外地
				$data['bound'] = 1;//1为绑定0没绑定
				//同一uid绑定过
				$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wd_car_num=:wd_car_num AND bound=:bound  AND uniacid=:uniacid',array(':distinction'=>0,':uid'=>$uid,':wd_car_num'=>trim($_GPC['wd_car_num']),':bound'=>0,':uniacid'=>$_W['uniacid']));
				if($boundcar){
					$wddata = pdo_update('vivawjw_user_bound_car',array('bound'=>1,'status'=>$data['status']),array('uid'=>$boundcar['uid'],'wd_car_num'=>$boundcar['wd_car_num']));
					if($wddata){
						echo json_encode($wdcar);exit;
					}
				}else{
					$wddata = pdo_insert('vivawjw_user_bound_car',$data);
					if($wddata){
						echo json_encode($wdcar);exit;
					}
				}
			}else{
				echo 300;exit;
			}
		}
		//解除绑定无锡和外地车辆
		if ($op == 'del_bound_car'){
			$id = $_GPC['id'];
			$jbcardata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$id,':uniacid'=>$_W['uniacid']));
			//判断无锡和外地，1为无锡，0为外地
			if($jbcardata['distinction'] == 1){
				$qbcar = $this->wxapi('CLQXBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$jbcardata['wx_type'],$jbcardata['wx_car_num'],$jbcardata['wx_car_engine']);
				$qbcar = $this->object2array($qbcar);
			}else{
				//解除绑定外定车辆
				$qbcar = $this->wxapi('CLQXBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$jbcardata['wd_type'],$jbcardata['wd_car_num'],$jbcardata['wd_car_engine']);
				$qbcar = $this->object2array($qbcar);
			}
			//$qbcar['State'] = 0;//解绑成功
			if($qbcar['State']){
				$info = '失败';
			}elseif($qbcar['State'] == 0){
				$delbound = pdo_update('vivawjw_user_bound_car',array('bound'=>$qbcar['State']),array('id'=>$id));
				if ($delbound){
					$info = '成功';
				}else{
					$info = '失败';
				}
			}else{
				$info = '失败';
			}
		}

		//违法查询
		if($op == 'wfcx'){
			$data = $this->wxwfapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$_GPC['wx_type'],trim(strtoupper($_GPC['wx_car_num'])),$_GPC['wx_car_engine']);
			$data = $this->object2array($data);
			$wflist = $data['WFXXList']['WFXX'];
			if ($data['State'] == 0) {
				switch ($data['ZT']) {
					case 'A':
						$zt = '正常';
						break;
					case 'B':
						$zt = '转出';
						break;
					case 'C':
						$zt = '被盗抢';
						break;
					case 'D':
						$zt = '停驶';
						break;
					case 'E':
						$zt = '注销';
						break;
					case 'G':
						$zt = '违法未处理';
						break;
					case 'H':
						$zt = '海关监管';
						break;
					case 'I':
						$zt = '事故未处理';
						break;
					case 'J':
						$zt = '嫌疑车';
						break;
					case 'K':
						$zt = '查封';
						break;
					case 'L':
						$zt = '扣留';
						break;
					case 'M':
						$zt = '强制注销';
						break;
					case 'N':
						$zt = '事故逃逸';
						break;
					case 'O':
						$zt = '锁定';
						break;
					default:
						$zt = '正常';
						break;
				}
			}elseif($data['State'] == 2){
				echo 200;exit;
			}else{
				echo 300;exit;
			}
			//var_dump($wflist);
			$wfnum = count($wflist);
			$fkje = 0;
			for($i=0;$i<$wfnum;$i++){
				$n = $wflist[$i]['FKJE'];
				$fkje = $fkje + $n;
			}
			echo json_encode($data);exit;
		}

		include $this->template('user_car');
	}

	//查询信息跳转支付接口
	public function doMobileWzfkapi(){
		global $_W,$_GPC;
		$uid = $_W['member']['uid'];
		$uniacid = $_W['uniacid'];
		$cardata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND uniacid=:uniacid AND status=:status AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$uniacid,':status'=>0,':bound'=>1));
		$drivingdata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid AND status=:status AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$uniacid,':status'=>0,':bound'=>1));
		if(!$cardata){
			echo 100;exit;
		}
		if(!$drivingdata){
			echo 300;exit;
		}
		if($cardata && $drivingdata){
			$sfzh = $drivingdata['wx_driv_num'] ? $drivingdata['wx_driv_num'] : $drivingdata['wd_driv_num'];
			$dabs = $drivingdata['wx_driv_record'] ?  $drivingdata['wx_driv_record'] : $drivingdata['wd_driv_record'];
			$cararr = array(
				'hpzl' => $cardata[0]['wx_type'] ? $cardata[0]['wx_type'] : $cardata[0]['wd_type'],
				'hphm' => $cardata[0]['wx_car_num'] ? $cardata[0]['wx_car_num'] : $cardata[0]['wd_car_num'],
				'fdjh' => $cardata[0]['wx_car_engine'] ? $cardata[0]['wx_car_engine'] : $cardata[0]['wd_car_engine'],
				'czxm' => '未知',
				'sfz' => $sfzh
			);

			$cararr1 = array(
				'hpzl' => $cardata[1]['wx_type'] ? $cardata[1]['wx_type'] : $cardata[1]['wd_type'],
				'hphm' => $cardata[1]['wx_car_num'] ? $cardata[1]['wx_car_num'] : $cardata[1]['wd_car_num'],
				'fdjh' => $cardata[1]['wx_car_engine'] ? $cardata[1]['wx_car_engine'] : $cardata[1]['wd_car_engine'],
				'czxm' => '未知',
				'sfz' => $sfzh
			);
			$cararrs = array(
				$cararr,
				$cardata[1] ? $cararr1 : null
			);
			//$openid = 'ocJugjjqEhy-V_M0KzopuJAmASEY';
			$openid = $_W['openid'];
			$a = array("openid"=>$openid,
				"jsrxm"=>"未知",
				"sfzh"=>$sfzh,
				"dabs"=>$dabs,
				"vehicleData"=> $cararrs
			);
			$json = base64_encode(json_encode($a));
			$key = 'tLbw255pwy!Z^M$c';
			$sign = strtoupper(md5($key.$json.$key));
			//var_dump($a);
			echo json_encode("http://www.smtsvs.com:48080/fjfl_wx_wx/systemUser/userAuthenticate.do?json=$json&sign=$sign");exit;
			//echo 200;exit;
		}
	}

	//查询违法图片
	public function doMobileAtest(){
		global $_W,$_GPC;
		$wfcar = $_GPC['carnum'];
		$wftp = $_GPC['wfxh'];
		$filename = 'images/'.$_W['uniacid']."/wfimg/".date("Y",time())."/".date("m",time())."/$wftp.jpg";
		$imgurl = tomedia($filename);
		$imginfo = get_headers($imgurl);

		$contLength = explode(':',$imginfo['4']);

		if($imginfo[0] != 'HTTP/1.1 200 OK' || trim($contLength[1]) <= 0){
			$data = $this->wxapi('WFTP','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$wftp,$wfcar);
			$data = $this->object2array($data);
			if($data['State'] == 0){
				$imgdata = $data['WFTP'];
				load()->func('file');
				file_write($filename, $imgdata);
				file_remote_upload($filename);
			}else{
				$imgdata = file_get_contents(MODULE_ROOT."/template/resource/img/nopic.jpg");
				load()->func('file');
				file_write($filename, $imgdata);
				file_remote_upload($filename);
			}



			/*$imgdata = $data['WFTP'];
			  if(!$imgdata){
				$imgdata = file_get_contents(MODULE_ROOT."/template/resource/img/nopic.jpg");
			}
			load()->func('file');
			file_write($filename, $imgdata);
			file_remote_upload($filename);*/
		}
		echo trim($imgurl);

	}

	//申请信息处理
	public function doMobileAppealpost(){
		global $_W,$_GPC;


		$cou = pdo_fetchcolumn('SELECT count(*) FROM  '.tablename('vivawjw_wfcx').'WHERE uid=:uid and wfjkxh=:wfjkxh and wfcph=:wfcph',array(':uid'=>$_W['member']['uid'],':wfjkxh'=>$_GPC['wfjkxh'],':wfcph'=>$_GPC['wfcph']));
		// echo $cou;
		if($cou>0){
			echo 300;exit;
		}
		$data['appeal_name'] = $_GPC['appeal_name'];
		$data['appeal_phone'] = $_GPC['appeal_phone'];
		$data['appeal_why'] = $_GPC['appeal_why'];
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = $_W['member']['uid'];
		$data['time'] = time();
		$data['openid']=$_W['openid'];
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
		$apitime=date('Y-m-d h:i', time());
		$tsnr= $_GPC['appeal_name'].":".$_GPC['appeal_phone'].";".$_GPC['appeal_why'];
		$dataapi=$this->wxapiss($_W['openid'],$apitime,strtoupper($_GPC['wfcph']),$_GPC['wfjkxh'],$tsnr);
		$dataapis=$this->object2array($dataapi);
		// echo json_encode($dataapis);exit;
		// echo $dataapis['State'];exit;
		if($dataapis['State']==0){
			if($data['appeal_why']){
				$insert_data = pdo_insert('vivawjw_wfcx',$data);
				if ($insert_data){
					echo 200;
				}
			}
		}else{
			// echo $dataapi['State'];
			echo 400;exit;
		}

	}


	//接口查询申诉结果3.23 xia

	public function doMobilessjgpost(){
		global $_W,$_GPC;
		$id =$_GPC['id'];
		$data= pdo_fetchall('SELECT id,wfcph,wfjkxh FROM'.tablename('vivawjw_wfcx').'WHERE id=:id',array(':id'=>$id));
		// echo $data[0]['id'];
		$dataapi=$this->wxapissjg($_W['openid'],$data[0]['wfcph'],$data[0]['wfjkxh']);
		$dataapis=$this->object2array($dataapi);
		echo $dataapis['SSJG'];
	}


	public function wxapissjg($wx,$carnum,$jk){
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
			$url = 'http://192.168.11.58/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			//echo '<pre>';
			//var_dump($c->register('wxzhcs','wxzhcs123456'));
			//C81DD8605F0531F0B6C717D07A8979F4
			//return $c->DJSS('C81DD8605F0531F0B6C717D07A8979F4',$wx,$time,$carnum,$jk,$nr);
			return $c->DJSSJGCX('C81DD8605F0531F0B6C717D07A8979F4',$wx,$carnum,$jk);
			// return $c->DJSS('C81DD8605F0531F0B6C717D07A8979F4','ocJugjrKn12kVR8lt_OSCGiU3rgk','2017-03-18 12:47','苏B7KR12','3202009014349753','左转弯前方有些许白漆，让人误以为是待转区域，白漆长久模糊了。更何况对面的车道就有待转区域。难道待转区域只划半条马路？！同样是不影响直行车辆的。若是没那些模糊的白漆，我也不会左拐出去等待的');

		} catch (SOAPFault $e) {
			print_r($e);
		}
	}

	//我的驾驶证
	public function doMobileUser_driving(){
		global $_W,$_GPC;
		/*if($_GPC['test'] != 1){
			die();
		}*/
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}


		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $_W['member']['uid'];
		//1.判断新表有无数据，有显示，没有显示绑定页
		$typedriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$_W['uniacid'],':bound'=>1));
		if($typedriving){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
		}else{
			//调取老数据
			$r = new Redis();
			$v = $r->connect('r-bp1fd9985c4f7234.redis.rds.aliyuncs.com',6379);
			$res = $r->auth('0qEFuasxAaqbT1c3');
			//$value = $r->get("Driver_ocJugjsllErxRFVPkxow5jZK_sr4");
			//2.判断老数据表，有两条取消绑定。一条插入新表
			$oldDriver = $r->get("Driver_".$_W['openid']);
			$oldDriver = json_decode($oldDriver,true);
			//$oldDriver = $this->object2array($oldDriver);
			//如果有绑定计算个数
			if($oldDriver['status'] == 1){
				$counDriverData = count($oldDriver);
			}
			$oldUser = $r->get("Users_".$_W['openid']);
			$oldUser = json_decode($oldUser,true);
			//$oldUser = $this->object2array($oldUser);
			//121为绑定驾驶证，且有绑定,计算个数
			if($oldUser['FAttentionType'] == 121 && $oldUser['FStatus'] == 1){
				$conUserData = count($oldUser);
			}
			//老表绑定的总数
			$oldBoundDriver = $counDriverData + $conUserData;
			if($oldBoundDriver > 1){
				//会直接访问接口，访问接口失败
				/*foreach ($oldDriver as $value) {
					$this->wxwfapi('JSZQXBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$value['cardnumber'],$value['recordnumber']);
				}
				foreach ($oldUser as $val) {
					$this->wxwfapi('JSZQXBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$val['FDriverCardNumber'],$val['FDriverRecordNumber']);
				}*/
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
			}elseif($oldBoundDriver == 1){
				$new['status'] = 0;//绑定状态
				$new['bound'] = 1;//绑定成功
				$new['time'] = $oldDriver[0]['dateline'] ? $oldDriver[0]['dateline'] : strtotime($oldUser[0]['FUpdateTime']);
				$new['issel'] =1;
				//老数据0为本地1为外地
				$islocal = $oldDriver[0]['islocal'] ? $oldDriver[0]['islocal'] : $oldUser[0]['FIsLocal'];//本地0，外地1
				$carnumber = $oldDriver[0]['cardnumber'] ? $oldDriver[0]['cardnumber'] : $oldUser[0]['FDriverCardNumber'];//驾驶证
				$driv_record = $oldDriver[0]['recordnumber'] ? $oldDriver[0]['recordnumber'] : $oldUser[0]['FDriverRecordNumber'];//档案编号
				if($islocal == 0){
					$new['distinction'] = 1;//新数据1为无锡本地0为外地
					$new['wx_driv_num'] = $carnumber;
					$new['wx_driv_record'] = $driv_record;
					pdo_insert('vivawjw_user_bound_driving',$new);
				}elseif($islocal == 1){
					$new['distinction'] = 0;//新数据1为无锡本地0为外地
					$new['wd_driv_num'] = $carnumber;
					$new['wd_driv_record'] = $driv_record;
					pdo_insert('vivawjw_user_bound_driving',$new);
				}
			}else{
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
			}
		}
		if($op == 'success_driving'){
			if($typedriving){
				//无锡驾驶证
				//var_dump($typedriving);
				$wxwfdata = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$typedriving['wx_driv_num'],$typedriving['wx_driv_record']);
				//$wxwfdata = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],'320223196504165739','700151');
				$wxwfdata = $this->object2array($wxwfdata);
				if ($wxwfdata) {
					switch ($wxwfdata['ZT']) {
						case 'A':
							$zt = '正常';
							break;
						case 'B':
							$zt = '超分';
							break;
						case 'C':
							$zt = '转出';
							break;
						case 'D':
							$zt = '暂扣';
							break;
						case 'E':
							$zt = '撤销';
							break;
						case 'F':
							$zt = '吊销';
							break;
						case 'G':
							$zt = '注销';
							break;
						case 'H':
							$zt = '违法未处理';
							break;
						case 'I':
							$zt = '事故未处理';
							break;
						case 'J':
							$zt = '停止使用';
							break;
						case 'K':
							$zt = '扣押';
							break;
						case 'L':
							$zt = '锁定';
							break;
						case 'M':
							$zt = '逾期未换证';
							break;
						case 'N':
							$zt = '延期换证';
							break;
						case 'P':
							$zt = '延期体检';
							break;
						case 'R':
							$zt = '注销可恢复';
							break;
						case 'S':
							$zt = '逾期未审验';
							break;
						case 'T':
							$zt = '延期审验';
							break;
						case 'U':
							$zt = '扣留';
							break;
						default:
							$zt = '正常';
							break;
					}
				}
			}
		}



		//无锡添加驾驶证
		if ($op == 'wx_drivpost'){
			//判断是否被绑定
			/*$wxdrivingnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$_W['member']['uid'],':bound'=>1));*/
			$wxdriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wx_driv_num=:wx_driv_num AND bound=:bound AND uniacid=:uniacid',array(':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>1,':uniacid'=>$_W['uniacid']));

			/*if($wxdrivingnum){
				echo 400;exit;
			}*/
			if($wxdriving){
				echo 100;exit;
			}
			//访问接口
			$wxdriving = $this->wxapi('JSZBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['wx_driv_num']),trim($_GPC['wx_driv_record']));
			$wxdriving = $this->object2array($wxdriving);
			//绑定失败State=1失败；0成功
			if($wxdriving['State'] == 0){
				$data['wx_driv_num'] = trim($_GPC['wx_driv_num']);
				$data['wx_driv_record'] = trim($_GPC['wx_driv_record']);
				$data['status'] = $wxdriving['State'];//绑定成功为0失败为1
				$data['distinction'] = 1;//1为无锡
				$data['bound'] = 1;//1为绑定0没绑定
				//同一uid绑定过
				$bounddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid=:uniacid AND distinction=:distinction AND uid=:uid AND wx_driv_num=:wx_driv_num AND bound=:bound',array(':uniacid'=>$_W['uniacid'],':distinction'=>1,':uid'=>$_W['member']['uid'],':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>0));
				if($bounddriving){
					$boundwxdata = pdo_update('vivawjw_user_bound_driving',array('bound'=>1,'status'=>$data['status']),array('uid'=>$bounddriving['uid'],'wx_driv_num'=>$bounddriving['wx_driv_num']));
					if($boundwxdata){
						echo json_encode($wxdriving);exit;
					}
				}else{
					$boundwxdata = pdo_insert('vivawjw_user_bound_driving',$data);
					if ($boundwxdata){
						echo json_encode($wxdriving);exit;
					}
				}
			}else{
				echo 300;exit;
			}
		}
		//外地添加驾驶证
		if ($op == 'wd_drivpost'){
			//判断是否被绑定
			/*$wddrivingnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$_W['member']['uid'],':bound'=>1));*/
			$wddriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wd_driv_num=:wd_driv_num AND bound=:bound AND uniacid=:uniacid',array(':wd_driv_num'=>trim($_GPC['wd_driv_num']),':bound'=>1,':uniacid'=>$_W['uniacid']));
			/*if($wddrivingnum){
				echo 400;exit;
			}*/
			if($wddriving){
				echo 100;exit;
			}
			//访问接口
			$wddriving = $this->wxapi('WDJSZZCSQ','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['wd_driv_num']),trim($_GPC['wd_driv_record']));
			$wddriving = $this->object2array($wddriving);
			//绑定失败State=1失败；0成功
			if($wddriving['State'] == 0){
				$data['wd_driv_num'] = trim($_GPC['wd_driv_num']);
				$data['wd_driv_record'] = trim($_GPC['wd_driv_record']);
				$data['distinction'] = 0;//0为外地
				$data['status'] = 2;//2为申请中；0 为申请成功;1为申请失败
				$data['bound'] = 1;//1为绑定0没绑定
				//同一uid是否绑定过
				$boundwddr = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE distinction=:distinction AND uid=:uid AND wd_driv_num=:wd_driv_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>0,':uid'=>$_W['member']['uid'],':wd_driv_num'=>trim($_GPC['wd_driv_num']),':bound'=>0,':uniacid'=>$_W['uniacid']));
				if($boundwddr){
					$wddata = pdo_update('vivawjw_user_bound_driving',array('bound'=>1,'status'=>$data['status']),array('uid'=>$boundwddr['uid'],'wd_driv_num'=>$boundwddr['wd_driv_num']));
					if($wddata){
						echo json_encode($wddriving);exit;
					}
				}
				$wddata = pdo_insert('vivawjw_user_bound_driving',$data);
				if ($wddata){
					if($wddata){
						echo json_encode($wddriving);exit;
					}
				}
			}else{
				echo 300;exit;
			}
		}
		//解除绑定驾驶证
		if ($op == 'del_bound_dr'){
			$id = $_GPC['id'];
			$jbdrdata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id=:id AND uniacid=:uniacid',array(':id'=>$id,':uniacid'=>$_W['uniacid']));
			//判断无锡和外地，1为无锡，0为外地
			if ($jbdrdata['distinction']) {
				$jbdr = $this->wxwfapi('JSZQXBD', 'C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'], $jbdrdata['wx_driv_num'], $jbdrdata['wx_driv_record']);
				$jbdr = $this->object2array($jbdr);
			}else{
				$jbdr = $this->wxwfapi('JSZQXBD', 'C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'], $jbdrdata['wd_driv_num'], $jbdrdata['wd_driv_record']);
				$jbdr = $this->object2array($jbdr);
			}
			//$jbdr['State'] = 0;//解绑成功
			if($jbdr['State'] ==1){
				$info = '失败';
			}elseif($jbdr['State'] == 0){
				$delbound = pdo_update('vivawjw_user_bound_driving',array('bound'=>0),array('id'=>$id));
				if ($delbound){
					$info = '成功';
				}else{
					$info = '失败';
				}
			}else{
				$info = '失败';
			}
		}

		if($op == 'jsrwfcx'){
			$jszh = trim($_GPC['jszh']);
			$dabh = trim($_GPC['dabh']);
			$datas = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$jszh,$dabh);
			//$datas = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid']','320681199407246632','378927');
			$data = $this->object2array($datas);
			//var_dump($_GPC);
			if($data['State'] == 0){
				if($data['ZT'] == 'A'){
					echo 300;exit;
				}else{
					echo json_encode($datas);exit;
				}
			}elseif($data['State'] == 2){
				echo 300;exit;
			}else{
				echo 200;exit;
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
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 4;
		$wfdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		include $this->template('user_complain');
	}

	//我的快处
	public function doMobileUser_dispose(){
		global $_W,$_GPC;

		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'kcdata';
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		$kcdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_sgkc').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		foreach ($kcdata as $info){
			$info['proof'] = explode(",",$info['proof']);
			$v = array();
			foreach($info['proof'] as $k){
				$type = strtolower(substr($k,strrpos($k,'.')+1));
				$img = array('jpeg','jpg','png');
				$video = array('mp4','mpeg','mov');
				if(in_array($type, $img)){
					$v['imgdir'][] = $k;
				}
				if(in_array($type, $video)){
					$v['videodir'][] = $k;
				}
			}
		}
		include $this->template('user_dispose');
	}

	public function roadareaCache(){ // 5mins 缓存  by zhangwei
		$roadareaCache = cache_load('roadareaCache');
		//cache_delete('roadareaCache');
		if(!$roadareaCache || TIMESTAMP-$roadareaCache['creattime']>(3600*6)){
			$roadareaCache['data'] = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');
			$roadareaCache['creattime'] = TIMESTAMP;
			cache_write('roadareaCache',$roadareaCache);
		}
		return $roadareaCache['data'];
	}

	//我的路况
	public function doMobileUser_road(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		$sclk = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx_sc').' WHERE uid=:uid',array(':uid'=>$uid));
		// $data = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');

		$data = $this->roadareaCache();
		//var_dump($data);
		$data = $this->object2array($data);
		$data = $data['ZGDLKResult'];
		foreach($data as $k => $v){
			//对比收藏
			foreach ($sclk as $key => $value) {
				if ($v['DWBH'] == $value['dwbh']) {
					$data[$k]['bool'] = 1;
					break;
				} else {
					$data[$k]['bool'] = 0;
				}
			}
		}

		$s=array();
		foreach ($sclk as $ks => $vs) {
			$s[] =  $vs['wzms'];
		}
		$s=array_unique($s);
		foreach ($sclk as $kks => $vss) {
			if($s[$kks]==$vss[$kks]){
				$sclk[$kks]['cf']="1";
			}
		}

		//var_dump($sclk);
		if($op == 'qxsclk'){
			$id = $_GPC['id'];
			$qxsclk = pdo_delete('vivawjw_lkcx_sc',array('wzms'=>$id));
			if($qxsclk){
				echo 200;exit;
			}
		}



		include $this->template('user_road');
	}

	public function doMobileSeltp_new(){ // 每30秒生成图片上传至远程文件夹
		global $_W, $_GPC;
		//提交接口对比数据
		$dwbh = $_GPC['dwbh'];
		$imgurl = tomedia('images/'.$_W['uniacid'].'/roadpic/'.$dwbh.'.png');
		$imginfo = get_headers($imgurl);
		$time = strtotime(str_replace("Last-Modified: ","",$imginfo[9]));

		if(!$time || TIMESTAMP - $time  > 180){
			$data = $this->wxapi('LKTPCX','C81DD8605F0531F0B6C717D07A8979F4',$dwbh);
			$data = $this->object2array($data);
			$data = $data['LKKZ'];
			//$data = base64_encode($data);

			load()->func('file');
			$filename = 'images/'.$_W['uniacid'].'/roadpic/'.$dwbh.'.png';

			file_write($filename, $data);
			file_remote_upload($filename);

			$time = $time.rand(100,999);

		}

		echo $imgurl."?$time";
	}

	//调取图片接口
	public function doMobileSeltp(){
		global $_W, $_GPC;
		//提交接口对比数据
		$dwbh = $_GPC['dwbh'];
		$data = $this->wxapi('LKTPCX','C81DD8605F0531F0B6C717D07A8979F4',$dwbh);
		$data = $this->object2array($data);
		$data = $data['LKKZ'];
		$data = base64_encode($data);
		echo $data;
		//echo '<img src="data:image/png;base64,'.$data.'"/>';
	}
	//我的在线学习
	public function doMobileUser_study(){
		global $_W,$_GPC;
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		$study = pdo_fetch('SELECT * FROM '.tablename('vivawjw_zxxx').' WHERE uid=:uid',array(':uid'=>$uid));
		if(!$study){
			$op =trim($_GPC['op'])? trim($_GPC['op']): 'empty';
		}else{
			if($study['status'] == 1){
				//审核通过
				$op =trim($_GPC['op'])? trim($_GPC['op']): 'tongguo';
			}elseif($study['status'] == 2){
				$op =trim($_GPC['op'])? trim($_GPC['op']): 'daishenhe';
			}elseif($study['status'] == 3){
				//审核未通过
				$op =trim($_GPC['op'])? trim($_GPC['op']): 'weitongguo';
			}elseif($study['status'] == 4){
				//学习中
				$op =trim($_GPC['op'])? trim($_GPC['op']): 'styding';
			}elseif($study['status'] == -1){
				//重审
				$op =trim($_GPC['op'])? trim($_GPC['op']): 'again';
				$studyTurndown  = explode(',',$study['turndown']);
				foreach ($studyTurndown as $k => $v){
					switch ($v){
						case 'video1':
							$turndown[$k] = '视频1';
							break;
						case 'video2':
							$turndown[$k] = '视频2';
							break;
						case 'video3':
							$turndown[$k] = '视频3';
							break;
						case 'video4':
							$turndown[$k] = '视频4';
							break;
						case 'datiimg':
							$turndown[$k] = '文明安全驾驶知识测试';
							break;
						case 'xindeimg':
							$turndown[$k] = '心得体会';
							break;
						case 'shentiimg':
							$turndown[$k] = '自主申报驾驶人身体情况';
							break;
						case 'sfzimg':
							$turndown[$k] = '身份证正面';
							break;
						case 'sffimg':
							$turndown[$k] = '身份证反面';
							break;
						case 'cnsimg':
							$turndown[$k] = '承诺书';
							break;
						case 'sccnsimg':
							$turndown[$k] = '手持承诺书';
							break;
						case 'qmimg':
							$turndown[$k] = '手持承诺书';
							break;
					}
				}
				$turndownstr = join('和',$turndown);
				$newarr=array();
				foreach ($turndown as $k=>$v){
					$newarr[$studyTurndown[$k]]=$v;
				}
			}
		}
		include $this->template('user_study');
	}
	//上传重审图片
	public function doMobileSub_image(){
		global $_W,$_GPC;
		$imgval = $_GPC['imgval'];
		foreach ($imgval as $value){
			$value[1] = $this -> downloadMedia($value[1]);
			if($value[0] == 'video1' || $value[0] == 'video2' || $value[0] == 'video3' || $value[0] == 'video4'){
				$againImg = pdo_update('vivawjw_zxxx_video',array('video_avatar'=>$value[1]),array('uid'=>$_W['member']['uid'],'video_name'=>$value[0]));
			}
			if($value[0] == 'datiimg' || $value[0] == 'xindeimg' || $value[0] == 'shentiimg' || $value[0] == 'sfzimg' || $value[0] == 'sffimg' || $value[0] == 'qmimg' || $value[0] == 'cnsimg' || $value[0] =='sccnsimg'){
				$againAvatar = pdo_update('vivawjw_zxxx',array($value[0]=>$value[1]),array('uid'=>$_W['member']['uid']));
			}
		}
		if($againImg || $againAvatar){
			$againAvatar = pdo_update('vivawjw_zxxx',array('status'=>2),array('uid'=>$_W['member']['uid']));
			if($againAvatar){
				echo 200;exit;
			}else{
				echo 300;exit;
			}
		}
	}
	public function downloadMedia($mediaId,$type="image"){
		$media = array("type"=>$type,"media_id"=>$mediaId);
		$account_api = WeAccount::create();
		return $account_api->downloadMedia($media);
	}
	//违法车辆驾驶证接口
	public function wxwfapi($api,$sign,$wx,$typt,$carnum,$engnum){
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
			//$url = 'https://192.168.11.51/WXWC/wcservice.asmx?wsd';
			$url = 'http://192.168.11.58/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			//echo '<pre>';
			//var_dump($c->register('wxzhcs','wxzhcs123456'));
			//C81DD8605F0531F0B6C717D07A8979F4
			return $c->$api($sign,$wx,$typt,$carnum,$engnum);

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

	public function wxapiss($wx,$time,$carnum,$jk,$nr){
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
			$url = 'http://192.168.11.58/WXWC/wcservice.asmx?wsdl';
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			//echo '<pre>';
			//var_dump($c->register('wxzhcs','wxzhcs123456'));
			//C81DD8605F0531F0B6C717D07A8979F4
			return $c->DJSS('C81DD8605F0531F0B6C717D07A8979F4',$wx,$time,$carnum,$jk,$nr);
			// return $c->DJSS('C81DD8605F0531F0B6C717D07A8979F4','ocJugjrKn12kVR8lt_OSCGiU3rgk','2017-03-18 12:47','苏B7KR12','3202009014349753','左转弯前方有些许白漆，让人误以为是待转区域，白漆长久模糊了。更何况对面的车道就有待转区域。难道待转区域只划半条马路？！同样是不影响直行车辆的。若是没那些模糊的白漆，我也不会左拐出去等待的');

		} catch (SOAPFault $e) {
			echo "<script>alert('系统繁忙，请稍后再试！')</script>";
		}
	}
	//对象转数组
	public function object2array($object) {
		foreach ($object as $k => $v) {
			if (is_array($v) || is_object($v)) {
				$arr[$k] = $this->object2array($v);
			} else {
				$arr[$k] = $v;
			}
		}
		return $arr;
	}








	//后台管理
	public function doWebManage(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
		$uniacid = $_W['uniacid'];
		//无锡绑定车辆列表
		if ($op == 'wx_car'){
			$distinction = 1;
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid = :uniacid AND distinction = :distinction',array(':uniacid' => $_W['uniacid'],':distinction'=>$distinction));
			$wxlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid = :uniacid AND distinction = :distinction ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid,':distinction'=>$distinction));
			$pager =pagination($total, $pindex, $psize);
		}
		//无锡绑定车辆信息
		if ($op == 'wx_car_info'){
			$id = $_GPC['id'];
			$wxone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id = :id ',array(':id'=>$id));
		}
		//外地绑定车辆列表
		if ($op == 'wd_car'){
			$distinction = 0;
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid = :uniacid AND distinction = :distinction',array(':uniacid' => $_W['uniacid'],':distinction'=>$distinction));
			$wdlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid = :uniacid AND distinction = :distinction ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid,':distinction'=>$distinction));
			$pager =pagination($total, $pindex, $psize);
		}
		//外地绑定车辆信息
		if ($op == 'wd_car_info'){
			$id = $_GPC['id'];
			$wdone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id = :id ',array(':id'=>$id));
		}
		//删除无锡绑定车辆、删除外地绑定车辆
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
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid = :uniacid AND distinction = :distinction',array(':uniacid' => $_W['uniacid'],':distinction'=>$distinction));
			$wxdriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid = :uniacid AND distinction = :distinction ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid,':distinction'=>$distinction));
			$pager =pagination($total, $pindex, $psize);
		}
		//无锡绑定驾驶证信息
		if ($op == 'wx_driv_info'){
			$id = $_GPC['id'];
			$wxdriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id AND uniacid=:uniacid',array(':id'=>$id,':uniacid'=>$_W['uniacid']));
		}

		//外地绑定驾驶证列表
		if ($op == 'wd_driving'){
			$distinction = 0;
			$pindex =max(1, intval($_GPC['page']));
			$psize =10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid = :uniacid AND distinction = :distinction',array(':uniacid' => $_W['uniacid'],':distinction'=>$distinction));
			$wddriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid = :uniacid AND distinction = :distinction ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid,':distinction'=>$distinction));
			$pager =pagination($total, $pindex, $psize);
		}
		//外地绑定驾驶证信息
		if ($op == 'wd_driv_info'){
			$id = $_GPC['id'];
			$wddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id AND uniacid=:uniaicd',array(':id'=>$id,':uniaicd'=>$_W['uniacid']));
		}


		//产品反馈
		if ($op == 'pro_feedback'){
			$pindex =max(1, intval($_GPC['page']));
			$psize = 10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_feedback').' WHERE uniacid = :uniacid ',array(':uniacid' => $_W['uniacid']));
			$feeddata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE uniacid = :uniacid  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid));
			$pager =pagination($total, $pindex, $psize);
		}
		//产品反馈信息
		if ($op == 'feedback_info'){
			$id = $_GPC['id'];
			$feedone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE id = :id ',array(':id'=>$id));
			$feedonef = pdo_fetch('SELECT fanid,uid FROM '.tablename('mc_mapping_fans').' WHERE uid = :id ',array(':id'=>$feedone['uid']));
			$feedone['fanid']=$feedonef['fanid'];
		}
		if($op == 'feed_del'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_user_feedback',array('id'=>$id));
			if($data){
				echo 200;exit;
			}
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





	//查询绑定外地驾驶证状态
	public function doMobileCxwdjsz(){
		global $_W,$_GPC;
		//查询外地绑定驾驶证状态
		load()->model('mc');
		$wddataapi = $this->wxapi('WDJSZZCSQCX','C81DD8605F0531F0B6C717D07A8979F4');
		$wddataapi = $this->object2array($wddataapi);
		/*$wddataapi['WDJSZZCSQCXResult'] = array(
			0=>array (
				"WXH"=> "o-LZZt5QGW1EhQdu-Lsc3-u0kBFc",
				"JSZH"=> "320925198309167413",
				"ZCZT"=> "0",
				"RKSJ"=> "2017-03-21 23:30:32"
			),
			1=>array(
				"WXH"=> "o-LZZtxUFgxqGjpaUEQ-TmKHidKE",
				"JSZH"=> "320681199407246632",
				"ZCZT"=> "0",
				"RKSJ"=> "2017-03-21 23:30:32"
			)
		);*/
		if($wddataapi['State'] == 0) {
			foreach ($wddataapi['WDJSZZCSQCXResult'] as $key => $value) {
				$arr[$key]['WXH'] =  mc_openid2uid($value['WXH']);
				$arr[$key]['JSZH'] = $value['JSZH'];
				$arr[$key]['ZCZT'] = $value['ZCZT'];
				$arr[$key]['RKSJ'] = strtotime($value['RKSJ']);
				$data= pdo_update('vivawjw_user_bound_driving',array('status'=>$value['ZCZT'],'time'=>strtotime($value['RKSJ'])),array('uid'=>mc_openid2uid($value['WXH']),'wd_driv_num'=>$value['JSZH'],'bound'=>1));
				if(!$data){
					echo $key.'---->'.$value['WXH'].'--->'.mc_openid2uid($value['WXH']).'--->'. $value['JSZH'] .'--->'.'<br>';
				}else{
					echo 'ok'.$key.'<br>';
				}
			}
		}else{
			echo $wddataapi['State'];
		}
	}


	//查询绑定外地车车辆
	public function doMobileCxwdcl(){
		load()->model('mc');
		$wddataapi = $this->wxapi('WDCLZCSQCX','C81DD8605F0531F0B6C717D07A8979F4');
		$wddataapi = $this->object2array($wddataapi);
		/*$wddataapi['WDCLZCSQCXResult'] = array(
			0=>array (
				"WXH"=> "o-LZZt1IzQJaNmtqNIPFKLE_XaOw",
				"HPZL"=> "02",
				"HPHM"=> "蒙DH7931",
				"ZCZT"=> "0",
				"RKSJ"=> "2017-03-21 23:30:32"
			),
			1=>array(
				"WXH"=> "o-LZZtyhRcnLsMJysDlJXFRE7C_0",
				"HPZL"=> "02",
				"HPHM"=> "蒙DH7922",
				"ZCZT"=> "1",
				"RKSJ"=> "2017-03-21 23:30:32"
			),
			2=>array(
				"WXH"=> "o-LZZtz1ekFbfZq729yXwFsfOwuY",
				"HPZL"=> "02",
				"HPHM"=> "苏DH7922",
				"ZCZT"=> "0",
				"RKSJ"=> "2017-03-21 23:30:32"
			)
		);*/
		if($wddataapi['State'] == 0){
			foreach ($wddataapi['WDCLZCSQCXResult'] as $key => $value){
				$data = pdo_update('vivawjw_user_bound_car',array('status'=>$value['ZCZT'],'time'=>strtotime($value['RKSJ'])),array('uid'=>mc_openid2uid($value['WXH']),'wd_type'=>$value['HPZL'],'wd_car_num'=>$value['HPHM'],'bound'=>1,'distinction'=>0));
				if(!$data){
					echo $key.'---->'. $value['WXH'].'-->'.mc_openid2uid($value['WXH']).'-->'.$value['HPHM'].'<br>';
				}else{
					echo 'ok'.$key.'<br>';
				}
			}
		}else{
			echo $wddataapi['State'];
		}
	}




}
?> 
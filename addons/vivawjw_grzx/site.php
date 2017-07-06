<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_grzx/template/resource/');
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
		load()->func('logging');
		//车辆类型
		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');

		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $data['uid'];
		//1.判断新表有无数据
		$bdcarnums = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND uniacid=:uniacid',array(':uid'=>$_W['member']['uid'],':uniacid'=>$_W['uniacid']));
		//如果没有绑定显示绑定页，如果有两条绑定显示成功页
		if($bdcarnums){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';	// 显示去绑定页面
			foreach ($bdcarnums as $k => $v) {
				if ($v['bound']) {  // 如果至少有一条数据为绑定状态，显示成功页
					$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_car';
					break;
				}
			}
		}else{
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
			/*$r = new Redis();
			$v = $r->connect('r-bp1fd9985c4f7234.redis.rds.aliyuncs.com',6379);
			$res = $r->auth('0qEFuasxAaqbT1c3');
			$oldData = $r->get("Users_".$_W['openid']);
			$oldData = json_decode($oldData,true);
			// $openId = $_W['openid'];
			// $oldData = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_old_users').' WHERE FOpenId=:FOpenId AND FStatus=:FStatus AND FAttentionType=:FAttentionType',array(':FOpenId'=>$openId,':FStatus'=>1,':FAttentionType'=>122));
			//如果有绑定数据导入新表
			if($oldData['FAttentionType'] == 122 && $oldData['FStatus'] == 1){
				//老数据0为本地1为外地
				if($oldData['FIsLocal'] == 1){
					if($oldData['FIsApply'] == 2){
						$new['status'] = 0;//绑定成功
					}elseif($oldData['FIsApply'] == -1){
						$new['status'] = 1;//绑定失败
					}elseif($oldData['FIsApply'] == 1 || $oldData['FIsApply'] == 0){
						$new['status'] = 2;//申请中
					}
					$new['uid'] = $_W['member']['uid'];
					$new['uniacid'] = $_W['uniacid'];
					$new['distinction'] = 0;//0为外地
					//$new['status'] = 0;//绑定状态
					$new['bound'] = 1;//绑定
					$new['time'] = strtotime($oldData['FUpdateTime']);
					$new['wd_type'] =  $oldData['FCarType'];//类型
					$new['wd_car_num'] = $oldData['FCarNumber'];//车牌
					$new['wd_car_engine'] = $oldData['FEngine'];//发动机号
					$new['issel'] = 1;
					pdo_insert('vivawjw_user_bound_car',$new);

				}elseif($oldData['FIsLocal'] == 0){
					$new['uid'] = $_W['member']['uid'];
					$new['uniacid'] = $_W['uniacid'];
					$new['distinction'] = 1;//新数据1为无锡
					$new['status'] = 0;//绑定状态
					$new['bound'] = 1;//绑定成功
					$new['time'] = strtotime($oldData['FUpdateTime']);
					$new['wx_type'] =  $oldData['FCarType'];//类型
					$new['wx_car_num'] = $oldData['FCarNumber'];//车牌
					$new['wx_car_engine'] = $oldData['FEngine'];//发动机号
					$new['issel'] = 1;
					pdo_insert('vivawjw_user_bound_car',$new);
				}
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_car';
			}else{
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
			}*/
		}
		/*---------------------------------------------------------------------------*/
		if($op == 'success_car'){
			//绑定车辆的数量；一个微信号最多绑定两辆车
			$bdcarnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1 ,':uniacid'=>$_W['uniacid']));
			//根据UID判断是否有绑定1为绑定0没绑定//所有绑定的信息
			$typecar = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));

			foreach ($typecar as $key => $value){
/*				$value['wx_type'] = $value['wx_type'] ? $value['wx_type'] : $value['wd_type'];
				$value['wx_car_num'] = $value['wx_car_num'] ? $value['wx_car_num'] : $value['wd_car_num'];
				$value['wx_car_engine'] = $value['wx_car_engine'] ? $value['wx_car_engine'] : $value['wd_car_engine'];*/
				if($value['distinction'] == 1){
					$data = $this->wxwfapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$value['wx_type'],$value['wx_car_num'],$value['wx_car_engine']);
					$data = $this->object2array($data);
					//日志
					logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLWFCX','openID'=>$_W['openid'],'地区(distinction)'=>$value['distinction'],'号牌类型'=>$value['wx_type'],'车牌号'=>$value['wx_car_num'],'发动机号'=>$value['wx_car_engine']), 'trace',$_GPC['m'].'_success_car_'.date('Ymd',time()));
					$typecar[$key]['YXQZ'] = $data['YXQZ'];
					$typecar[$key]['QZBFQZ'] = $data['QZBFQZ'];
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
					$typecar[$key]['ZT'] = $zt;
				}
			}
			/*echo '<pre>';
			var_dump($typecar);*/
		}
		//无锡绑定车辆
		if ($op == 'wx_car_post'){
			//判断用户最多绑定两辆车
			$wxcargnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));
			if($wxcargnum >= 2){
				echo 100;exit;
			}else{
				//判断是否被绑定
				$seldata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND wx_type=:wx_type AND wx_car_num=:wx_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>1,':wx_type'=>trim($_GPC['wx_type']),':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>1,':uniacid'=>$_W['uniacid']));
				if($seldata){
					echo 400;exit;
				}
			}
			$wxcar = $this->wxwfapi('CLBD','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['wx_type']),trim(strtoupper($_GPC['wx_car_num'])),trim($_GPC['wx_car_engine']));
			$wxcar = $this->object2array($wxcar);
			logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLBD','openID'=>$_W['openid'],'号牌类型'=>$value['wx_type'],'车牌号'=>$value['wx_car_num'],'发动机号'=>$value['wx_car_engine']), 'trace',$_GPC['m'].'_wx_car_post_'.date('Ymd',time()));
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
				//$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wx_car_num=:wx_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>1,':uid'=>$uid,':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>0,':uniacid'=>$_W['uniacid']));


				// 接口绑定成功后，直接删除以往记录！

				pdo_delete('vivawjw_user_bound_car',array("uniacid"=>$_W['uniacid'],"wx_car_num"=>trim($_GPC['wx_car_num'])));

				if($data['uid'] == 0) {
					logging_run(array('方法名'=>'无锡绑定','UID'=>$data['uid'],'openID'=>$_W['openid'],'号牌类型'=>$value['wx_type'],'车牌号'=>$value['wx_car_num'],'发动机号'=>$value['wx_car_engine']), 'trace',$_GPC['m'].'_UID=0_'.date('Ymd',time()));
					echo 700;exit;
				}
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
				/*$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wd_car_num=:wd_car_num AND bound=:bound AND uniacid=:uniacid',array(':distinction'=>0,':uid'=>$uid,':wd_car_num'=>trim($_GPC['wd_car_num']),':bound'=>0,':uniacid'=>$_W['uniacid']));
				if($boundcar){
					$wddata = pdo_update('vivawjw_user_bound_car',array('bound'=>1,'status'=>$data['status']),array('uid'=>$boundcar['uid'],'wd_car_num'=>$boundcar['wd_car_num']));
					if($wddata){
						echo json_encode($wdcar);exit;
					}
				}else{*/
				if($data['uid'] == 0) {
					logging_run(array('方法名'=>'外地绑定','UID'=>$data['uid'],'openID'=>$_W['openid'],'号牌类型'=>$value['wd_type'],'车牌号'=>$value['wd_car_num'],'发动机号'=>$value['wd_car_engine']), 'trace',$_GPC['m'].'_UID=0_'.date('Ymd',time()));
					echo 700;exit;
				}
					$wddata = pdo_insert('vivawjw_user_bound_car',$data);
					if($wddata){
						echo json_encode($wdcar);exit;
					}
				//}
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
			$data = $this->wxwfapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$_GPC['wx_type'],trim(strtoupper($_GPC['wx_car_num'])),trim(strtoupper($_GPC['wx_car_engine'])));
			$data = $this->object2array($data);
			//日志
			logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLBD','openID'=>$_W['openid'],'号牌类型'=>$_GPC['wx_type'],'车牌号'=>trim(strtoupper($_GPC['wx_car_num'])),'发动机号'=>trim(strtoupper($_GPC['wx_car_engine']))), 'trace',$_GPC['m'].'_wfcx_'.date('Ymd',time()));

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
			if($cardata[0]['distinction'] == 1){
				$carType0 = $cardata[0]['wx_type'];
				$carNum0 = $cardata[0]['wx_car_num'];
				$carEngine0 = $cardata[0]['wx_car_engine'];
			}elseif ($cardata[0]['distinction'] == 0){
				$carType0 = $cardata[0]['wd_type'];
				$carNum0 = $cardata[0]['wd_car_num'];
				$carEngine0 = $cardata[0]['wd_car_engine'];
			}
			$cararr = array(
				'hpzl' => $carType0,
				'hphm' => $carNum0,
				'fdjh' => $carEngine0,
				'czxm' => '未知',
				'sfz' => $sfzh
			);

			if($cardata[1]['distinction'] == 1){
				$carType1 = $cardata[1]['wx_type'];
				$carNum1 = $cardata[1]['wx_car_num'];
				$carEngine1 = $cardata[1]['wx_car_engine'];
			}elseif ($cardata[1]['distinction'] == 0){
				$carType1 = $cardata[1]['wd_type'];
				$carNum1 = $cardata[1]['wd_car_num'];
				$carEngine1 = $cardata[1]['wd_car_engine'];
			}
			$cararr1 = array(
				'hpzl' => $carType1,
				'hphm' => $carNum1,
				'fdjh' => $carEngine1,
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
				file_delete($filename);
			}else{
				$imgdata = file_get_contents(MODULE_ROOT."/template/resource/img/nopic.jpg");
				load()->func('file');
				file_write($filename, $imgdata);
				file_remote_upload($filename);
				file_delete($filename);
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
		//载入日志函数
		load()->func('logging');

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
		logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'DJSSJGCX','openID'=>$_W['openid'],'车牌号'=>$_GPC['wfcph'],'监控序号'=>$_GPC['wfjkxh'],'内容'=>$tsnr ,'状态'=>$dataapis['State']), 'trace',$_GPC['m'].'_shensu_'.date('Ymd',time()));

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
			echo "系统繁忙，请稍后再试！";
		}
	}

	//我的驾驶证
	public function doMobileUser_driving(){
		global $_W,$_GPC;
		/*if($_GPC['test'] != 1){
			die("<h1 style='width:80%;margin:0 auto;font-size:30px;'>系统升级，请稍后再试！</h1>");
		}*/
		//载入日志函数
		load()->func('logging');
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}

		/*$r = new Redis();
		$v = $r->connect('r-bp1fd9985c4f7234.redis.rds.aliyuncs.com',6379);
		$res = $r->auth('0qEFuasxAaqbT1c3');
		//$value = $r->get("Driver_ocJugjsllErxRFVPkxow5jZK_sr4");
		//2.判断老数据表，有两条取消绑定。一条插入新表
		$oldDriver = $r->get("Driver_ocJugjjaXo3cK4_oOPDjnVSKCXPA");
		$oldDriver = json_decode($oldDriver,true);
		var_dump($oldDriver);*/
		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $_W['member']['uid'];
		//1.判断新表有无数据，有显示，没有显示绑定页
		$typedriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid ORDER BY bound DESC',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
		if($typedriving){
			if($typedriving['bound'] == 1){
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
			}else{
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
			}
		}else{
			//调取老数据
			/*$r = new Redis();
			$v = $r->connect('r-bp1fd9985c4f7234.redis.rds.aliyuncs.com',6379);
			$res = $r->auth('0qEFuasxAaqbT1c3');
			//$value = $r->get("Driver_ocJugjsllErxRFVPkxow5jZK_sr4");
			$oldDriver = $r->get("Driver_".$_W['openid']);
			$oldDriver = json_decode($oldDriver,true);
			//如果有绑定计算个数
			if($oldDriver['status'] == 1){
				$new['uid'] = $_W['member']['uid'];
				$new['uniacid'] =  $_W['uniacid'];
				$new['status'] = 0;//绑定状态
	            $new['bound'] = 1;//绑定成功
	            $new['time'] = $oldDriver['dateline'];
	            $new['issel'] =1;
	            //老数据0为本地1为外地
	            $islocal = $oldDriver['islocal'];//本地0，外地1
	            $carnumber = $oldDriver['cardnumber'];//驾驶证
	            $driv_record = $oldDriver['recordnumber'];//档案编号
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
				$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
			}else{
				$oldUser = $r->get("Users_".$_W['openid']);
				$oldUser = json_decode($oldUser,true);
				//$oldUser = $this->object2array($oldUser);
				//121为绑定驾驶证，且有绑定,计算个数
				if($oldUser['FAttentionType'] == 121 && $oldUser['FStatus'] == 1){
					$new['uid'] = $_W['member']['uid'];
					$new['uniacid'] =  $_W['uniacid'];
					$new['status'] = 0;//绑定状态
		            $new['bound'] = 1;//绑定成功
		            $new['time'] = strtotime($oldUser['FUpdateTime']);
		            $new['issel'] =1;
		            //老数据0为本地1为外地
		            $islocal = $oldUser['FIsLocal'];//本地0，外地1
		            $carnumber = $oldUser['FDriverCardNumber'];//驾驶证
		            $driv_record = $oldUser['FDriverRecordNumber'];//档案编号
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
		            $op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
				}else{
					$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
				}
			}*/
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
		}
		if($op == 'success_driving'){
			if($typedriving){
				//无锡驾驶证
				$typedriving['wx_driv_num'] = $typedriving['wx_driv_num'] ? $typedriving['wx_driv_num'] : $typedriving['wd_driv_num'];
				$typedriving['wx_driv_record'] = $typedriving['wx_driv_record'] ? $typedriving['wx_driv_record'] : substr($typedriving['wd_driv_record'],-6);
				//var_dump($typedriving);
				$wxwfdata = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$typedriving['wx_driv_num'],$typedriving['wx_driv_record']);
				//$wxwfdata = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],'320223196504165739','700151');
				$wxwfdata = $this->object2array($wxwfdata);
				//日志
				logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'JSZQXBD','openID'=>$_W['openid'],'地区(distinction)'=>$typedriving['distinction'],'驾驶证号'=>$typedriving['wx_driv_num'],'档案编号'=>$typedriving['wx_driv_record']), 'trace',$_GPC['m'].'_success_driving_'.date('Ymd',time()));
				if ($wxwfdata) {
					$wxwfdata['ZT'] = str_split($wxwfdata['ZT'], 1);
					foreach ($wxwfdata['ZT'] as $k => $v){
						switch ($v) {
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
						$wxwfdata['ZT'][$k] = $zt;
					}
					$wxwfdata['ZT'] = implode('，', $wxwfdata['ZT']);
				}
			}
		}



		//无锡添加驾驶证
		if ($op == 'wx_drivpost'){
			//判断是否被绑定
			/*$wxdrivingnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$_W['member']['uid'],':bound'=>1));*/
			$wxdriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wx_driv_num=:wx_driv_num AND bound=:bound',array(':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>1));

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
				$bounddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE distinction=:distinction AND uid=:uid AND wx_driv_num=:wx_driv_num AND bound=:bound',array(':distinction'=>1,':uid'=>$_W['member']['uid'],':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>0));
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
			$wddriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wd_driv_num=:wd_driv_num AND bound=:bound ',array(':wd_driv_num'=>trim($_GPC['wd_driv_num']),':bound'=>1));
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
				$boundwddr = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE distinction=:distinction AND uid=:uid AND wd_driv_num=:wd_driv_num AND bound=:bound',array(':distinction'=>0,':uid'=>$_W['member']['uid'],':wd_driv_num'=>trim($_GPC['wd_driv_num']),':bound'=>0));
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
			$jbdrdata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id=:id',array(':id'=>$id));
			//判断无锡和外地，1为无锡，0为外地
			if ($jbdrdata['distinction'] == 1) {
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
			$jszh = trim(strtoupper($_GPC['jszh']));
			$dabh = trim(strtoupper($_GPC['dabh']));
			$datas = $this->wxwfapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],$jszh,$dabh);
			//$datas = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid']','320681199407246632','378927');
			$data = $this->object2array($datas);
			//日志
			logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'JSRWFCX','openID'=>$_W['openid'],'地区(distinction)'=>$typedriving['distinction'],'驾驶证号'=>$jszh,'档案编号'=>$dabh), 'trace',$_GPC['m'].'_jsrwfcx_'.date('Ymd',time()));

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
	//调取图片接口
	public function doMobileSeltp(){
		/*global $_W, $_GPC;
		//提交接口对比数据
		$dwbh = $_GPC['dwbh'];
		$data = $this->wxapi('LKTPCX','C81DD8605F0531F0B6C717D07A8979F4',$dwbh);
		$data = $this->object2array($data);
		$data = $data['LKKZ'];
		$data = base64_encode($data);
		echo $data;
		//echo '<img src="data:image/png;base64,'.$data.'"/>';*/

		global $_W, $_GPC;
		//提交接口对比数据
		$dwbh = $_GPC['dwbh'];
		$imgurl = tomedia('images/'.$_W['uniacid'].'/roadpic/'.$dwbh.'.png');
		$imginfo = get_headers($imgurl);
		$time = strtotime(str_replace("Last-Modified: ","",$imginfo[5]));
		if(!$time || TIMESTAMP - $time  > 120){
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

		echo $imgurl.'?'.$time;
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
							$turndown[$k] = '签名照片';
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

	//重新学习
	public function doMobileAgainStudy(){
		global $_W,$_GPC;
		$upData['status'] = 4;
		$upData['studyplan'] = 2;
		$whereData['uid'] = $_GPC['uid'];
		$whereData['uniacid'] = $_W['uniacid'];
		$againData = pdo_update('vivawjw_zxxx',$upData,$whereData);
		if($againData){
			$data = pdo_update('vivawjw_zxxx_video',array('status'=>1),array('uid'=>$_GPC['uid']));
			if($data){
				echo 200;exit;
			}else{
				echo 400;exit;
			}
		}else{
			echo 300;exit;
		}
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

	//绑定失败提交页面
	public function doMobileProBound(){
		global $_W,$_GPC;
		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');
		include $this->template('pro_bound');
	}
	//提交信息
	public function doMobileBoundInfo(){
		global $_W,$_GPC;
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = $_W['member']['uid'];
		$data['openid'] = $_W['openid'];
		$data['reason'] = trim($_GPC['reason']);

		$data['proof'] = trim($_GPC['proof']);
		$proof = trim($_GPC['proof']);
		$proof = explode(",",$proof);
		$foto=array();
		foreach($proof as $v){
			$DLres = $this -> downloadMedia($v);
			if(!is_array($DLres)){
				array_push($foto, $DLres);
			}
		}
		if($foto)$data['proof'] = implode(",",$foto);

		$data['time'] = time();
		$type = $_GPC['type'];
		//驾驶证
		if($type == 'q_7'){
			$data['bound_driver'] = trim(strtoupper($_GPC['bound_driver']));
			$data['bound_drnum'] = trim(strtoupper($_GPC['bound_drnum']));
			$selData = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_again_bound').' WHERE uniacid=:uniacid AND openid=:openid AND bound_driver=:bound_driver',array(':uniacid'=>$data['uniacid'],':openid'=>$data['openid'],':bound_driver'=>$data['bound_driver']));
			if($selData){
				echo 300;exit;
			}else{
				$data['state'] = 0;
				$data['status'] = 2;//待审核
				$data = pdo_insert('vivawjw_again_bound',$data);
				if($data){
					echo 200;exit;
				}else{
					echo 100;exit;
				}
			}
			//车辆
		}elseif($type == 'q_8'){
			$data['bound_cartype'] = trim(strtoupper($_GPC['bound_cartype']));
			$data['bound_car'] = trim(strtoupper($_GPC['bound_car']));
			$data['bound_carnum'] = trim(strtoupper($_GPC['bound_carnum']));
			$selData = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_again_bound').' WHERE uniacid=:uniacid AND openid=:openid AND bound_car=:bound_car',array(':uniacid'=>$data['uniacid'],':openid'=>$data['openid'],':bound_car'=>$data['bound_car']));
			if($selData){
				echo 300;exit;
			}else {
				$data['state'] = 1;
				$data['status'] = 2;//待审核
				$data = pdo_insert('vivawjw_again_bound', $data);
				if ($data) {
					echo 200;exit;
				} else {
					echo 100;exit;
				}
			}
		}
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
			$wxdriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
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
			$wddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
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

		//绑定反馈列表
		if ($op == 'pro_bound'){
			$pindex =max(1, intval($_GPC['page']));
			$psize = 10;
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_again_bound').' WHERE uniacid = :uniacid ',array(':uniacid' => $_W['uniacid']));
			$feeddata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_again_bound').' WHERE uniacid = :uniacid  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' =>$uniacid));
			$pager =pagination($total, $pindex, $psize);
		}
		//绑定反馈信息
		if ($op == 'bound_info'){
			$id = $_GPC['id'];
			$feedone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_again_bound').' WHERE id = :id ',array(':id'=>$id));
			$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');
			$feedonef = pdo_fetch('SELECT fanid,uid FROM '.tablename('mc_mapping_fans').' WHERE uid = :id ',array(':id'=>$feedone['uid']));
			$feedone['fanid']=$feedonef['fanid'];
			//echo $feedonef['fanid'];
			foreach ($cartype as $k => $v){
				if($feedone['bound_cartype'] == $v['carnum']){
					$cartype =  $v['cartype'].'('.$v['carnum'].')';
				}
			}
			$feedone['proof'] = explode(",",$feedone['proof']);
			$v = array();
			foreach($feedone['proof'] as $k){
				$type = strtolower(substr($k,strrpos($k,'.')+1));
				$img = array('jpeg','jpg','png');
				if(in_array($type, $img)){
					$v['imgdir'][] = $k;
				}

			}
			//var_dump($cartype);
		}
		//删除绑定反馈信息
		if($op == 'pro_bound_del'){
			$id = $_GPC['id'];
			$boundCardel = pdo_delete('vivawjw_again_bound',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($boundCardel){
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
	//产品反馈受理
	public function doWebFeedturndown(){
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

	//绑定反馈受理
	public function doWebBoundturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_again_bound',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}
	//强制解绑
	public function doWebQzjb(){
		global $_W,$_GPC;
		//载入日志函数
		load()->func('logging');
		$qzjbData = pdo_fetch('SELECT * FROM '.tablename('vivawjw_again_bound').' WHERE id=:id',array(':id'=>$_GPC['id']));
		if($qzjbData['state'] == 1){//车辆
			$cartype = trim($qzjbData['bound_cartype']);
			$carnum = trim(strtoupper($qzjbData['bound_car']));
			$carengine = substr(trim(strtoupper($qzjbData['bound_carnum'])),-6);
			$bdcar = $this->wxwfapi('CLQXBD','C81DD8605F0531F0B6C717D07A8979F4','QZJBOPENID',$cartype,$carnum,$carengine);
			$bdcar = $this->object2array($bdcar);
			if($bdcar['State'] == 0){
				//记录数组数据
				logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLQXBD','车辆类型'=>$cartype,'车牌号'=>$carnum,'发动机号'=>$carengine,'状态'=>$bdcar['State']), 'trace',$_GPC['m'].date('Ymd',time()));
				echo 200;exit;
			}else{
				//记录数组数据
				logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLQXBD','车辆类型'=>$cartype,'车牌号'=>$carnum,'发动机号'=>$carengine,'状态'=>$bdcar['State']), 'trace',$_GPC['m'].date('Ymd',time()));
				echo 100;exit;
			}
		}elseif($qzjbData['state'] == 0){//驾驶证
			$drivenum = trim(strtoupper($qzjbData['bound_driver']));
			$driverecord = substr(trim(strtoupper($qzjbData['bound_drnum'])),-6);
			$bddriver = $this->wxapi('JSZQXBD','C81DD8605F0531F0B6C717D07A8979F4','QZJBOPENID',$drivenum,$driverecord);
			$bddriver = $this->object2array($bddriver);
			if($bddriver['State'] == 0){
				logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'JSZQXBD','驾驶证号'=>$drivenum,'档案编号'=>$driverecord,'状态'=>$bddriver['State']), 'trace',$_GPC['m'].date('Ymd',time()));
				echo 200;exit;
			}else{
				logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'JSZQXBD','驾驶证号'=>$drivenum,'档案编号'=>$driverecord,'状态'=>$bddriver['State']), 'trace',$_GPC['m'].date('Ymd',time()));
				echo 100;
			}
		}else{
			echo 100;
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

		//print_r($wddataapi);die;

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
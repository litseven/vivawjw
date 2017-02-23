<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
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
			$warnlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_warn').' WHERE uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
			//不接收推送的消息存入库
			$warnstatus = pdo_fetchall('SELECT title_key FROM '.tablename('vivawjw_user_warn_status').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,':uniacid'=>$_W['uniacid']));
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
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $data['uid'];
		//车辆类型
		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');
		//根据UID判断是否有绑定1为绑定0没绑定//所有绑定的信息
		$typecar = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound AND uniacid=:uniacid',array(':uid'=>$uid,':bound'=>1,':uniacid'=>$_W['uniacid']));
		//后去绑定信息的违法记录
/*--------有效期和强制报废期------------*/
		foreach ($typecar as $key => $value){
			$data = $this->wxapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$value['wx_type'],$value['wx_car_num'],$value['wx_car_engine']);
			$data = $this->object2array($data);
			$value['YXQZ'] = $data['YXQZ'];
			$value['QZBFQZ'] = $data['QZBFQZ'];
		}
		//var_dump($typecar);

/*---------------------------------------------------------------------------*/
		if ($typecar){
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_car';
			//绑定车辆的数量；一个微信号最多绑定两辆车
			$bdcarnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$uid,':bound'=>1));
			//查询外地绑定车辆状态
			$wddatakey = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND distinction=:distinction AND bound=:bound',array(':uid'=>$uid,':distinction'=>0,':bound'=>1));
			$wddataapi = $this->wxapi('WDCLZCSQCX','C81DD8605F0531F0B6C717D07A8979F4');
			$wddataapi = $this->object2array($wddataapi);
			//echo $wddatakey['wd_car_num'];//绑定的外地车牌号码
			if($wddataapi['State'] == 0){
				foreach ($wddataapi['WDCLZCSQCXResult'] as $key => $value){
					$arr[$key]['WXH'] = $value['WXH'];
					$arr[$key]['HPHM'] = $value['HPHM'];
					$arr[$key]['ZCZT'] = $value['ZCZT'];
				}
				foreach ($arr as $k => $v) {
					//$wddatakey['wd_car_num']
					if (strpos($v['HPHM'],$wddatakey['wd_car_num']) !== false) {
						//var_dump($v);
						pdo_update('vivawjw_user_bound_car',array('status'=>$v['ZCZT']),array('uid'=>$uid,'distinction'=>0,'wd_car_num'=>$v['HPHM']));
					}
				}
				//测试用（访问一次更新数据库中数据，访问第二次更新页面显示状态）
				/*$arr = array(
					0=> array(
						'ZCZT' => 0,
						'HPHM' => '蒙D3H793'
					)
				);
				foreach ($arr as $k => $v) {
					//$wddatakey['wd_car_num']
					if (strpos($v['HPHM'], '蒙D3H793') !== false) {
						//var_dump($v);
						pdo_update('vivawjw_user_bound_car',array('status'=>$v['ZCZT']),array('uid'=>$uid,'distinction'=>0,'wd_car_num'=>$v['HPHM']));
					}
				}*/
			}
			foreach ($typecar as $key => &$value){
				$data = $this->wxapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$value['wx_type'],$value['wx_car_num'],$value['wx_car_engine']);
				$data = $this->object2array($data);
				$value['YXQZ'] = $data['YXQZ'];
				$value['QZBFQZ'] = $data['QZBFQZ'];
			}
			//var_dump($typecar);
		}else{
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_car';
		}

		//无锡绑定车辆
		if ($op == 'wx_car_post'){
			//判断用户最多绑定两辆车
			$wxcargnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$uid,':bound'=>1));
			if($wxcargnum >= 2){
				echo 100;exit;
			}else{
				//判断是否被绑定
				$seldata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND wx_car_num=:wx_car_num AND bound=:bound',array(':distinction'=>1,':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>1));
				if($seldata){
					echo 400;exit;
				}
			}

			$wxcar = $this->wxapi('CLBD','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',trim($_GPC['wx_type']),trim($_GPC['wx_car_num']),trim($_GPC['wx_car_engine']));
			$wxcar = $this->object2array($wxcar);
			//绑定失败State=1失败；0成功
			if($wxcar['State']){
				echo 300;exit;
			}else{
				//绑定保存到数据库中
				$data['wx_type'] = trim($_GPC['wx_type']);
				$data['wx_car_num'] = trim($_GPC['wx_car_num']);
				$data['wx_car_engine'] = trim($_GPC['wx_car_engine']);
				$data['status'] = $wxcar['State'];//绑定成功为0失败为1
				$data['distinction'] = 1;//1为无锡0为外地
				$data['bound'] = 1;//1为绑定0没绑定
				$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wx_car_num=:wx_car_num AND bound=:bound',array(':distinction'=>1,':uid'=>$uid,':wx_car_num'=>trim($_GPC['wx_car_num']),':bound'=>0));
				//同一uid绑定过
				if($boundcar){
					$wxdata = pdo_update('vivawjw_user_bound_car',array('bound'=>1,'status'=>$data['status']),array('uid'=>$boundcar['uid'],'wx_car_num'=>$boundcar['wx_car_num']));
					if($wxdata){
						echo json_encode($wxcar);exit;
					}
				}else{
					$wxdata = pdo_insert('vivawjw_user_bound_car',$data);
					if($wxdata){
						echo json_encode($wxcar);exit;
					}
				}
			}
		}
		//解除绑定无锡和外地车辆
		if ($op == 'del_bound_car'){
			$id = $_GPC['id'];
			$jbcardata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id=:id',array(':id'=>$id));
			//判断无锡和外地，1为无锡，0为外地
			if($jbcardata['distinction']){
				$qbcar = $this->wxapi('CLQXBD','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$jbcardata['wx_type'],$jbcardata['wx_car_num'],$jbcardata['wx_car_engine']);
				$qbcar = $this->object2array($qbcar);
			}else{
				//解除绑定外定车辆
				$qbcar = $this->wxapi('CLQXBD','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$jbcardata['wd_type'],$jbcardata['wd_car_num'],$jbcardata['wd_car_engine']);
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
		//外地绑定车辆
		if ($op == 'wd_car_post'){
			//判断是否被绑定
			$wdcargnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$uid,':bound'=>1));
			if($wdcargnum >= 2){
				echo 100;exit;
			}else{
				$seldata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND wd_car_num=:wd_car_num AND bound=:bound',array(':distinction'=>0,':wd_car_num'=>trim($_GPC['wd_car_num']),':bound'=>1));
				if($seldata){
					echo 400;exit;
				}
			}
			$wdcar = $this->wxapi('WDCLZCSQ','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',trim($_GPC['wd_type']),trim($_GPC['wd_car_num']),trim($_GPC['wd_car_engine']));
			$wdcar = $this->object2array($wdcar);
			//绑定失败State=1失败；0成功
			if($wdcar['State']){
				echo 300;exit;
			}else{
				$data['wd_type'] = trim($_GPC['wd_type']);
				$data['wd_car_num'] = trim($_GPC['wd_car_num']);
				$data['wd_car_engine'] = trim($_GPC['wd_car_engine']);
				$data['status'] = 2;//2为申请中；0 为申请成功;1为申请失败
				$data['distinction'] = 0;//1为无锡0为外地
				$data['bound'] = 1;//1为绑定0没绑定
				//同一uid绑定过
				$boundcar = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE distinction=:distinction AND uid=:uid AND wd_car_num=:wd_car_num AND bound=:bound',array(':distinction'=>0,':uid'=>$uid,':wd_car_num'=>trim($_GPC['wd_car_num']),':bound'=>0));
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
			}
		}
		//违法查询
		if($op == 'wfcx'){
			$data = $this->wxapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$_GPC['wx_type'],$_GPC['wx_car_num'],$_GPC['wx_car_engine']);
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
				if($zt == '正常'){
					echo 200;exit;
				}
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

		}

		include $this->template('user_car');
	}
	//申请信息处理
	public function doMobileAppealpost(){
		global $_W,$_GPC;
		$data['appeal_name'] = $_GPC['appeal_name'];
		$data['appeal_phone'] = $_GPC['appeal_phone'];
		$data['appeal_why'] = $_GPC['appeal_why'];
		$data['uniacid'] = $_W['uniacid'];
		$data['uid'] = $_W['member']['uid'];
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
		if($data['appeal_name'] && $data['appeal_phone'] && $data['appeal_why']){
			$insert_data = pdo_insert('vivawjw_wfcx',$data);
			if ($insert_data){
				echo 200;
			}
		}
	}
	//我的驾驶证
	public function doMobileUser_driving(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		//var_dump($_W['fans']['openid']);
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$data['uid'] = $_W['member']['uid'];
		$data['uniacid'] = $_W['uniacid'];
		$data['time'] = time();
		$uid = $data['uid'];
		//所有绑定的信息
		$typedriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$_W['uniacid'],':bound'=>1));
		if ($typedriving['distinction'] == '0'){
			//var_dump($typedriving);
			//外地驾驶证
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
			//绑定车辆的数量//distinction:1为无锡，0为外地,一个微信号只绑定一个驾驶证
			//查询外地绑定驾驶证状态
			//$wddatakey = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND distinction=:distinction AND bound=:bound',array(':uid'=>$uid,':distinction'=>0,':bound'=>1));
			$wddataapi = $this->wxapi('WDJSZZCSQCX','C81DD8605F0531F0B6C717D07A8979F4');
			$wddataapi = $this->object2array($wddataapi);
			//echo $wddatakey['wd_driv_num'];//绑定的外地车牌号码
			//var_dump($wddataapi);
			if($wddataapi['State'] == 0) {
				foreach ($wddataapi['WDJSZZCSQCXResult'] as $key => $value) {
					$arr[$key]['WXH'] = $value['WXH'];
					$arr[$key]['JSZH'] = $value['JSZH'];
					$arr[$key]['ZCZT'] = $value['ZCZT'];
				}
				foreach ($arr as $k => $v) {
					//$wddatakey['wd_car_num']
					if (strpos($v['JSZH'], $typedriving['wd_driv_num']) !== false) {
						//var_dump($v);
						pdo_update('vivawjw_user_bound_driving', array('status' => $v['ZCZT']), array('uid' => $uid, 'distinction' => 0, 'wd_driv_num' => $v['JSZH']));
					}
				}
			}
		}elseif($typedriving['distinction'] == 1){
			//无锡驾驶证
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'success_driving';
			//var_dump($typedriving);
			//$wxwfdata = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$typedriving['wx_driv_num'],$typedriving['wx_driv_record']);
			$wxwfdata = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs','320223196504165739','700151');
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
		}else{
			$op = trim($_GPC['op'])? trim($_GPC['op']): 'wx_driving';
		}
		//无锡添加驾驶证
		if ($op == 'wx_drivpost'){
			//判断是否被绑定
			$wxdrivingnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$_W['member']['uid'],':bound'=>1));
			$wxdriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wx_driv_num=:wx_driv_num AND bound=:bound',array(':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>1));
			if(!$uid){
				echo 300;exit;
			}
			if($wxdrivingnum){
				echo 400;exit;
			}
			if($wxdriving){
				echo 100;exit;
			}
			//访问接口
			$wxdriving = $this->wxapi('JSZBD','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',trim($_GPC['wx_driv_num']),trim($_GPC['wx_driv_record']));
			$wxdriving = $this->object2array($wxdriving);
			//绑定失败State=1失败；0成功
			if($wxdriving['State']){
				echo 300;exit;
			}else{
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
			}
		}
		//外地添加驾驶证
		if ($op == 'wd_drivpost'){
			//判断是否被绑定
			$wddrivingnum = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND bound=:bound',array(':uid'=>$_W['member']['uid'],':bound'=>1));
			$wddriving = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_user_bound_driving').' WHERE wx_driv_num=:wx_driv_num AND bound=:bound ',array(':wx_driv_num'=>trim($_GPC['wx_driv_num']),':bound'=>1));
			if($wddrivingnum){
				echo 400;exit;
			}
			if($wddriving){
				echo 100;exit;
			}
			//访问接口
			$wddriving = $this->wxapi('WDJSZZCSQ','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',trim($_GPC['wd_driv_num']),trim($_GPC['wd_driv_record']));
			$wddriving = $this->object2array($wddriving);
			//绑定失败State=1失败；0成功
			if($wddriving['State']){
				echo 300;exit;
			}else{
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
			}
		}
		//解除绑定驾驶证
		if ($op == 'del_bound_dr'){
			$id = $_GPC['id'];
			$jbdrdata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id=:id',array(':id'=>$id));
			//判断无锡和外地，1为无锡，0为外地
			if ($jbdrdata['distinction']) {
				$jbdr = $this->wxapi('JSZQXBD', 'C81DD8605F0531F0B6C717D07A8979F4', 'wxzhcs', $jbdrdata['wx_driv_num'], $jbdrdata['wx_driv_record']);
				$jbdr = $this->object2array($jbdr);
			}else{
				$jbdr = $this->wxapi('JSZQXBD', 'C81DD8605F0531F0B6C717D07A8979F4', 'wxzhcs', $jbdrdata['wd_driv_num'], $jbdrdata['wd_driv_record']);
				$jbdr = $this->object2array($jbdr);
			}
			//$jbdr['State'] = 0;//解绑成功
			if($jbdr['State']){
				$info = '失败';
			}elseif($jbdr['State'] == 0){
				$delbound = pdo_update('vivawjw_user_bound_driving',array('bound'=>$jbdr['State']),array('id'=>$id));
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
			$datas = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs',$jszh,$dabh);
			//$datas = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4','wxzhcs','320681199407246632','378927');
			$data = $this->object2array($datas);
			//var_dump($_GPC);
			if($data['State'] == 0){
				if($data['ZT'] == 'A'){
					echo 300;exit;
				}else{
					echo json_encode($datas);exit;
				}
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
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;
		$kcdata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_sgkc').' WHERE uid = :uid AND uniacid = :uniacid',array(':uid'=>$uid,'uniacid'=>$_W['uniacid']));
		include $this->template('user_dispose');
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
		$data = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');
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


		//var_dump($sclk);
		if($op == 'qxsclk'){
			$id = $_GPC['id'];
			$qxsclk = pdo_delete('vivawjw_lkcx_sc',array('id'=>$id));
			if($qxsclk){
				echo 200;exit;
			}
		}

		include $this->template('user_road');
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
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		/*---------------------------------------------------------------------------------------------------------------------------------*/
		$uid = $_W['member']['uid'];
		//$uid = $data['uid'] = 2;

		include $this->template('user_study');
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
			$wxlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//无锡绑定车辆信息
		if ($op == 'wx_car_info'){
			$id = $_GPC['id'];
			$wxone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE id = :id ',array(':id'=>$id));
		}
		//外地绑定车辆列表
		if ($op == 'wd_car'){
			$distinction = 0;
			$wdlist = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
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
			$wxdriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//无锡绑定驾驶证信息
		if ($op == 'wx_driv_info'){
			$id = $_GPC['id'];
			$wxdriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
		}

		//外地绑定驾驶证列表
		if ($op == 'wd_driving'){
			$distinction = 0;
			$wddriving = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uniacid =:uniacid AND distinction = :distinction',array(':uniacid'=>$uniacid,':distinction'=>$distinction));
		}
		//外地绑定驾驶证信息
		if ($op == 'wd_driv_info'){
			$id = $_GPC['id'];
			$wddriving = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE id = :id ',array(':id'=>$id));
		}


		//产品反馈
		if ($op == 'pro_feedback'){
			$feeddata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE uniacid = :uniacid',array(':uniacid'=>$_W['uniacid']));
		}
		//产品反馈信息
		if ($op == 'feedback_info'){
			$id = $_GPC['id'];
			$feedone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_feedback').' WHERE id = :id ',array(':id'=>$id));
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

}
?> 
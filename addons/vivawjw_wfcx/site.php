<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_wfcx/template/resource/');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_wfcx/template/resource/');
class vivawjw_wfcxModuleSite extends WeModuleSite
{

	public function doMobileTest(){
		$test = get_headers('http://wxjjtest.scienmedia.com/pros/attachment/images/1/wfimg/2017/03/3202009012476793.jpg');
		$tests = explode(':',$test['4']);

		var_dump(trim($tests[1]));
		if(trim($tests[1]) < 0){
			echo 0;
		}else{
			echo 1;
		}

	}
	/*
	 * 入口
	 */
	public function doMobileIllegal(){
		global $_W,$_GPC;
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$userid = $_W['member']['uid'];
		//var_dump($_W['openid']);
		$cartype = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_cartype').' ORDER BY id ASC');

		include $this->template('illegal');
	}
	//申请信息
	public function doMobileWxappeal(){
		global $_W,$_GPC;
		load()->func('tpl');
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'post';
		if (empty($_W['fans']['nickname'])) {
			mc_oauth_userinfo();
		}
		$userid = $_W['member']['uid'];

		include $this->template('wxappeal');
	}

	//验证是否绑定
	public function doMobileAppealverify(){
		global $_W,$_GPC;
		$carnum = $_GPC['carnum'];
		$uid = $_W['member']['uid'];
		if(substr($carnum,0,4) == '苏B'){
			$data = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND wx_car_num=:wx_car_num AND bound=:bound',array(':uid'=>$uid,':wx_car_num'=>$carnum,':bound'=>1));
			if($data){
				echo 200;exit;
			}else{
				echo 100;exit;
			}
		}else{
			$data = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND wd_car_num=:wd_car_num AND bound=:bound',array(':uid'=>$uid,':wd_car_num'=>$carnum,':bound'=>1));
			if($data){
				echo 200;exit;
			}else{
				echo 100;exit;
			}
		}


	}


	//申请信息处理
	public function doMobileAppealpost(){
		global $_W,$_GPC;


		$cou = pdo_fetchcolumn('SELECT count(*) FROM  '.tablename('vivawjw_wfcx').' WHERE uid=:uid and wfjkxh=:wfjkxh and wfcph=:wfcph',array(':uid'=>$_W['member']['uid'],':wfjkxh'=>$_GPC['wfjkxh'],':wfcph'=>$_GPC['wfcph']));
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
		$dataapi=$this->wxapiss($_W['openid'],$apitime,$_GPC['wfcph'],$_GPC['wfjkxh'],$_GPC['appeal_why']);
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

	//车辆违法查询接口
	public function doMobileApipostcl(){
		global $_W,$_GPC;
		//载入日志函数
		load()->func('logging');
		$data = $this->wxapi('CLWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['cartype']),trim(strtoupper($_GPC['carnum'])),trim(strtoupper($_GPC['enginenum'])));
		echo json_encode($data);
		logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'CLWFCX','车辆类型'=>$_GPC['cartype'],'车牌号'=>strtoupper($_GPC['carnum']),'发动机号'=>$_GPC['enginenum'],'openId'=>$_W['openid'],'UID'=>$_W['member']['uid']), 'trace',$_GPC['m']);

	}
	//驾驶人违法查询
	public function doMobileApipostjsr(){
		global $_W,$_GPC;
		//载入日志函数
		load()->func('logging');
		$data = $this->wxapi('JSRWFCX','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],trim($_GPC['drnunum']),trim($_GPC['filenum']));
		echo json_encode($data);
		logging_run(array('方法名'=>$_GPC['do'],'接口名'=>'JSRWFCX','驾驶证号'=>$_GPC['drnunum'],'档案编号'=>$_GPC['filenum'],'openId'=>$_W['openid'],'UID'=>$_W['member']['uid']), 'trace',$_GPC['m']);
		//var_dump($data);
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
		echo $imgurl;

	}
	//查询信息跳转支付接口
	public function doMobileWzfkapi(){
		global $_W,$_GPC;
		$uid = $_W['member']['uid'];
		$uniacid = $_W['uniacid'];
		$cardata = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_user_bound_car').' WHERE uid=:uid AND uniacid=:uniacid AND status=:status AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$uniacid,':status'=>0,':bound'=>1));
		$drivingdata = pdo_fetch('SELECT * FROM '.tablename('vivawjw_user_bound_driving').' WHERE uid=:uid AND uniacid=:uniacid AND status=:status AND bound=:bound',array(':uid'=>$uid,':uniacid'=>$uniacid,':status'=>0,':bound'=>1));
		if($cardata){

        }
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

			$a = array("openid"=>$_W['openid'],
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
			$url = 'http://192.168.11.58/WXWC/wcservice.asmx?wsdl';
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

	/*
	 * 后台管理
	 */
	//后台
	//申请信息管理
	public function doWebManage(){
		global $_W,$_GPC;
		$op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
		//获取页码
		$pindex =max(1, intval($_GPC['page']));
		$psize =10;
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid',array(':uniacid' => $_W['uniacid']));
		$list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid' => $_W['uniacid']));
		$pager =pagination($total, $pindex, $psize);
		if($op == 'view'){
			$dataone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_wfcx').' WHERE uniacid = :uniacid AND id = :id',array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
		}
		//删除数据
		if ($op == 'sub_delete'){
			$id = $_GPC['id'];
			$data = pdo_delete('vivawjw_wfcx',array('id'=>$id,'uniacid'=>$_W['uniacid']));
			if ($data){
				echo 200;exit;
			}
		}
		include $this->template('manage');
	}
	//申诉信息驳回
	public function doWebSsturndown(){
		global $_W,$_GPC;
		$id = $_GPC['id'];
		$turndown = $_GPC['turndown'];
		$status = $_GPC['status'];
		$sittime = time();
		$drdata = pdo_update('vivawjw_wfcx',array('turndown'=>$turndown,'status'=>$status,'sittime'=>$sittime),array('id'=>$id));
		if($drdata){
			echo 200;exit;
		}
	}

}
?> 
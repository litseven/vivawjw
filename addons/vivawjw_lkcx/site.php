<?php
//ini_set('display_errors', 0);
//error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_lkcx/template/resource/');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_lkcx/template/resource/');
	class Vivawjw_lkcxModuleSite extends WeModuleSite {

		public function doMobileRoadsel(){
			global $_W, $_GPC;
            $op = $op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
            $uid = $_W['member']['uid'];
            $sclk = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx_sc').' WHERE uid=:uid',array(':uid'=>$uid));
            //var_dump($sclk);
            //提交接口对比数据
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
            //var_dump($data);
            $arr = array(
                0 => array('name' => '梁溪区'),
                1 => array('name' => '滨湖区'),
                2 => array('name' => '新吴区'),
                3 => array('name' => '锡山区'),
                4 => array('name' => '惠山区'),
                5 => array('name' => '高架道路'),
                6 => array('name' => '高速公路'),
                7 => array('name' => '主要景区周边'),
                8 => array('name' => '灵山景区周边'),
                9 => array('name' => '鼋头渚景区周边'),
                10 => array('name' => '地铁3号线施工周边'),
            );
            $i = 0;
            $child = array();
            foreach ($arr as $k => $v) {

                foreach ($data as $key => $value){
                    if ($v['name'] == $value['ZGDID']) {
                        $child[$i] = $value;
                        $i++;
                    }
                }
                $arr[$k]['child'] = $child;
                unset($child);
            }
            //var_dump($arr);
            //查询
            if($op == 'search'){
                $keyword = $_GPC['keyword'];
                foreach ($data as $key => $value){
                    //echo $key.'---'.$value['DWBH'].'---'.$value['WZMS'].'<br/>';
                    $arr[$key]['DWBH'] = $value['DWBH'];
                    $arr[$key]['WZMS'] = $value['WZMS'];
                }
                foreach ($arr as $kk => $vv) {
                    if (strpos($vv['WZMS'],$keyword) !== false) {
                        $return[$kk] = $vv;
                    }
                }
            }


            //echo '<pre>';
            //var_dump($keyword);
			include $this->template('roadsel');
        }

        //收藏路况
        public function doMobileSclk(){
            global $_W, $_GPC;
            $data['uid'] = $_W['member']['uid'];
            if(!$data['uid']){
                exit;
            }
            $data['dwbh'] =  trim($_GPC['dwbh']);
            $data['wzms'] =  trim($_GPC['wzms']);
            if($data['dwbh']){
                $sclk = pdo_insert('vivawjw_lkcx_sc',$data);
            }
            if($sclk){
                echo 200;exit;
            }
        }
        //取消收藏路况
        public function doMobileQxsclk(){
            global $_W, $_GPC;
            $uid = $_W['member']['uid'];
            $dwbh =  trim($_GPC['dwbh']);
            $wzms =  trim($_GPC['wzms']);
            if($dwbh){
                $qxsclk = pdo_delete('vivawjw_lkcx_sc',array('uid'=>$uid,'dwbh'=>$dwbh,'wzms'=>$wzms));
            }
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
        //搜索关键字查询
        public function doMobileSearchkey(){
            global $_W, $_GPC;
            $keyword = $_GPC['keyword'];
            $data = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');
            $data = $this->object2array($data);
            foreach ($data['ZGDLKResult'] as $key => $value){
                //echo $key.'---'.$value['DWBH'].'---'.$value['WZMS'].'<br/>';
                $arr[$key]['DWBH'] = $value['DWBH'];
                $arr[$key]['WZMS'] = $value['WZMS'];
            }
            foreach ($arr as $k => $v) {
                if (strpos($v['WZMS'], $keyword) !== false) {
                    $return[$k] = $v;
                }
            }
            //echo '<pre>';
            //var_dump($return);
            //var_dump($arr);
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
                print_r($e);
            }
        }
        //提交路况信息
		public function doMobileRoadpost(){
			global $_W,$_GPC;
            $data['uid'] = trim($_GPC['uid']);
            $data['uniacid'] = $_W['uniacid'];
            $data['pictinfo'] = trim($_GPC['pict_info']);
			$data['proof'] = trim($_GPC['proof']);
            $data['time'] = time();
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
            $rodata = pdo_insert('vivawjw_lkcx',$data);
            if ($rodata){
                echo 200;exit;
            }


        }

        //地图
        public function doMobileSearchMap(){
            global $_W,$_GPC;

            include $this->template('wxmap');
        }

        public function downloadMedia($mediaId,$type="image"){
            $media = array("type"=>$type,"media_id"=>$mediaId);
            $account_api = WeAccount::create();
            return $account_api->downloadMedia($media);
        }
		
	    //后台
		public function doWebManage() {
			global $_W, $_GPC;
            $op =empty($_GPC['op'])? 'list' : trim($_GPC['op']);
            if($op == 'list'){
                $condition['uniacid'] = ':uniacid';
                $params[':uniacid'] =$_W['uniacid'];
                $_GPC['status'] = isset($_GPC['status'])?$_GPC['status']:2;
                $status =intval($_GPC['status']);
                $params[':status'] = $status;
                $pindex =max(1, intval($_GPC['page']));
                $psize =10;
                $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vivawjw_lkcx'). ' WHERE uniacid = :uniacid AND status = :status', $params);
                $list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx').' WHERE uniacid = :uniacid AND status = :status  ORDER BY id desc LIMIT '.($pindex - 1) * $psize.','.$psize,$params);
                $pager =pagination($total, $pindex, $psize);
            }
            if($op == 'view'){
                $id =intval($_GPC['id']);
                $quhua =pdo_fetchall('SELECT * FROM ' . tablename('vivawjw_lkcx') . " where uniacid = :uniacid",array(":uniacid"=>$_W['uniacid'])); //' WHERE ' . $condition
                $info =pdo_fetch('SELECT a.*,b.fanid FROM ' . tablename('vivawjw_lkcx'). ' as a left join ' . tablename('mc_mapping_fans'). ' as b on (a.uid = b.uid)  WHERE a.uniacid = :aid AND a.id = :id', array(':aid' => $_W['uniacid'], ':id' => $id));
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
            if($op == 'status'){
                $id =intval($_GPC['id']);
                $data['status'] = intval($_GPC['status']);
                $data['turndown'] = trim($_GPC['failreason']);
                $data['sittime'] = time();
                if($data['status'] == 1){
                    $data = pdo_update('vivawjw_lkcx', array('status'=>1,'sittime' =>time()), array('uniacid' => $_W['uniacid'], 'id' => $id));
                    echo 200;exit;
                }
                if($data['status'] == 3){
                    $data = pdo_update('vivawjw_lkcx', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
                    echo 300;exit;
                }

            }

            include $this->template('manage');
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
        public function doMobileLkzb(){
            global $_W, $_GPC;
            $data = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkzb'));
            echo json_encode($data);
        }*/

    }
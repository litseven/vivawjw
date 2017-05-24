<?php
//if($_GET['test']!=1){
//echo "<h1 style='color:#f90;margin-top:20%;text-align:center'>路况信息升级中，请稍后访问！</h1>";
//die;}
/*ini_set('display_errors', 0);
error_reporting(E_ALL);*/
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_lkcx/template/resource/');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/vivawjw_lkcx/template/resource/');

	class Vivawjw_lkcxModuleSite extends WeModuleSite {

//主路况接口
    public function doMobileGaodeMap(){
        global $_GPC;
        load()->func('logging');
        $key = 'GaodeKey!427';
        $sign = strtoupper(md5($key));
        if($_GPC['sign'] != $sign){
            $return['errno'] = 1;//sign不正确
            $return['errormsg'] = 'Access Denied!';
            exit(json_encode($return));
        }
        $mapData = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');
        $mapData = $this->object2array($mapData);
        logging_run(array('sign'=>$_GPC['sign'],'state'=>$mapData['State']), 'trace','GaodeMap'.date('Ymd',time()));
        exit(json_encode($mapData));

    }

    //图片接口
    public function doMobileGaodeImg(){
        global $_GPC;
        load()->func('logging');
        $dwbh = $_GPC['dwbh'];
        $key = 'GaodeKey!427';
        $sign = strtoupper(md5($key.$dwbh));
        if($_GPC['sign'] != $sign){
            $return['errno'] = 1;//sign不正确
            $return['errormsg'] = 'Access Denied!';
            echo  json_encode($return);exit;
        }
        $ImgData = $this->wxapi('LKTPCX','C81DD8605F0531F0B6C717D07A8979F4',$dwbh);
        $ImgData = $this->object2array($ImgData);
        $ImgData['LKKZ'] = base64_encode($ImgData['LKKZ']);
        logging_run(array('sign'=>$_GPC['sign'],'dwbh'=>$_GPC['dwbh'],'state'=>$ImgData['State']), 'trace','GaodeImg'.date('Ymd',time()));
        exit(json_encode($ImgData));
    }



    /*public function doMobileGaoTest(){
        global $_W, $_GPC;
        load()->func('communication');
        $dwbh =  121;
        $key = 'GaodeKey!427';
        $sign = strtoupper(md5($key));
        $data = ihttp_post('http://wxjjtest.scienmedia.com/pros/app/index.php?i=1&c=entry&do=GaodeMap&m=vivawjw_lkcx&sign='.$sign.'&dwbh='.$dwbh);
        echo '<pre>';
        var_dump($data['content']);
    }*/














		public function roadareaCache(){ // 5mins 缓存  by zhangwei
			$roadareaCache = cache_load('roadareaCache');
			//cache_delete('roadareaCache');
			if(!$roadareaCache || TIMESTAMP-$roadareaCache['creattime']>(1800)){
						$roadareaCache['data'] = $this->wxapi('ZGDLKCX','C81DD8605F0531F0B6C717D07A8979F4');
						$roadareaCache['creattime'] = TIMESTAMP;
						cache_write('roadareaCache',$roadareaCache);
				}
			return $roadareaCache['data'];
        }

        //缓存图片
        public function doMobileImgcache(){
            global $_W, $_GPC;
            $data = $this->roadareaCache();
            $data = $this->object2array($data);
            $data = $data['ZGDLKResult'];
            foreach ($data as $key => $value){
                /*echo '<pre>';
                var_dump($key.'-'.$value['DWBH']);*/
                $dwbh = $value['DWBH'];
                $data = $this->wxapi('LKTPCX','C81DD8605F0531F0B6C717D07A8979F4',$dwbh);
                $data = $this->object2array($data);
                $data = $data['LKKZ'];
                //$data = base64_encode($data);

                load()->func('file');
                $filename = 'images/'.$_W['uniacid'].'/roadpic/'.$dwbh.'.png';

                file_write($filename, $data);
                file_remote_upload($filename);
            }
            //echo microtime(true) - $_SERVER['REQUEST_TIME'] . 's';
        }



		public function doMobileRoadsel(){
			global $_W, $_GPC;
            $op = $op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
            $uid = $_W['member']['uid'];
            $sclk = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx_sc').' WHERE uid=:uid',array(':uid'=>$uid));
            //var_dump($sclk);
            //提交接口对比数据
            $data = $this->roadareaCache();



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

			$scien_temp = $scien_arr = array();
			foreach($data as $v){$scien_temp[$v['ZGDName']] = NULL;}
			foreach($scien_temp as $k=>$v){array_push($scien_arr,array('name'=>$k));}
			$arr = $scien_arr;
//            $arr = array(
//                0 => array('name' => '梁溪区'),
//                1 => array('name' => '滨湖区'),
//                2 => array('name' => '新吴区'),
//                3 => array('name' => '锡山区'),
//                4 => array('name' => '惠山区'),
//                5 => array('name' => '高架道路'),
//                6 => array('name' => '高速公路'),
//                7 => array('name' => '主要景区周边'),
//                8 => array('name' => '灵山景区周边'),
//                9 => array('name' => '鼋头渚景区周边'),
//                10 =>array('name' => '地铁3号线施工周边'),
//                11 =>array('name' => '无锡马拉松周边重要路口'),
//            );

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
            // var_dump($arr);
            // echo '<pre>';
            // print_r($arr);
            // echo '</pre>';
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

			include $this->template('roadsel');
        }


            public function doMobileRoadselzfb(){
            global $_W, $_GPC;
            $op = $op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
            $uid = $_W['member']['uid'];
            $sclk = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx_sc').' WHERE uid=:uid',array(':uid'=>$uid));
            //var_dump($sclk);
            //提交接口对比数据
            $data = $this->roadareaCache();



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

            $scien_temp = $scien_arr = array();
            foreach($data as $v){$scien_temp[$v['ZGDName']] = NULL;}
            foreach($scien_temp as $k=>$v){array_push($scien_arr,array('name'=>$k));}
            $arr = $scien_arr;

//            $arr = array(
//                0 => array('name' => '梁溪区'),
//                1 => array('name' => '滨湖区'),
//                2 => array('name' => '新吴区'),
//                3 => array('name' => '锡山区'),
//                4 => array('name' => '惠山区'),
//                5 => array('name' => '高架道路'),
//                6 => array('name' => '高速公路'),
//                7 => array('name' => '主要景区周边'),
//                8 => array('name' => '灵山景区周边'),
//                9 => array('name' => '鼋头渚景区周边'),
//                10 =>array('name' => '地铁3号线施工周边'),
//                11 =>array('name' => '无锡马拉松周边重要路口'),
//            );

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
            // var_dump($arr);
            // echo '<pre>';
            // print_r($arr);
            // echo '</pre>';
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

            include $this->template('roadsel_zfb');
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


        //调取图片接口
        public function doMobileSeltp_new(){ // 每120秒生成图片上传至远程文件夹
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
        //搜索关键字查询
        public function doMobileSearchkey(){
            global $_W, $_GPC;
            $keyword = $_GPC['keyword'];
            $data = $this->roadareaCache();
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

        //提交路况信息
        public function doMobileRoadpost(){
            global $_W,$_GPC;

            $data['uid'] = $_W['member']['uid'];
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

            $img=explode(',', $data['proof']);

            if($img[0]!=""){

                $img[0] = file_get_contents('http://file.oss.scienmedia.com/'.$img[0]);
                $img[0]=base64_encode($img[0]);
            }

            if($img[1]!=""){

                $img[1] = file_get_contents('http://file.oss.scienmedia.com/'.$img[1]);
                $img[1]=base64_encode($img[1]);
            }
            if($img[2]!=""){

                $img[2] = file_get_contents('http://file.oss.scienmedia.com/'.$img[2]);
                $img[2]=base64_encode($img[2]);
            }
            $dataApi = $this->wxapi('JTLKBL','C81DD8605F0531F0B6C717D07A8979F4',$_W['openid'],time(),date('Y-m-d H:i',time()),$data['pictinfo'],$img[0],$img[1],$img[2]);
            $dataApi=$this->object2array($dataApi);
            if($dataApi['State'] == 0){
                $rodata = pdo_insert('vivawjw_lkcx',$data);
                if ($rodata){
                    echo 200;exit;
                }else{
                    echo 300;exit;
                }
            }else{
                echo 300;exit;
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
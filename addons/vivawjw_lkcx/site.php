<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
	class Vivawjw_lkcxModuleSite extends WeModuleSite {

		public function doMobileRoadsel(){
			global $_W, $_GPC;
            $op = $op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
            if (empty($_W['fans']['nickname'])) {
                mc_oauth_userinfo();
            }
            $user = $_W['member']['uid'];

			include $this->template('roadsel');
			}

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
                $list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_lkcx').' WHERE uniacid = :uniacid AND status = :status  ORDER BY sittime desc LIMIT '.($pindex - 1) * $psize.','.$psize,$params);
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

    }
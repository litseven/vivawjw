<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
//define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/pros/addons/vivawjw_sgkcl/template/resource/');
	class Vivawjw_sgkclModuleSite extends WeModuleSite {


		public function doMobileAccident(){
			global $_W, $_GPC;
            $op = $op =trim($_GPC['op'])? trim($_GPC['op']): 'start';
            if (empty($_W['fans']['nickname'])) {
                mc_oauth_userinfo();
            }
            $userid = $_W['member']['uid'];
            //报警编号
            $renum = $_GPC['renum'];
            $lpaddr = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_sgkc_addr'));
            if($op == 'lp'){
                $id = $_GPC['id'];
                $lpaddrone = pdo_fetch('SELECT * FROM '.tablename('vivawjw_sgkc_addr').' WHERE uniacid=:uniacid AND id=:id',array(':uniacid'=>$_W['uniacid'],':id'=>$id));
                $data = array(
                    'title' => $lpaddrone['title'],
                    'addr' => $lpaddrone['address'],
                    'mobile' => $lpaddrone['mobile']
                );
                echo json_encode($data);exit;
            }
			include $this->template('accident');
			}

		public function doMobilePostacc(){
			global $_W,$_GPC;
			$data['uid'] =  $_W['member']['uid'];
			$data['uniacid'] = $_W['uniacid'];
			$data['accaddr'] = trim($_GPC['accaddr']);
			$data['accname'] = trim($_GPC['accname']);
			$data['accothername'] = trim($_GPC['accothername']);
			$data['accnum'] = trim($_GPC['accnum']);
			$data['accothernum'] = trim($_GPC['accothernum']);
			$data['accotherphone'] = trim($_GPC['accotherphone']);
			$data['accserve'] = trim($_GPC['accserve']);
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
			$data['recordnum'] = 'NO-'.time();
			$data['acctime'] = time();
			$accadd = pdo_insert("vivawjw_sgkc",$data);
                if ($accadd){
                        echo $data['recordnum'];
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
                $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vivawjw_sgkc'). ' WHERE uniacid = :uniacid AND status = :status', $params);
                $list = pdo_fetchall('SELECT * FROM '.tablename('vivawjw_sgkc').' WHERE uniacid = :uniacid AND status = :status  ORDER BY sittime desc LIMIT '.($pindex - 1) * $psize.','.$psize,$params);
                $pager =pagination($total, $pindex, $psize);
            }
            if($op == 'view'){
                $id =intval($_GPC['id']);
                $quhua =pdo_fetchall('SELECT * FROM ' . tablename('vivawjw_sgkc') . " where uniacid = :uniacid",array(":uniacid"=>$_W['uniacid'])); //' WHERE ' . $condition
                $info =pdo_fetch('SELECT a.*,b.fanid FROM ' . tablename('vivawjw_sgkc'). ' as a left join ' . tablename('mc_mapping_fans'). ' as b on (a.uid = b.uid)  WHERE a.uniacid = :aid AND a.id = :id', array(':aid' => $_W['uniacid'], ':id' => $id));
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
                    $data = pdo_update('vivawjw_sgkc', array('status'=>1,'sittime' =>time()), array('uniacid' => $_W['uniacid'], 'id' => $id));
                    echo 200;exit;
                }
                if($data['status'] == 3){
                    $data = pdo_update('vivawjw_sgkc', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
                    echo 300;exit;
                }

            }

            include $this->template('manage');
        }

    }
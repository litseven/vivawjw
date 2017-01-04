<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);
defined('IN_IA') or exit('Access Denied');
include("common.php");
define('S_URL', 'http://wxjj.scienmedia.com/addons/'.$_GET["m"].'/template/resource');


	class Vivawjwtakefoto_zmnModuleSite extends WeModuleSite {
	
		
		
		public function doMobileTakefoto(){
			global $_W, $_GPC;
			include $this->template('takefoto');   
			}

		public function doMobilePostwz(){
			global $_W,$_GPC;
			checkauth();
			$uid = $_W['member']['uid'];
			$data['uid'] = $uid;
			$data['uniacid'] = $_W['uniacid'];
			$data['proof'] = trim($_GPC['proof']);
			$proof = trim($_GPC['proof']);
			$proof = explode(",",$proof);
			$foto=array();
			foreach($proof as $v){
                $DLres = downloadMedia($v);
                    if(!is_array($DLres)){
                            array_push($foto, $DLres);
                        }
                    }
			if($foto)$data['proof'] = implode(",",$foto);
			$data['memo'] = trim($_GPC['memo']);
			$data['addtime'] = date("Y-m-d H:i:s",time());
			pdo_insert("vivawjwtakefoto_zmn",$data);
			//mc_scien_ucitem_log("交通违章",pdo_insertid(),"随手拍");
			echo 200;
			}


		
	
		public function doWebWzlist() { 
			global $_W, $_GPC;
			$op =empty($_GPC['op'])? 'list' : trim($_GPC['op']); 
		if($op == 'list'){
			$scienpage = intval($_GPC['page']) ? intval($_GPC['page']) : isetcookie("__global_zmnlist_page", $sid, 86400 * 7);
			
			if(intval($_GPC['page'])){
				$scienpage = intval($_GPC['page']);
				isetcookie("__global_zmnlist_page", $scienpage, 86400 * 7);
				}
			else{
				$scienpage = intval($_GPC['__global_zmnlist_page']); 
				}	
			
		
			$condition =' uniacid = :aid';
			$params[':aid'] =$_W['uniacid'];
			if(!empty($_GPC['keyword'])){
				$condition .= " AND (tel LIKE '%{$_GPC['keyword']}%' or realname LIKE '%{$_GPC['keyword']}%'  )";
			}
			
			$_GPC['status'] = isset($_GPC['status'])?$_GPC['status']:0;
			$status =intval($_GPC['status']);
				$condition .= ' AND status = :stu';
				$params[':stu'] =$status;
		
		
			$pindex =max(1, $scienpage);
			$psize =20;
			$total =pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vivawjwtakefoto_zmn'). ' WHERE ' . $condition, $params);
			$lists =pdo_fetchall('SELECT * FROM ' . tablename('vivawjwtakefoto_zmn'). ' WHERE ' . $condition . ' ORDER BY addtime DESC LIMIT '.($pindex - 1)* $psize.','.$psize, $params);
			
			
			$pager =pagination($total, $pindex, $psize);
		}
		
		if($op == 'view'){
		

			$id =intval($_GPC['id']);
			$quhua =pdo_fetchall('SELECT * FROM ' . tablename('vivawjwtakefoto_quhua') . " where uniacid = :uniacid",array(":uniacid"=>$_W['uniacid'])); //' WHERE ' . $condition
			$info =pdo_fetch('SELECT a.*,b.fanid FROM ' . tablename('vivawjwtakefoto_zmn'). ' as a left join ' . tablename('mc_mapping_fans'). ' as b on (a.uid = b.uid)  WHERE a.uniacid = :aid AND a.id = :id', array(':aid' => $_W['uniacid'], ':id' => $id));
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
		
			//print_r($v);
		
		}
		
		
		if($op == 'status'){
			
			$id =intval($_GPC['id']);
			
			$duetime = pdo_fetchcolumn("select duetime from ".tablename("vivawjwtakefoto_zmn") ."where id = $id");
			if($duetime=='0000-00-00 00:00:00')$data['duetime']=date("Y-m-d H:i:s",time());
			$data['status'] = intval($_GPC['status']);
			$data['failreason'] = trim($_GPC['failreason']);
			$data['jdsbh'] = trim($_GPC['jdsbh']);
			
			if($data['status']==6){
			pdo_update('vivawjwtakefoto_zmn', array("duetime"=>date("Y-m-d H:i:s",time())), array('uniacid' => $_W['uniacid'], 'id' => $id));
			exit(json_encode(array("status"=>200)));
				}

			pdo_update('vivawjwtakefoto_zmn', $data, array('uniacid' => $_W['uniacid'], 'id' => $id));
				message("更新状态成功", referer(), "success");
			}	
			
		
		
		
			include $this->template('wzlist');   
			}
	
	
}
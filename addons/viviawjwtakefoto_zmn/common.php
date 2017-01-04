<?php
function getstationJSON(){
global $_W;

	$data = pdo_fetchall("select zoon from ". tablename('wjw_station'). " where uniacid = {$_W['uniacid']} group by zoon ");
	$jsonarr = array();
	foreach($data as $v){
		
		$sta = pdo_fetchall("select station from ". tablename('wjw_station'). " where zoon = '{$v['zoon']}' and uniacid = {$_W['uniacid']} ");
		$sub = array();
		foreach($sta as $vv){
			
			array_push($sub,$vv['station']);
			}
		array_push($jsonarr,array("zoon"=>$v['zoon'],"sub"=>$sub));
		}
	
	
	return json_encode($jsonarr);
	
	}

function getguardroomJSON(){
global $_W;

	$data = pdo_fetchall("select zoon from ". tablename('wjw_station'). " where uniacid = {$_W['uniacid']} group by zoon ");
	$jsonarr = array();
	foreach($data as $v){
		
		$sta = pdo_fetchall("select station from ". tablename('wjw_station'). " where zoon = '{$v['zoon']}' and uniacid = {$_W['uniacid']} ");
		$sub = array();
		foreach($sta as $vv){
			
			  $gr = pdo_fetchall("select id,grname from ". tablename('wjw_guardroom'). " where policestation = '{$vv['station']}' and uniacid = {$_W['uniacid']} ");
			  $thirub = array();
			  foreach($gr as $vvv){
				  array_push($thirub,$vvv);
				  }
				$subarray = array('station'=>$vv['station'],'guardroom'=>$thirub);
			array_push($sub,$subarray);
			}
		array_push($jsonarr,array("zoon"=>$v['zoon'],"sub"=>$sub));
		}
	
	//print_r($jsonarr);exit();
	return json_encode($jsonarr);
	
	}
 


function downloadMedia($mediaId,$type="image"){
$media = array("type"=>$type,"media_id"=>$mediaId);	
$account_api = WeAccount::create();
return $account_api->downloadMedia($media);
	}
	
	
function simplest_xml_to_array($xmlstring) {
    return json_decode(json_encode((array) simplexml_load_string($xmlstring)), true);
}	
?>
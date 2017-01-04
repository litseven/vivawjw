<?php
/**
 * 查找UME模块微站定义
 *
 * @author litseven
 * @url 
 */
defined('IN_IA') or exit('Access Denied');

class Site_umeModuleSite extends WeModuleSite {

	public function doMobileIndexx() {
		//这个操作被定义用来呈现 功能封面
        global $_W,$_GPC;
        load()->func('tpl');
        //print_r($url_get);
        include $this->template('indexx');
	}


	public function doMobilePostgb(){
        global $_W,$_GPC;
        $data['address'] = $_GPC['address'];
        $data['range'] = !empty($_GPC['range']) ? $_GPC['range'] : 1000;
        $data['num'] = !empty($_GPC['num']) ? $_GPC['num'] :3;
        $data = pdo_update('sit_ume',$data,array('start'=>1));
        if ($data) {
            echo json_encode(array('status' => 200));
        }else{
            echo json_encode(array('status'=>300));
        }

    }
	public function doWebGuanli() {
		//这个操作被定义用来呈现 管理中心导航菜单
        //echo 45646;
        include $this->template('guanli');
	}

}
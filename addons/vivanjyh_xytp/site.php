<?php
/**
 * vivanjyh_xytp模块微站定义
 *
 * @author litseven
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/resource/');
class Vivanjyh_xytpModuleSite extends WeModuleSite {

    //首页
    public function doMobileVote(){
        global $_W,$_GPC;
        $op =trim($_GPC['op'])? trim($_GPC['op']): 'jingli';
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        /*---------------------------------------------------------------------------------------------------------------------------------*/
        $userid = $_W['member']['uid'];
        //$uid = $data['uid'] = 3;

        include $this->template('vote');
    }

    //列表
	public function doMobileVotelist() {
        global $_W,$_GPC;
        $op = trim($_GPC['op'])? trim($_GPC['op']): 'list';
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        /*---------------------------------------------------------------------------------------------------------------------------------*/
        $uid = $_W['member']['uid'];
        //$uid = $data['uid'] = 3;
        //1.中支及直属支行;2.支行及部门;3.客户经理

        if($op == 'list'){
            $datalist = pdo_fetchall('SELECT * FROM '.tablename('vivanjyh_xytp').' WHERE uniacid=:uniacid',array(':uniacid'=>$_W['uniacid']));
            foreach ($datalist as &$value){
                $value['vote'] = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivanjyh_xytp_num').' WHERE  numid = :numid',array(':numid'=>$value['numid']));
            }
        }

        //搜索
        if($op == 'search'){
            $numid = trim($_GPC['numid']);
            $seadata = pdo_fetch('SELECT * FROM '.tablename('vivanjyh_xytp').' WHERE uniacid=:uniacid AND numid = :numid',array(':uniacid'=>$_W['uniacid'],':numid'=>$numid));
            $vote = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivanjyh_xytp_num').' WHERE  numid = :numid',array(':numid'=>$numid));
            $seadata['vote'] = $vote;
            //var_dump($seadata);
            echo json_encode($seadata);exit;
        }


        include $this->template('votelist');
	}
	//提交投票
    public function doMobileVotepost(){
        global $_W,$_GPC;
        $uid = $_GPC['uid'];
        $check_val = $_GPC['check_val'];
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $start = mktime(0, 0, 0, $month, $day, $year);
        $end = mktime(23, 59, 59, $month, $day, $year);
        //查找是有有投票信息
        $selde = pdo_fetchall('SELECT degree FROM '.tablename('vivanjyh_xytp_num').' WHERE uid = :uid AND sittime>=:start AND sittime<=:end ORDER BY  degree ASC LIMIT 1',array(':uid'=>$uid, ':start' => $start, ':end' => $end));
        //如果有次数degree-1
        if ($selde) {
            $degree = $selde[0]['degree'] - 1;
        } else {
            //如果没有初始值为2
            $degree = 2;
        }
        //次数用完（0-1）
        if ($degree == -1){
            echo -1;exit;
        }
        //插入数据
        foreach ($check_val as $val){
            $indata = array(
                'uniacid' => $_W['uniacid'],
                'uid' => $uid,
                'numid' => $val,
                'degree' => $degree,
                'sittime' => time()
            );
            $data = pdo_insert('vivanjyh_xytp_num', $indata);
        }
        if ($data) {
            echo $degree;exit;
        }
    }
    //后台
	public function doWebAdmin() {
		//这个操作被定义用来呈现 规则列表
        global $_W,$_GPC;
        $_GPC['columns'] = isset($_GPC['columns'])?$_GPC['columns']:1;
        $columns = intval($_GPC['columns']);
        $pindex =max(1, intval($_GPC['page']));
        $psize =10;
        $total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('vivanjyh_xytp'). ' WHERE uniacid = :uniacid AND columns = :columns',array(':uniacid'=>$_W['uniacid'],':columns'=>$columns));
        $list = pdo_fetchall('SELECT * FROM '.tablename('vivanjyh_xytp').' WHERE uniacid = :uniacid AND columns = :columns LIMIT '.($pindex - 1) * $psize.','.$psize,array(':uniacid'=>$_W['uniacid'],':columns'=>$columns));
        foreach ($list as &$value){
            $value['vote'] = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('vivanjyh_xytp_num').' WHERE  numid = :numid',array(':numid'=>$value['numid']));
        }
        $pager =pagination($total, $pindex, $psize);
        include $this->template('admin');
	}

}
<?php
/**
 * 留言测试模块微站定义
 *
 * @author 123
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');
define('ALL_URL','http://192.168.31.220/pros/addons/'.$_GET['m'].'/template/resource');
class Liu_yanModuleSite extends WeModuleSite {
    //留言首页
	public function doMobileFengmian1() {
		//这个操作被定义用来呈现 功能封面
        global $_W,$_GPC;
        if (empty($_W['fans']['nickname'])) {
            mc_oauth_userinfo();
        }
        $user = $_W['fans'];
       /* echo '<pre>';
        var_dump($_W['account']['jssdkconfig']);
        echo '</pre>';*/
        include $this->template('index1');
	}
	//留言提交数据处理
	public function doMobilePostgb(){
        global $_W,$_GPC;
        $data['uid'] = $_W['fans']['uid'];
        $data['uniacid'] = $_W['uniacid'];
        $data['uname'] = $_GPC['uname'];
        $data['content'] = $_GPC['content'];
        $data['avatar'] = $_GPC['avatar'];
        $data['times'] = time();
        if (empty($data['uname'])){
            echo json_encode(array('status'=>300));exit;
        }
        if (empty($data['content'])){
            echo json_encode(array('status'=>400));exit;
        }
        $isuid = pdo_fetchcolumn('SELECT COUNT(*) FROM ' .tablename('messages').'WHERE uid=:uid and uniacid=:uniacid ',array(':uid'=>$data['uid'],':uniacid'=>$_W['uniacid']));
        if ($isuid){
            echo json_encode(array('status'=>500));exit;
        }
        pdo_insert("messages",$data);
        echo json_encode(array('status'=>200));
    }
    //留言列表
    public function doMobileGblist(){
        global $_W,$_GPC;
        //获取页码
        $page = isset($_GPC['p'])?$_GPC['p']:1;
        //最小页码为1
        $pindex =max(1, $page);
        $psize = 6;
        //获取总页数
        $allPage = pdo_fetchcolumn('SELECT COUNT(*) FROM '.tablename('messages').' WHERE uniacid = :uniacid and pass = 1',array(':uniacid'=>$_W['uniacid']));

        //显示的页数
        $allPage = ceil($allPage/$psize);
        //获取数据
        $lists = pdo_fetchall('SELECT uid,uname,content,avatar,times,hit FROM '.tablename('messages').' WHERE uniacid = :uniacid and pass = 1',array(':uniacid'=>$_W['uniacid']));
        foreach($lists as &$v){
            $v['ishit'] = pdo_fetchcolumn("select count(*) from ".tablename('message_hit')." where uniacid = :uniacid and toid = :toid and uid = :uid",array(":uniacid"=>$_W['uniacid'],":toid"=>$v['uid'],":uid"=>$_W['member']['uid']));
        }
        include $this->template('gblist');
    }
    //留言点赞处理
    public function doMobileHit(){
        global $_W,$_GPC;
        $data['uid'] = $_W['fans']['uid'];//点赞
        $data['toid'] = $_GPC['touid'];//被赞uid
        $data['uniacid'] = $_W['uniacid'];
        $data['time'] = time();
        $isexit = pdo_fetchcolumn("SELECT COUNT(*) FROM ".tablename('message_hit')." WHERE uid=:uid AND uniacid=:uniacid AND toid=:toid ",array(':uid'=>$data['uid'],':uniacid'=>$data['uniacid'],':toid'=>$data['toid']));
        if (!$isexit){
            pdo_update('messages','hit=hit+1',array('uid'=>$data['uniacid'],'uid'=>$data['toid']));
            pdo_insert('message_hit',$data);
            echo json_encode(array('status'=>200));
        }
    }
    //留言后台数据
	public function doWebRukou() {
		//这个操作被定义用来呈现 管理中心导航菜单
        global $_W,$_GPC;
        $op =trim($_GPC['op'])? trim($_GPC['op']): 'list';
        if ($op ==  'list') {
            $list = pdo_fetchall("SELECT * FROM " . tablename('messages'));

        }elseif ($op == 'display_switch'){
            $id = intval($_GPC['id']);
            $pass = intval($_GPC['pass']);
            $uniacid = intval($_W['uniacid']);
            $data = pdo_update('messages',array('pass'=>$pass),array('id'=>$id,'uniacid'=>$uniacid));
            if ($data){
                exit('ok');
            }else{
                exit('no');
            }
        }

        include $this->template('guestbook');
    }

}
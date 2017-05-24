<?php

defined('IN_IA') or exit('Access Denied');

class Vivanjyh_jryyModule extends WeModule {

	public function settingsDisplay($settings) {
		global $_W, $_GPC;
        load()->func('tpl');

		if(checksubmit()) {
//            empty($_GPC['data']['title']) && message('请填写标题名称');
//			$dat =array(
//				'uniacid' => $_W['uniacid'],
//				'title' => $_GPC['title'],
//                'footer' => $_GPC['footer'],
//                'logoimg' => $_GPC['logoimg'],
//                'bgimg' => $_GPC['bgimg'],
//                'mobile' => $_GPC['mobile'],
//                'zhihang' => $_GPC['zhihang']
//				);

			if ($this->saveSettings($_GPC['data'])) {

                message('配置参数更新成功！', referer(), 'success');
            }


		}
		//这里来展示设置项表单
		include $this->template('setting');
	}

}
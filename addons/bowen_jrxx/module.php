<?php
/**
 * 今日信息（修复版）模块定义
 *
 * @author ju_残泪
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Bowen_jrxxModule extends WeModule {
	public $table_reply = 'bowen_jrxx_reply';
	public function fieldsFormDisplay($rid = 0) {
		//要嵌入规则编辑页的自定义内容，这里 $rid 为对应的规则编号，新增时为 0
		load()->func('tpl');
		if($rid==0){

			$reply = array(
				'title'=> '今日信息',
				'description' => 'XX小助，致力于打造XX最贴心的微信校园助手，我们提供查课表、查成绩、查天气，查快递；看笑话、查馆藏、表白墙等超多校园助手功能服务~',
				'tq' => '合川',
			);
		}else{
			$reply = pdo_fetch("SELECT * FROM ".tablename($this->table_reply)." WHERE rid = :rid ORDER BY `id` DESC", array(':rid' => $rid));
		}

		include $this->template('form');
	}

	public function fieldsFormValidate($rid = 0) {
		//规则编辑保存时，要进行的数据验证，返回空串表示验证无误，返回其他字符串将呈现为错误提示。这里 $rid 为对应的规则编号，新增时为 0
		return '';
	}

	public function fieldsFormSubmit($rid) {
		//规则验证无误保存入库时执行，这里应该进行自定义字段的保存。这里 $rid 为对应的规则编号
		global $_W,$_GPC;
		$id = intval($_GPC['reply_id']);
		$insert = array(
			'rid' => $rid,
			'uniacid' => $_W['uniacid'],
			'title' => $_GPC['title'],
			'description' => $_GPC['description'],
			'tq' => $_GPC['tq'],
		);
		if (empty($id)) {
			pdo_insert($this->table_reply, $insert);
		} else {
			pdo_update($this->table_reply, $insert, array('id' => $id));
		}
	}

	public function ruleDeleted($rid) {
		//删除规则时调用，这里 $rid 为对应的规则编号
		$replies = pdo_fetchall("SELECT id  FROM ".tablename($this->table_reply)." WHERE rid = '$rid'");
		$deleteid = array();
		if (!empty($replies)) {
			foreach ($replies as $index => $row) {
				$deleteid[] = $row['id'];
			}
		}
		pdo_delete($this->table_reply, "id IN ('".implode("','", $deleteid)."')");
	}


}
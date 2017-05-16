<?php
defined('IN_IA') or exit('Access Denied');

/**
 * 通过 openid 获取用户在被借权的公众号的粉丝信息
 * @param  String $openid 用户在被借权的公众号的openid($_W['fans']['openid'])
 * @return int         返回用户在被借权的公众号的 uid
 */
function getFansInfo($openid) {
	global $_W;
	$fan = pdo_fetch('SELECT * FROM ' . tablename('mc_mapping_fans') . ' WHERE openid=:openid', array(':openid' => $openid));
	if (!$fan) {
		return '';
	}
	if(is_base64($fan['tag'])){
		$fan['tag'] = base64_decode($fan['tag']);
	}
	if(is_serialized($fan['tag'])){
		$fan['tag'] = iunserializer($fan['tag']);
	}
	$profile['uid'] = $fan['uid'];
	$profile['openid'] = $fan['openid'];
	if(!empty($fan['tag'])) {
		if (!empty($fan['tag']['language'])) {
			$profile['language'] = $fan['tag']['language'];
		}
		if(!empty($fan['tag']['nickname'])) {
			$profile['nickname'] = $fan['tag']['nickname'];
		}
		if(!empty($fan['tag']['sex'])) {
			$profile['gender'] = $fan['tag']['sex'];
		}
		if(!empty($fan['tag']['city'])) {
			$profile['residecity'] = $fan['tag']['city'] . '市';
		}
		if(!empty($fan['tag']['province'])) {
			$profile['resideprovince'] = $fan['tag']['province'] . '省';
		}
		if(!empty($fan['tag']['country'])) {
			$profile['nationality'] = $fan['tag']['country'];
		}
		if(!empty($fan['tag']['headimgurl'])) {
			$profile['avatar'] = rtrim($fan['tag']['headimgurl'], '0');
		}
	}
	$profile['follow'] = $fan['follow'];
	$profile['followtime'] = $fan['followtime'];
	$profile['tagid_list'] = $fan['tag']['tagid_list'];
	return $profile;
}

/**
 * 通过 openid 获取用户在被借权的公众号的 member 信息
 *  @param  $openid 用户的 openid
 *  @param  $acid 被借权的公众号的 uniacid
 *  @return  返回用户的会员信息
 */
function getMember($openid, $acid = '') {
	global $_W;
	$fansinfo = getFansInfo($openid);
	if (!$fansinfo) {
		return '';
	}
	$uid = $fansinfo['uid'];
	if (!$uid) {
		return '';
	}
	$member = pdo_fetch('SELECT `uid`, `realname`, `mobile`, `email`, `groupid`, `credit1`, `credit2`, `credit6` FROM ' . tablename('mc_members') . ' WHERE  uid=:uid', array('uid' => $uid));
	return $member;
}
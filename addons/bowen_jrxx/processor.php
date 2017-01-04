<?php
/**
 * 今日信息（修复版）模块处理程序
 *
 * @author ju_残泪
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Bowen_jrxxModuleProcessor extends WeModuleProcessor {
	public $table_reply = 'bowen_jrxx_reply';
	public function respond() {
		$content = $this->message['content'];
        $weekarray=array("日","一","二","三","四","五","六");
		$nowday='今天是'.date("Y年n月j日 ")."\n星期".$weekarray[date("w")]."\n";
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
		$rid = $this->rule;
		$fromuser = $this->message['from'];
		if($rid) {
			$reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE rid = :rid", array(':rid' => $rid));
			if($reply) {
				$api = $this->desc($reply['tq']);
				$news = array();
				$news[] = array(
					'title' => $reply['title'],
					'description' => $nowday.$api['dat']."\n 今日英语 \n".$api['api']['content'].$api['api']['note']."\n".str_replace('词霸小编','小编',$api['api']['translation']),
					'url' => $this->createMobileUrl('home', array('id' => $reply['id'])),
				);
				return $this->respNews($news);
			}
		}
		return null;
	}
	public function desc($tq='合川'){

		$api = json_decode(file_get_contents('http://open.iciba.com/dsapi/'),true);
		$url = 'http://php.weather.sina.com.cn/xml.php?city=%s&password=DJOYnieT8234jlsK&day=0';
		$url = sprintf($url, urlencode(iconv('utf-8', 'gb2312', $tq)));
		$resp=ihttp_request($url);
		$obj = isimplexml_load_string($resp['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
		$dat = $obj->Weather->city . "今日天气：" . PHP_EOL .
			'今天白天'.$obj->Weather->status1.'，'. $obj->Weather->temperature1 . '摄氏度。' . PHP_EOL .
			$obj->Weather->direction1 . '，' . $obj->Weather->power1 . PHP_EOL .
			'今天夜间'.$obj->Weather->status2.'，'. $obj->Weather->temperature2 . '摄氏度。' . PHP_EOL .
			$obj->Weather->direction2 . '，' . $obj->Weather->power2 . PHP_EOL;
		$ap_arr=array("api"=>$api,"dat"=>$dat);

		return $ap_arr;


	}
}
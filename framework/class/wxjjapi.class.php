<?php

/**

	Designed by Scien author SnpStudios.
	Created at 2016-11-22 14:46

 */

defined('IN_IA') or exit('Access Denied');
load() -> func('communication');
class wxjjapi{
	static $uri = 'http://192.168.11.51:5028/WXWC/wcservice.asmx?wsdl';
	static $sign = 'C81DD8605F0531F0B6C717D07A8979F4';
	static $debug = 0;
	static $status = array(
							0 => '处置成功',
							1 => '未说明的失败原因',
							2 => '正在处理中',
							3 => '信息不完整，请求不予处理',
							4 => '无法联系车主',
							5 => '请求的编号不存在',
							6 => '消息推送成功',
							7 => '消息推送失败',
							8 => '鉴权失败',
							9 => '实名认证成功',
							10=> '实名认证失败',
							11=> '认证用户不存在',
							12=> '提交的参数不正确'
							);
	//private $globalW;
	
	public function globalW(){ // 获取global值
		global $_W;
		return $_W;
		}
	
	public function charset($str){
		return iconv("gb2312","utf-8//IGNORE",$str);
		}
	
	public function XXGZ(){//获取推送消息
		libxml_disable_entity_loader(false);
		$opts = array(
			'ssl'   => array(
				'verify_peer'          => false
			),
			'https' => array(
				'curl_verify_ssl_peer'  => false,
				'curl_verify_ssl_host'  => false
			)
		);
		$streamContext = stream_context_create($opts);
		try {
			$url = self::$uri;
			$c = new SoapClient($url,['stream_context' => $streamContext]);
			return $this->format($c->XXGZ(self::$sign));

		} catch (SOAPFault $e) {
			return($e);
		}
        }
	
	
			
	public function format(&$object) {
             $object =  json_decode( json_encode( $object),true);
             return  $object;
    }
	
	}
<?php


class Auth_tencent{
	const EXPIRED_SECONDS = 2592000;
	const apiName = 'auth';
	const appid = '4413';
	const secretKey = 'd818ffe4d560057a3b9641490b7ec0ca';
	const ResultKey = 'd3b54bd1673d34ef95d92f76ff188342';
	//redirect 回调地址
	const redirect = 'http://lab.scienmedia.com/app/index.php?i=2&c=entry&do=callback&m=viva_bbs';
	const expired = 600;
	const type = 0;
	const info_url = "https://iauth.wecity.qq.com/new/cgi-bin/getdetectinfo.php";

	
    static function getjsAppSign($apiName='auth',$uid,$flag='2',$type='0',$id='',$name='',$pic_key='') {

        $now = time();
        $plainText = 'a='.self::appid.'&m='.$apiName.'&t='.$now.'&e='.self::expired;
        $bin = hash_hmac("SHA1", $plainText, self::secretKey, true);
        $bin = $bin.$plainText;
        if($flag=="1"){

            $signature = base64_encode($bin);
            if($type==1){
                $sig = md5(self::redirect."-".$signature."-".self::appid."-".$uid."-".$id."-".$name."-".$pic_key."-".$type."-authkey");
                $arr = array (
                    'signature'=>$signature,
                    'sig'=>$sig,
                    'appid'=>self::appid,
                    'redirect'=>self::redirect,
                    'uid'=>$uid,
                    'type'=>$type,
                    'id'=>$id,
                    'name'=>$name,
                    'pic_key'=>$pic_key);
            }else{
                 $sig = md5(self::redirect."-".$signature."-".self::appid."-".$type."-".$uid."-authkey");
                $arr = array ('signature'=>$signature,'sig'=>$sig,'appid'=>self::appid,'redirect'=>self::redirect,'uid'=>$uid,'type'=>$type);
            }
                
               // echo json_encode($arr);
			return $arr;
        }else{
            $sign = base64_encode($bin);

                    return $sign;
        }
        
    }	
	
	
    static function stripPkcs7Padding($string){
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);

        if(preg_match("/$slastc{".$slast."}/", $string)){
            $string = substr($string, 0, strlen($string)-$slast);
            return $string;
        } else {
            return false;
        }
    }

	
    /**
     * 发送post请求
     * @param $request
     * @return mixed
     */
  
    static function request_post($request) {

        $header = $request['header'] ;
        $header[] = 'Method:POST';
        $ch = curl_init();                                                      //初始化curl
        curl_setopt($ch, CURLOPT_URL,$request['url']);                  //抓取指定网页
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                  //设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);             //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_TIMEOUT, $request['timeout']);         //超时时间
        curl_setopt($ch, CURLOPT_POST, 1);                       //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request['data']);         //数据
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);         //true any ca
        $data = curl_exec($ch);                                                 //运行curl
        curl_close($ch);
        // echo $data;
        return $data;
    }

	
    /**
     * 解密
     * @param String $encryptedText 二进制的密文
     * @param String $key 密钥
     * @return String
     */
   
   static function aes256cbcDecrypt($encryptedText, $key) {
        $encryptedText =base64_decode($encryptedText);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128,MCRYPT_MODE_ECB),MCRYPT_ENCRYPT);
        return self::stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encryptedText, MCRYPT_MODE_ECB, $iv));
    }


	
	static function getInfo($token){
				$sig = md5(self::appid."-".$token."-authkey");
		
				$data = "appid=".self::appid."&token=".$token."&sig=".$sig;
				$request = array(
					'url' => self::info_url,
					'method' => 'post',
					'timeout' => 100,
					'data' => $data,
					'header' => array(
						  'signature:'.self::getjsAppSign('getdetectinfo'),
						'Content-Type:application/x-www-form-urlencoded',
					),
				);
				$data=self::request_post($request);
				$info=json_decode($data,true);
				if($info['errorcode']==0){
				$arr=self::aes256cbcDecrypt($info['data'],self::ResultKey);
				return json_decode($arr,true);}
				else{
					return false;	
				}
	}
	
	
    /**
     * 获取参数签名校验
     * 注：图片不用 urlencode
     * @param $array
     * @return string
     */
    static function getSig($array){
        $s = "";
        foreach ($array as $value){
            $s .= $value."-";
        }
        $s .="authkey";
        return md5($s);
    }



}

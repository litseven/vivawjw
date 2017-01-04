<?php
/**
 * 圣诞派礼
 *
 * @author 刘靜
 * @url http://bbs.we7.cc/
 */
defined('IN_IA') or exit('Access Denied');

class Bowen_jrxxModuleSite extends WeModuleSite {
    public $table_reply = 'bowen_jrxx_reply';

    public function doMobileHome(){
        global $_GPC;
        $id = intval($_GPC['id']);
        $weekarray=array("日","一","二","三","四","五","六");
        $nowday='今天是'.date("Y年n月j日 ")."\n星期".$weekarray[date("w")]."\n";
        $reply = pdo_fetch("SELECT * FROM " . tablename($this->table_reply) . " WHERE id = :id", array(':id' => $id));
        $api = $this->desc($reply['tq']);
        include $this->template('home');

    }

    public function desc($tq='上海'){
        load()->func('communication');
        $api = json_decode(file_get_contents('http://open.iciba.com/dsapi/'),true);
        $url = 'http://php.weather.sina.com.cn/xml.php?city=%s&password=DJOYnieT8234jlsK&day=0';
        $url = sprintf($url, urlencode(iconv('utf-8', 'gb2312', $tq)));
        $resp=ihttp_request($url);
        $obj = isimplexml_load_string($resp['content'], 'SimpleXMLElement', LIBXML_NOCDATA);
        $dat = $obj->Weather->city . "今日天气：<br>" . PHP_EOL .
            '今天白天'.$obj->Weather->status1.'，'. $obj->Weather->temperature1 . '摄氏度。' . PHP_EOL .
            $obj->Weather->direction1 . '，' . $obj->Weather->power1 . PHP_EOL .
            '<br>今天夜间'.$obj->Weather->status2.'，'. $obj->Weather->temperature2 . '摄氏度。' . PHP_EOL .
            $obj->Weather->direction2 . '，' . $obj->Weather->power2 . PHP_EOL;
        $ap_arr=array("api"=>$api,"dat"=>$dat);

        return $ap_arr;


    }


        }
?>
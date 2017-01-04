<?php
/**
 * 查找UME模块处理程序
 *
 * @author litseven
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
class Site_umeModuleProcessor extends WeModuleProcessor {

	public function respond() {
        global $_W, $_GPC;

		$location = $this->message['msgtype'];
		//这里定义此模块进行消息处理时的具体过程, 请查看微擎文档来编写你的代码
        if($location == 'location'){
            $lat = $this->message['location_x'];
            $lng = $this->message['location_y'];
            $arr = pdo_fetch('SELECT * FROM '.tablename('sit_ume').'WHERE start = 1');
            //搜索范围
            $range = $arr['range'];
            //搜索地点
            $keyword = $arr['address'];
            //搜索数量
            $num = $arr['num'];
            $data = $this->search($lat,$lng,$range,$keyword);
            $data = $data['data'];
            foreach ($data as $k => $v){
                if ($k < $num){
                    $news[$k] = array(
                        //'title' => $v['title'].'  距离您'.$v['_distance'].'米',
                        'title' => $v['title'].' 距离您'.$this->getDistance($lat,$lng,$v['location']['lat'],$v['location']['lng']).'米',
                        'picurl' => 'http://192.168.31.220/pros/addons/site_ume/template/resource/images/659954_093431044742_2.jpg',
                        //创建一个带openid信息的访问模块introduce方法的地址，这里也可以直接写http://we7.cc
                        'url' => 'http://apis.map.qq.com/tools/poimarker?type=0&marker=coord:'.$v['location']['lat'].','.$v['location']['lng'].';title:'.$v['title'].';addr:'.$v['address'].'&key=6QRBZ-4YMLK-KXEJP-AWRND-HRGCV-CSF7C&referer=myapp',
                    );
                }

            }

        }
       /* load()->func('logging');

        //记录数组数据
        logging_run(array('range' => $arr['range'], 'address' => $arr['address']));*/
        //$this->vd($arr);
        return $this->respNews($news);
	}

    /**
     * 地点搜索（Search接口）
     * @param  float $lat
     * @param  float $lng
     * @param  string $keyword
     * @return array
     */
	public function search($lat,$lng,$range,$keyword){
        load()->func('communication');
        $url = 'http://apis.map.qq.com/ws/place/v1/search?boundary=nearby('.$lat.','.$lng.','.$range.')&keyword='.$keyword.'&key=d84d6d83e0e51e481e50454ccbe8986b';
        $url_get = ihttp_get($url);
        $url_get = $this->object2array(json_decode($url_get['content']));
        return $url_get;
    }


    /**
     * 对象转换为数组
     * @param  object $object
     * @return array
     */
    public function object2array($object) {
        foreach ($object as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $arr[$k] = $this->object2array($v);
            } else {
                $arr[$k] = $v;
            }
        }
        return $arr;
    }

    /**
     * 求出两个经纬度之间的距离
     * @param $lat1
     * @param $lng1
     * @param $lat2
     * @param $lng2
     * @return float
     */
    function getDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6367000;

        $lat1 = ($lat1 * pi() ) / 180;
        $lng1 = ($lng1 * pi() ) / 180;

        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;

        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
        $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;

        return round($calculatedDistance);
    }












    public function vd($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
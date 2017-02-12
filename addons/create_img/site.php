<?php
/**
 *
 *
 * @author litseven
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
//define('S_URL','http://'. $_SERVER['HTTP_HOST'].'/pros/addons/'.$_GET['m'].'/template/mobile');
define('S_URL', 'http://'. $_SERVER['HTTP_HOST'].'/addons/'.$_GET['m'].'/template/mobile');
class Create_imgModuleSite extends WeModuleSite {
    /**
     * 男用模板
     * @var array
     */
    protected $male = array(
        '0' => array(
            'file' => 'male1',	// 图片模板文件名
            'x_axis' => 100,	// 文本框位置左上角 X 坐标
            'y_line' => 180,	// 文本框位置左上角 Y 坐标
            'w' => 75,			// 文本框宽度
            'h' => 40			// 文本框高度
        ),
        '1' => array(
            'file' => 'male2',
            'x_axis' => 270,
            'y_line' => 242,
            'w' => 230,
            'h' => 40
        ),
        '2' => array(
            'file' => 'male3',
            'x_axis' => 200,
            'y_line' => 245,
            'w' => 200,
            'h' => 55
        ),
        '3' => array(
            'file' => 'male4',
            'x_axis' => 120,
            'y_line' => 313,
            'w' => 100,
            'h' => 50
        ),
    );

    /**
     * 女
     * @var array
     */
    protected $female = array(
        '0' => array(
            'file' => 'female1',
            'x_axis' => 260,
            'y_line' =>290,
            'w' => 150,
            'h' => 50
        ),
        '1' => array(
            'file' => 'female2',
            'x_axis' => 140,
            'y_line' => 275,
            'w' => 150,
            'h' => 45
        ),
        '2' => array(
            'file' => 'female3',
            'x_axis' => 330,
            'y_line' => 230,
            'w' => 88,
            'h' => 40
        ),
        '3' => array(
            'file' => 'female4',
            'x_axis' => 280,
            'y_line' => 280,
            'w' => 250,
            'h' => 50
        ),
    );

    /**
     * 通用
     * @var array
     */
    protected $global = array(
        '0' => array(
            'file' => 'global1',
            'x_axis' => 430,
            'y_line' => 112,
            'w' => 130,
            'h' => 32
        ),
        '1' => array(
            'file' => 'global2',
            'x_axis' => 105,
            'y_line' => 226,
            'w' => 92,
            'h' => 50
        ),
        '2' => array(
            'file' => 'global3',
            'x_axis' => 220,
            'y_line' => 261,
            'w' => 210,
            'h' => 35
        ),
        '3' => array(
            'file' => 'global4',
            'x_axis' => 130,
            'y_line' => 195,
            'w' => 100,
            'h' => 42
        ),
        '4' => array(
            'file' => 'global5',
            'x_axis' => 100,
            'y_line' => 263,
            'w' => 120,
            'h' => 36
        ),
        '5' => array(
            'file' => 'global6',
            'x_axis' => 280,
            'y_line' => 285,
            'w' => 105,
            'h' => 50
        ),
    );
	public function doMobileIndexx() {
		//这个操作被定义用来呈现 功能封面
        global $_W,$_GPC;
        //var_dump($_W);
        $op =trim($_GPC['op'])? trim($_GPC['op']): 'index';
        $key = rand(0,9);
        if ($op == 'page2'){
            $key = $_GPC['key'];
        }
        if ($op == 'page3'){
            $key = $_GPC['key'];
        }
        include $this->template('indexx');
	}
	public function doMobileShowImg(){
        global $_W,$_GPC;
        $op = $_GPC['op'];
        $key = $_GPC['key'];
        $sex = $_GPC['sex'];
        //设置没张模板名字位置
//        $key = 8;
//        $sex = 'female';
        if ($sex == 'meale'){
            $file = array_merge($this->male,$this->global);
        }elseif ($sex == 'female'){
            $file = array_merge($this->female,$this->global);
        }
        $file = $file[$key];
        $name = trim($_GPC['name']);

        $src_path = dirname(__FILE__) . '/template/resource/bgimg/'. $file['file'].'.png';
        $this->createImg($name,$src_path,$file['x_axis'], $file['y_line'], $file['w'], $file['h']);
        if ($op = 'page2'){
            include $this->template('indexx');
        }

    }
    public function createImg($name,$src_path,$x_axis,$y_line,$w, $h) {
        $bg_path = dirname(__FILE__) . '/template/resource/bgimg/bg.jpg';
        $fontttf = dirname(__FILE__) . '/template/resource/font/msyhbd.ttf';
        $bg = imagecreatefromjpeg($bg_path);
        $src = imagecreatefrompng($src_path);
        list($src_w, $src_h) = getimagesize($src_path);
        imagecopy($bg, $src, 0, 0, 20, 160, $src_w,$src_h);
        $len = mb_strlen($name, 'utf8');
        $color = imagecolorallocate($src, 51, 51, 51);
        $fontsize = $this->size($h, $fontttf, $name, $w, $h);
        imagettftext($bg, $fontsize , 0, $x_axis,$y_line, $color, $fontttf, $name);
        imagesavealpha($bg , true);
        header("content-type: image/jpeg");
        imagejpeg($bg);
        imagedestroy($bg);
    }
    /**
     * 计算字体大小
     * @param  int $fontsize 最大字体大小
     * @param  string $fontttf  字体路径
     * @param  string $name     文字
     * @param  int $w 图片文本框宽度
     * @param  int $h 图片文本框高度
     * @return int 字体大小
     */
    public function size($fontsize, $fontttf, $name, $w, $h) {
        for ($i=0; $i < $fontsize; $i++) {
            $tbox = imagettfbbox($fontsize, 0, $fontttf, $name);
            if (($tbox[2] - $tbox[0]) > $w || ($tbox[1] - $tbox[7]) > $h) {
                $fontsize--;
                $fontsize = $this->size($fontsize, $fontttf, $name, $w, $h);
            } else {
                return $fontsize;
            }
        }
    }

    public function doWebGuanli() {
        //这个操作被定义用来呈现 管理中心导航菜单

        include $this->template('setting');
    }
}
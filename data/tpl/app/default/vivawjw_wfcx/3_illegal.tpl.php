<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="<?php echo S_URL;?>css/index.css" rel="stylesheet">
    <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
    <title>违法查询</title>
</head>
<body >
<div class="content chaxun">
    <div class="content_box">
        <div class="ill_head">
            <div class="illegal show_car ill_current">
                <div class="ill_tit ">车辆违法查询</div>
            </div>
            <div class="illegal show_driving">
                <div class="ill_tit">驾驶证违法查询</div>
            </div>
        </div>
        <div class="ill_query start"  style="display: block;">
            <div class="query_box clwf"  style="display:block;">
                <div class="car_style">
                    <div>车辆类型</div>
                    <select name="cartype">
                        <option value="02">小型车辆</option>
                        <option value="01">大型车辆</option>
                    </select>
                </div>
                <div class="car_style">
                    <div>车牌号码</div>
                    <input type="text" name="carnum" id="car_number" value="苏BV286Z">
                </div>
                <div class="car_style">
                    <div>发动机号<br><a class="where">(在哪里)</a></div>
                    <input type="text" name="enginenum" placeholder="请输入发动机号后六位" id="car_fa" value="101923">
                </div>
                <a id="query">查询</a>
                <div class="query_word1">视频及外地违法图片请到各违法处理点查看</div>
                <div class="query_word2"><a href="" style="color: #2ecc71">查看我绑定车辆的违法信息</a></div>
            </div>

            <div class="query_box jszwf" style="display:none">
                <div class="car_style">
                    <div>驾驶证编号</div>
                    <input type="text" name="drnunum" id="car_number1" placeholder="请输入驾驶证编号" value="320201197601293018">
                </div>
                <div class="car_style">
                    <div>档案编号<br><a class="where">(在哪里)</a></div>
                    <input type="text" name="filenum" placeholder="请输入档案编号后六位" id="car_fa1" value="243509">
                </div>
                <a id="query1">查询</a>
                <div class="query_word3">查看我绑定驾驶证的违法信息</div>
            </div>

        </div>

<script>
    var process = false;
    $("#loading-fs").hide();
    $(function(){
        $('#query').click(function(){
            if(process)return false;
            process = true;
            var cartype = $("[name = 'cartype']").val();
            var carnum = $("[name = 'carnum']").val();
            var enginenum = $("[name = 'enginenum']").val();
            reg=/^苏B[0-9A-Z]{5}$/;
            if (!reg.test(carnum)){
                alert('请正确输入车牌号');return false;
            }
            if (enginenum == ''){
                alert('发动机号不能为空！');return false;
            }
            if(enginenum.length != 6){
                alert('发动机号码输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('apipostcl')?>",{'cartype':cartype,'carnum':carnum,'enginenum':enginenum},function(data){
                console.log(data);
                if (data.State == 0){
                    var list = data.WFXXList.WFXX;
                    var fkje = 0;
                    for (var i = 0; i < list.length; i++){
                        var n = list[i].FKJE;
                        fkje = fkje + n;
                    }
                    switch (data.ZT) {
                        case 'A':
                            var a = '正常';
                            break;
                        case 'B':
                            var a = '转出';
                            break;
                        case 'C':
                            var a = '被盗抢';
                            break;
                        case 'D':
                            var a = '停驶';
                            break;
                        case 'E':
                            var a = '注销';
                            break;
                        case 'G':
                            var a = '违法未处理';
                            break;
                        case 'H':
                            var a = '海关监管';
                            break;
                        case 'I':
                            var a = '事故未处理';
                            break;
                        case 'J':
                            var a = '嫌疑车';
                            break;
                        case 'K':
                            var a = '查封';
                            break;
                        case 'L':
                            var a = '扣留';
                            break;
                        case 'M':
                            var a = '强制注销';
                            break;
                        case 'N':
                            var a = '事故逃逸';
                            break;
                        case 'O':
                            var a = '锁定';
                            break;
                        default:
                            var a = '正常';
                            break;
                    }
                    $('.start').hide();
                    $('.end').show();
                    $('.cphm').text(data.HPHM);
                    $('.wfts').text(list.length);
                    $('.fkje').text(fkje);
                    $('.wflist').empty();
                    for (var j = 0; j < list.length; j++){
                        var wftime = list[j].WFSJ;//违法时间
                        var wfaddr = list[j].WFDD;//违法地点
                        var wfcontent = list[j].WFXW;//违法行为
                        var wfcjjg = list[j].CJJGMC;//采集机构
                        var wfjkxh = list[j].XH;//监控序号
                        var wfclbj = list[j].CLBJ;//违法处理标记
                        var wfrksj = list[j].RKSJ;//入录时间
                        var wffkjf = list[j].WFJFS;//处罚记分
                        var wfcph = carnum;//违法车牌
                        var wfmoney = list[j].FKJE;//处罚金额
                        //  data-wftime="'+wftime+'" data-wfaddr="'+wfaddr+'" data-wfcontent="'+wfcontent+'" data-wfjkxh="'+wfjkxh+'" data-wfclbj="'+wfclbj+'"
                        //  data-wfrksj="'+wfrksj+'" data-wffkjf="'+wffkjf+'" data-wfcph="'+wfcph+'" data-wfmoney="'+wfmoney+'"
                        $('.wflist').append('<div class="query_info"><div class="query_info1"><div class="arrow_left"><img src="<?php echo S_URL;?>img/jiant.png"></div> <div class="info_right"><a href="javascript:;" class="shensu" data-wftime="'+wftime+'" data-wfaddr="'+wfaddr+'" data-wfcontent="'+wfcontent+'" data-wfjkxh="'+wfjkxh+'" data-wfclbj="'+wfclbj+'" data-wfcjjg="'+wfcjjg+'" data-wfrksj="'+wfrksj+'" data-wffkjf="'+wffkjf+'" data-wfcph="'+wfcph+'" data-wfmoney="'+wfmoney+'">申诉</a> <div class="chu" data-to="0"><div>违法时间:<span>'+list[j].WFSJ+'</span></div><div>违法地点:<span>'+list[j].WFDD+'</span></div> </div> </div> </div><div class="info_detail hide" ><div class="detail_box"><div>违法行为:<span>'+list[j].WFXW+'</span></div> <div>违法图片:<span>视频及外地违法图片请到各违法处理点查看</span></div><div>处理状态:<span>'+a+'</span></div><div>罚款金额:<span>'+list[j].FKJE+'</span></div></div></div></div>');
                    }
                    $('.chu').click(function(){
                        if($(this).data('to') == 0){
                            $(this).data('to',1);
                            $(this).parents('.query_info1').siblings('.info_detail').show();
                            /*$(this).siblings('.info_detail').show().parent().siblings().find('.info_detail').hide();*/
                            $(this).parents('.query_info1').find('.arrow_left img').css('transform','rotate(180deg)');
                        }else{
                            $(this).data('to',0);
                            $(this).parents('.query_info1').siblings('.info_detail').hide();
                            $(this).parents('.query_info1').find('.arrow_left img').css('transform','rotate(0deg)');
                        }
                    });
                    $('.shensu').click(function(){
                        $('.wfcjjg').val($(this).attr('data-wfcjjg'));
                        $('.wftime').val($(this).attr('data-wftime'));
                        $('.wfaddr').val($(this).attr('data-wfaddr'));
                        $('.wfcontent').val($(this).attr('data-wfcontent'));
                        $('.wfjkxh').val($(this).attr('data-wfjkxh'));
                        $('.wfclbj').val($(this).attr('data-wfclbj'));
                        $('.wfrksj').val($(this).attr('data-wfrksj'));
                        $('.wffkjf').val($(this).attr('data-wffkjf'));
                        $('.wfmoney').val($(this).attr('data-wfmoney'));
                        $('.wfcph').val(carnum);
                            $('.susub').show();
                            $('.chaxun').hide();
                    });
                    $('.endjsyxx').hide();
                    $('.endclwf').show();
                }
                process = false;

            },'JSON');

            $("#loading-fs").hide();
        });

        $('#query1').click(function(){
            if(process)return false;
            process = true;
            var drnunum = $("[name = 'drnunum']").val();
            var filenum = $("[name = 'filenum']").val();
            if (drnunum == ''){
                alert('发动机号不能为空！');return false;
            }
            if (filenum == ''){
                alert('发动机号不能为空！');return false;
            }
            if(filenum.length != 6){
                alert('发动机号码输入有误！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('apipostjsr')?>",{'drnunum':drnunum,'filenum':filenum},function(data){
                console.log(data);
                if (data){
                    switch (data.ZT) {
                        case 'A':
                            var a = '正常';
                            break;
                        case 'B':
                            var a = '转出';
                            break;
                        case 'C':
                            var a = '被盗抢';
                            break;
                        case 'D':
                            var a = '停驶';
                            break;
                        case 'E':
                            var a = '注销';
                            break;
                        case 'G':
                            var a = '违法未处理';
                            break;
                        case 'H':
                            var a = '海关监管';
                            break;
                        case 'I':
                            var a = '事故未处理';
                            break;
                        case 'J':
                            var a = '嫌疑车';
                            break;
                        case 'K':
                            var a = '查封';
                            break;
                        case 'L':
                            var a = '扣留';
                            break;
                        case 'M':
                            var a = '强制注销';
                            break;
                        case 'N':
                            var a = '事故逃逸';
                            break;
                        case 'O':
                            var a = '锁定';
                            break;
                        default:
                            var a = '正常';
                            break;
                    }
                    $('.start').hide();
                    $('.end').show();
                    $('.endclwf').hide();
                    $('.endjsyxx').show();
                    $('.ljjf').text(data.LJJF);
                    $('.ccdjrq').text(data.CCDJRQ);
                    $('.yxqz').text(data.YXQZ);
                    $('.jsrzt').text(a);
                }
                process = false;
            },'JSON');

            $("#loading-fs").hide();
        });

    });
</script>
<div id="loading-fs">
    <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>

        <div class="ill_query end" style="background-color:#eeeeee;display: none">
            <div class="endclwf">
                <div class="query_box">
                    <div class="license">
                        <div class="licen_img">
                            <img src="<?php echo S_URL;?>img/lisence.png">
                            <div class="cphm"></div>
                        </div>
                        <div class="licen_mon">共<span class="wfts"></span>条违法信息<br>罚款<span class="fkje"></span>元</div>
                    </div>
                    <div style="margin-top:18px;" class="wflist">

                    </div>
                    <div class="query_word4"><a href="<?php  echo $this->createMobileUrl('illegal',array('op'=>'start'))?>" style="color: #2ecc71">查询其他车辆违法信息</a></div>
                </div>
                <div class="binding">
                    <div class="binding_box">
                        <a href="" class="plus" style="text-decoration: none">&#xe616;</a>
                        <div>绑定该车辆，接收违法通知</div>
                        <div class="close"><i>&#xe611;</i></div>
                    </div>
                </div>
            </div>

            <div class="query_box endjsyxx" style="background-color: white;display:none;">
                <div class="driv_info">驾驶员信息</div>
                <div class="illegal_box">
                    <div class="ill_score">
                        <div>累计计分</div>
                        <div class="ljjf"></div>
                    </div><div class="ill_score">
                    <div>准驾车型</div>
                    <div>C1</div>
                </div>
                    <div class="ill_score">
                        <div>有效时间起</div>
                        <div class="ccdjrq"></div>
                    </div>
                    <div class="ill_score">
                        <div>有效时间止</div>
                        <div class="yxqz"></div>
                    </div>
                    <div class="ill_score">
                        <div>状态</div>
                        <div class="jsrzt"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('wxsdk', TEMPLATE_INCLUDEPATH)) : (include template('wxsdk', TEMPLATE_INCLUDEPATH));?>
<script>
    $(function(){
        $('.show_car').click(function(){
            $(this).addClass('ill_current');
            $('.show_driving').removeClass('ill_current');
            $('.jszwf').hide();
            $('.clwf').show();
            $('.start').show();
            $('.end').hide();

        });
        $('.show_driving').click(function(){
            $(this).addClass('ill_current');
            $('.show_car').removeClass('ill_current');
            $('.jszwf').show();
            $('.clwf').hide();
            $('.start').show();
            $('.end').hide();

        });


        $('.chu').click(function(){
            if($(this).data('to') == 0){
                $(this).data('to',1);
                $(this).parents('.query_info1').siblings('.info_detail').show();
                /*$(this).siblings('.info_detail').show().parent().siblings().find('.info_detail').hide();*/
                $(this).parents('.query_info1').find('.arrow_left img').css('transform','rotate(180deg)');
            }else{
                $(this).data('to',0);
                $(this).parents('.query_info1').siblings('.info_detail').hide();
                $(this).parents('.query_info1').find('.arrow_left img').css('transform','rotate(0deg)');
            }
        });


        $('.close').click(function(){
            $('.binding').hide();
        });
    });
    wx.ready(function(){
        $('.where').click(function(){
            wx.previewImage({
                current: 'http://120.27.230.32/pros/addons/vivawjw_wfcx/template/resource/img/help_0.jpg', // 当前显示图片的http链接
                urls: ['http://120.27.230.32/pros/addons/vivawjw_wfcx/template/resource/img/help_0.jpg'] // 需要预览的图片http链接列表
            });
        });

    });
</script>

<div class="content susub" style="display: none">
    <div class="content_box">
        <form>
            <div class="appeal_box">
                <div class="appeal_name">
                    <div>姓名:</div>
                    <input type="text" name="appeal_name" placeholder="请输入您的姓名" >
                    <input type="hidden" value="<?php  echo $userid;?>" name="userid">
                </div>
                <div class="appeal_phone">
                    <div>联系方式:</div>
                    <input type="text" name="appeal_phone" placeholder="请输入您的手机号" >
                </div>
                <div class="appeal_why">
                    <div>申诉原因:</div>
                    <textarea placeholder="请输入申诉原因(100字)" name="appeal_why"></textarea>
                </div>
            </div>
            <input type="hidden" class="wfcjjg" value="">
            <input type="hidden" class="wftime" value="">
            <input type="hidden" class="wfaddr" value="">
            <input type="hidden" class="wfcontent" value="">
            <input type="hidden" class="wfjkxh" value="">
            <input type="hidden" class="wfclbj" value="">
            <input type="hidden" class="wfrksj" value="">
            <input type="hidden" class="wffkjf" value="">
            <input type="hidden" class="wfmoney" value="">
            <input type="hidden" class="wfcph" value="">
            <a id="appeal_sub">提交</a>
        </form>
    </div>
</div>
<script>

    $("#loading-fs").hide();
    $(function(){
        $('#appeal_sub').click(function(){
            if(process)return false;
            process = true;
            var appeal_name = $("[name = 'appeal_name']").val();
            var appeal_phone = $("[name = 'appeal_phone']").val();
            var appeal_why = $("[name = 'appeal_why']").val();
            var wfcjjg = $('.wfcjjg').val();
            var wftime = $('.wftime').val();
            var wfaddr = $('.wfaddr').val();
            var wfcontent = $('.wfcontent').val();
            var wfjkxh = $('.wfjkxh').val();
            var wfclbj = $('.wfclbj').val();
            var wfrksj = $('.wfrksj').val();
            var wffkjf = $('.wffkjf').val();
            var wfmoney = $('.wfmoney').val();
            var wfcph = $('.wfcph').val();
            if (appeal_name == ''){
                alert('请留下您的名字！');return false;
            }
            if (appeal_phone == ''){
                alert('手机号不能为空！');return false;
            }
            if(!(/^1[34578]\d{9}$/.test(appeal_phone))){
                alert("手机号码有误，请重填");return false;
            }
            if (appeal_why == ''){
                alert('请填写申诉原因,方便我们帮到您！');return false;
            }
            if (appeal_why.length > 100){
                alert('您字数过多，我看不过来，请在100字符之内！');return false;
            }
            $("#loading-fs").show();
            $.post("<?php  echo $this->createMobileUrl('appealpost')?>",{'wfcph':wfcph,'wfmoney':wfmoney,'wffkjf':wffkjf,'wfrksj':wfrksj,'wfclbj':wfclbj,'wfjkxh':wfjkxh,'wfcontent':wfcontent,'wfaddr':wfaddr,'wftime':wftime,'wfcjjg':wfcjjg,'appeal_why':appeal_why,'appeal_phone':appeal_phone,'appeal_name':appeal_name},function(data){
                if(data == 200){
                    alert('ok');
                }
                process = false;
            });
        });
    });
</script>
</body>
</html>

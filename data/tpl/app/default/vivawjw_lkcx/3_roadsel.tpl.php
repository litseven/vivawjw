<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
     <meta name="apple-mobile-web-app-capable" content="yes">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
     <script src="http://imgcdn.yimiaoxiao.com/jquery.min.js"></script>
     <script src="http://imgcdn.yimiaoxiao.com/jquery.form.min.js"></script>
     <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
     <title>路况查询</title>
</head>
<?php  if($op == 'list') { ?>
<body  style="background-color:#eeeeee;">
<div class="content">
     <div class="content_box">
          <div class="search_box">
               <div class="search">
                    <input type="text" placeholder="道路关键词" id="search">
                    <button><i>&#xe601;</i></button>
               </div>
          </div>

          <div class="place_box">

               <div class="areas">
                    <!--梁溪区-->
                    <div class="area1" data-target="q_1">
                         <div class="area_1">
                              <i>&#xe612;</i>
                              <div>梁溪区</div>
                         </div>
                    </div>
                    <!--滨湖区-->
                    <div class="area1"  data-target="q_2">
                         <div class="area_1">
                              <i>&#xe612;</i>
                              <div>滨湖区</div>
                         </div>

                    </div>

               </div>
               <!--详细信息-->
               <div class="detail_box" id="q_1" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>
                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>


               </div>

               <div class="detail_box" id="q_2" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春1申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="areas">
                    <!--新吴区-->
                    <div class="area1" data-target="q_3">
                         <div class="area_1" >
                              <i>&#xe612;</i>
                              <div>新吴区</div>
                         </div>
                    </div>
                    <!--锡山区-->
                    <div class="area1" data-target="q_4">
                         <div class="area_1">
                              <i>&#xe612;</i>
                              <div>锡山区</div>
                         </div>

                    </div>

               </div>
               <!--详细信息-->

               <div class="detail_box" id="q_3" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春34申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>已收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>已收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="detail_box" id="q_4" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春3申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春5申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

               </div>

               <div class="areas">
                    <!--惠山区-->
                    <div class="area1" data-target="q_5">
                         <div class="area_1" >
                              <i>&#xe612;</i>
                              <div>惠山区</div>
                         </div>
                    </div>
                    <!--高架道路-->
                    <div class="area1" data-target="q_6">
                         <div class="area_1">
                              <i>&#xe612;</i>
                              <div>高架道路</div>
                         </div>

                    </div>

               </div>

               <!--详细信息-->

               <div class="detail_box" id="q_5" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">惠山区申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="detail_box" id="q_6" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">高架道路申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春5申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>

                    </div>
               </div>

               <div class="areas">
                    <!--高速公路-->
                    <div class="area1" data-target="q_7">
                         <div class="area_1" >
                              <i>&#xe612;</i>
                              <div>高速公路</div>
                         </div>
                    </div>
                    <!--主要景区周边-->
                    <div class="area1" data-target="q_8">
                         <div class="area_1">
                              <i>&#xe612;</i>
                              <div style="margin-left:30%;">主要景区周边</div>
                         </div>

                    </div>

               </div>

               <!--详细信息-->

               <div class="detail_box" id="q_7" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">高速公路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="detail_box" id="q_8" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">主要景区周边申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">春5申路北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>

               </div>


               <div class="areas">
                    <!--灵山景区周边-->
                    <div class="area1" data-target="q_9" id="area1">
                         <div class="area_1" >
                              <i style="padding-left:5%;">&#xe612;</i>
                              <div style="margin-left:21%;">灵山景区周边</div>
                         </div>
                    </div>

               </div>

               <div class="detail_box" id="q_9" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">灵山景区北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="areas">
                    <!--鼋头渚竞速周边-->
                    <div class="area1" data-target="q_10" id="area1">
                         <div class="area_1" >
                              <i style="padding-left:5%;">&#xe612;</i>
                              <div style="margin-left:21%;">鼋头渚竞速周边</div>
                         </div>
                    </div>

               </div>

               <div class="detail_box" id="q_10" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">鼋头渚竞速周边北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

               <div class="areas">
                    <!--地铁3号线施工周边-->
                    <div class="area1" data-target="q_11" id="area1">
                         <div class="area_1" >
                              <i style="padding-left:5%;">&#xe612;</i>
                              <div style="margin-left:21%;">地铁3号线施工周边</div>
                         </div>
                    </div>

               </div>

               <div class="detail_box" id="q_11" style="display:none;">
                    <div>
                         <div class="detail1" data-ti='0'>
                              <i class="detail_icon1">&#xe622;</i>
                              <div class="deta_addr">地铁3号线施工周边边北大街路口</div>
                              <div class="deta_collect">
                                   <i>&#xe623;</i>
                                   <span>收藏</span>
                              </div>
                         </div>

                         <div class="detail_boxes hide">
                              <img src="<?php echo S_URL;?>img/sy.jpg" style="display: block;">
                              <div class="detail11_box">
                                   <div>注：图片每5分钟自动更新，图片加载较慢，请耐心等待</div>
                              </div>
                         </div>
                    </div>
               </div>

          </div>

          <a id="road" href="<?php  echo $this->createMobileUrl('roadsel',array('op'=>'roadpsot'))?>" style="text-decoration: none">我要报路况</a>

     </div>
</div>
<script>
     //展开样式
     $('.area1').click(function(){
          if($(this).hasClass('area_current')){
               $('.area1').removeClass('area_current');
               $('.detail_box').hide();
               return false;
          }else{
               $('.area1').removeClass('area_current');
               $(this).addClass('area_current');
               _t = $(this).attr('data-target');
               $('.detail_box').hide();
               $("#"+_t).show();
          }
     });
     $('.deta_collect i').click(function(){
          if($(this).hasClass('star')){
               $(this).removeClass('star');
               $(this).siblings('.deta_collect span').text('收藏');
               return;
          }else{
               /*$('.deta_collect i').removeClass('star');*/
               $(this).addClass('star');
               /*$('.deta_collect span').text('收藏');*/
               $(this).siblings('.deta_collect span').text('已收藏');
          }
     })
     $('.detail1').click(function(){

          if($(this).data('ti')== 0){
               $(this).data('ti',1);
               $(this).siblings('.detail_boxes').show();
               $(this).find('.detail_icon1').css('transform','rotate(90deg)');

          }else{
               $(this).data('ti',0);
               $(this).siblings('.detail_boxes').hide();
               $(this).find('.detail_icon1').css('transform','rotate(0deg)');

          }
     })
</script>
<?php  } ?>
<?php  if($op == 'roadpsot') { ?>
<body>
<div class="content">
     <div class="content_box">
          <div class="picture_banner"><img src="<?php echo S_URL;?>img/banner5.jpg"></div>
          <div class="picture_tip">您如果遇到交通拥堵或者看到交通事故，请拍下来在这里告诉我们，
               我们会第一时间疏导处理。</div>
          <div class="upload_img">
               <div class="upload1"></div>
               <div class="upload1"></div>
               <div class="upload1"></div>
               <div class="upload1"></div>
               <input type="hidden" name="proof">
               <input type="hidden" name="uid" value="<?php  echo $user;?>">
          </div>
          <div class="upload_tip">图片限制大小3000k，文明上网请勿上传与主题无关的内容。</div>
               <textarea class="pict_intro" placeholder="请您提供照片相关说明，我们会及时认真处理。" onFocus="this.placeholder=''" onBlur="this.placeholder='请您提供照片相关说明，我们会及时认真处理。'"></textarea>
          <a class="picture_btn">确认</a>
     </div>
</div>


<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('wxsdk', TEMPLATE_INCLUDEPATH)) : (include template('wxsdk', TEMPLATE_INCLUDEPATH));?>
<script>
     //上传图片
     var process = false;
     $("#loading-fs").hide();
     $(function(){
          $('.upload_img>div').click(function(){
               var _this = $(this);
               wx.chooseImage({
                    count: 1, // 默认9
                    sizeType: ['compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    success: function (res) {
                         var localIds = res.localIds;
                         wx.uploadImage({
                              localId: localIds[0],
                              isShowProgressTips: 1,
                              success: function (res) {
                                   var serverId = res.serverId;
                                   //console.log(serverId);
                                   _this.attr('data-id',serverId);
                                   _this.css({"background-image":"url("+res.localId+")"});
                              }
                         });
                    }
               });
          });

          $('.picture_btn').click(function(){
               if(process)return false;
               var pict_info = $('.pict_intro').val();
               var uid = $("[name = 'uid']").val();
               //获取上传图片地址
               proof = [];
               $(".upload_img>div").each(function(){
                    sid = $(this).data('id');
                    if(sid)proof.push(sid);
               });
               $("[name='proof']").val(proof.join(','));
               proof = $("[name='proof']").val();
               if(!proof){alert("请上传照片！");return false;}
               if (pict_info == ''){
                    alert('请您输入说明！');return false;
               }
               $("#loading-fs").show();
               $.post("<?php  echo $this->createMobileUrl('roadpost')?>",{uid:uid,pict_info:pict_info,proof:proof},function(data){
                    if(data){
                         window.location.href = "<?php  echo $this->createMobileUrl('roadsel',array('op'=>'success'))?>";
                    }else{
                         $("#loading-fs").hide();
                         alert('提交失败！');
                    }

               });
               process = true;
          });
     });
</script>
<?php  } ?>
<?php  if($op == 'success') { ?>
<body>
<div class="content">
     <div class="content_box">
          <div class="appeal1_box">
               <i class="appeal_suc">&#xe60d;</i>
               <div>您上传的结果我们已经收到，将尽快为您处理。</div>
          </div>
          <a id="appeal_back" href="<?php  echo $this->createMobileUrl('roadsel',array('op'=>'list'))?>">返回</a>
     </div>
</div>
<?php  } ?>
<div id="loading-fs">
     <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
</body>
</html>

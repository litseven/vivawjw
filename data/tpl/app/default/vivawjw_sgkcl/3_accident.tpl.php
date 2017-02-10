<?php defined('IN_IA') or exit('Access Denied');?><!doctype html>
<html>
<head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimal-ui,user-scalable=1.0" />
     <meta name="apple-mobile-web-app-capable" content="yes">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <link href="<?php echo S_URL;?>css/style.css" rel="stylesheet">
     <script src="<?php echo S_URL;?>js/jquery-3.0.0.js"></script>
     <script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
     <title>事故快处</title>
</head>

<body>
<?php  if($op == 'start') { ?>
<div class="content">
     <div class="content_box">
          <div class="adap_range">
               <div>事故快处适应范围</div>
               <div>适用范围：无人员伤亡，无公共设施损坏，车辆可移动，各方驾驶人持有效的驾驶证，各方机动车有号牌、年检合格，无饮酒、无吸食毒品、麻醉药品，各方车辆均有交强险，各方事故当事人对交通事故事实无异议。确保安全，放置警示标志，开启危险报警灯（双闪灯），注意过往车辆。</div>
          </div>
          <a id="certain" href="<?php  echo $this->createMobileUrl('accident',array('op'=>'accpost'))?>">确认</a>
     </div>
</div>
<?php  } ?>
<?php  if($op == 'accpost') { ?>
<div class="content">
     <div class="content_box">
          <div class="acc_head">
               <div>事故相关信息</div>
          </div>
          <div class="acc_place">
               <div class="shigu">事故地点</div>
               <input type="text" name="accaddr" class="accaddr" value="" >
          </div>

          <div class="acc_photo">
               <div>现场照片<span>(查看拍摄样片)</span></div>
               <div class="upload_img">
                    <div class="upload1"></div>
                    <div class="upload1"></div>
                    <div class="upload1"></div>
                    <div class="upload1"></div>
                    <input type="hidden" name="proof">
               </div>
               <div class="photo_part">
                    <div>驾驶证照</div>
                    <div>车辆正面照</div>
                    <div>车辆尾照</div>
                    <div>碰撞位置照片</div>
               </div>
          </div>

          <div class="acc_name">
               <div>姓名</div>
               <input type="text" name="accname" placeholder="姓名">
               <input type="hidden" value="<?php  echo $userid;?>" name="user">
          </div>
          <div class="acc_name">
               <div>对方姓名</div>
               <input type="text" name="accothername" placeholder="姓名">
          </div>

          <div class="acc_number">
               <div>车牌号</div>
               <input type="text" name="accnum" value="苏B">
          </div>

          <div class="acc_number">
               <div>对方车牌号</div>
               <input type="text" name="accothernum" value="苏B">
          </div>

          <div class="acc_ph">
               <div>对方电话</div>
               <input type="text" name="accotherphone" placeholder="手机号">
          </div>

          <div class="acc_range">
               <div>理赔受理范围</div>
               <!--<select>
                   <option>无人员伤亡</option>
                   <option>无人员伤亡</option>
                   <option>无人员伤亡</option>
               </select>-->
          </div>
          <div class="range_box">
               <div>适用于没有人员伤亡，无公共设施损坏，车辆可以移动，各方驾驶人持有效的机动车驾驶证，各方机动车辆有号牌、年检合格，无饮酒、无吸食毒品、麻醉
                    药品，各方车辆均有交强险，各方事故当事人对交通事故事实无异议。</div>
          </div>

          <div class="acc_serv">
               <div>理赔服务点</div>
               <select name="accserve" class="lptitle">
                    <?php  if(is_array($lpaddr)) { foreach($lpaddr as $item) { ?>
                         <option  data-id="<?php  echo $item['id'];?>" class="title" value="<?php  echo $item['title'];?>" data-addr="<?php  echo $item['address'];?>" data-mob="<?php  echo $item['mobile'];?>" data-lat="<?php  echo $item['lat'];?>" data-lng="<?php  echo $item['lng'];?>"><?php  echo $item['title'];?></option>
                    <?php  } } ?>
               </select>
               <button class="linkaddr" data-lat="<?php  echo $lpaddr[0]['lat'];?>" data-lng="<?php  echo $lpaddr[0]['lng'];?>"><i>&#xe602;</i></button>
          </div>
          <div class="serv_box">
               <div>理赔服务点地址、联系电话及工作时间（服务点工作时间： 09:00—17:00）</div>
               <div class="lpaddr"><?php  echo $lpaddr[0]['address'];?></div>
               <div class="lpmobile"><?php  echo $lpaddr[0]['mobile'];?></div>
          </div>
          <a class="acc_step">下一步</a>
     </div>

</div>
<div id="loading-fs">
     <div><i id="loadanim"></i><span>提交数据中，请稍后 ...</span></div>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('wxsdk', TEMPLATE_INCLUDEPATH)) : (include template('wxsdk', TEMPLATE_INCLUDEPATH));?>
<script>
     wx.ready(function() {
          wx.getLocation({
               type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
               success: function (res) {
                    var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                    var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                    var speed = res.speed; // 速度，以米/每秒计
                    var accuracy = res.accuracy; // 位置精度
                    $.ajax({
                         url: 'http://apis.map.qq.com/ws/geocoder/v1/?location=' + latitude + ',' + longitude + '&key=6QRBZ-4YMLK-KXEJP-AWRND-HRGCV-CSF7C&get_poi=1&output=jsonp',
                         type: "get",
                         dataType: "jsonp",
                         jsonp: "callback",
                         success: function (data) {
                              var name = (data.result.address);
                              $('.accaddr').val(name);
                         }
                    });
               }
          });
          /*//获取理赔地图
          $('.linkaddr').on('click', function(){
                _lat = $(this).attr('data-lat');
                _lng = $(this).attr('data-lng');
               var _addr = $('.lpaddr').html();
               var _title = $('.lptitle option:selected').val();
               alert(_lat);
               alert(_lng);
               wx.openLocation({
                    latitude:_lat, // 纬度，浮点数，范围为90 ~ -90
                    longitude:_lng, // 经度，浮点数，范围为180 ~ -180。
                    name: _title, // 位置名
                    address: _addr, // 地址详情说明
                    scale: 28, // 地图缩放级别,整形值,范围从1~28。默认为最大
                    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
               });
          });*/
     });
     //上传图片
     var process = false;
     $(function(){
          //选择理赔服务点
          $('.acc_serv>.lptitle').change(function(){
               var addr = $('option:selected').attr('data-addr');
               var mob = $('option:selected').attr('data-mob');
               var lat = $('option:selected').attr('data-lat');
               var lng = $('option:selected').attr('data-lng');
               $('.lpaddr').html(addr);
               $('.lpmobile').html(mob);
               $('.acc_serv .linkaddr').attr('data-lat',lat);
               $('.acc_serv .linkaddr').attr('data-lng',lng);
          });
          $('.linkaddr').click(function(){
               var _lat = $(this).attr('data-lat');
               var _lng = $(this).attr('data-lng');
               var _addr = $('.lpaddr').html();
               var _title = $('.lptitle option:selected').val();
               window.location.href = 'http://apis.map.qq.com/uri/v1/marker?marker=coord:'+_lat+','+_lng+';title:'+_title+';addr:'+_addr+'&referer=myapp'
          });
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

          $('.acc_step').click(function(){
               if(process)return false;
               var accaddr =  $("[name='accaddr']").val();
               var userid =  $("[name='userid']").val();
               var accname =  $("[name='accname']").val();
               var accothername =  $("[name='accothername']").val();
               var accnum =  $("[name='accnum']").val();
               var accothernum =  $("[name='accothernum']").val();
               var accotherphone =  $("[name='accotherphone']").val();
               var accserve =  $("[name='accserve']").val();
               if (accaddr == ''){
                    alert('请正确输入事故地点');return false;
               }
               if (accname == ''){
                    alert('请输入您的姓名');return false;
               }

               if (accothername == ''){
                    alert('请正确输入对方人的姓名');return false;
               }
               var pattern = /^1[34578]\d{9}$/;
               if (!pattern.test(accotherphone)){
                    alert('手机号码有误！');return false;
               }
               reg=/^苏B[0-9A-Z]{5}$/;
               if (!reg.test(accnum)){
                    alert('请正确输入车牌号');return false;
               }
               if (!reg.test(accothernum)){
                    alert('请正确输入车牌号');return false;
               }
               var pattern = /^1[34578]\d{9}$/;
               if (!pattern.test(accotherphone)){
                    alert('手机号码有误！');return false;
               }

               //获取上传图片地址
               proof = [];
               $(".upload_img>div").each(function(){
                    sid = $(this).data('id');
                    if(sid)proof.push(sid);
               });
               $("[name='proof']").val(proof.join(','));
               proof = $("[name='proof']").val();
               if(!proof){alert("请上传照片！");return false;}
               $("#loading-fs").show();
               $.post("<?php  echo $this->createMobileUrl('postacc')?>",{userid:userid,accaddr:accaddr,accothername:accothername,accname:accname,accothername:accothername,accnum:accnum,accothernum:accothernum,accserve:accserve,accotherphone:accotherphone,proof:proof},function(data){
                    if(data){
                         window.location.href = "<?php  echo $this->createMobileUrl('accident',array('op'=>'success'))?>&renum=" + data;
                    }else{
                         alert('提交失败！');
                    }

               });
               process = true;
          });
     });
</script>
<?php  } ?>
<?php  if($op == 'success') { ?>
<div class="content">
     <div class="content_box">
          <div class="appeal1_box">
               <i class="appeal_suc">&#xe60d;</i>
               <div>您的报警记录号为 <?php  echo $renum;?>，我们将为您尽快处理，请迅速撤离现场。</div>
          </div>
          <a id="appeal_back" href="<?php  echo $this->createMobileUrl('accident',array('op'=>'start'))?>">返回</a>
     </div>
</div>
<?php  } ?>
</body>
</html>

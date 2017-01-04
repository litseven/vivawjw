
var userName;//用户名
//点击生成按钮,我也要玩按钮 跳转到生成页
function openCreatePage(){
	//userName=document.getElementById('name').value;
	//alert(userName);

};

//点击再玩一次 回到首页
function openHomePage(){
};

//点击求分享 打开分享页面
function openSharePage(){
	
};
function openTrouble(){
    document.getElementById('maskDiv').style.display='block';
}
function closeTrouble(){
    document.getElementById('maskDiv').style.display='none';
}

$(function () {
    $(window).on('resize', function () {
        //alert($(window).height());
        if ($(window).height() < 400) {
            $('#btn').css('display', 'none');
            $('#nameTitle').css('display', 'none');
            $('.input_box').css('top', '60%');
            $('.radio_box').css('top', '85%');
            $('#userName').attr('placeholder','输入名字试试看呢');
        }else{
            $('#btn').css('display', 'block');
            $('#nameTitle').css('display', 'block');
            $('.radio_box').css('top', '58.9%');
            $('.input_box').css('top', '43%');
            $('#userName').removeAttr('placeholder');
        }
    });



})




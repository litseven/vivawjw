(function(){

//配置
var config = {
	'audio':{
		'icon':'audio-record-play',
		'text':true
	},
	'loading': 'loading-ic'
};

//loading
window.onload = function(){
	$('#loading').hide();
}

//分享

$('#js-btn-share').bind('tap',function(){
	$('#js-share').show();
})
$('#js-share').bind('tap',function(){
	$(this).hide();
});


var pageIndex = 1,
	pageTotal = $('.page').length,
	towards = { up:1, right:2, down:3, left:4},
	isAnimating = false;

//禁用手机默认的触屏滚动行为
document.addEventListener('touchmove',function(event){
	event.preventDefault(); },false);

$(document).swipeUp(function(){
	if (isAnimating) return;
	if (pageIndex < pageTotal) { 
		pageIndex+=1; 
	} else if (pageIndex == 0) {
		pageIndex=2;
	}else{
		pageIndex+=1;
	};
	pageMove(towards.up);
	
	
	//第一页动画开始
	if(pageIndex == 2){
		
		}
	    
	//第一页动画结束
	
	
	
	//第二页动画开始
	if(pageIndex == 2){
		
		}
	    
	//第二页动画结束
	
	
	//第三页动画开始
	if(pageIndex == 3){
		
		
		}
	   
	//第三页动画结束
	
	
	
	//第四页动画开始
	if(pageIndex == 4 ){
		
		}
	   
	   //第四页动画结束
	   
	   
	 //第五页动画开始
	if(pageIndex == 5 ){
		
	    
		}
	   
	   //第五页动画结束
	   
	 //第六页动画开始
	 if(pageIndex == 6){
		
		 }
	
	 
	 //第六页动画结束  
	  
	   
	  
})

$(document).swipeDown(function(){
	if (isAnimating) return;
	if (pageIndex > 1 && pageIndex <= pageTotal) { 
		pageIndex-=1; 
	} else if (pageIndex>pageTotal) {
		pageIndex = pageTotal-1;
	} else if (pageIndex <= 1) {
//		pageIndex = 0;
		return false;
	}else{
		pageIndex=pageTotal-1;
	};
	pageMove(towards.down);	
	
	
	//第二页动画
	if(pageIndex == 2){
		
		}
		
	//第三页动画
	if(pageIndex == 3){
		
		}
	
	//第四页动画
	if(pageIndex == 4 ){
		
		}
	    
	//第五页动画
	if(pageIndex == 5 ){
		
		}
	
	//第六页动画
	if(pageIndex == 6 ){
		
		}
})





function pageMove(tw){
	var lastPage;
	/*if(tw=='1'){
		if(pageIndex==1){
			lastPage = ".page-"+pageTotal;
		}else{
			lastPage = ".page-"+(pageIndex-1);
		}
		
	}else if(tw=='3'){
		if(pageIndex==pageTotal){
			lastPage = ".page-1";
		}else{
			lastPage = ".page-"+(pageIndex+1);
		}
		
	}*/
	
	/*不是无限循环*/
	if(tw=='1'){
		if(pageIndex==1){
			lastPage = ".page-1";
		}else if (pageIndex > pageTotal) {
			return false;
		}else{
			lastPage = ".page-"+(pageIndex-1);
		}
		
	}else if(tw=='3'){
		if(pageIndex>=pageTotal){
			lastPage = ".page-"+(pageTotal-1);
		} else if (pageIndex < 1) {
			return false;	
		}else {
			lastPage = ".page-"+(pageIndex+1);
		}
	}
			
	
	if(pageIndex > pageTotal){
		var nowPage = ".page-" + (pageTotal-1);
	} else {
		var nowPage = ".page-"+pageIndex;
	}
	switch(tw) {
		case towards.up:
			outClass = 'pt-page-moveToTop';
			inClass = 'pt-page-moveFromBottom';
			break;
		case towards.down:
			outClass = 'pt-page-moveToBottom';
			inClass = 'pt-page-moveFromTop';
			break;
	}
	isAnimating = true;
	$(nowPage).removeClass("hide");

	$(lastPage).addClass(outClass);
	$(nowPage).addClass(inClass);
	
	setTimeout(function(){
		$(lastPage).removeClass('page-current');
		$(lastPage).removeClass(outClass);
		$(lastPage).addClass("hide");
		$(lastPage).find("img").addClass("hide");
		
		$(nowPage).addClass('page-current');
		$(nowPage).removeClass(inClass);
		$(nowPage).find("img").removeClass("hide");
		
		isAnimating = false;
	},600);

}
})();
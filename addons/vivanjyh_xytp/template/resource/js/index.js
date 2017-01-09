
//跳转至内页
function toListPage(){
	location.href='list.html';
}

//投票
function onChoice(obj){
	var currImg = obj.getElementsByTagName('img')[0].src;
	var sum= parseInt(obj.getElementsByTagName('span')[0].innerText);	
	if(currImg.indexOf('choice2-icon.png') > 0){ 
	    // 选择
		obj.getElementsByTagName('img')[0].src='img/choice-icon.png';
		obj.getElementsByTagName('span')[0].innerText = sum + 1;
		//obj.getElementsByClassName('checkedFlag')[0].value = 1;
		//obj.getElementsByClassName('num')[0].value = sum + 1;
	}else{
		// 取消选择
		obj.getElementsByTagName('img')[0].src='img/choice2-icon.png';
		obj.getElementsByTagName('span')[0].innerText = sum - 1;
		//obj.getElementsByClassName('checkedFlag')[0].value = 0;
		//obj.getElementsByClassName('num')[0].value = sum - 1;
	}
	
	
}

//底部导航切换
function clearNavStyle(){
	var nav1 = document.getElementById('nav1');
	nav1.getElementsByTagName('img')[0].src= 'img/bottonbtn1.png';
	nav1.getElementsByTagName('div')[0].style.color='#d0a306';
	
	var nav2 = document.getElementById('nav2');
	nav2.getElementsByTagName('img')[0].src= 'img/bottonbtn2.png';
	nav2.getElementsByTagName('div')[0].style.color='#d0a306';
	
	var nav3 = document.getElementById('nav3');
	nav3.getElementsByTagName('img')[0].src= 'img/bottonbtn3.png';
	nav3.getElementsByTagName('div')[0].style.color='#d0a306';
}

/*//客户经理
function toItem1(){
	clearNavStyle();
	var nav1 = document.getElementById('nav1');
	nav1.getElementsByTagName('img')[0].src= 'img/bottonbtn1-hover.png';
	nav1.getElementsByTagName('div')[0].style.color='#6f4710';
}
//中行及部门
function toItem2(){
	clearNavStyle();
	var nav2 = document.getElementById('nav2');
	nav2.getElementsByTagName('img')[0].src= 'img/bottonbtn2-hover.png';
	nav2.getElementsByTagName('div')[0].style.color='#6f4710';

}
//中支及直属支行
function toItem3(){
	clearNavStyle();
	var nav3 = document.getElementById('nav3');
	nav3.getElementsByTagName('img')[0].src= 'img/bottonbtn3-hover.png';
	nav3.getElementsByTagName('div')[0].style.color='#6f4710';
}*/




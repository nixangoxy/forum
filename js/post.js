// JavaScript Document
window.onload = function(){
	code();
	
	var ubb = document.getElementById('ubb');
	var ubbimg = ubb.getElementsByTagName('img');
	var fm = document.getElementsByTagName('form')[0];
	var font = document.getElementById('font');
	var color = document.getElementById('color');
	var html = document.getElementsByTagName('html')[0];
	
	fm.onsubmit = function(){
		if(fm.title.value.length<2 || fm.title.value.length>40){
			alert('title不得小于2位或者大于40位');
			fm.title.value = '';	//清空
			fm.title.focus();	//将焦点移至表单字段
			return false;
		}
		if(fm.content.value.length<10){
			alert('content不得小于10位');
			fm.content.value = '';	//清空
			fm.content.focus();	//将焦点移至表单字段
			return false;
		}
		//验证码
		if(fm.code.value.length != 4){
			alert('验证码必须4位');
			fm.code.value = '';	//清空
			fm.code.focus();	//将焦点移至表单字段
			return false;
		}
		return true;
	};
	
	var q = document.getElementById('q');
	var qa = q.getElementsByTagName('a');
	
	qa[0].onclick = function(){
		window.open('q.php?num=48&path=qpic/1/','q','width=400,height=400,scrollbars=1');
	};
	qa[1].onclick = function(){
		window.open('q.php?num=10&path=qpic/2/','q','width=400,height=400,scrollbars=1');
	};
	qa[2].onclick = function(){
		window.open('q.php?num=39&path=qpic/3/','q','width=400,height=400,scrollbars=1');
	};
	
	html.onmouseup = function(){
		font.style.display = 'none';
		color.style.display = 'none';
	}
	ubbimg[0].onclick = function(){
		font.style.display = 'block';
	};
	ubbimg[2].onclick = function(){
		content('[b][/b]');
	};
	ubbimg[3].onclick = function(){
		content('[i][/i]');
	};
	ubbimg[4].onclick = function(){
		content('[u][/u]');
	};
	ubbimg[5].onclick = function(){
		content('[s][/s]');
	};
	ubbimg[7].onclick = function(){
		color.style.display = 'block';
		fm.t.focus();
	};
	ubbimg[8].onclick = function(){
		var url = prompt('请输入网址','http://');
		if(url){
			if(/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)){
				content('[url]'+url+'[/url]');
			}else{
				alert('url格式错误');
			}
		}
	};
	ubbimg[9].onclick = function(){
		var email = prompt('请输入电子邮件','@');
		if(email){
			if(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email)){
				content('[email]'+email+'[/email]');
			}else{
				alert('email格式错误');
			}
		}
	};
	ubbimg[10].onclick = function(){
		var img = prompt('请输入图片地址','');
		if(img){
			content('[img]'+img+'[/img]');
		}
	};
	ubbimg[11].onclick = function(){
		var flash = prompt('请输入视频flash','http://');
		if(flash){
			if(/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+/.test(flash)){
				content('[flash]'+flash+'[/flash]');
			}else{
				alert('视频不合法');
			}
		}
	};
	ubbimg[18].onclick = function(){
		fm.content.rows += 2;
	};
	ubbimg[19].onclick = function(){
		fm.content.rows -= 2;
	};
	fm.t.onclick = function(){
		alert(123);
		showcolor(this.value);
	};
	
	function content(string){
		fm.content.value += string;
	}
	
};

function font(size){
	document.getElementsByTagName('form')[0].content.value += '[size='+size+'][/size]';
}

function showcolor(value){
	document.getElementsByTagName('form')[0].content.value += '[color='+value+'][/color]';
}
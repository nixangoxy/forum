<?php
	/**
	*删除非空目录
	*/
	function _remove_Dir($dirName)
	{
	    if(! is_dir($dirName))
	    {
		  return false;
	    }
	    $handle = @opendir($dirName);
	    while(($file = @readdir($handle)) !== false)
	    {
		  if($file != '.' && $file != '..')
		  {
			$dir = $dirName . '/' . $file;
			is_dir($dir) ? _remove_Dir($dir) : @unlink($dir);
		  }
	    }
	    closedir($handle);
	    return rmdir($dirName) ;
	} 

	function _manage_login(){
		if((!isset($_COOKIE['username'])) || (!isset($_SESSION['admin']))){
			_alert_back('非法登录');
		}
	}

	/**
	*设置时间
	*/
	function _timed($_nowtime,$_pretime,$_second){
		if($_nowtime-$_pretime < $_second){
			_alert_back('请阁下休息一会再发帖');
		}
	}

	/**
	*_runtime()是用来获取执行耗时的
	*@access public  表示函数对外公开
	*@return float  表示返回出来的是浮点型数字
	*/
	function _runtime(){
		$_mtime = explode(' ',microtime());
		return $_mtime[1] + $_mtime[0];
	}
	
	/**
	*_alert_back()  表示JS弹窗
	*@access public
	*@param $_info
	*@return void
	*/
	function _alert_back($_info){
		echo "<script type='text/javascript'>alert('".$_info."');history.back();</script>";
		exit();
	}
	
	function _alert_close($_info){
		echo "<script type='text/javascript'>alert('".$_info."');window.close();</script>";
		exit();
	}
	
	function _location($_info,$_url){
		if(!empty($_info)){
			echo "<script type='text/javascript'>alert('".$_info."');location.href='$_url';</script>";
			exit();
		}else{
			header('Location:'.$_url);
		}
	}
	
	/**
	*登录状态的判断
	*防止在登录状态下，然后直接在地址栏跳转到注册界面
	*/
	function _login_state(){
		if(isset($_COOKIE['username'])){
			_alert_back('登录状态无法进行本操作');
		}
	}
	
	/**
	*判断唯一标示符是否异常
	*/
	function _uniqid($_mysql_uniqid,$_cookie_uniqid){
		//为了防止cookie伪造，还要比对一下唯一标示符uniqid()
		if($_mysql_uniqid != $_cookie_uniqid){
			_alert_back('唯一标示符异常');
		}
	}
	
	//生成缩略图
	function _thumb($_filename,$_percent) {
		//生成png标头文件
		header('Content-type: image/png');
		$_n = explode('.',$_filename);
		//获取文件信息，长和高
		list($_width, $_height) = getimagesize($_filename);
		//生成缩微的长和高
		$_new_width = $_width * $_percent;
		$_new_height = $_height * $_percent;
		//创建一个以0.3百分比新长度的画布
		$_new_image = imagecreatetruecolor($_new_width,$_new_height);
		//按照已有的图片创建一个画布
		switch ($_n[1]) {
			case 'jpeg':
			case 'jpg' : $_image = imagecreatefromjpeg($_filename);
				break;
			case 'png' : $_image = imagecreatefrompng($_filename);
				break;
			case 'gif' : $_image = imagecreatefrompng($_filename);
				break;
		}
		echo "<script>alert($_width);</script>";
		//将原图采集后重新复制到新图上，就缩略了
		imagecopyresampled($_new_image, $_image, 0, 0, 0, 0, $_new_width,$_new_height, $_width, $_height);
		ob_clean();	//清空输出缓冲区，我的妈呀，终于找到解决方案了，加了这句就能显示图片了
		imagepng($_new_image);
		imagedestroy($_new_image);
		imagedestroy($_image);
	}
	
	function _get_xml($_xmlfile){
		$_html = array();
		if(file_exists($_xmlfile)){
			$_xml = file_get_contents('new.xml');
			preg_match_all('/<vip>(.*)<\/vip>/s',$_xml,$_dom);
			foreach($_dom[1] as $_value){
				preg_match_all('/<id>(.*)<\/id>/s',$_value,$_id);
				preg_match_all('/<username>(.*)<\/username>/s',$_value,$_username);
				preg_match_all('/<sex>(.*)<\/sex>/s',$_value,$_sex);
				preg_match_all('/<face>(.*)<\/face>/s',$_value,$_face);
				preg_match_all('/<email>(.*)<\/email>/s',$_value,$_email);
				preg_match_all('/<url>(.*)<\/url>/s',$_value,$_url);
				$_html['id'] = $_id[1][0];
				$_html['username'] = $_username[1][0];
				$_html['sex'] = $_sex[1][0];
				$_html['face'] = $_face[1][0];
				$_html['email'] = $_email[1][0];
				$_html['url'] = $_url[1][0];
			}
		}else{
			echo "文件不存在";
		}
		return $_html;
	}
	
	function _set_xml($_xmlfile,$_clean){
		$_fp = fopen('new.xml','w');
		if(!$_fp){
			exit('系统错误，文件不存在！');
		}
		flock($_fp,LOCK_EX);
		
		$_string = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "<vip>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<id>{$_clean['id']}</id>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<username>{$_clean['username']}</username>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<sex>{$_clean['sex']}</sex>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<face>{$_clean['face']}</face>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<email>{$_clean['email']}</email>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "\t<url>{$_clean['url']}</url>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		$_string = "</vip>\r\n";
		fwrite($_fp,$_string,strlen($_string));
		
		flock($_fp,LOCK_UN);
		fclose($_fp);
	}
	
	function _ubb($_string){
		$_string = nl2br($_string);	//将\n转换为<br/>
		$_string = preg_replace('/\[size=(.*)\](.*)\[\/size\]/U','<span style="font-size:\1px">\2</span>',$_string);
		$_string = preg_replace('/\[b\](.*)\[\/b\]/U','<strong>\1</strong>',$_string);
		$_string = preg_replace('/\[i\](.*)\[\/i\]/U','<em>\1</em>',$_string);
		$_string = preg_replace('/\[u\](.*)\[\/u\]/U','<span style="text-decoration:underline">\1</span>',$_string);
		$_string = preg_replace('/\[s\](.*)\[\/s\]/U','<span style="text-decoration:line-through">\1</span>',$_string);
		$_string = preg_replace('/\[color=(.*)\](.*)\[\/color\]/U','<span style="color:\1">\2</span>',$_string);
		$_string = preg_replace('/\[url\](.*)\[\/url\]/U','<a href="\1" target="_blank">\1</a>',$_string);
		$_string = preg_replace('/\[email\](.*)\[\/email\]/U','<a href="mailto:\1">\1</a>',$_string);
		$_string = preg_replace('/\[img\](.*)\[\/img\]/U','<img src="\1" alt="图片" />',$_string);
		$_string = preg_replace('/\[flash\](.*)\[\/flash\]/U','<embed style="width:480px;height:400px" src="\1" />',$_string);
		return $_string;
	}
	
	/**
	*标题截取函数
	*/
	function _title($_string,$_strlen=14){
		if(mb_strlen($_string,'utf-8')>$_strlen){
			$_string = mb_substr($_string,0,14,'utf-8')."...";
		}
		return $_string;
	}
	
	/**
	*过滤html，使得输入的html不对页面进行改变
	*/
	function _html($_string){
		if(is_array($_string)){
			foreach($_string as $_key => $_value){
				$_string[$_key] = @htmlspecialchars($_value);
			}
		}else{
			$_string = @htmlspecialchars($_string);
		}
		return $_string;
	}
	
	/**
	*是否需要转义
	*/
	function _mysql_string($_string){
		//get_magic_quotes.gpc()如果开启状态，那么就不需要转义
		if(!GPC){
			if(is_array($_string)){
				foreach($_string as $_key => $_value){
					$_string[$_key] = _mysql_string($_value);
				}
			}else{
				$_string = mysql_real_escape_string($_string);
			}
		}
		
		return $_string;
	}
	
	/**
	*分页参数
	*/
	function _page($_sql,$_size = 10){
		//将里面的所有变量都取出来，外部可以访问
		global $_page,$_pagesize,$_pagenum,$_pageabsolute,$_num;
		//分页模块
		if(isset($_GET['page'])){
			$_page = $_GET['page'];	//第几页
			if(empty($_page) || $_page<=0 || !is_numeric($_page)){
				$_page = 1;
			}else{
				$_page = intval($_page);	//取整数部分
			}
		}else{
			$_page = 1;
		}
		
		$_pagesize = $_size;
		
		//首先要得到所有的数据总和
		$_num = _num_rows(_query($_sql));
		if($_num == 0){
			$_pageabsolute = 1;
		}else{
			$_pageabsolute = ceil($_num / $_pagesize);
		}
		if($_page > $_pageabsolute){
			$_page = $_pageabsolute;
		}
		$_pagenum = ($_page-1)*$_pagesize;	//每一页第一张图片是第几	
	}
	
	/**
	*分页函数
	*/
	function _paging($_type){
		global $_page,$_pageabsolute,$_num,$_id;
		if($_type == 1){				//数字分页
			echo '<div id="page_num">';
				echo '<ul>';
				for($i=0;$i<$_pageabsolute;$i++){
					if($_page == $i+1){
						echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'" class="selected">'.($i+1).'</a></li>';
					}else{
						echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($i+1).'">'.($i+1).'</a></li>';
					}
				}                       
				echo '</ul>';
			echo '</div>';
		}else if($_type == 2){		//文本分页
            	echo '<div id="page_text">';
                        echo '<ul>';
                              echo '<li>'.$_page.'/'.$_pageabsolute.'页 | </li>';
                              echo '<li>共有<strong>'.$_num.'</strong>条数据 | </li>';
					
                              if($_page == 1){
                              	echo '<li>首页 | </li>';
                                    echo '<li>上一页 | </li>';
                              }else{
                                    echo '<li><a href="'.SCRIPT.'.php">首页</a> | </li>';
                                    echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page-1).'">上一页</a> | </li>';
                              }
                              if($_page == $_pageabsolute){
                                    echo '<li>下一页 | </li>';
                                    echo '<li>尾页</li>';
                              }else{
                                    echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_page+1).'">下一页</a> | </li>';
                                    echo '<li><a href="'.SCRIPT.'.php?'.$_id.'page='.($_pageabsolute).'">尾页</a></li>';
                              }

                        echo '</ul>';
                  echo '</div>';
		}else{
			_paging(2);
		}
	}
	
	/**
	*删除session
	*/
	function _session_destroy(){
		//首先判断是否有开启session，有的话猜删除
		if(isset($_SESSION)){
			session_destroy();
		}
	}
	
	function _unsetcookies(){
		setcookie('username','',time()-1);
		setcookie('uniqid','',time()-1);
		//setcookie('photo','',time()-1);
		_session_destroy();
		_location(null,'index.php');
	}
	
	function _sha1_uniqid(){
		return _mysql_string(sha1(uniqid(rand(),true)));
	}
	
	/**
	*验证码比对
	*/
	function _check_code($_first_code,$_end_code){
		if($_first_code != $_end_code){
			_alert_back('验证码不正确');
		}	
	}
	
	/**
	*_code()是验证码函数
	*@access public
	*@param int $_width  表示验证码的长度
	*@param int $_height  表示验证码的高度
	*@param int $_rnd_num  表示验证码的位数
	*@param bool $_flag  表示验证码是否需要边框
	*@return void  这个函数执行后产生一个验证码
	*/
	function _code($_width=75,$_height=25,$_rnd_num=4,$_flag=false){
		//创建随机码
		$_nmsg = '';
		for($i=0;$i<$_rnd_num;$i++){
			$_nmsg .= dechex(mt_rand(0,15));
		}
		
		//保存在session里
		$_SESSION['code'] = $_nmsg;
		
		//创建一张图像
		$_img = imagecreatetruecolor($_width,$_height);
		
		//白色
		$_white = imagecolorallocate($_img,255,255,255);
		//填充
		imagefill($_img,0,0,$_white);
		
		$_black = '';
		if($_flag){
			//黑色边框
			$_black = imagecolorallocate($_img,0,0,0);
			imagerectangle($_img,0,0,$_width-1,$_height-1,$_black);
		}
		
		//随机画出6个线条
		for($i=0;$i<6;$i++){
			$_rnd_color = imagecolorallocate($_img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imageline($_img,mt_rand(0,$_width),mt_rand(0,$_height),mt_rand(0,$_width),mt_rand(0,$_height),$_rnd_color);
		}
		
		//随机雪花
		for($i=0;$i<100;$i++){
			$_rnd_color = imagecolorallocate($_img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
			imagestring($_img,1,mt_rand(1,$_width),mt_rand(1,$_height),'*',$_rnd_color);
		}
		
		//输出验证码
		for($i=0;$i<strlen($_SESSION['code']);$i++){
			$_rnd_color = imagecolorallocate($_img,mt_rand(0,100),mt_rand(0,150),mt_rand(0,200));
			imagestring($_img,5,$i*$_width/$_rnd_num+mt_rand(1,10),mt_rand(1,$_height/2),$_SESSION['code'][$i],$_rnd_color);
		}
		
		//输出图像
		header('Content-Type:image/png');
		imagepng($_img);
		
		//销毁
		imagedestroy($_img);
	}
?>
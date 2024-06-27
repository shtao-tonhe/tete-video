<?php
	/* Redis链接 */
	function connectionRedis(){
		$REDIS_HOST= DI()->config->get('app.REDIS_HOST');
		$REDIS_AUTH= DI()->config->get('app.REDIS_AUTH');
		$REDIS_PORT= DI()->config->get('app.REDIS_PORT');
		$redis = new Redis();
		$redis -> pconnect($REDIS_HOST,$REDIS_PORT);
		$redis -> auth($REDIS_AUTH);

		DI()->redis=$redis;
	}
	/* 设置缓存 */
	function setcache($key,$info){
		$config=getConfigPri();
		if($config['cache_switch']!=1){
			return 1;
		}

		DI()->redis->set($key,json_encode($info));
		DI()->redis->expire($key, $config['cache_time']); 

		return 1;
	}	
	/* 设置缓存 可自定义时间*/
	function setcaches($key,$info,$time=0){
		DI()->redis->set($key,json_encode($info));
        if($time>0){
            DI()->redis->expire($key, $time); 
        }
		
		return 1;
	}
	/* 获取缓存 */
	function getcache($key){
		$config=getConfigPri();

		if($config['cache_switch']!=1){
			$isexist=false;
		}else{
			$isexist=DI()->redis->Get($key);
		}

		return json_decode($isexist,true);
	}		
	/* 获取缓存 不判断后台设置 */
	function getcaches($key){

		$isexist=DI()->redis->Get($key);
		
		return json_decode($isexist,true);
	}
	/* 删除缓存 */
	function delcache($key){
		$isexist=DI()->redis->del($key);
		return 1;
	}
    
	/* 去除NULL 判断空处理 主要针对字符串类型*/
	function checkNull($checkstr){
		$checkstr=urldecode($checkstr);
		$checkstr=htmlspecialchars($checkstr);
		$checkstr=trim($checkstr);
		//$checkstr=filterEmoji($checkstr);
		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		return $str;	
	}
	
	/* 去除emoji表情 */
	function filterEmoji($str){
		$str = preg_replace_callback(
			'/./u',
			function (array $match) {
				return strlen($match[0]) >= 4 ? '' : $match[0];
			},
			$str);
		return $str;
	}
    

	/* 检验手机号 */
	function checkMobile($mobile){

		$ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}else{
			return 0;
		}
	}
	/* 随机数 */
	function random($length = 6 , $numeric = 0) {
		PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
		if($numeric) {
			$hash = sprintf('%0'.$length.'d', mt_rand(0, pow(10, $length) - 1));
		} else {
			$hash = '';
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789abcdefghjkmnpqrstuvwxyz';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) {
				$hash .= $chars[mt_rand(0, $max)];
			}
		}
		return $hash;
	}	
	/* 发送验证码 - 互译无线*/
	function sendCode_huyi($mobile,$code){
		$rs=array();
		$config = getConfigPri();

		if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;
        }

		/* 互亿无线 */
		$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
		
		$post_data = "account=".$config['ihuyi_account']."&password=".$config['ihuyi_ps']."&mobile=".$mobile."&content=".rawurlencode("您的验证码是：".$code."。请不要把验证码泄露给其他人。");
		//密码可以使用明文密码或使用32位MD5加密
		$gets = xml_to_array(Post($post_data, $target));

		if($gets['SubmitResult']['code']==2){
			$rs['code']=0;
		}else{
			$rs['code']=1002;
			//$rs['msg']=$gets['SubmitResult']['msg'];
			$rs['msg']="获取失败";
		} 
		return $rs;
	}
	
	function Post($curlPost,$url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
		$return_str = curl_exec($curl);
		curl_close($curl);
		return $return_str;
	}
	
	function xml_to_array($xml){
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return $arr;
	}

    
    /* 发送验证码 -- 阿里云 */
	function sendCode($country_code,$mobile,$code){
        
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$config = getConfigPri();
        
        if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;
        }
        
       if($config['code_switch']=='1'){//阿里云
			$res=sendCodeByAli($country_code,$mobile,$code);
		}else{
			$res=sendCodeByRonglian($mobile,$code);//容联云
		}


		return $rs;
	}

	function sendCodeByRonglian($mobile,$code){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$config = getConfigPri();
        require_once API_ROOT.'/../sdk/ronglianyun/CCPRestSDK.php';
        
        //主帐号
        $accountSid= $config['ccp_sid'];
        //主帐号Token
        $accountToken= $config['ccp_token'];
        //应用Id
        $appId=$config['ccp_appid'];
        //请求地址，格式如下，不需要写https://
        $serverIP='app.cloopen.com';
        //请求端口 
        $serverPort='8883';
        //REST版本号
        $softVersion='2013-12-26';
        
        $tempId=$config['ccp_tempid'];
        
        //file_put_contents(API_ROOT.'/../data/sendCode_rly_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 post_data: accountSid:'.$accountSid.";accountToken:{$accountToken};appId:{$appId};tempId:{$tempId}\r\n",FILE_APPEND);

        $rest = new REST($serverIP,$serverPort,$softVersion);
        $rest->setAccount($accountSid,$accountToken);
        $rest->setAppId($appId);
        
        $datas=[];
        $datas[]=$code;
        
        $result = $rest->sendTemplateSMS($mobile,$datas,$tempId);
        //file_put_contents(API_ROOT.'/../data/sendCode_rly_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
        
         if($result == NULL ) {
            $rs['code']=1002;
			$rs['msg']="获取失败";
            return $rs;
         }
         if($result->statusCode!='000000') {
            $rs['code']=1002;
			$rs['msg']="获取失败";
            return $rs;
         }
        

		return $rs;
	}

	//阿里云短信
	function sendCodeByAli($country_code,$mobile,$code){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $configpri = getConfigPri();

        //判断是否是国外
        $aly_sendcode_type=$configpri['aly_sendcode_type'];
        if($aly_sendcode_type==1 && $country_code!=86){ //国内
        	$rs['code']=1002;
			$rs['msg']="平台短信仅支持中国大陆地区";
            return $rs;
        }

        if($aly_sendcode_type==2 && $country_code==86){
        	$rs['code']=1002;
			$rs['msg']='平台短信仅支持国际/港澳台地区';
			return $rs;
        }
		
		require_once API_ROOT.'/../sdk/aliyunsms/AliSmsApi.php';

		$config_dl  = array(
            'accessKeyId' => $configpri['aly_keyid'], 
            'accessKeySecret' => $configpri['aly_secret'], 
            'PhoneNumbers' => $mobile, 
            'SignName' => $configpri['aly_signName'], //国内短信签名 
            'TemplateCode' => $configpri['aly_templateCode'], //国内短信模板ID
            'TemplateParam' => array("code"=>$code) 
        );

        $config_hw  = array(
            'accessKeyId' => $configpri['aly_keyid'], 
            'accessKeySecret' => $configpri['aly_secret'], 
            'PhoneNumbers' => $country_code.$mobile, 
            'SignName' => $configpri['aly_hw_signName'], //港澳台/国外短信签名 
            'TemplateCode' => $configpri['aly_hw_templateCode'], //港澳台/国外短信模板ID
            'TemplateParam' => array("code"=>$code) 
        );
        
		if($aly_sendcode_type==1){ //国内
            $config=$config_dl;
        }else if($aly_sendcode_type==2){ //国际/港澳台地区
            $config=$config_hw;
        }else{

            if($country_code==86){
                $config=$config_dl;
            }else{
                $config=$config_hw;
            }
        }
		 
		$go = new \AliSmsApi($config);
		$result = $go->send_sms();
		file_put_contents(API_ROOT.'/../log/sendCode_aly_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
		
        if($result == NULL ) {
            $rs['code']=1002;
			$rs['msg']="发送失败";
            return $rs;
        }
		if($result['Code']!='OK') {
            //TODO 添加错误处理逻辑
            $rs['code']=1002;
			$rs['msg']="获取失败";
            return $rs;
        }
		return $rs;
	}

	//腾讯云短信
	function sendCodeByTencentSms($nationCode,$mobile,$code){
		require_once API_ROOT."/../sdk/tencentSms/index.php";
		$rs=array();
		$configpri = getConfigPri();
        
        $appid=$configpri['tencent_sms_appid'];
        $appkey=$configpri['tencent_sms_appkey'];


		$smsSign = '';
		$tencent_sendcode_type=$configpri['tencent_sendcode_type'];

		if($tencent_sendcode_type==1){ //中国大陆
			$smsSign = $configpri['tencent_sms_signName'];
			$templateId = $configpri['tencent_sms_templateCode'];
		}else{
			$smsSign=$configpri['tencent_sms_hw_signName'];
			$templateId = $configpri['tencent_sms_hw_templateCode'];
		}


	
		$sender = new \Qcloud\Sms\SmsSingleSender($appid,$appkey);

		$params = [$code]; //参数列表与腾讯云后台创建模板时加的参数列表保持一致
		$result = $sender->sendWithParam($nationCode, $mobile, $templateId, $params, $smsSign, "", "");  // 签名参数未提供或者为空时，会使用默认签名发送短信
				
		//file_put_contents(API_ROOT.'/../log/sendCode_tencent_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
		$arr=json_decode($result,TRUE);

		if($arr['result']==0 && $arr['errmsg']=='OK'){
            //setSendcode(array('type'=>'1','account'=>$mobile,'content'=>"验证码:".$code."---国家区号:".$nationCode));
			$rs['code']=0;
		}else{
			$rs['code']=1002;
			$rs['msg']=$arr['errmsg'];
			// $rs['msg']='验证码发送失败';
		} 
		return $rs;		
				
	}
	
	/* 检测文件后缀 */
	function checkExt($filename){
		$config=array("jpg","png","jpeg");
		$ext   =   pathinfo(strip_tags($filename), PATHINFO_EXTENSION);
		 
		return empty($config) ? true : in_array(strtolower($ext), $config);
	}	    
	
	/* 密码检查 */
	function passcheck($user_pass) {
		$num = preg_match("/^[a-zA-Z]+$/",$user_pass);
		$word = preg_match("/^[0-9]+$/",$user_pass);
		$check = preg_match("/^[a-zA-Z0-9]{6,12}$/",$user_pass);
		if($num || $word ){
			return 2;
		}else if(!$check){
			return 0;
		}		
		return 1;
	}
	
	/* 密码加密 */
	function setPass($pass){
		$authcode='rCt52pF2cnnKNB3Hkp';
		$pass="###".md5(md5($authcode.$pass));
		return $pass;
	}	
	
	/* 公共配置 */
	function getConfigPub() {
		$key='getConfigPub';
		$config=getcaches($key);

		if(!$config){
			$config= DI()->notorm->option
					->select('option_value')
					->where("option_name='site_info'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
		}

		if(is_array($config['login_type'])){
            
        }else if($config['login_type']){
            $config['login_type']=preg_split('/,|，/',$config['login_type']);
        }else{
            $config['login_type']=array();
        }
        
        if(is_array($config['share_type'])){
            
        }else if($config['share_type']){
            $config['share_type']=preg_split('/,|，/',$config['share_type']);
        }else{
            $config['share_type']=array();
        }
        
        $config['watermark']=get_upload_path($config['watermark']);
        
		return 	$config;
	}		
	
	/* 私密配置 */
	function getConfigPri() {
		$key='getConfigPri';
		$config=getcaches($key);

		if(!$config){
			$config= DI()->notorm->option
					->select('option_value')
					->where("option_name='configpri'")
					->fetchOne();
            $config=json_decode($config['option_value'],true);
			setcaches($key,$config);
		}
        
        
		return 	$config;
	}		
	
	/**
	 * 返回带协议的域名
	 */
	function get_host(){
		$config=getConfigPub();
		return $config['site'];
	}	
	
	/**
	 * 转化数据库保存的文件路径，为可以访问的url cloudtype:云存储方式 0 本地 1 七牛云 2 腾讯云
	 */
	function get_upload_path($file){

        if($file==''){
            return $file;
        }

        $configpri=getConfigPri();

		if(strpos($file,"http")===0){

			//将字符串分隔
			$file_arr=explode('%@%cloudtype=',$file);
			

			$cloudtype=$file_arr['1'];
			$file=$file_arr['0'];

			if(!isset($cloudtype)){
				return html_entity_decode($file);
			}


		
			
			if(strpos($file,"http")===0){
				return html_entity_decode($file_arr['0']);
			}else if($cloudtype==1){ //存储方式为七牛
				return html_entity_decode($file);
			}else if($cloudtype==2 && $configpri['tx_private_signature']){
				return setTxUrl(html_entity_decode($file)); //腾讯云存储为私有读写时需要调用该方法获取签名验证
			}else if($cloudtype==3){ //存储方式为亚马逊
				return html_entity_decode($file);
			}else {
				return html_entity_decode($file);
			}
	
			
		}else if(strpos($file,"/")===0){

			$filepath= get_host().$file;
			return html_entity_decode($filepath);

		}else{
			//$space_host= DI()->config->get('app.Qiniu.space_host');
			//$filepath=$space_host."/".$file;

			//将字符串分隔
			$file_arr=explode('%@%cloudtype=',$file);

			$cloudtype=$file_arr['1'];
			$file=$file_arr['0'];

			if($cloudtype==1){ //七牛存储
				$space_host=$configpri['qiniu_protocol']."://".$configpri['qiniu_domain']."/";
			}else if($cloudtype==2){ //腾讯云存储
				$space_host=$configpri['tx_domain_url'];
			}else if($cloudtype==3){ //亚马逊存储
				$space_host=$configpri['aws_hosturl'];
			}else{
				$space_host="http://";
			}
			
			$filepath=$space_host.$file;

			if(!isset($cloudtype)){
				return html_entity_decode($filepath);
			}

			if($cloudtype==2 && $configpri['tx_private_signature']){ //腾讯云存储 且 需要签名验证
				return setTxUrl(html_entity_decode($filepath)); //腾讯云存储为私有读写时需要调用该方法获取签名验证
			}else{
				return html_entity_decode($filepath);
			}

			
		}
	}
	
	/* 判断是否关注 */
	function isAttention($uid,$touid) {

		if($touid==0){  //系统管理员直接返回1，不让用户关注系统管理员
			return "1";
		}

		if($uid<0||$touid<0){
			return "0";
		}

		$isexist=DI()->notorm->user_attention
					->select("*")
					->where('uid=? and touid=?',$uid,$touid)
					->fetchOne();
		if($isexist){
			return  '1';
		}else{
			return  '0';
		}			 
	}
	/* 是否黑名单 */
	function isBlack($uid,$touid) {	
		$isexist=DI()->notorm->user_black
				->select("*")
				->where('uid=? and touid=?',$uid,$touid)
				->fetchOne();
		if($isexist){
			return '1';
		}else{
			return '0';					
		}
	}	
	
	
	/* 判断token */
	function checkToken($uid,$token) {

		//判断用户是否存在
		$is_exist=checkUserIsExist($uid);

		if(!$is_exist){
			return 700;
		}

		$userinfo=getcaches("token_".$uid);
		if(!$userinfo){
			$userinfo=DI()->notorm->user_token
						->select('token,expire_time')
						->where('user_id = ? ', $uid)
						->fetchOne();
			
			setcaches("token_".$uid,$userinfo);
		}
		
		if($userinfo['token']!=$token || $userinfo['expire_time']<time()){

			return 700;				
		}
         
        $isBlackUser=isBlackUser($uid);         
        if($isBlackUser==0){
			return 10020;//账号被禁用
		}
			
        return 	0;				 		
	}	
	
	/* 用户基本信息 */
	function getUserInfo($uid,$tree='') {


		if($uid==0){
			if($uid==='dsp_admin_1'){

				$config=getConfigPub(); 

				$info['user_nicename']=$config['app_name']."官方";	
				$info['avatar']=get_upload_path('/officeMsg.png');
				$info['avatar_thumb']=get_upload_path('/officeMsg.png');
				$info['id']="dsp_admin_1";

			}else if($uid==='dsp_admin_2'){

				$info['user_nicename']="系统通知";	
				$info['avatar']=get_upload_path('/systemMsg.png');
				$info['avatar_thumb']=get_upload_path('/systemMsg.png');
				$info['id']="dsp_admin_2";
			}else if($uid==='goodsorder_admin'){

				$info['user_nicename']="订单消息";	
				$info['avatar']=get_upload_path('/goodsorderMsg.png');
				$info['avatar_thumb']=get_upload_path('/goodsorderMsg.png');
				$info['id']="goodsorder_admin";
			}else{

				$info['user_nicename']="系统管理员";	
				$info['avatar']=get_upload_path('/default.png');
				$info['avatar_thumb']=get_upload_path('/default_thumb.png');
				$info['id']="0";
			}

			$info['coin']="0";
			$info['sex']="1";
			$info['signature']='';
			$info['province']='';
			$info['city']='城市未填写';
			$info['birthday']='';
			$info['praise']='0';
			$info['fans']='0';
			$info['follows']='0';
			$info['workVideos']='0'; //作品数
			$info['likeVideos']='0'; //喜欢别人的视频数
			$info['age']="年龄未填写";
			$info['vipinfo']=array('isvip'=>'0','vip_endtime'=>'');
			$info['issuper']="0";
			$info['votestotal']="0";
			$info['consumption']="0";
			$info['hometown']="";
			$info['user_status']="1";
			$info['isrecommend']="0";
			$info['recommend_time']="0";
			$info['is_firstlogin']="0";

		}else{

			$info=getCache("userinfo_".$uid);
			$info=false;
			if(!$info){
				$info=DI()->notorm->user
						->select('id,user_nicename,avatar,avatar_thumb,sex,signature,province,city,area,birthday,age,issuper,coin,votestotal,consumption,user_status,isrecommend,recommend_time,is_firstlogin')
						->where('id=? and user_type="2"',$uid)
						->fetchOne();

				if($info){

					if($info['age']<0){
						$info['age']="年龄未填写";
					}else{
						$info['age'].="岁";
					}

					if($info['city']==""){
						$info['city']="城市未填写";
					}

					if($info['user_status']==3){ //账号已注销

						$info['praise']='0';
						$info['fans']='0';
						$info['follows']='0';
						$info['workVideos']='0';
						$info['likeVideos']='0';
						$info['vipinfo']=array('isvip'=>'0','vip_endtime'=>'');

					}else{
						$info['praise']=getPraises($uid);
						$info['fans']=getFans($uid);
						$info['follows']=getFollows($uid);
						$info['workVideos']=getWorks($uid);
						$info['likeVideos']=getLikes($uid);
						$info['vipinfo']=getUserVipInfo($uid); //获取用户的vip信息
					}

					$info['avatar']=get_upload_path($info['avatar']);
					$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
					$info['hometown']=$info['province'].$info['city'].$info['area'];



				}else{

					$info['user_nicename']='用户';
					$info['avatar']=get_upload_path('/default.png');
					$info['avatar_thumb']=get_upload_path('/default_thumb.png');
					$info['sex']='0';
					$info['signature']='这家伙很懒，什么都没留下';
					$info['province']='省份未填写';
					$info['city']='城市未填写';
					$info['birthday']='';
					$info['age']="年龄未填写";
					$info['praise']='0';
					$info['fans']='0';
					$info['follows']='0';
					$info['workVideos']='0';
					$info['likeVideos']='0';
					$info['vipinfo']=array('isvip'=>'0','vip_endtime'=>'');
					$info['issuper']="0";
					$info['coin']="0";
					$info['votestotal']="0";
					$info['consumption']="0";  //app端做格式化处理 如1.6万
					$info['hometown']="";
					$info['hometown']="1";
					$info['isrecommend']="0";
					$info['recommend_time']="0";
					$info['is_firstlogin']="0";


				}
			
			}

			if($tree){

				$info['user_nicename']=eachReplaceSensitiveWords($tree,$info['user_nicename']); //用户昵称过滤敏感词
				$info['signature']=eachReplaceSensitiveWords($tree,$info['signature']); //个性签名过滤敏感词
			}else{
				$info['user_nicename']=ReplaceSensitiveWords($info['user_nicename']); //用户昵称过滤敏感词
				$info['signature']=ReplaceSensitiveWords($info['signature']); //个性签名过滤敏感词
			}

		}

		


		return 	$info;		
	}
	
	
	/* 统计 关注 */
	function getFollows($uid) {
		$count=DI()->notorm->user_attention
				->where('uid=? and touid>0 ',$uid)  //关注系统管理员不显示
				->count();
		return 	$count;
	}

	/* 统计 个人作品数 */
	function getWorks($uid) {
		$count=DI()->notorm->user_video
				->where('uid=? and isdel=0 and status=1 and is_ad=0',$uid)
				->count();
		return 	$count;
	}

	/* 统计 个人喜欢其他人的作品数 */
	function getLikes($uid) {
		

		$count=DI()->notorm->user_video_like
				->where('uid=? and status=1',$uid)  //status=1表示视频状态正常，未被二次拒绝或被下架
				->count();

		return 	$count;
	}			
	
	/* 统计 粉丝 */
	function getFans($uid) {
		$count=DI()->notorm->user_attention
				->where('touid=? ',$uid)
				->count();
		return 	$count;
	}		
	/**
	*  @desc 根据两点间的经纬度计算距离
	*  @param float $lat 纬度值
	*  @param float $lng 经度值
	*/
	function getDistance($lat1, $lng1, $lat2, $lng2){
		$earthRadius = 6371000; //近似地球半径 单位 米
		 /*
		   Convert these degrees to radians
		   to work with the formula
		 */

		$lat1 = ($lat1 * pi() ) / 180;
		$lng1 = ($lng1 * pi() ) / 180;

		$lat2 = ($lat2 * pi() ) / 180;
		$lng2 = ($lng2 * pi() ) / 180;


		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;
		
		$distance=$calculatedDistance/1000;
		if($distance<10){
			$rs=round($distance,2);
		}else if($distance > 1000){
			$rs='>1000';
		}else{
			$rs=round($distance);
		}
		return $rs.'km';
	}
	/* 判断账号是否禁用 */
	function isBan($uid){
		$status=DI()->notorm->user
					->select("user_status")
					->where('id=?',$uid)
					->fetchOne();
		if(!$status || $status['user_status']==0){
			return 0;
		}
		return 1;
	}
	/* 是否认证 */
	function isAuth($uid){
		$status=DI()->notorm->user_auth
					->select("status")
					->where('uid=?',$uid)
					->fetchOne();
		if($status && $status['status']==1){
			return 1;
		}

		return 0;
	}
	/* 过滤字符 */
	function filterField($field){
		$configpri=getConfigPri();
		
		$sensitive_field=$configpri['sensitive_field'];
		
		$sensitive=explode(",",$sensitive_field);
		$replace=array();
		$preg=array();
		foreach($sensitive as $k=>$v){
			if($v){
				$re='';
				$num=mb_strlen($v);
				for($i=0;$i<$num;$i++){
					$re.='*';
				}
				$replace[$k]=$re;
				$preg[$k]='/'.$v.'/';
			}else{
				unset($sensitive[$k]);
			}
		}
		
		return preg_replace($preg,$replace,$field);
	}
	/* 时间差计算 */
	function datetime($time){
		$cha=time()-$time;
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		/* 秒 */
		$s=$cha%60;
		/* 分 */
		$i=floor($iz%60);
		/* 时 */
		$h=floor($hz/24);
		/* 天 */
		
		if($cha<60){
			return $cha.'秒前';
		}else if($iz<60){
			return $iz.'分钟前';
		}else if($hz<24){
			return $hz.'小时前';
		}else if($dz<30){
			return $dz.'天前';
		}else{
			return date("Y-m-d",$time);
		}
	}

	/* 时长格式化 */
	function getSeconds($time,$type=0){

		if(!$time){
			return (string)$time;
		}

	    $value = array(
	      "years"   => 0,
	      "days"    => 0,
	      "hours"   => 0,
	      "minutes" => 0,
	      "seconds" => 0
	    );

	    if($time >= 31556926){
	      $value["years"] = floor($time/31556926);
	      $time = ($time%31556926);
	    }
	    if($time >= 86400){
	      $value["days"] = floor($time/86400);
	      $time = ($time%86400);
	    }
	    if($time >= 3600){
	      $value["hours"] = floor($time/3600);
	      $time = ($time%3600);
	    }
	    if($time >= 60){
	      $value["minutes"] = floor($time/60);
	      $time = ($time%60);
	    }
	    $value["seconds"] = floor($time);

	    if($value['years']){
	    	if($type==1&&$value['years']<10){
	    		$value['years']='0'.$value['years'];
	    	}
	    }

	    if($value['days']){
	    	if($type==1&&$value['days']<10){
	    		$value['days']='0'.$value['days'];
	    	}
	    }

	    if($value['hours']){
	    	if($type==1&&$value['hours']<10){
	    		$value['hours']='0'.$value['hours'];
	    	}
	    }

	    if($value['minutes']){
	    	if($type==1&&$value['minutes']<10){
	    		$value['minutes']='0'.$value['minutes'];
	    	}
	    }

	    if($value['seconds']){
	    	if($type==1&&$value['seconds']<10){
	    		$value['seconds']='0'.$value['seconds'];
	    	}
	    }

	    if($value['years']){
	    	$t=$value["years"] ."年".$value["days"] ."天". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
	    }else if($value['days']){
	    	$t=$value["days"] ."天". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
	    }else if($value['hours']){
	    	$t=$value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";
	    }else if($value['minutes']){
	    	$t=$value["minutes"] ."分".$value["seconds"]."秒";
	    }else if($value['seconds']){
	    	$t=$value["seconds"]."秒";
	    }
	    
	    return $t;

	}	
	
	/* 数字格式化 */
	function NumberFormat($num){
		if($num<10000){

		}else if($num<1000000){
			$num=round($num/10000,2).'w';
		}else if($num<100000000){
			$num=round($num/10000,1).'w';
		}else if($num<10000000000){
			$num=round($num/100000000,2).'y';
		}else{
			$num=round($num/100000000,1).'y';
		}


		return $num;
	}



	
	/* ip限定 */
	function ip_limit(){
		$configpri=getConfigPri();
		if($configpri['iplimit_switch']==0){
			return 0;
		}
		$date = date("Ymd");
		$ip= ip2long($_SERVER["REMOTE_ADDR"]) ; 
		
		$isexist=DI()->notorm->getcode_limit_ip
				->select('ip,date,times')
				->where(' ip=? ',$ip) 
				->fetchOne();
		if(!$isexist){
			$data=array(
				"ip" => $ip,
				"date" => $date,
				"times" => 1,
			);
			$isexist=DI()->notorm->getcode_limit_ip->insert($data);
			return 0;
		}elseif($date == $isexist['date'] && $isexist['times'] >= $configpri['iplimit_times'] ){
			return 1;
		}else{
			if($date == $isexist['date']){
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('times'=> new NotORM_Literal("times + 1 ")));
				return 0;
			}else{
				$isexist=DI()->notorm->getcode_limit_ip
						->where(' ip=? ',$ip) 
						->update(array('date'=> $date ,'times'=>1));
				return 0;
			}
		}	
	}

	/**极光推送*/
	function jgsend($uid,$videoid,$type){
		/* 极光推送 */
		$configpri=getConfigPri();
		$app_key = $configpri['jpush_key'];
		$master_secret = $configpri['jpush_secret'];
		$userinfo=getUserInfo($uid);
		
		if($app_key && $master_secret){
			require './JPush/autoload.php';

			// 初始化
			$client = new \JPush\Client($app_key, $master_secret,null);
			
			$anthorinfo=array(
				"uid"=>$userinfo['uid'],
				"avatar"=>$userinfo['avatar'],
				"avatar_thumb"=>$userinfo['avatar_thumb'],
				"user_nicename"=>$userinfo['user_nicename'],
				"title"=>$userinfo['title'],
				"city"=>$userinfo['city'],
				"stream"=>'',
				"pull"=>'',
				"thumb"=>$userinfo['thumb'],
			);
			$fansids = getFansIds($uid,$videoid,$type); 
		
			$uids=array_column($fansids,'uid');
			
			$nums=count($uids);	
			$apns_production=false;
			if($configpri['jpush_sandbox']){
				$apns_production=true;
			}

			for($i=0;$i<$nums;){
				$alias=array();
				for($n=0;$n<1000;$n++,$i++){
					if($uids[$i]){
						$alias[]=$uids[$i].'PUSH';								 
					}else{
						break;
					}
				}

				$anthorinfo['user_nicename']=ReplaceSensitiveWords($anthorinfo['user_nicename']); //用户昵称过滤敏感词


				try{

					$result = $client->push()
							->setPlatform('all')
							->addAlias($alias)
							->setNotificationAlert('"'.$anthorinfo['user_nicename'].'"发布了新的视频，快来看看吧')
							->iosNotification('"'.$anthorinfo['user_nicename'].'"发布了新的视频，快来看看吧', array(
								'sound' => 'sound.caf',
								'category' => 'jiguang',
								'extras' => array(
									'userinfo' => $anthorinfo
								),
							))
							->androidNotification('"'.$anthorinfo['user_nicename'].'"发布了新的视频，快来看看吧', array(
								'extras' => array(
									'userinfo' => $anthorinfo
								),
							))
							->options(array(
								'sendno' => 100,
								'time_to_live' => 0,
								'apns_production' =>  $apns_production,
							))
							->send();
				} catch (Exception $e) {   
					//file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
					//file_put_contents('./jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
				}					
			}			
		}
		/* 极光推送 */
	}

	//账号是否禁用
	function isBlackUser($uid){

		$userinfo=DI()->notorm->user->where("id=".$uid." and user_status=0")->fetchOne();
		
		if($userinfo){
			return 0;//禁用
		}
		return 1;//启用


	}

	/*检测手机号是否存在*/
	function checkMoblieIsExist($mobile){
		$res=DI()->notorm->user->select("id,user_nicename,user_type")->where("mobile='{$mobile}'")->fetchOne();


		if($res){
			//判断账号是否被禁用
			if($res['user_status']==0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 0;
		}
		
	}


	/*检测手机号是否可以发送验证码*/
	function checkMoblieCanCode($mobile){
		$res=DI()->notorm->user->select("id,user_nicename,user_type,user_status")->where("mobile='{$mobile}'")->fetchOne();


		if($res){
			//判断账号是否被禁用
			if($res['user_status']==0){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
		
	}

	/*获取用户的视频点赞总数*/
	function getPraises($uid){
		$res=DI()->notorm->user_video->where("uid=?",$uid)->sum("likes");

		if(!$res){
			$res="0";
		}	

		return $res;
	}

	/*获取音乐信息*/
	function getMusicInfo($user_nicename,$musicid){

		$res=DI()->notorm->user_music->select("id,title,author,img_url,length,file_url,use_nums")->where("id=?",$musicid)->fetchOne();

		if(!$res){
			$res=array();
			$res['id']='0';
			$res['title']='';
			$res['author']='';
			$res['img_url']='';
			$res['length']='00:00';
			$res['file_url']='';
			$res['use_nums']='0';
			$res['music_format']='@'.$user_nicename.'创作的原声';

		}else{
			$res['music_format']=$res['title'].'--'.$res['anchor'];
			$res['img_url']=get_upload_path($res['img_url']);
			$res['file_url']=get_upload_path($res['file_url']);
		}

		

		return $res;

	}

	/*距离格式化*/
	function distanceFormat($distance){
		if($distance<1000){
			return $distance.'米';
		}else{

			if(floor($distance/10)<10){
				return number_format($distance/10,1);  //保留一位小数，会四舍五入
			}else{
				return ">10千米";
			}
		}
	}

	/* 视频是否点赞 */
	function ifLike($uid,$videoid){
		$like=DI()->notorm->user_video_like
				->select("id")
				->where("uid='{$uid}' and videoid='{$videoid}'")
				->fetchOne();
		if($like){
			return 1;
		}else{
			return 0;
		}	
	}



	/* 腾讯COS处理 */
    /*function setTxUrl($url){
        
        if(!strstr($url,'myqcloud')){
            return $url;
        }
        
        $url_a=parse_url($url);
        
        $file=$url_a['path'];
        $signkey='Shanghai0912'; //腾讯云后台设置（控制台->存储桶->域名管理->CDN鉴权配置->鉴权Key）
        $now_time = time();
        $sign=md5($signkey.$file.$now_time);
        
        return $url.'?sign='.$sign.'&t='.$now_time;
        
    }*/


    /*极光IM用户名前缀（与APP端统一）*/
	function userSendBefore(){
		$before='';
		return $before;
	}

    /*极光IM*/
    function jMessageIM($test,$uid,$adminName){

        //获取后台配置的极光推送app_key和master_secret

        $configPri=getConfigPri();
        $appKey = $configPri['jpush_key'];
        $masterSecret =  $configPri['jpush_secret'];

        if($appKey&&$masterSecret){

            //var_dump(API_ROOT);

            //极光IM
           include_once(API_ROOT.'/../sdk/jmessage/autoload.php');//导入极光IM类库，注意使用require_once和路径写法

            $jm = new \JMessage\JMessage($appKey, $masterSecret); //注意类文件路径写法
            

            //注册管理员
            $admin = new \JMessage\IM\Admin($jm); //注意类文件路径写法
            $nickname="";
            switch($adminName){
                case "dsp_comment":
                $nickname="评论管理";
                break;
                case "dsp_at":
                $nickname="@管理";
                break;
                case "dsp_like":
                $nickname="赞管理";
                break;
                case "dsp_fans":
                $nickname="粉丝管理";
                break;
                case "goodsorder_admin":
                $nickname="订单管理";
                break;

            }


            $regInfo = [
                'username' => $adminName,
                'password' => $adminName,
                'nickname'=>$nickname
            ];


            $response = $admin->register($regInfo);



            if($response['body']==""||$response['body']['error']['code']==899001){ //新管理员注册成功或管理员已经存在

                //发布消息
                $message = new \JMessage\IM\Message($jm); //注意类文件路径写法

                $user = new \JMessage\IM\User($jm); //注意类文件路径写法

                $before=userSendBefore(); //获取极光用户账号前缀

                $from = [
                    'id'   => $adminName, //短视频系统规定系统通知必须是该账号（与APP保持一致）
                    'type' => 'admin'
                ];

                $msg = [
                   'text' => $test
                ];

                $notification =[
                    'notifiable'=>false  //是否在通知栏展示
                ];

                $target = [
                    'id'   => $before.$uid,
                    'type' => 'single'
                ];

                $response = $message->sendText(1, $from, $target, $msg,$notification,[]);  //最后一个参数代表其他选项数组，主要是配置消息是否离线存储，默认为true
            }

        }

    }
    
    /* 标签信息 */
    function getLabelInfo($labelid){
        $key='LabelInfo_'.$labelid;
        $info=getcaches($key);
        if(!$info){
            $info=DI()->notorm->label
                ->select("id,name,des,thumb")
                ->where('id=?',$labelid)
                ->fetchOne();
            if($info){
                setcaches($key,$info);
            }
        }
        if($info){
            $info['thumb']=get_upload_path($info['thumb']);
        }
        
        return $info;
    }

    /* 校验签名 */
    function checkSign($data,$sign){
        //return 1;
        if($sign==''){
            return 0;
        }
        $key=DI()->config->get('app.sign_key');
        $str='';
        ksort($data);
        foreach($data as $k=>$v){
            $str.=$k.'='.$v.'&';
        }
        $str.=$key;
        $newsign=md5($str);
        
        if($sign==$newsign){
            return 1;
        }
        return 0;
    }
    
    /* 视频数据处理 */
    function handleVideo($uid,$v,$tree){
      
		$userinfo=getUserInfo($v['uid'],$tree);
		if(!$userinfo){
			$userinfo['user_nicename']="已删除";
		}

		if($v['title']){
			$v['title']=eachReplaceSensitiveWords($tree,$v['title']);
		}

		//防止uid为0时因为找不到用户信息而出现头像昵称为null的问题
		$v['user_nicename']=$userinfo['user_nicename'];
		$v['avatar']=$userinfo['avatar'];
		
		$v['userinfo']=$userinfo;
		$v['datetime']=datetime($v['addtime']);	
		$v['addtime_format']=$v['addtime'];
		$v['addtime']=date('Y-m-d H:i:s',$v['addtime']);
		
		$v['comments']=NumberFormat($v['comments']);	
		$v['likes']=NumberFormat($v['likes']);	
		$v['steps']=NumberFormat($v['steps']);
		$v['shares']=NumberFormat($v['shares']);
        
        $v['islike']='0';	
        $v['isattent']='0';
        
		if($uid>0){
			$v['islike']=(string)ifLike($uid,$v['id']);	
		}
        
        if($uid>0 && $uid!=$v['uid']){
            $v['isattent']=(string)isAttention($uid,$v['uid']);	
        }

		$v['musicinfo']=getMusicInfo($userinfo['user_nicename'],$v['music_id']);	
		$v['thumb']=get_upload_path($v['thumb']);
		$v['thumb_s']=get_upload_path($v['thumb_s']);
		$v['href']=encryption(get_upload_path($v['href']));
		$v['href_w']=encryption(get_upload_path($v['href_w']));
        
        if($v['ad_url']){
			$v['ad_url']=get_upload_path($v['ad_url']);
		}
        if($v['ad_endtime']<time()){
            $v['ad_url']='';
        }
        
        /* 商品 */
        $goodsinfo=(object)[];

        $v['goodsinfo']=$goodsinfo;

        $goods_type='0';

        $goodsid=$v['goodsid'];
        if($goodsid){
        	$goods_type=DI()->notorm->shop_goods->where("id=?",$goodsid)->fetchOne('type');
        }

        $v['goods_type']=(String)$goods_type;
        
        /* 标签 */
        $label_name='';
        if($v['labelid']>0){
            $labelinfo=getLabelInfo($v['labelid']);
            if($labelinfo){
                $label_name=$labelinfo['name'];
            }else{
                $v['labelid']='0';
            }
        }
        $v['label_name']=$label_name;

        //获取对应用户的直播信息
        $liveinfo=getLiveInfo($v['uid']);
        $v['liveinfo']=$liveinfo;

        if($v['coin']){
        	$configpri=getConfigPri();
	        $watch_video_type=$configpri['watch_video_type'];
	        if(!$watch_video_type){ //次数限制模式,将视频需消费钻石数改为0
	        	$v['coin']='0'; 
	        }
        }

        
        
		unset($v['ad_endtime']);
		unset($v['orderno']);
		unset($v['isdel']);
		unset($v['show_val']);

        return $v;
    }
    
    function encryption($code){
		$str = 'HmTPvkJ3otK5gp.COdrAi:q09Z62ash-QGn8V;FNIlbfM/D74Wj&S_E=UzYuw?1ecxXyLRB';
		$strl=strlen($str);
        
	   	$len = strlen($code);

      	$newCode = '';
	   	for($i=0;$i<$len;$i++){
         	for($j=0;$j<$strl;$j++){
            	if($str[$j]==$code[$i]){
               		if(($j+1)==$strl){
                   		$newCode.=$str[0];
	               	}else{
	                   	$newCode.=$str[$j+1];
	               	}
	            }
         	}
      	}
      	return $newCode;
	}	
    
    function decrypt($code){
		$str = 'HmTPvkJ3otK5gp.COdrAi:q09Z62ash-QGn8V;FNIlbfM/D74Wj&S_E=UzYuw?1ecxXyLRB';
		$strl=strlen($str);

	   	$len = strlen($code);

      	$newCode = '';
	   	for($i=0;$i<$len;$i++){
     		for($j=0;$j<$strl;$j++){
        		if($str[$j]==$code[$i]){
	           		if($j-1<0){
	        			$newCode.=$str[$strl-1];
	               	}else{
						$newCode.=$str[$j-1];
	               	}
            	}
         	}
      	}
      	return $newCode;
	}
    
    /* 腾讯云存储 
     * 单文件云存储
     * files  单个文件上传信息(包含键值)   格式 $files['file']=$_FILES["file"]
     * type  文件类型 img图片 video视频 music音乐
     */
    function qcloud($files='',$type='video'){
        
        $rs=array('code'=>1000,'data'=>[],'msg'=>'上传失败');
        
        $configpri=getConfigPri();
        
        /* 腾讯云 */
        require_once(API_ROOT.'/../sdk/qcloud/autoload.php');

        $folder = '/'.$configpri['txvideofolder'];
        if($type=='img'){
            $folder = '/'.$configpri['tximgfolder'];
        }
        
        $file_name = $_FILES["file"]["name"];
        $src = $_FILES["file"]["tmp_name"];
        if($files){
            $file_name = $files["file"]["name"];
            $src = $files["file"]["tmp_name"];
        }

        $fnarray=explode('.', $file_name);

        $file_suffix = strtolower(end($fnarray)); //后缀名

        $dst = $folder.'/'.date('YmdHis').rand(1,999).'.'.$file_suffix;

        $cosClient = new \Qcloud\Cos\Client(array(
            'region' => $configpri['txcloud_region'], #地域，如ap-guangzhou,ap-beijing-1
            'credentials' => array(
                'secretId' => $configpri['txcloud_secret_id'],
                'secretKey' => $configpri['txcloud_secret_key'],
            ),
        ));

        // 若初始化 Client 时未填写 appId，则 bucket 的命名规则为{name}-{appid} ，此处填写的存储桶名称必须为此格式
        $bucket = $configpri['txcloud_bucket'].'-'.$configpri['txcloud_appid'];
        try {
            $result = $cosClient->upload(
                $bucket = $bucket,
                $key = $dst,
                $body = fopen($src, 'rb')
            );
            $url = $result['Location'];
        } catch (\Exception $e) {
            $rs['msg']=$e->getMessage();
            return $rs;
        }
        
        $rs['code']=0;
        $rs['data']['url']=$url;
        
        return $rs;
    }


    //获取用户的vip信息
    function getUserVipInfo($uid){
    	$result=array();

    	$configpri=getConfigPri();
    	$result['vip_switch']=(string)$configpri['vip_switch'];
    	
    	$now=time();
    	$vipInfo=DI()->notorm->user->where("id=?",$uid)->select("vip_endtime")->fetchOne();

    	if(!$vipInfo){
    		$result['isvip']='0';
    		$result['vip_endtime']='';
    		return $result;
    	}

    	if($vipInfo['vip_endtime']<=$now){
    		$result['isvip']='0';
    		$result['vip_endtime']='';

    		return $result;
    	}

    	$result['isvip']='1';
    	$result['vip_endtime']=date("Y.m.d",$vipInfo['vip_endtime']);

    	return $result;

    }

    //检测注册时vip限制
    function checkRegIpLimit($mobileid,$ip){

    	$configpri=getConfigPri();
		$same_device_ip_regnums=$configpri['same_device_ip_regnums'];
		if(!$same_device_ip_regnums){
			return 0;
		}


		//获取同一设备 同一ip下的总注册量
		$count=DI()->notorm->user->where("mobileid='{$mobileid}' and ip=?",$ip)->count();
		if($count<$same_device_ip_regnums){
			return 0;
		}

		return 1;

    }

    //检测视频分类
    function checkVideoClass($where){
    	$isexist=DI()->notorm->user_video_class->where($where)->where("status=1")->fetchOne();
    	if(!$isexist){
    		return 0;
    	}

    	return 1;
    }

    //过滤关键词基础方法
    function trieTreeBasic(){
    	require_once API_ROOT.'/public/TrieTree/TrieTree.php';

		//创建树
		$tree = new AbelZhou\Tree\TrieTree();

		$configpri=getConfigPri();
		$newKeywords=$configpri['sensitive_words'];

		$newKeywords=explode(",",$newKeywords);

		//向树上挂载敏感词
		foreach ($newKeywords as $keyword){
		    $tree->append($keyword);
		}

		//获取整棵树
		//$treeObj = $tree->getTree();

		/*print_r($treeObj);
		die;*/

		return $tree;



    }

    //检测是否存在敏感词
    function checkSensitiveWords($str){
    	if(!$str){
    		return 0;
    	}


    	$configpri=getConfigPri();
		$newKeywords=$configpri['sensitive_words'];


		if(!$newKeywords){
			return 0;
		}

    	$tree=trieTreeBasic();

    	$res = $tree->search($str);

    	if(!$res){
    		return 0;
    	}

    	return 1;

    }


    //单条信息敏感词替换
    function ReplaceSensitiveWords($str){


    	if(!$str){
    		return "";
    	}

    	$configpri=getConfigPri();
		$newKeywords=$configpri['sensitive_words'];

		if(!$newKeywords){
			return $str;
		}

		$tree=trieTreeBasic();

    	$res = $tree->search($str);

    	if(!$res){
    		return $str;
    	}

    	foreach ($res as $k => $v) {
    		$len=mb_strlen($v['word']);
    		$replace="";
    		for ($i=0; $i <$len ; $i++) { 
    			$replace.="*";
    		}

    		$str=str_replace($v['word'],$replace,$str);
    	}


    	return $str;
    }


    //多条信息敏感词过滤【调用处需结合trieTreeBasic函数一起使用】
    function eachReplaceSensitiveWords($tree,$str){

    	$res = $tree->search($str);

    	if(!$res){
    		return $str;
    	}

    	foreach ($res as $k => $v) {
    		$len=mb_strlen($v['word']);

    		$replace=str_repeat("*",$len);

    		$str=str_replace($v['word'],$replace,$str);
    	}


    	return $str;
    }

    //获取用户观看视频的最新时间
    function getUserWatchTime($uid){
    	$info=DI()->notorm->user_video_watchtime->where("uid=?",$uid)->fetchOne("video_watchtime");
    	return $info;
    }

    //更新用户观看视频的最新时间
    function setUserWatchTime($uid){
    	$info=DI()->notorm->user_video_watchtime->where("uid=?",$uid)->fetchOne();
    	$now=time();
    	if($info){
    		DI()->notorm->user_video_watchtime->where("uid=?",$uid)->update(array("video_watchtime"=>$now));
    	}else{
    		DI()->notorm->user_video_watchtime->insert(array("uid"=>$uid,"video_watchtime"=>$now));
    	}

    	return 1;
    }

    //更新用户观看视频记录(这里是记录的最新一天的观看记录，要区别用户观看历史记录功能)
    function setUserWatchLists($uid,$videoid,$isfrist){

    	$info=DI()->notorm->user_video_watchlists->where("uid=?",$uid)->fetchOne();

    	if($info){

    		if($isfrist){ //重新记录
    			DI()->notorm->user_video_watchlists->where("uid=?",$uid)->update(array("video_ids"=>$videoid));
    		}else{


    			$str=$info['video_ids'].",".$videoid;
    			DI()->notorm->user_video_watchlists->where("uid=?",$uid)->update(array("video_ids"=>$str));
    		}

    		
    	}else{
    		DI()->notorm->user_video_watchlists->insert(array("uid"=>$uid,"video_ids"=>$videoid));
    	}

    	return 1;
    }

    //获取用户观看视频记录
    function getUserWatchLists($uid){
    	$info=DI()->notorm->user_video_watchlists->where("uid=?",$uid)->fetchOne("video_ids");
    	if($info){
    		$info=explode(",", $info);
    	}else{
    		$info=[];
    	}
    	return $info;
    }


    //获取游客的最新观看时间
    function getTouristWatchTime($mobileid){
    	$info=DI()->notorm->tourist_video_watchtime->where("mobileid=?",$mobileid)->fetchOne("video_watchtime");
    	return $info;
    }

    //更新游客的观看视频最新时间
    function setTouristWatchTime($mobileid){
    	$info=DI()->notorm->tourist_video_watchtime->where("mobileid=?",$mobileid)->fetchOne();
    	$now=time();
    	if($info){
    		DI()->notorm->tourist_video_watchtime->where("mobileid=?",$mobileid)->update(array("video_watchtime"=>$now));
    	}else{
    		DI()->notorm->tourist_video_watchtime->insert(array("mobileid"=>$mobileid,"video_watchtime"=>$now));
    	}

    	return 1;
    }

    //更新游客观看视频记录
    function setTouristWatchLists($mobileid,$videoid,$isfrist){

    	$info=DI()->notorm->tourist_video_watchlists->where("mobileid=?",$mobileid)->fetchOne();

    	if($info){

    		if($isfrist){ //重新记录
    			DI()->notorm->tourist_video_watchlists->where("mobileid=?",$mobileid)->update(array("video_ids"=>$videoid));
    		}else{
    			$str=$info['video_ids'].",".$videoid;
    			DI()->notorm->tourist_video_watchlists->where("mobileid=?",$mobileid)->update(array("video_ids"=>$str));
    		}

    		
    	}else{
    		DI()->notorm->tourist_video_watchlists->insert(array("mobileid"=>$mobileid,"video_ids"=>$videoid));
    	}

    	return 1;
    }

    //获取游客观看视频记录
    function getTouristWatchLists($mobileid){
    	$info=DI()->notorm->tourist_video_watchlists->where("mobileid=?",$mobileid)->fetchOne("video_ids");
    	if($info){
    		$info=explode(",", $info);
    	}else{
    		$info=[];
    	}
    	return $info;
    }

    //获取用户的付费视频记录
    function getVideoPayLists($uid){
    	$info=DI()->notorm->user_video_paylists->where("uid=?",$uid)->fetchOne("video_ids");
    	if($info){
    		$info=explode(",", $info);
    	}else{
    		$info=[];
    	}
    	return $info;
    }

    //更新用户的付费视频记录
    function setVideoPayLists($uid,$videoid){
    	$info=DI()->notorm->user_video_paylists->where("uid=?",$uid)->fetchOne("video_ids");
    	if($info){
    		$videoids=$info['video_ids'].",".$videoid;

    		DI()->notorm->user_video_paylists->where("uid=?",$uid)->update(array("video_ids"=>$videoids));

    	}else{
    		DI()->notorm->user_video_paylists->where("uid=?",$uid)->insert(array("uid"=>$uid,"video_ids"=>$videoid));
    	}

    	
    }

    //获取用户的钻石
    function getUserCoin($uid){
    	$coin=DI()->notorm->user->where("id=?",$uid)->fetchOne("coin");
    	if(!$coin){
    		$coin=0;
    	}
    	return $coin;
    }

    //更新用户的钻石数
    function changeUserCoin($uid,$coin,$type=0){ //type 为0 扣费 type 为1 增加  type
    	if(!$type){      
    		$res=DI()->notorm->user->where("id=? and coin>=?",$uid,$coin)->update(array("coin"=>new NotORM_Literal("coin-{$coin}")));
    		
    	}else{           
    		$res=DI()->notorm->user->where("id=?",$uid)->update(array("coin"=>new NotORM_Literal("coin + {$coin}")));
    	}

    	if(!$res){
    		return 0;
    	}

    	if(!$type){     
    		DI()->notorm->user->where("id=?",$uid)->update(array("consumption"=>new NotORM_Literal("consumption + {$coin}")));
    	}

    	return 1;

    }

    //写入钻石消费记录
    function setCoinRecord($data){
    	DI()->notorm->user_coinrecord->insert($data);
    }

    //写入映票收入记录
    function setVoteRecord($data){
    	DI()->notorm->votes_record->insert($data);
    	
    }

    //更新用户的映票
    function changeUserVotes($uid,$votes,$type=0){ //type 为0 扣费 type 为1 增加

    	if(!$type){

    		$res=DI()->notorm->user->where("id=? and votes >=?",$uid,$votes)
		    	->update(
		    		array('votes'=>new NotORM_Literal("votes-{$votes}"))
		    	);

    	}else{

    		$res=DI()->notorm->user->where("id=?",$uid)
		    	->update(
		    		array(
		    			'votes'=>new NotORM_Literal("votes+{$votes}"),
		    			'votestotal'=>new NotORM_Literal("votestotal+{$votes}")
		    		)
		    	);

    	}

    	if(!$res){
    		return 0;
    	}

    	return 1;
    	
    }

    //获取粉丝的极光推送id

    function getFansIds($touid) {
        
        $list=array();
		$fansids=DI()->notorm->user_attention
					->select("uid")
					->where('touid=?',$touid)
					->fetchAll();
                    
        if($fansids){
            $uids=array_column($fansids,'uid');

            //file_put_contents("12.txt", json_encode($uids));
            
            $pushids=DI()->notorm->user_pushid
					->select("pushid")
					->where('uid',$uids)
					->fetchAll();
			//file_put_contents("23.txt", json_encode($pushids));
            $list=array_column($pushids,'pushid');
            $list=array_filter($list);
        }
        return $list;
    }

    //检测视频是否存在
    function checkVideoIsExist($videoid){
    	$video=DI()->notorm->user_video
			->select("uid")
			->where("id=? and isdel=0 and status=1",$videoid)
			->fetchOne();

		if(!$video){
			return 0;
		}

		return 1;
    }
    //根据视频id获取视频详情
    function getVideoInfoById($videoid,$column='*'){
    	$info=DI()->notorm->user_video
    		->select($column)
    		->where("id=? and isdel=0 and status=1",$videoid)
    		->fetchOne();

    	if(!$info){
    		$info=[];
    	}

    	return $info;
    }

    /**
	*  @desc 获取推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	function PrivateKeyA($host,$stream,$type){
		$configpri=getConfigPri();
		$cdn_switch=$configpri['cdn_switch'];
		//$cdn_switch=2;
		switch($cdn_switch){
			case '1':
				$url=PrivateKey_tx($host,$stream,$type);
				break;
		}

		
		return $url;
	}

	/**
	*  @desc 腾讯云推拉流地址
	*  @param string $host 协议，如:http、rtmp
	*  @param string $stream 流名,如有则包含 .flv、.m3u8
	*  @param int $type 类型，0表示播流，1表示推流
	*/
	function PrivateKey_tx($host,$stream,$type){
		$configpri=getConfigPri();

		$bizid=$configpri['tx_bizid'];
		$push_url_key=$configpri['tx_push_key'];
		$push=$configpri['tx_push'];
		$pull=$configpri['tx_pull'];
		$stream_a=explode('.',$stream);
		$streamKey = $stream_a[0];
		$ext = $stream_a[1];
		
		//$live_code = $bizid . "_" .$streamKey;      	
		$live_code = $streamKey;      	
		$now_time = time() + 3*60*60;
		$txTime = dechex($now_time); //转为16进制

		$txSecret = md5($push_url_key . $live_code . $txTime);
		$safe_url = "?txSecret=" .$txSecret."&txTime=" .$txTime;		

		if($type==1){
			//$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record=flv" .$safe_url;	可录像
			$url = "rtmp://{$push}/live/" . $live_code . $safe_url;	
		}else{
			$url = "http://{$pull}/live/" . $live_code . ".flv";
		}
		
		return $url;
	}

	//获取用户的映票总数
	function getUserVotesTotal($uid){
		$votestotal=DI()->notorm->user->where("id=?",$uid)->fetchOne("votestotal");
		return $votestotal;

	}

	// 判断直播用户权限
	function isAdmin($uid,$liveuid) {
		if($uid==$liveuid){
			return 50; //主播
		}
		$isuper=isSuper($uid);
		if($isuper){
			return 60; //超级管理员
		}
		$isexist=DI()->notorm->user_livemanager
					->select("*")
					->where('uid=? and liveuid=?',$uid,$liveuid)
					->fetchOne();
		if($isexist){
			return  40; //房间管理员
		}
		
		return  30; //普通用户
			
	}

	// 判断账号是否超管
	function isSuper($uid){
		$isexist=DI()->notorm->user_super
					->select("*")
					->where('uid=?',$uid)
					->fetchOne();
		if($isexist){
			return 1;
		}			
		return 0;
	}

	//判断用户是否在直播
	function getLiveInfo($uid){
		$info=DI()->notorm->user_live->select("uid,islive,stream,pull,showid")->where("uid=?",$uid)->fetchOne();
		if(!$info){
			$info=array(
				"islive"=>"0",
				"stream"=>"",
				"pull"=>"",
				"showid"=>"0",
				"uid"=>$uid
			);
		}


		return $info;
	}

	//判断用户是否存在
	function checkUserIsExist($uid){
		$info=DI()->notorm->user->select("user_login")->where("id=? and user_type=2",$uid)->fetchOne();
		if(!$info){
			return 0;
		}

		return 1;
	}




    /*腾讯云存储私有读写的签名验证start*/

    function setTxUrl($url){


    	/*if(!strstr($url,'myqcloud')){
            return $url;
        }*/

        $url_a=parse_url($url);

        // 获取前端过来的参数
		$method = isset($_GET['method']) ? $_GET['method'] : 'get';
		$pathname = isset($url_a['path']) ? $url_a['path'] : '/';

		if($pathname=="/"){
			return $url;
		}

		$signinfo=getcaches($pathname);

		if($signinfo){

			$now=time();

			if($signinfo['endtime']>$now){
				return $url."?".$signinfo['sign'];
			}	
		}



		// 获取临时密钥，计算签名
		$tempKeys = getTempKeys();

		if ($tempKeys && isset($tempKeys['credentials'])) {
		    $data = array(
		        'Authorization' => getAuthorization($tempKeys, $method, $pathname),
		        //'Authorization' => aaa($tempKeys), 
		        'XCosSecurityToken' => $tempKeys['credentials']['sessionToken'],
		    );
		} else {
		    //$data = array('error'=> $tempKeys);
		    return $url;
		}

		$sign=$data['Authorization']."&x-cos-security-token=".$data['XCosSecurityToken'];

		$signArr=array(

			"endtime"=>time()-10+600,
			"sign"=>$sign

		);


		DI()->redis->set($pathname,json_encode($signArr));

		$str=$url."?".$sign;

		return $str;

    }

    
    // 获取临时密钥
	function getTempKeys() {

	    $config=getCosConfig();

	    // 判断是否修改了 AllowPrefix
	    if ($config['AllowPrefix'] === '_ALLOW_DIR_/*') {
	        return array('error'=> '请修改 AllowPrefix 配置项，指定允许上传的路径前缀');
	    }

	    $ShortBucketName = substr($config['Bucket'],0, strripos($config['Bucket'], '-'));
	    $AppId = substr($config['Bucket'], 1 + strripos($config['Bucket'], '-'));
	    $policy = array(
	        'version'=> '2.0',
	        'statement'=> array(
	            array(
	                'action'=> array(
	                   
	                    // 简单文件操作
	                    'name/cos:PutObject',
	                    'name/cos:PostObject',
	                    'name/cos:AppendObject',
	                    'name/cos:GetObject',
	                    'name/cos:HeadObject',
	                    'name/cos:OptionsObject',
	                    'name/cos:PutObjectCopy',
	                    'name/cos:PostObjectRestore',
	                    // 分片上传操作
	                    'name/cos:InitiateMultipartUpload',
	                    'name/cos:ListMultipartUploads',
	                    'name/cos:ListParts',
	                    'name/cos:UploadPart',
	                    'name/cos:CompleteMultipartUpload',
	                    'name/cos:AbortMultipartUpload',
	                ),
	                'effect'=> 'allow',
	                'principal'=> array('qcs'=> array('*')),
	                'resource'=> array(
	                    'qcs::cos:' . $config['Region'] . ':uid/' . $AppId . ':prefix//' . $AppId . '/' . $ShortBucketName . '/',
	                    'qcs::cos:' . $config['Region'] . ':uid/' . $AppId . ':prefix//' . $AppId . '/' . $ShortBucketName . '/' . resourceUrlEncode($config['AllowPrefix'])
	                )
	            )
	        )
	    );

	    $policyStr = str_replace('\\/', '/', json_encode($policy));
	    $Action = 'GetFederationToken';
	    $Nonce = rand(10000, 20000);
	    $Timestamp = time() - 1;
	    $Method = 'GET';

	    $params = array(
	        'Action'=> $Action,
	        'Nonce'=> $Nonce,
	        'Region'=> '',
	        'SecretId'=> $config['SecretId'],
	        'Timestamp'=> $Timestamp,
	        'durationSeconds'=> 7200,
	        'name'=> 'cos',
	        'policy'=> urlencode($policyStr)
	    );
	    $params['Signature'] = urlencode(getSignature($params, $config['SecretKey'], $Method));

	    $url = $config['Url'] . '?' . json2str($params);
	    $ch = curl_init($url);
	    $config['Proxy'] && curl_setopt($ch, CURLOPT_PROXY, $config['Proxy']);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($ch);
	    if(curl_errno($ch)) $result = curl_error($ch);
	    curl_close($ch);

	    $result = json_decode($result, 1);
	    if (isset($result['data'])) $result = $result['data'];

	    return $result;
	}


	function getCosConfig(){

    	$configpri=getConfigPri();

    	// 配置参数
		$config = array(
		    'Url' => 'https://sts.api.qcloud.com/v2/index.php',
		    'Domain' => 'sts.api.qcloud.com',
		    'Proxy' => '',
		    'SecretId' => $configpri['txcloud_secret_id'], // 固定密钥
		    'SecretKey' => $configpri['txcloud_secret_key'], // 固定密钥
		    'Bucket' => $configpri['txcloud_bucket'].'-'.$configpri['txcloud_appid'],//dsp-1257569725,
		    'Region' => $configpri['txcloud_region'], //存储桶地域
		    'AllowPrefix' => '*', // 这里改成允许的路径前缀，这里可以根据自己网站的用户登录态判断允许上传的目录，例子：* 或者 a/* 或者 a.jpg
		);

		return $config;

    }

    // 计算临时密钥用的签名
	function resourceUrlEncode($str) {
	    $str = rawurlencode($str);
	    //特殊处理字符 !()~
	    $str = str_replace('%2F', '/', $str);
	    $str = str_replace('%2A', '*', $str);
	    $str = str_replace('%21', '!', $str);
	    $str = str_replace('%28', '(', $str);
	    $str = str_replace('%29', ')', $str);
	    $str = str_replace('%7E', '~', $str);
	    return $str;
	}


	// 计算 COS API 请求用的签名
	function getAuthorization($keys, $method, $pathname){

	    // 获取个人 API 密钥 https://console.qcloud.com/capi
	    $SecretId = $keys['credentials']['tmpSecretId'];
	    $SecretKey = $keys['credentials']['tmpSecretKey'];


	    // 整理参数
	    $query = array();
	    $headers = array();
	    $method = strtolower($method ? $method : 'get');
	    $pathname = $pathname ? $pathname : '/';
	    substr($pathname, 0, 1) != '/' && ($pathname = '/' . $pathname);

	    

	    // 签名有效起止时间
	    $now = time() - 1;
	    $expired = $now + 600; // 签名过期时刻，600 秒后

	    // 要用到的 Authorization 参数列表
	    $qSignAlgorithm = 'sha1';
	    $qAk = $SecretId;
	    $qSignTime = $now . ';' . $expired;
	    //$qSignTime = "1554284206;1554287806";
	    //$qKeyTime = $now . ';' . $expired;
	    $qKeyTime = $qSignTime;
	    $qHeaderList = strtolower(implode(';', getObjectKeys($headers)));

	    $qUrlParamList = strtolower(implode(';', getObjectKeys($query)));

	    // 签名算法说明文档：https://www.qcloud.com/document/product/436/7778
	    // 步骤一：计算 SignKey
	    $signKey = hash_hmac("sha1", $qKeyTime, $SecretKey);

	    // 步骤二：构成 FormatString
	    $formatString = implode("\n", array(strtolower($method), $pathname, obj2str($query), obj2str($headers), ''));


	    //header('x-test-method', $method);
	   // header('x-test-pathname', $pathname);

	    

	    // 步骤三：计算 StringToSign
	    $stringToSign = implode("\n", array('sha1', $qSignTime, sha1($formatString), ''));


	    // 步骤四：计算 Signature
	    $qSignature = hash_hmac('sha1', $stringToSign, $signKey);



	    // 步骤五：构造 Authorization
	    $authorization = implode('&', array(
	        'q-sign-algorithm=' . $qSignAlgorithm,
	        'q-ak=' . $qAk,
	        'q-sign-time=' . $qSignTime,
	        'q-key-time=' . $qKeyTime,
	        'q-header-list=' . $qHeaderList,
	        'q-url-param-list=' . $qUrlParamList,
	        'q-signature=' . $qSignature
	    ));

	    return $authorization;
	}

	// 工具方法
	function getObjectKeys($obj){

	    $list = array_keys($obj);
	    sort($list);
	    return $list;
	}

	function obj2str($obj){

	    $list = array();
	    $keyList = getObjectKeys($obj);
	    $len = count($keyList);
	    for ($i = 0; $i < $len; $i++) {
	        $key = $keyList[$i];
	        $val = isset($obj[$key]) ? $obj[$key] : '';
	        $key = strtolower($key);
	        $list[] = rawurlencode($key) . '=' . rawurlencode($val);
	    }
	    return implode('&', $list);
	}

	// 计算临时密钥用的签名
	function getSignature($opt, $key, $method) {
	    $config=getCosConfig();
	    $formatString = $method . $config['Domain'] . '/v2/index.php?' . json2str($opt);
	    $formatString = urldecode($formatString);
	    $sign = hash_hmac('sha1', $formatString, $key);
	    $sign = base64_encode(hex2bin($sign));
	    return $sign;
	}

	// obj 转 query string
    function json2str($obj) {
	    ksort($obj);
	    $arr = array();
	    foreach ($obj as $key => $val) {
	        array_push($arr, $key . '=' . $val);
	    }
	    return join('&', $arr);
	}
    
	/*私有读写的签名验证end*/


	//为文件拼接存储方式,方便get_upload_path做签名处理

	function setCloudType($url){
		if(!$url){
			return $url;
		}
		$configpri=getConfigPri();
		$cloudtype=$configpri['cloudtype'];

		$url=$url."%@%cloudtype=".$cloudtype;
		return $url;
	}

	//检测用户是否填写过邀请码
	function checkAgentIsExist($uid){
		$isexist=DI()->notorm->agent
                    ->select('*')
                    ->where('uid=?',$uid)
                    ->fetchOne();
        if(!$isexist){
        	return 0;
        }

        return 1;
	}

	//判断视频是否为广告视频
	function checkVideoIsAd($videoid){
		$info=DI()->notorm->user_video->where("id=? and is_ad=1",$videoid)->fetchOne();
		if(!$info){
			return 0;
		}

		return 1;
	}

	//判断用户是否注销
	function checkIsDestroyByLogin($user_login){
		$user_status=DI()->notorm->user->where("user_login=?",$user_login)->fetchOne('user_status');
		if($user_status==3){
			return 1;
		}

		return 0;
	}


	//判断用户是否注销
	function checkIsDestroyByUid($uid){
		$user_status=DI()->notorm->user->where("id=?",$uid)->fetchOne('user_status');
		if($user_status==3){
			return 1;
		}

		return 0;
	}


	/*删除用户极光账号*/
    function delIM($uid){

        //获取后台配置的极光推送app_key和master_secret

        $configPri=getConfigPri();
        $appKey = $configPri['jpush_key'];
        $masterSecret =  $configPri['jpush_secret'];

        if($appKey&&$masterSecret){

            //var_dump(API_ROOT);

            //极光IM
           include_once(API_ROOT.'/../sdk/jmessage/autoload.php');//导入极光IM类库，注意使用require_once和路径写法

            $jm = new \JMessage\JMessage($appKey, $masterSecret); //注意类文件路径写法

            $user = new \JMessage\IM\User($jm); //注意类文件路径写法

            $before=userSendBefore(); //获取极光用户账号前缀

            $response=$user->delete($before.$uid);

        }

    }
	
	
	//每日任务处理
	function dailyTasks($uid,$data){
		$configpri=getConfigPri();
		$type=$data['type'];  //type 任务类型 
		
		// 当天时间
		$time=strtotime(date("Y-m-d 00:00:00",time()));
		$where="uid={$uid} and type={$type}";	
		//每日任务
		$info=DI()->notorm->user_daily_tasks
    			->where($where)
    			->select("*")
    			->fetchOne();
		
		if($info){

    		if($info['addtime']!=$time){
    			DI()->notorm->user_daily_tasks
    				->where($where)
    				->delete();
    			$info=[];
    		}else{
    			if($info['state']==1||$info['state']==2){
    				return 1;
    			}
    		}
    	}
				
		$save=[
			'uid'=>$uid,
			'type'=>$type,
			'addtime'=>$time,
			'uptime'=>time(),
		];
		$state='0';
		if($type==1){  //1观看直播
			$target=$configpri['watch_live_term'];
			$reward=$configpri['watch_live_coin'];

			
		}else if($type==2){ //2观看视频
			$target=$configpri['watch_video_term'];
			$reward=$configpri['watch_video_coin'];	

		}else if($type==3){ //3直播奖励
			$target=$configpri['open_live_term']*60;
			$reward=$configpri['open_live_coin'];
			

		}else if($type==4){ //4打赏奖励
			$target=$configpri['award_live_term'];
			$reward=$configpri['award_live_coin'];
			
			$schedule=ceil($data['total']);
			
		}else if($type==5){ //5分享奖励
			$target=$configpri['share_live_term'];
			$reward=$configpri['share_live_coin'];
			
			$schedule=ceil($data['nums']);
		}
		
		//关于时间奖励的处理
		if(in_array($type,['1','2','3'])){
			
			$day=date("d",$data['starttime']); 
			$day2=date("d",$data['endtime']);
			if($day!=$day2){ //判断结束时间是否超过当天, 超过则按照今天凌晨来算
				$data['starttime']=$time;
			}
			
			$schedulet=0;
			$time_diff=$data['endtime']-$data['starttime'];
			if($time_diff>=60){ //超过一分钟才算有效时间
				$schedulet=$time_diff/60; //观看时长
			}

			$schedule=floor($schedulet); //向下取整
		}
		
		
		
		if(!$info  || $info['addtime']!=$time){  //当数据中查不到当天的数据时
			$save['target']=$target;
			$save['reward']=$reward;
			if($schedule>=$target){
				$schedule=$target;
				$state='1';
			}
		}else{  //当有今天的数据时
			$schedule=$info['schedule']+$schedule;
			if($schedule>=$info['target']){
				$schedule=$info['target'];
				$state='1';
			}
		}
		$save['schedule']=(int)$schedule;  //进度
		$save['state']=$state; //状态
		
		
		if(!$info){
			DI()->notorm->user_daily_tasks->insert($save);
		}else{
			DI()->notorm->user_daily_tasks->where('id=?',$info['id'])->update($save);
		}

		
		//删除用户每日任务数据
		$key="seeDailyTasks_".$uid;
		delcache($key);
	}
	
	
	//二维数bai组内进行模du糊搜索
	function array_searchs($field,$data) {

		$arr=$result=array();
		foreach($data as $k => $v){
		
			$lists=$v['lists'];
		
			foreach ($lists as $key => $value) {

				if(strstr($value, $field) !== false){
					array_push($arr, $key);
				}
				
			}
			foreach ($arr as $key => $value) {
				if(array_key_exists($value,$lists)){
					array_push($result, $lists[$value]);
				}
			}
		
		}
		return $result;
	}
	
	
	/* 判断商品是否收藏 */
	function isGoodsCollect($uid,$goodsid) {

		if($uid<0||$goodsid<0){
			return "0";
		}

		$isexist=DI()->notorm->user_goods_collect
					->select("*")
					->where('uid=? and goodsid=?',$uid,$goodsid)
					->fetchOne();
		if($isexist){
			return  '1';
		}else{
			return  '0';
		}			 
	}

	//处理热门视频 type 处理类型 1要更新视频的热门信息 0不处理
    function changeVideoPopular($videoid,$pnums,$type){


    	//如果该视频正在上热门，将上热门剩余的钻石退回
		$popinfo=DI()->notorm->popular_orders->where("videoid=? and refund_status=0 and status=1",$videoid)->select("id,uid,touid,money,nums")->fetchOne();


		if($popinfo['nums']){

			$coin=$pnums/$popinfo['nums']*$popinfo['money'];
			$coin=floor($coin);

			if($coin>=1){
				$isok=changeUserCoin($popinfo['uid'],$coin,1);
				if($isok){

					$data=array(
						'type'=>'income',
						'action'=>'pop_refund',
						'uid'=>$popinfo['touid'], //视频发布者
						'touid'=>$popinfo['uid'], //花钱帮助上热门用户的id
						'totalcoin'=>$coin,
						'videoid'=>$videoid,
						'addtime'=>time()

					);
					//写入钻石消费记录
					setCoinRecord($data);

					if($type){
						//更新视频的热门信息
						$data1=array(
							'p_expire'=>0,
							'p_nums'=>0,
							'p_add'=>0
						);
						DI()->notorm->user_video->where("id=?",$videoid)->update($data1);
					}
				
				}
			}

			//将上热门记录的退款状态修改一下
			DI()->notorm->popular_orders->where("id=?",$popinfo['id'])->update(["refund_status"=>1]);

		}

    }

    /////////////////新增/////////////////////////

    // 根据不同条件获取订单总数
	function getOrderNums($where){
		
		$count=DI()->notorm->shop_order->where($where)->count();
		return $count;
	}
	
	/**
	 * 判断是否为合法的身份证号码
	 * @param $mobile
	 * @return int
	 */
	function isCreditNo($vStr){

		return true;
		
		$vCity = array(
		  	'11','12','13','14','15','21','22',
		  	'23','31','32','33','34','35','36',
		  	'37','41','42','43','44','45','46',
		  	'50','51','52','53','54','61','62',
		  	'63','64','65','71','81','82','91'
		);
		
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)){
		 	return false;
		}

	 	if (!in_array(substr($vStr, 0, 2), $vCity)){
	 		return false;
	 	}
	 
	 	$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
	 	$vLength = strlen($vStr);

	 	if($vLength == 18){
	  		$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
	 	}else{
	  		$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
	 	}

		if(date('Y-m-d', strtotime($vBirthday)) != $vBirthday){
		 	return false;
		}

	 	if ($vLength == 18) {
	  		$vSum = 0;
	  		for ($i = 17 ; $i >= 0 ; $i--) {
	   			$vSubStr = substr($vStr, 17 - $i, 1);
	   			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
	  		}
	  		if($vSum % 11 != 1){
	  			return false;
	  		}
	 	}

	 	return true;
	}


	//单个商品信息格式化处理
	function handleGoods($goodsinfo){


		//获取商品的分类名称
        $one_classinfo=getGoodsClassInfo($goodsinfo['one_classid']);
        $two_classinfo=getGoodsClassInfo($goodsinfo['two_classid']);
        $three_classinfo=getGoodsClassInfo($goodsinfo['three_classid']);

        $goodsinfo['one_class_name']=isset($one_classinfo['gc_name'])?$one_classinfo['gc_name']:'分类不存在';
        $goodsinfo['two_class_name']=isset($two_classinfo['gc_name'])?$two_classinfo['gc_name']:'分类不存在';
        $goodsinfo['three_class_name']=isset($three_classinfo['gc_name'])?$three_classinfo['gc_name']:'分类不存在';

        $goodsinfo['hits']=isset($goodsinfo['hits'])?NumberFormat($goodsinfo['hits']):'0';
        $goodsinfo['sale_nums']=isset($goodsinfo['sale_nums'])?NumberFormat($goodsinfo['sale_nums']):'0';
        $goodsinfo['video_url_format']=isset($goodsinfo['video_url'])?get_upload_path($goodsinfo['video_url']):'';
        $goodsinfo['video_thumb_format']=isset($goodsinfo['video_thumb'])?get_upload_path($goodsinfo['video_thumb']):'';

        if($goodsinfo['thumbs']){
        	$thumb_arr=explode(',',$goodsinfo['thumbs']);
	        foreach ($thumb_arr as $k => $v) {
	        	$thumb_arr[$k]=get_upload_path($v);
	        }
        }else{
        	$thumb_arr=[];
        }

        $goodsinfo['thumbs_format']=$thumb_arr;

        if($goodsinfo['type']==1){ //外链商品
        	$goodsinfo['specs_format']=[];
        }else{

        	$spec_arr=(array)json_decode($goodsinfo['specs'],true);
	        foreach ($spec_arr as $k => $v) {
	        	$spec_arr[$k]['thumb']=get_upload_path($v['thumb']);
	        }
	        $goodsinfo['specs_format']=$spec_arr;
        }

        

        if($goodsinfo['pictures']){
        	$picture_arr=explode(',', $goodsinfo['pictures']);
	        foreach ($picture_arr as $k => $v) {
	        	$picture_arr[$k]=get_upload_path($v);
	        }
        }else{
        	$picture_arr=[];
        }

        

        $goodsinfo['pictures_format']=$picture_arr;

        if($goodsinfo['postage']==0){
        	$goodsinfo['postage']='0.0';
        }

        if($goodsinfo['share_income']==0){
        	$goodsinfo['share_income']='0.0';
        }

        unset($goodsinfo['addtime']);
        unset($goodsinfo['uptime']);

        return $goodsinfo;
	}
	
	
    //获取商品分类信息
	function getGoodsClassInfo($classid){
		$info=DI()->notorm->shop_goods_class->where("gc_id=?",$classid)->fetchOne();
		if(!$info){
			return '';
		}
		return $info;
	}

	/*判断店铺是否审核通过*/
	function checkShopIsPass($uid){
		$info=DI()->notorm->shop_apply->select("status")->where("uid=?",$uid)->fetchOne();
		if(!$info){
			return '0';
		}

		$status=$info['status'];
		if($status!=1){
			return '0';
		}

		return '1';
	}

	//更改商品库存
	function changeShopGoodsSpecNum($goodsid,$spec_id,$nums,$type){
		$goods_info=DI()->notorm->shop_goods
				->where("id=?",$goodsid)
				->fetchOne();

		if(!$goods_info){
			return 0;
		}

		$spec_arr=json_decode($goods_info['specs'],true);
		$specid_arr=array_column($spec_arr, 'spec_id');

		if(!in_array($spec_id, $specid_arr)){
			return 0;
		}


		//file_put_contents("222.txt", "goodsid:".$goodsid.";spec_id:".$spec_id.";nums:".$nums.";type:".$type);

		foreach ($spec_arr as $k => $v) {
			if($v['spec_id']==$spec_id){
				if($type==1){
					$spec_num=$v['spec_num']+$nums;
				}else{
					$spec_num=$v['spec_num']-$nums;
				}
				
				if($spec_num<0){
					$spec_num=0;
				}

				$spec_arr[$k]['spec_num']=(string)$spec_num;
			}
		}


		$spec_str=json_encode($spec_arr);

		//file_put_contents("333.txt", $spec_str);

		DI()->notorm->shop_goods->where("id=?",$goodsid)->update(array('specs'=>$spec_str));

		return 1;

	}

	// 获取用户店铺余额
	function getUserShopBalance($uid){
		$info=DI()->notorm->user
			->select("balance,balance_total")
			->where("id=?",$uid)
			->fetchOne();

		return $info;
	}

	// 获取店铺商品订单详情
	function getShopOrderInfo($where,$files='*'){

		$info=DI()->notorm->shop_order
			->select($files)
			->where($where)
			->fetchOne();

		
		return $info;
		
	}

	//订单自动处理【用于买家/卖家获取订单列表时自动处理】
	function goodsOrderAutoProcess($uid,$where){

        $list=DI()->notorm->shop_order
            ->select("*")
            ->where($where)
            ->where("status !=-1") //待付款、待发货、待收货、待评价、已评价、退款
            ->order("addtime desc")
            ->fetchAll();

        $now=time();
        $effective_time=getShopEffectiveTime();
        

        foreach ($list as $k => $v) {

            if($v['status']==0){ //待付款要判断是否付款超时

                $pay_end=$v['addtime']+$effective_time['shop_payment_time']*60;
                if($pay_end<=$now){
                    $data=array(
                        'status'=>-1,
                        'cancel_time'=>$now
                    );
                    changeShopOrderStatus($v['uid'],$v['id'],$data); //将订单关闭

                    //商品规格库存回增
                    changeShopGoodsSpecNum($v['goodsid'],$v['spec_id'],$v['nums'],1);

                    //给买家发消息
                    $title="你购买的“".$v['goods_name']."”订单由于超时未付款,已自动关闭";
                    $data1=array(
			            'uid'=>$v['uid'],
			            'orderid'=>$v['id'],
			            'title'=>$title,
			            'addtime'=>$now,
			            'type'=>'0'

			        );

			        addShopGoodsOrderMessage($data1);
			        //发送极光IM
        			jMessageIM($title,$v['uid'],'goodsorder_admin');

                }
            }

            if($v['status']==1){ //买家已付款 判断卖家发货是否超时

            	//如果买家没有申请退款
            	if($v['refund_status']==0){

            		$shipment_end=$v['paytime']+$effective_time['shop_shipment_time']*60*60*24;
	                
            	}else{ //买家申请了退款，判断时间超时，要根据退款最终的处理时间
            	
            		$shipment_end=$v['refund_endtime']+$effective_time['shop_shipment_time']*60*60*24;
            	}

            	if($shipment_end<=$now){
                    $data=array(
                        'status'=>-1,
                        'cancel_time'=>$now
                    );
                    changeShopOrderStatus($v['uid'],$v['id'],$data); //将订单关闭

                    //退还买家货款
                    setUserBalance($v['uid'],1,$v['total']);

                    //添加余额操作记录
                    $data1=array(
                        'uid'=>$v['uid'],
                        'touid'=>$v['shop_uid'],
                        'balance'=>$v['total'],
                        'type'=>1,
                        'action'=>3, //卖家超时未发货,退款给买家
                        'orderid'=>$v['id'],
                        'addtime'=>$now

                    );

                    addBalanceRecord($data1);

                    //店铺逾期发货记录+1
                    DI()->notorm->shop_apply
                    	->where("uid=?",$v['shop_uid'])
                    	->update(
                    		array('shipment_overdue_num' => new NotORM_Literal("shipment_overdue_num + 1"))
                    	);

                    //减去商品销量
            		changeShopGoodsSaleNums($v['goodsid'],0,$v['nums']);

                   	//减去店铺销量
        			changeShopSaleNums($v['shop_uid'],0,$v['nums']);

                    //给买家发消息
                    $title="你购买的“".$v['goods_name']."”订单由于卖家超时未发货已自动关闭,货款已退还到余额账户中";
                    $data2=array(
			            'uid'=>$v['uid'],
			            'orderid'=>$v['id'],
			            'title'=>$title,
			            'addtime'=>$now,
			            'type'=>'0'

			        );

			        addShopGoodsOrderMessage($data2);
			        //发送极光IM
        			jMessageIM($title,$v['uid'],'goodsorder_admin');

                }


                
            }

            if($v['status']==2){ //待收货 判断自动确认收货时间是否已满足

                //如果买家没有申请退款
            	if($v['refund_status']==0){
            		$receive_end=$v['shipment_time']+$effective_time['shop_receive_time']*60*60*24;
            	}else{
            		$receive_end=$v['refund_endtime']+$effective_time['shop_receive_time']*60*60*24;
            	}

                if($receive_end<=$now){
                    $data=array(
                        'status'=>3,
                        'receive_time'=>$now
                    );

                    changeShopOrderStatus($v['uid'],$v['id'],$data); //将订单改为待评价

                    //给买家发消息
                    $title="你购买的“".$v['goods_name']."”订单已自动确认收货";
                    $data1=array(
			            'uid'=>$v['uid'],
			            'orderid'=>$v['id'],
			            'title'=>$title,
			            'addtime'=>$now,
			            'type'=>'0'

			        );

			        addShopGoodsOrderMessage($data1);
			        //发送极光IM
        			jMessageIM($title,$v['uid'],'goodsorder_admin');
                }

            }


            if(($v['status']==3||$v['status']==4)&&$v['settlement_time']==0){  //待评价或已评价 且未结算

            	//判断是否有过退货处理 判断确认收货后是否达到后台设置的给卖家打款的时间
            	if($v['refund_status']==0){
            		$settlement_end=$v['receive_time']+$effective_time['shop_settlement_time']*60*60*24;
            	}else{
            		$settlement_end=$v['refund_endtime']+$effective_time['shop_settlement_time']*60*60*24;	
            	}

            	
            	if($settlement_end<=$now){

            		

			        //判断自动结算记录是否存在
			        $balance_record=DI()->notorm->user_balance_record->where("uid=? and touid=? and type=1 and action=2 and orderid=?",$v['shop_uid'],$v['uid'],$v['id'])->fetchOne();

			        if(!$balance_record){


			        	//计算主播代售平台商品佣金
                    	if($v['commission']>0 && $v['liveuid']){

                    		//给主播增加余额
                    		setUserBalance($v['liveuid'],1,$v['commission']);

                    		//写入余额操作记录
                    		$data3=array(
		                        'uid'=>$v['liveuid'], //主播ID
		                        'touid'=>$v['uid'], //买家用户ID
		                        'balance'=>$v['commission'],
		                        'type'=>1,
		                        'action'=>9, //代售平台商品佣金
		                        'orderid'=>$v['id'],
		                		'addtime'=>$now

		                    );

		                    addBalanceRecord($data3);

		                    //给主播发消息
		                    $title1="买家购买的“".$v['goods_name']."”订单佣金".$v['commission']."已自动结算到你的账户";

		                    $data4=array(
					            'uid'=>$v['liveuid'],
					            'orderid'=>$v['id'],
					            'title'=>$title1,
					            'addtime'=>$now,
					            'type'=>'1',
					            'is_commission'=>'1'

					        );

					        addShopGoodsOrderMessage($data4);
					        //发送极光IM
		        			jMessageIM($title,$v['liveuid'],'goodsorder_admin');

                    	}

                    	//计算分享用户的分享佣金
                    	if($v['shareuid']>0 && $v['share_income']){
                    		//给用户增加余额
                    		setUserBalance($v['shareuid'],1,$v['share_income']);

                    		//写入余额操作记录
                    		$data5=array(
		                        'uid'=>$v['shareuid'], //分享用户ID
		                        'touid'=>$v['uid'], //买家用户ID
		                        'balance'=>$v['share_income'],
		                        'type'=>1,
		                        'action'=>10, //分享商品给其他用户购买后获得佣金
		                        'orderid'=>$v['id'],
		                		'addtime'=>$now

		                    );

		                    addBalanceRecord($data5);

                    	}

                    	//给卖家增加余额
				        $balance=$v['total']-$v['share_income'];

				        if($v['order_percent']>0){
				            $balance=$balance*(100-$v['order_percent'])/100;
				            $balance=round($balance,2);
				        }


				        $res1=setUserBalance($v['shop_uid'],1,$balance);

				        //更改订单信息
				        $data=array(
				        	'settlement_time'=>$now
				        );

				        changeShopOrderStatus($v['uid'],$v['id'],$data);

			        	//添加余额操作记录
	                    $data1=array(
	                        'uid'=>$v['shop_uid'],
	                        'touid'=>$v['uid'],
	                        'balance'=>$balance,
	                        'type'=>1,
	                        'action'=>2, //系统自动结算货款给卖家
	                        'orderid'=>$v['id'],
	                		'addtime'=>$now

	                    );

	                    addBalanceRecord($data1);

	                    //主播才发送消息,平台自营不发消息
	                    if($v['shop_uid']>1){

	                    	//给卖家发消息
		                    $title="买家购买的“".$v['goods_name']."”订单已自动结算到你的账户";
		                    $data2=array(
					            'uid'=>$v['shop_uid'],
					            'orderid'=>$v['id'],
					            'title'=>$title,
					            'addtime'=>$now,
					            'type'=>'1'					            

					        );

					        addShopGoodsOrderMessage($data2);
					        //发送极光IM
		        			jMessageIM($title,$v['shop_uid'],'goodsorder_admin');
	                    }

			        }

			        

            	}	


            }

            if($v['status']==5&&$v['refund_status']==0){ //退款 判断等待卖家处理的时间是否超出后台设定的时间，如果超出，自动退款

            	//获取退款申请信息
            	$where=array(
                    'orderid'=>$v['id']
            	);

	            $refund_info=getShopOrderRefundInfo($where);
	            

	            if($refund_info['is_platform_interpose']==0&&$refund_info['shop_result']==0){ //平台未介入且店家未处理

	            	$refund_end=$refund_info['addtime']+$effective_time['shop_refund_time']*60*60*24;


	            	if($refund_end<=$now){

	            		//更改订单退款状态
	            		$data=array(
	                        'refund_status'=>1,
	                        'refund_endtime'=>$now
	                    );

	                    changeShopOrderStatus($v['uid'],$v['id'],$data);

	                    //更改订单退款记录信息

	                    $data1=array(
	                    	'system_process_time'=>$now,
	                    	'status'=>1,

	                    );

	                    changeGoodsOrderRefund($where,$data1);

	            	
	            		//退还买家货款
	                    setUserBalance($v['uid'],1,$v['total']);

	                    //添加余额操作记录
	                    $data1=array(
	                        'uid'=>$v['uid'],
	                        'touid'=>$v['shop_uid'],
	                        'balance'=>$v['total'],
	                        'type'=>1,
	                        'action'=>4, //买家发起退款，卖家超时未处理，系统自动退款
	                        'orderid'=>$v['id'],
                    		'addtime'=>$now

	                    );

	                    addBalanceRecord($data1);

	                    //减去商品销量
            			changeShopGoodsSaleNums($v['goodsid'],0,$v['nums']);

            			//减去店铺销量
        				changeShopSaleNums($v['shop_uid'],0,$v['nums']);

        				//商品规格库存回增
        				changeShopGoodsSpecNum($v['goodsid'],$v['spec_id'],$v['nums'],1);

            			//给买家发消息
	                    $title="你申请的“".$v['goods_name']."”订单退款卖家超时未处理,已自动退款到你的余额账户中";
	                    $data2=array(
				            'uid'=>$v['uid'],
				            'orderid'=>$v['id'],
				            'title'=>$title,
				            'addtime'=>$now,
				            'type'=>'0'

				        );

				        addShopGoodsOrderMessage($data2);
				        //发送极光IM
	        			jMessageIM($title,$v['uid'],'goodsorder_admin');


	            	}
	            	
	            }

	            if($refund_info['is_platform_interpose']==0&&$refund_info['shop_result']==-1){ //未申请平台介入且店家已拒绝
	            	//超时，退款自动完成,订单自动进入退款前状态
	            	$finish_endtime=$refund_info['shop_process_time']+$effective_time['shop_refund_finish_time']*60*60*24;
	            	if($finish_endtime<=$now){

	            		//更改退款订单状态

	            		$data=array(
	            			'status'=>1,
	            			'system_process_time'=>$now
	            		);

	            		changeGoodsOrderRefund($where,$data);


	            		//更改订单状态
	            		$data1=array(
	            			'refund_endtime'=>$now,
	            			'refund_status'=>-1
	            		);

	            		if($v['receive_time']>0){
	            			$data1['status']=3; //待评价
	            		}else{

	            			if($v['shipment_time']>0){
		            			$data1['status']=2; //待收货
		            		}else{
		            			$data1['status']=1; //待发货
		            		}

	            		}

	            		changeShopOrderStatus($v['uid'],$v['id'],$data1);

	            		//给买家发消息
	                    $title="你购买的“".$v['goods_name']."”订单退款申请被卖家拒绝后,".$effective_time['shop_refund_finish_time']."天内你没有进一步操作,系统自动处理结束";
	                    $data2=array(
				            'uid'=>$v['uid'],
				            'orderid'=>$v['id'],
				            'title'=>$title,
				            'addtime'=>$now,
				            'type'=>'0'

				        );

				        addShopGoodsOrderMessage($data2);
				        //发送极光IM
	        			jMessageIM($title,$v['uid'],'goodsorder_admin');

	            	}
	            }

            }



        }

	}


	// 修改店铺商品订单状态【 -1 已关闭  0 待付款 1 待发货 2 待收货 3 待评价 4 已评价 5 退款】
	function changeShopOrderStatus($uid,$orderid,$data){

		$res=DI()->notorm->shop_order
			->where("id=?",$orderid)
			->update($data);

		return $res;
	}

	//写入订单操作记录
	function addShopGoodsOrderMessage($data){
		$res=DI()->notorm->shop_order_message->insert($data);
		return $res;
	}

	//获取商城订单退款详情
    function getShopOrderRefundInfo($where){
    	$info=DI()->notorm->shop_order_refund
    			->where($where)
    			->fetchOne();

    	return $info;
    }

    //修改用户的余额 type:0 扣除余额 1 增加余额
	function setUserBalance($uid,$type,$balance){

		$res=0;

		if($type==0){ //扣除用户余额，增加用户余额消费总额
			$res=DI()->notorm->user
				->where("id=? and balance>=?",$uid,$balance)
				->update(array('balance' => new NotORM_Literal("balance - {$balance}"),'balance_consumption'=>new NotORM_Literal("balance_consumption + {$balance}")) );

		}else if($type==1){ //增加用户余额

			$res=DI()->notorm->user
				->where("id=?",$uid)
				->update(array('balance' => new NotORM_Literal("balance + {$balance}"),'balance_total'=>new NotORM_Literal("balance_total + {$balance}")) );
		}

		return $res;
		
	}

	//添加余额操作记录
	function addBalanceRecord($data){
		$res=DI()->notorm->user_balance_record->insert($data);
		return $res;
	}

	//获取店铺设置的有效时间
	function getShopEffectiveTime(){


		$configpri=getConfigPri();
		$shop_payment_time=$configpri['shop_payment_time']; //付款有效时间（单位：分钟）
		$shop_shipment_time=$configpri['shop_shipment_time']; //发货有效时间（单位：天）
		$shop_receive_time=$configpri['shop_receive_time']; //自动确认收货时间（单位：天）
		$shop_refund_time=$configpri['shop_refund_time']; //买家发起退款,卖家不做处理自动退款时间（单位：天）
		$shop_refund_finish_time=$configpri['shop_refund_finish_time']; //卖家拒绝买家退款后,买家不做任何操作,退款自动完成时间（单位：天）
		$shop_receive_refund_time=$configpri['shop_receive_refund_time']; //订单确认收货后,指定天内可以发起退货退款（单位：天）
		$shop_settlement_time=$configpri['shop_settlement_time']; //订单确认收货后,货款自动打到卖家的时间（单位：天）

		$data['shop_payment_time']=$shop_payment_time;
		$data['shop_shipment_time']=$shop_shipment_time;
		$data['shop_receive_time']=$shop_receive_time;
		$data['shop_refund_time']=$shop_refund_time;
		$data['shop_refund_finish_time']=$shop_refund_finish_time;
		$data['shop_receive_refund_time']=$shop_receive_refund_time;
		$data['shop_settlement_time']=$shop_settlement_time;
		
		return $data;
	}

	//商品订单详情处理
	function handleGoodsOrder($orderinfo){
		$orderinfo['address_format']=$orderinfo['province'].' '.$orderinfo['city'].' '.$orderinfo['area'].' '.$orderinfo['address'];
		$orderinfo['spec_thumb_format']=get_upload_path($orderinfo['spec_thumb']); //商品规格封面

		$effective_time=getShopEffectiveTime();

		$now=time();
		switch ($orderinfo['type']) {
			case '1':
				$orderinfo['type_name']='支付宝';
				break;

			case '2':
				$orderinfo['type_name']=$Think.\lang('WECHAT');
				break;

			case '3':
				$orderinfo['type_name']=$Think.\lang('BALANCE');
				break;

			case '4':
				$orderinfo['type_name']=$Think.\lang('WECHAT_APPLET');
				break;
			case '5':
				$orderinfo['type_name']='Paypal';
				break;
		}

		$orderinfo['status_name']='';
		$orderinfo['status_desc']='';
		$orderinfo['is_refund']='0';

		switch ($orderinfo['status']) {
			case '-1': //已关闭
				$orderinfo['status_name']='交易关闭';
				$orderinfo['status_desc']='因支付超时,交易关闭';
				break;
			case '0': //待付款
				$orderinfo['status_name']='等待买家付款';
				$end=$orderinfo['addtime']+$effective_time['shop_payment_time']*60;
				$cha=$end-$now;
				$orderinfo['status_desc']='剩余时间 '.getSeconds($cha,1);
				break;
			case '1': //待发货
				$orderinfo['status_name']='支付成功,等待卖家发货';
				if($orderinfo['refund_status']==0){ //只要退款未处理过
					$orderinfo['is_refund']='1'; //是否可退款 0 否 1 是
				}

				break;
			case '2': //已发货 待收货
				$orderinfo['status_name']='卖家已发货';
				$end=$orderinfo['shipment_time']+$effective_time['shop_receive_time']*24*60*60;
				$cha=$end-$now;
				$orderinfo['status_desc']='自动确认收货还剩'.getSeconds($cha);

				if($orderinfo['refund_status']==0){ //只要退款未处理过
					$orderinfo['is_refund']='1'; //是否可退款 0 否 1 是
				}

				break;
			case '3': //已收货待评价
				$orderinfo['status_name']='已签收';
				$orderinfo['status_desc']='交易成功,快去评价一下吧';
				$end=$orderinfo['receive_time']+$effective_time['shop_receive_refund_time']*24*60*60;
				if(($orderinfo['refund_status']==0)&&($now<$end)){ //只要退款未处理过 且在后台设定的退货时间范围内就可以发起退款
					$orderinfo['is_refund']='1'; //是否可退款 0 否 1 是
				}
				break;
			case '4': //已评价
				$orderinfo['status_name']='订单已评价';
				break;

			case '5': //请求退款详情单独接口

				if($orderinfo['refund_status']==1){ //退款成功

					$orderinfo['status_name']='退款成功';

				}else if($orderinfo['refund_status']==0){ //退款中状态

					//获取退款详情
					$refund_where=array(
						'orderid'=>$orderinfo['id']
					);
					$refund_info=getShopOrderRefundInfo($refund_where);

					if($refund_info['is_platform_interpose']==0){

						if($refund_info['shop_result']==0){
							$orderinfo['status_name']='等待卖家处理';
						}else if($refund_info['shop_result']==-1){
							$orderinfo['status_name']='卖家已拒绝';
						}

					}else{
						$orderinfo['status_name']='等待平台处理';
					}

					

				}

				
				break;


		}

		$orderinfo['addtime']=date("Y-m-d H:i:s",$orderinfo['addtime']); //添加时间

		$orderinfo['cancel_time']=$orderinfo['cancel_time']>0?date("Y-m-d H:i:s",$orderinfo['cancel_time']):''; //取消时间
		
		$orderinfo['paytime']=$orderinfo['paytime']>0?date("Y-m-d H:i:s",$orderinfo['paytime']):''; //支付时间
		
		$orderinfo['shipment_time']=$orderinfo['shipment_time']>0?date("Y-m-d H:i:s",$orderinfo['shipment_time']):''; //发货时间
		
		$orderinfo['receive_time']=$orderinfo['receive_time']>0?date("Y-m-d H:i:s",$orderinfo['receive_time']):''; //收货时间
		
		$orderinfo['evaluate_time']=$orderinfo['evaluate_time']>0?date("Y-m-d H:i:s",$orderinfo['evaluate_time']):''; //评价时间

		$orderinfo['settlement_time']=$orderinfo['settlement_time']>0?date("Y-m-d H:i:s",$orderinfo['settlement_time']):''; //结算时间

		$orderinfo['refund_starttime']=$orderinfo['refund_starttime']>0?date("Y-m-d H:i:s",$orderinfo['refund_starttime']):''; //退款申请时间

		$orderinfo['refund_endtime']=$orderinfo['refund_endtime']>0?date("Y-m-d H:i:s",$orderinfo['refund_endtime']):''; //退款处理结束时间
		

		return $orderinfo;
	}

	//获取物流信息
	function getExpressInfoByKDN($express_code,$express_number,$phone){
		$configpri=getConfigPri();
        $express_type=isset($configpri['express_type'])?$configpri['express_type']:'';
        $EBusinessID=isset($configpri['express_id_dev'])?$configpri['express_id_dev']:'';
        $AppKey=isset($configpri['express_appkey_dev'])?$configpri['express_appkey_dev']:'';

        //$ReqURL='http://sandboxapi.kdniao.com:8080/kdniaosandbox/gateway/exterfaceInvoke.json'; //免费版即时查询【快递鸟测试账号专属查询地址】
        $ReqURL='http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx'; //免费版即时查询【已注册商户ID真实即时查询地址】

        if($express_type){ //正式付费物流跟踪版
            $EBusinessID=isset($configpri['express_id'])?$configpri['express_id']:'';
            $AppKey=isset($configpri['express_appkey'])?$configpri['express_appkey']:'';
            $ReqURL='http://api.kdniao.com/api/dist'; //物流跟踪版查询【已注册商户ID真实即时查询地址】
        }

        $requestData=array(
            'ShipperCode'=>$express_code,
            'LogisticCode'=>$express_number
        );

        if($express_code=='SF'){ //顺丰要带上发件人/收件人手机号的后四位
        	$requestData['CustomerName']=substr($phone, -4);
        }

        $requestData= json_encode($requestData);
        
        $datas = array(
            'EBusinessID' => $EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );

        //物流跟踪版消息报文
        if($express_type){
        	$datas['RequestType']='8001';
        }

        $datas['DataSign'] = encrypt_kdn($requestData, $AppKey);

        $result=sendPost_KDN($ReqURL, $datas);

        return json_decode($result,true);


	}


	/**
     * 快递鸟电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名
     */
    function encrypt_kdn($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    function sendPost_KDN($url, $datas) {
        $temps = array();   
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);      
        }   
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        if(empty($url_info['port']))
        {
            $url_info['port']=80;   
        }
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);  
        
        return $gets;
    }

    function is_true($val, $return_null=false){
        $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
        return ( $boolval===null && !$return_null ? false : $boolval );
    }

    //获取物流状态【即时查询版】
    function getExpressStateInfo($express_code,$express_number,$express_name,$username,$phone){

    	$express_info=[];

    	$express_info_kdn=getExpressInfoByKDN($express_code,$express_number,$phone);
    	$express_state=$express_info_kdn['State']; //物流状态 0-暂无轨迹信息 1-已揽收 2-在途中  3-已签收4-问题件

    	if(!$express_state){
            $express_info['state_name']='包裹正在等待揽收';
            $express_info['desc']=$express_name.' '.$express_number;
        }elseif($express_state==1){
            $express_info['state_name']='包裹已揽收';
            $express_info['desc']=$express_name.' '.$express_number;
        }elseif($express_state==2){
            $express_info['state_name']='包裹运输中';
            $express_info['desc']=$express_name.' '.$express_number;
        }elseif($express_state==3){
            $express_info['state_name']='包裹已签收';
            $express_info['desc']='签收人：'.$username;
        }

        return $express_info;
    }


    //更改退款详情信息
    function changeGoodsOrderRefund($where,$data){
    	$res=DI()->notorm->shop_order_refund
    			->where($where)
    			->update($data);

    	return $res;
    }

    //添加退款操作记录
    function setGoodsOrderRefundList($data){
    	$res=DI()->notorm->shop_order_refund_list->insert($data);
    	return $res;
    }

    //更新商品的销量 type=0 减 type=1 增
	function changeShopGoodsSaleNums($goodsid,$type,$nums){
		if($type==0){

			$res=DI()->notorm->shop_goods
			->where("id=? and sale_nums>= ?",$goodsid,$nums)
			->update(
				array('sale_nums' => new NotORM_Literal("sale_nums - {$nums}"))
			);

		}else{
			$res=DI()->notorm->shop_goods
			->where("id=?",$goodsid)
			->update(
				array('sale_nums' => new NotORM_Literal("sale_nums + {$nums}"))
			);
		}

		return $res;
		
	}

	//更新商品的销量 type=0 减 type=1 增
	function changeShopSaleNums($uid,$type,$nums){
		if($type==0){

			$res=DI()->notorm->shop_apply
			->where("uid=? and sale_nums>= ?",$uid,$nums)
			->update(
				array('sale_nums' => new NotORM_Literal("sale_nums - {$nums}"))
			);

		}else{
			$res=DI()->notorm->shop_apply
			->where("uid=?",$uid)
			->update(
				array('sale_nums' => new NotORM_Literal("sale_nums + {$nums}"))
			);
		}

		return $res;
		
	}

	//获取商品评价的追评信息
	function getGoodsAppendComment($uid,$orderid){

		$info=DI()->notorm->shop_order_comments
				->where("uid=? and orderid=? and is_append=1",$uid,$orderid)
				->fetchOne();

		return $info;
	}

	//商品评价信息处理
	function handleGoodsComments($comments_info){

		$comments_info['time_format']=secondsFormat($comments_info['addtime']);
		$comments_info['video_thumb']=get_upload_path($comments_info['video_thumb']);
		$comments_info['video_url']=get_upload_path($comments_info['video_url']);

		if($comments_info['thumbs']!=''){
			$thumb_arr=explode(',',$comments_info['thumbs']);
			foreach ($thumb_arr as $k => $v) {
				$thumb_arr[$k]=get_upload_path($v);
			}
		}else{
			$thumb_arr=array();	
		}

		
		$comments_info['thumb_format']=$thumb_arr;

		$order_info=getShopOrderInfo(array('id'=>$comments_info['orderid']),'spec_name');


		$comments_info['spec_name']=$order_info['spec_name']; //商品规格名称

		//获取用户信息
		$user_info=DI()->notorm->user
					->where("id=?",$comments_info['uid'])
					->select("avatar,user_nicename")
					->fetchOne();

		$comments_info['user_nicename']=$user_info['user_nicename'];
		$comments_info['avatar']=get_upload_path($user_info['avatar']);
		if($comments_info['is_anonym']){
			$comments_info['user_nicename']='匿名用户';
			$comments_info['avatar']=get_upload_path("/anonym.png");
		}
		

		unset($comments_info['service_points']);
		unset($comments_info['express_points']);
		unset($comments_info['thumbs']);
		unset($comments_info['is_anonym']);

		return $comments_info;
	}

	/* 时长格式化 */
	function secondsFormat($time){

		$now=time();
		$cha=$now-$time;

		if($cha<60){
			return '刚刚';
		}

		if($cha>=4*24*60*60){ //超过4天
			$now_year=date('Y',$now);
			$time_year=date('Y',$time);

			if($now_year==$time_year){
				return date("m月d日",$time);
			}else{
				return date("Y年m月d日",$time);
			}

		}else{

			$iz=floor($cha/60);
			$hz=floor($iz/60);
			$dz=floor($hz/24);

			if($dz>3){
				return '3天前';
			}else if($dz>2){
				return '2天前';
			}else if($dz>1){
				return '1天前';
			}

			if($hz>1){
				return $hz.'小时前';
			}

			return $iz.'分钟前';
			

		}

	}


	// 商城分类-二级
    function getShopTwoClass(){
        $key="twoGoodsClass";
		$list=getcaches($key);
		if(!$list){
            $list=DI()->notorm->shop_goods_class
					->select("gc_id,gc_name,gc_icon")
					->where('gc_isshow=1 and gc_grade=2')
                    ->order("gc_sort")
					->fetchAll();
            if($list){
                setcaches($key,$list);
            }
			
		}
        foreach($list as $k=>$v){
            $v['gc_icon']=get_upload_path($v['gc_icon']);
            $list[$k]=$v;
        }
        return $list;        
        
    }


    // 商城分类-三级级
    function getShopThreeClass($classid){
        $key="threeGoodsClass_".$classid;
		$list=getcaches($key);
		if(!$list){
            $list=DI()->notorm->shop_goods_class
					->select("gc_id,gc_name")
					->where("gc_isshow=1 and gc_grade=3 and gc_parentid={$classid}")
                    ->order("gc_sort")
					->fetchAll();
            if($list){
                setcaches($key,$list);
            }else{
				$list=[];
			}
			
		}

        return $list;        
        
    }

    //检测姓名
	function checkUsername($username){
		$preg='/^(?=.*\d.*\b)/';
		$isok = preg_match($preg,$username);
		if($isok){
			return 1;
		}else{
			return 0;
		}
	}

	//身份证检测
	/*function checkCardNo($cardno){
		
		$preg='/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/';
		$isok=preg_match($preg, $cardno);
		if($isok){
			return 1;
		}else{
			return 0;
		}
	}*/

	function checkCardNo($vStr){

		$vCity = array(
		  	'11','12','13','14','15','21','22',
		  	'23','31','32','33','34','35','36',
		  	'37','41','42','43','44','45','46',
		  	'50','51','52','53','54','61','62',
		  	'63','64','65','71','81','82','91'
		);
		
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)){
		 	return false;
		}

	 	if (!in_array(substr($vStr, 0, 2), $vCity)){
	 		return false;
	 	}
	 
	 	$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
	 	$vLength = strlen($vStr);

	 	if($vLength == 18){
	  		$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
	 	}else{
	  		$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
	 	}

		if(date('Y-m-d', strtotime($vBirthday)) != $vBirthday){
		 	return false;
		}

	 	if ($vLength == 18) {
	  		$vSum = 0;
	  		for ($i = 17 ; $i >= 0 ; $i--) {
	   			$vSubStr = substr($vStr, 17 - $i, 1);
	   			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
	  		}
	  		if($vSum % 11 != 1){
	  			return false;
	  		}
	 	}

	 	return true;
	}

	//获取店铺协商历史
	function getShopOrderRefundList($where){
		$list=DI()->notorm->shop_order_refund_list
			->where($where)
			->order("addtime desc")
			->fetchAll();
		
		return $list;
	}

	//代售平台商品列表格式化处理
	function handlePlatformGoods($list,$platform_list,$type){

		foreach ($list as $k => $v) {
            $thumb_arr=explode(',',$v['thumbs']);
            $list[$k]['thumb']=get_upload_path($thumb_arr[0]);

            
            if($v['type']==1){ //外链商品
                $list[$k]['price']=$v['present_price'];
                $list[$k]['specs']=[];
            }else{
                $spec_arr=json_decode($v['specs'],true);
                $list[$k]['price']=$spec_arr[0]['price'];
                $list[$k]['specs']=$spec_arr;
            }


        	if($platform_list){
        		foreach ($platform_list as $k1 => $v1) {
	                if($v1['goodsid']==$v['id']){
	                	$list[$k]['issale']=$v1['issale'];
	                    $list[$k]['live_isshow']=$v1['live_isshow'];
	                    break;
	                }
            	}
        	}
            	

            unset($list[$k]['thumbs']);
            unset($list[$k]['present_price']);
            unset($list[$k]['specs']);
        }

        return $list;
	}

	//检测用户代售商品
	function checkUserSalePlatformGoods($where){
		$info=DI()->notorm->seller_platform_goods
		->where($where)
		->fetchOne();
		if(!$info){
			return 0;
		}

		return 1;
	}

	//获取代售平台商品记录
	function getOnsalePlatformInfo($where){
		$info=DI()->notorm->seller_platform_goods
		->where($where)
		->fetchOne();

		return $info;
	}

	//修改代售平台商品记录的信息
	function setOnsalePlatformInfo($where,$data){
		$res=DI()->notorm->seller_platform_goods
		->where($where)
		->update($data);
		return $res;
	}

	//获取低延迟推流和播流地址
	function getLowLatencyStream($stream){

		$configpri=getConfigPri();
		$nowtime=time();
		$cdn_switch=$configpri['cdn_switch'];  //cdn_switch  0表示直播模式 1表示直播+连麦模式

        if($cdn_switch==1){
            $bizid = $configpri['tx_bizid'];
            $push_url_key = $configpri['tx_push_key'];
            $tx_acc_key = $configpri['tx_acc_key'];
            $push = $configpri['tx_push'];
            $pull = $configpri['tx_pull'];

            $now_time2 = $nowtime + 3*60*60;
            $txTime = dechex($now_time2);
            
            $live_code = $stream ;

            $txSecret = md5($push_url_key . $live_code . $txTime);
            $safe_url = "?txSecret=" . $txSecret."&txTime=" .$txTime;
            $push_url = "rtmp://" . $push . "/live/" .  $live_code .$safe_url. "&bizid=" . $bizid ;
            
            $txSecret2 = md5($tx_acc_key . $live_code . $txTime);
            $safe_url2 = "?txSecret=" . $txSecret2."&txTime=" .$txTime;
            $play_url = "rtmp://" . $pull . "/live/" .$live_code .$safe_url2. "&bizid=" . $bizid;
            
            
        }else{
			$push_url=PrivateKeyA('rtmp',$stream,1);
			$play_url=PrivateKeyA('rtmp',$stream,0);
		}
		
        $info=array(
			"pushurl" => $push_url,
			"timestamp" => $nowtime, 
			"playurl" => $play_url
		);

		return $info;
	}

	/*获取店铺申请状态*/
	function getShopApplyStatus($uid){
		$info=DI()->notorm->shop_apply
				->select("status")
                ->where("uid=?",$uid)
                ->fetchOne();

        if(!$info){
        	return '-1';
        }

        return $info['status'];
	}

	// 获取用户的余额
	function getUserBalance($uid){
		$res=array(
			'balance'=>'0.00',
			'balance_total'=>'0.00'
		);

		$info=DI()->notorm->user->where("id=?",$uid)->select("balance,balance_total")->fetchOne();

		if($info){
			$res['balance']=$info['balance'];
			$res['balance_total']=$info['balance_total'];
		}

		return $res;
	}

	//商品列表格式化处理
	function handleGoodsList($where,$p,$order="id desc"){

		if($p<1){
            $p=1;
        }
        
        $nums=50;
        $start=($p-1)*$nums;
		
        $list=DI()->notorm->shop_goods
                ->select("id,name,thumbs,sale_nums,specs,hits,issale,type,original_price,present_price,status,live_isshow,commission")
                ->where($where)
                ->order($order)
                ->limit($start,$nums)
                ->fetchAll();
		

		if(!$list){
			return [];
		}

		foreach ($list as $k => $v) {
            $thumb_arr=explode(',',$v['thumbs']);
            $list[$k]['thumb']=get_upload_path($thumb_arr[0]);

            
            if($v['type']==1){ //外链商品
            	$list[$k]['price']=$v['present_price'];
            	$list[$k]['specs']=[];
            }else{
            	$spec_arr=json_decode($v['specs'],true);
            	$list[$k]['price']=$spec_arr[0]['price'];
            	$list[$k]['specs']=$spec_arr;
            }
 

            unset($list[$k]['thumbs']);
            unset($list[$k]['present_price']);
        }

        return $list;
	}

	// 根据不同条件获取物流列表信息
	function getExpressInfo($where){
		$info=DI()->notorm->shop_express
				->where($where)
				->fetchOne();

		return $info;
	}

	/* 生成二维码 */
    
    function scerweima($url=''){

        $key=md5($url);
        
        //生成二维码图片
        $filename2 = '/upload/qr/'.$key.'.png';
        $filename = API_ROOT.'/../public/upload/qr/'.$key.'.png';
        
        if(!file_exists($filename)){
            require_once API_ROOT.'/../sdk/phpqrcode/phpqrcode.php';
            
            $value = $url;					//二维码内容
            
            $errorCorrectionLevel = 'H';	//容错级别 
            $matrixPointSize = 6.2068965517241379310344827586207;			//生成图片大小  
            
            //生成二维码图片
            \QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2); 
        }
      
        return $filename2;
    }

    /* 直播分类 */
    function getLiveClass(){
        $key="getLiveClass";
		$list=getcaches($key);

		if(!$list){
            $list=DI()->notorm->live_class
					->select("*")
                    ->order("list_order asc,id desc")
					->fetchAll();
            if($list){
                setcaches($key,$list);
            }
			
		}

		foreach ($list as $k => $v) {
			$list[$k]['id']=(string)$v['id'];
			$list[$k]['list_order']=(string)$v['list_order'];
		}
        
        return $list;        
        
    }


    /* 检测用户是否存在 */
    function checkUser($where){
        if($where==''){
            return 0;
        }

        $isexist=DI()->notorm->user->where($where)->fetchOne();
        
        if($isexist){
            return 1;
        }
        
        return 0;
    }

    //验证数字是否整数/两位小数
    function checkNumber($num){

    	if(floor($num) ==$num){
    		return 1;
    	}

    	if (preg_match('/^[0-9]+(.[0-9]{1,2})$/', $num)) {
    		return 1;
    	}

    	return 0;
    }

    //亚马逊存储
    function awscloud($files,$type){
    	$rs=array('code'=>1000,'data'=>[],'msg'=>'上传失败');

    	$name=$files["file"]['name'];
    	$name_arr=explode(".", $name);
        $suffix=$name_arr[count($name_arr)-1];

		$rand=rand(0,100000);
		$name=time().$rand.'.'.$suffix;

		require_once(API_ROOT.'/../sdk/aws/aws-autoloader.php');

		$configpri=getConfigPri();
			
		$sharedConfig = [
			'profile' => 'default',
			'region' => $configpri['aws_region'], //区域
			'version' => 'latest',
			'Content-Type' => $files["file"]['type'],
			//'debug'   => true
		];

		$sdk = new \Aws\Sdk($sharedConfig);	
		$s3Client = $sdk->createS3();
		
		$result = $s3Client->putObject([
			'Bucket' => $configpri['aws_bucket'],
			'Key' => $name,
			'ACL' => 'public-read',
			'Content-Type' => $files["file"]['type'],
			'Body' => fopen($files["file"]['tmp_name'], 'r')
		]);


		$a = (array)$result;

		$n = 0;
		$aws_res=1;

		foreach($a as $k =>$t){
			if($n==0){
				$n++;
				$info = $t['ObjectURL'];
				if($info){
					//return $info;
				}else{
					$aws_res=0;
				}
			}
		}

		if(!$aws_res){
			return $rs;
		}

		$rs['code']=0;
        $rs['data']['url']=$name;

        return $rs;

    }
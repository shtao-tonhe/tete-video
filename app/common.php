<?php

    use think\Db;
    use cmf\lib\Storage;
	
	
	
	
    // 应用公共文件
    error_reporting(E_ERROR | E_WARNING | E_PARSE);

    require_once dirname(__FILE__).'/redis.php';
	
	
	/**
	 * 获取当前登录的管事员id
	 * @return int
	 */
	function get_current_admin_id(){
		return session('ADMIN_ID');
	}

    /* 去除NULL 判断空处理 主要针对字符串类型*/
	function checkNull($checkstr){
        $checkstr=trim($checkstr);
		$checkstr=urldecode($checkstr);

		if( strstr($checkstr,'null') || (!$checkstr && $checkstr!=0 ) ){
			$str='';
		}else{
			$str=$checkstr;
		}
		$str=htmlspecialchars($str);
		return $str;	
	}
    
    /* 检验手机号 */
	function checkMobile($mobile){
		$ismobile = preg_match("/^1[3|4|5|6|7|8|9]\d{9}$/",$mobile);
		if($ismobile){
			return 1;
		}
        
        return 0;
		
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
    
    /**
	 * 转化数据库保存的文件路径，为可以访问的url
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

			if($cloudtype==1){ //存储方式为七牛
				return html_entity_decode($file);
			}else if($cloudtype==2 && $configpri['tx_private_signature']){

				return setTxUrl(html_entity_decode($file)); //腾讯云存储为私有读写时需要调用该方法获取签名验证
			}else if($cloudtype==3){
				return html_entity_decode($file);
			}else{
				return html_entity_decode($file);
			}


		}else if(strpos($file,"/")===0){

			$filepath= cmf_get_domain().$file;
			return $filepath;
		}else{

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

    /* 公共配置 */
    function getConfigPub() {
        $key='getConfigPub';
        $config=getcaches($key);
		$config=false;
        if(!$config){
            $config= cmf_get_option('site_info', $options);
            setcaches($key,$config);
        }

        return 	$config;
    }		


    /* 获取私密配置 */
	function getConfigPri() {
		$key='getConfigPri';
		$config=getcaches($key);
		$config=false;
		if(!$config){
			
            $config=cmf_get_option('configpri');
			setcaches($key,$config);
		}
        
        
		return 	$config;
	}

	/* 判断token */
	function checkToken($uid,$token) {
        if($uid<1 || $token==''){
            return 700;
        }
        $key="token_".$uid;
		$userinfo=getCache($key);
		if(!$userinfo){
			$userinfo=Db::name('user_token')
					->field('token,expire_time')
					->where(['user_id'=>$uid])
					->find();
            if($userinfo){
                setCache($key,$userinfo);
            }
		}

		if(!$userinfo || $userinfo['token']!=$token || $userinfo['expire_time']<time()){
			return 700;				
		}
		
	
        
        return 	0;				
		
	}
    
	/* 用户基本信息 */

    function getUserInfo($uid) {
        $info= Db::name("user")
			->field("id,user_nicename,avatar,avatar_thumb,sex,signature,province,city,birthday,user_status,coin,isrecommend,recommend_time")
			->where("id='{$uid}'")
			->find();
		if($info){
			$info['avatar']=get_upload_path($info['avatar']);
			$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);

		}
				
		return 	$info;		
    }	
    

    
    /* 腾讯IM签名 */
    function setSig($id){
		$sig='';
		$configpri=getConfigPri();
		$appid=$configpri['im_sdkappid'];
		//return $sig;	
		try{
            $path= CMF_ROOT.'sdk/txim/';
			require_once( $path ."TLSSig.php");
			$api = new \TLSSigAPI();
			$api->SetAppid($appid);
			$private = file_get_contents( $path .'keys/private_key.pem');
			$api->SetPrivateKey($private);
			$public = file_get_contents( $path .'keys/public_key.pem');
			$api->SetPublicKey($public);
			$sig = $api->genSig($id);
		}catch(Exception $e){
				//echo $e->getMessage();
            file_put_contents(CMF_ROOT.'log/setSig.txt',date('y-m-d H:i:s').'提交参数信息 :'.$e->getMessage()."\r\n",FILE_APPEND);
		}
        
		return $sig;		
	}

    /* 腾讯IM REST API */
    function getTxRestApi(){
		$configpri=getConfigPri();
		$sdkappid=$configpri['im_sdkappid'];
		$identifier=$configpri['im_admin'];
	
        $sig=setSig($identifier);
        
        $path= CMF_ROOT.'sdk/txim/';
        require_once( $path."restapi/TimRestApi.php");
        
        $api = createRestAPI();
        $api->init($sdkappid, $identifier);
			//托管模式
        $ret = $api->set_user_sig($sig);
        
        if($ret == false){
            file_put_contents(CMF_ROOT.'log/RESTAPI.txt',date('y-m-d H:i:s').'提交参数信息 :'.'设置管理员usrsig失败'."\r\n",FILE_APPEND);
        }
        
        return $api;
	}
    
    /* 时长 */
	/*function getLength($cha,$type=0){
		$iz=floor($cha/60);
		$hz=floor($iz/60);
		$dz=floor($hz/24);
		// 秒
		$s=$cha%60;
		// 分
		$i=floor($iz%60);
		// 时
		$h=floor($hz/24);
		// 天
		
        if($type==1){
            if($s<10){
                $s='0'.$s;
            }
            if($i<10){
                $i='0'.$i;
            }

            if($h<10){
                $h='0'.$h;
            }
            
            if($hz<10){
                $hz='0'.$hz;
            }
            return $hz.':'.$i.':'.$s;
        }
        
        if($type==2){
            if($s<10){
                $s='0'.$s;
            }
            if($i<10){
                $i='0'.$i;
            }
            if($hz>0){
                if($hz<10){
                    $hz='0'.$hz;
                }
                
                return $hz.':'.$i.':'.$s;
            }
            
            return $i.':'.$s;
        }
        
        
		if($cha<60){
			return $cha.'秒';
		}else if($iz<60){
			return $iz.'分'.$s.'秒';
		}else if($hz<24){
			return $hz.'小时'.$i.'分'.$s.'秒';
		}else if($dz<30){
			return $dz.'天'.$h.'小时'.$i.'分'.$s.'秒';
		}
	}*/

    function getLength($time,$type=0){

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


	/**导出Excel 表格
    * @param $expTitle 名称
    * @param $expCellName 参数
    * @param $expTableData 内容
    * @throws \PHPExcel_Exception
    * @throws \PHPExcel_Reader_Exception
    */
	function exportExcel($expTitle,$expCellName,$expTableData,$cellName){

		//$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$xlsTitle =  $expTitle;//文件名称
		$fileName = $xlsTitle.'_'.date('YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		
        $path= CMF_ROOT.'sdk/PHPExcel/';
        require_once( $path ."PHPExcel.php");
        
		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		for($i=0;$i<$cellNum;$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'1', $expCellName[$i][1]);
		}
		for($i=0;$i<$dataNum;$i++){
			for($j=0;$j<$cellNum;$j++){
				$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+2), filterEmoji( $expTableData[$i][$expCellName[$j][0]] ) );
			}
		}
		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xlsx"');
		header("Content-Disposition:attachment;filename={$fileName}.xlsx");//attachment新窗口打印inline本窗口打印
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');//Excel5为xls格式，excel2007为xlsx格式
		$objWriter->save('php://output');
		exit;
	}
	
	
	function get_file_suffix($file_name, $allow_type = array()){

      $fnarray=explode('.', $file_name);

      $file_suffix = strtolower(end($fnarray));


      if (empty($allow_type)){
        return true;
      }else{
        if (in_array($file_suffix, $allow_type)){
			return true;
        }else{
			return false;
        }
      }
    }
	
	/* 
    单文件云存储
    files  单个文件上传信息(包含键值)   $files['file']=$_FILES["file"]
    type  文件类型 img图片 video视频 music音乐
    
	 */
	function adminUploadFilesBF($files='',$type="video"){

		$rs=array('code'=>1000,'data'=>[],'msg'=>'上传失败');
		
		
		
		//获取后台上传配置
		$configpri=getConfigPri();
		if($configpri['cloudtype']==1){  //七牛云存储
			require_once CMF_ROOT.'sdk/qiniu/autoload.php';

			// 需要填写你的 Access Key 和 Secret Key
			$accessKey = $configpri['qiniu_accesskey'];
			$secretKey = $configpri['qiniu_secretkey'];
			$bucket = $configpri['qiniu_bucket'];
			$qiniu_domain_url = $configpri['qiniu_domain_url'];

			// 构建鉴权对象
			$auth = new \Qiniu\Auth($accessKey, $secretKey);

			// 生成上传 Token
			$token = $auth->uploadToken($bucket);

			// 要上传文件的本地路径
			$filePath = $files['file']['tmp_name'];

			// 上传到七牛后保存的文件名
			$ext=strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
			$key = date('Ymd').'/'.uniqid().'.'.$ext;

			// 初始化 UploadManager 对象并进行文件的上传。
			$uploadMgr = new \Qiniu\Storage\UploadManager();
			
			// 调用 UploadManager 的 putFile 方法进行文件的上传。
			list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
			
			if($err !== null){
				$rs['msg']=$err->getResponse()->error;
				return $rs;
			}

			$url=$key;
			$url_p=$qiniu_domain_url.$key;
			
		}else if($configpri['cloudtype']==2){ //腾讯云存储

			/* 腾讯云 */
			require_once(CMF_ROOT.'sdk/qcloud/autoload.php');

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
				$url_p=$url;
			} catch (\Exception $e) {
				$rs['msg']=$e->getMessage();
				return $rs;
			}
		}
		
		$rs['code']=0;
		$rs['data']['url']=$url;
		$rs['data']['url_p']=$url_p;

		return $rs;
	}


	/* 
    单文件云存储
    files  单个文件上传信息(包含键值)   $files['file']=$_FILES["file"]
    type  文件类型 img图片 video视频 music音乐
    
 */
function adminUploadFiles($files='',$type="video"){

	$name=$files["file"]['name'];
    $pathinfo=pathinfo($name);
    
    if(!isset($pathinfo['extension'])){
        $files["file"]['name']=$name.'.jpg';
    }

    $rs=array('code'=>1000,'data'=>[],'msg'=>'上传失败');
	//获取后台上传配置
	$configpri=getConfigPri();

	$cloudtype=$configpri['cloudtype'];

	if($cloudtype==1){  //七牛云存储
		require_once CMF_ROOT.'sdk/qiniu/autoload.php';

        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = $configpri['qiniu_accesskey'];
        $secretKey = $configpri['qiniu_secretkey'];
        $bucket = $configpri['qiniu_bucket'];
        /* $qiniu_domain_url = $configpri['qiniu_domain_url']; */
        $qiniu_domain_url =  $configpri['qiniu_protocol']."://".$configpri['qiniu_domain']."/";

        // 构建鉴权对象
        $auth = new \Qiniu\Auth($accessKey, $secretKey);

        // 生成上传 Token
        $token = $auth->uploadToken($bucket);

        // 要上传文件的本地路径
        $filePath = $files['file']['tmp_name'];

        // 上传到七牛后保存的文件名
        $ext=strtolower(pathinfo($files['file']['name'], PATHINFO_EXTENSION));
        $key = date('Ymd').'/'.uniqid().'.'.$ext;

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new \Qiniu\Storage\UploadManager();
        
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);

        
        
        if($err !== null){
            $rs['msg']=$err->getResponse()->error;
            return $rs;
        }

        $url=$qiniu_domain_url.$key;
        $url_p=$key;


	}else if($cloudtype==2){ //腾讯云存储

        require_once(CMF_ROOT.'sdk/qcloud/autoload.php');
        
        $file_name = $_FILES["file"]["name"];
        $src = $_FILES["file"]["tmp_name"];
        if($files){
            $file_name = $files["file"]["name"];
            $src = $files["file"]["tmp_name"];
        }

        $fnarray=explode('.', $file_name);

        $file_suffix = strtolower(end($fnarray)); //后缀名

        $dst = date('YmdHis').rand(1,999).'.'.$file_suffix;

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

            $url_p=str_replace($configpri['tx_domain_url'], '', $url);

        } catch (\Exception $e) {
            $rs['msg']=$e->getMessage();
            return $rs;
        }
	}else if($cloudtype==3){ //亚马逊存储


		$name_arr=explode(".", $name);
        $suffix=$name_arr[count($name_arr)-1];

		$rand=rand(0,100000);
		$name=time().$rand.'.'.$suffix;

		$path= CMF_ROOT.'sdk/aws/aws-autoloader.php';
		require_once($path);


		if(!empty($files)){
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
			
			$aws_res=1;
			$a = (array)$result;
			$n = 0;


			foreach($a as $k =>$t){
				if($n==0){
					$n++;
					$info = $t['ObjectURL'];
					if($info){
						//return $info;
						//return $name;
					}else{
						$aws_res=0;
					}
				}
			}

			if($aws_res){
				$url_p=$name;
				$url=$configpri['aws_hosturl'].$name;
			}
		}
	}
    
    $rs['code']=0;
    $rs['data']['url']=$url_p;
    $rs['data']['url_p']=setCloudType($url_p); //设置存储方式
    $rs['data']['url_c']=$url;

    $tx_private_signature=$configpri['tx_private_signature'];

    if($cloudtype==2 && $tx_private_signature){ //腾讯存储桶为私有读 需要进行文件验证签名
    	$rs['data']['url_c']=setTxUrl($url_p); //签名地址
    }

    //return $rs;

    //同步Upload.php返回格式
    return [
        'filepath'    => $rs['data']['url_p'], //带存储方式的【存储用】
        "name"        => '',
        'id'          => time().rand(1,99),
        'preview_url' =>$rs['data']['url_c'], //带签名的【展示用】
        'url'         => $rs['data']['url_c'],
        'code'		  =>0
    ];


	
}

	
	/* 数字格式化 */
	function NumberFormat($num){
		if($num<10000){

		}else if($num<1000000){
			$num=round($num/10000,2).'万';
		}else if($num<100000000){
			$num=round($num/10000,1).'万';
		}else if($num<10000000000){
			$num=round($num/100000000,2).'亿';
		}else{
			$num=round($num/100000000,1).'亿';
		}
		return $num;
	}
	
	/* 生成邀请码 */
	function createCode(){
		$code = 'ABCDEFGHIJKLMNPQRSTUVWXYZ';
		$rand = $code[rand(0,25)]
			.strtoupper(dechex(date('m')))
			.date('d').substr(time(),-5)
			.substr(microtime(),2,5)
			.sprintf('%02d',rand(0,99));
		for(
			$a = md5( $rand, true ),
			$s = '123456789ABCDEFGHIJKLMNPQRSTUV',
			$d = '',
			$f = 0;
			$f < 6;
			$g = ord( $a[ $f ] ),
			$d .= $s[ ( $g ^ ord( $a[ $f + 6 ] ) ) - $g & 0x1F ],
			$f++
		);
		if(mb_strlen($d)==6){
			$oneinfo=Db::name("user")->field("id")->where("code='{$d}'")->find();
			if(!$oneinfo){
				return $d;
			}
		}
		
        $d=createCode();
		return $d;
	}
	
	function m_s($a){
		return $a;
		$url=$_SERVER['HTTP_HOST'];
		$domain=cmf_get_domain();
		$domain=str_replace("https://", '', $domain);
		$domain=str_replace("http://", '', $domain);
		if($url==$domain){
			$l=strlen($a);
			$sl=$l-6;
			$s='';
			for($i=0;$i<$sl;$i++){
				$s.='*';
			}
			$rs=substr_replace($a,$s,3,$sl);
			return $rs;
		}
		return $a;
	}
	
	
	/*极光IM用户名前缀（与APP端统一）*/
	function userSendBefore(){
		$before='';
		return $before;
	}
		
	
	/*极光IM*/
	function jMessageIM($test,$uid,$adminName='dsp_admin_2'){
		//获取后台配置的极光推送app_key和master_secret
		$configPri=getConfigPri();
		$appKey = $configPri['jpush_key'];
		$masterSecret =  $configPri['jpush_secret'];

		if($appKey&&$masterSecret){
			//极光IM			
			require_once CMF_ROOT.'sdk/jmessage/autoload.php'; //导入极光IM类库
			$jm = new \JMessage\JMessage($appKey, $masterSecret);
			//注册管理员
			$admin = new \JMessage\IM\Admin($jm);

			$nickname="";

			if(!$adminName){
				$adminName='dsp_admin_2'; //短视频系统规定系统通知必须是该账号（与APP保持一致）
			}

			switch($adminName){
                case "goodsorder_admin":
                $nickname="订单管理";
                break;

                default:
                $nickname="系统通知";
               
            }

			$regInfo = [
			    'username' => $adminName,
			    'password' => $adminName,
			    'nickname'=>$nickname
			];

			$response = $admin->register($regInfo);
			if($response['body']==""||$response['body']['error']['code']==899001){ //新管理员注册成功或管理员已经存在

				//发布消息
				$message = new \JMessage\IM\Message($jm);

				$user = new \JMessage\IM\User($jm);

				$before=userSendBefore(); //获取极光用户账号前缀

				$from = [
				    'id'   => $adminName, 
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
		$streamKey = isset($stream_a[0])? $stream_a[0] : '';
		$ext = isset($stream_a[1])? $stream_a[1] : '';
		
		//$live_code = $bizid . "_" .$streamKey;    
		$live_code = $streamKey;    
		   	
		$now_time = time() + 3*60*60;
		$txTime = dechex($now_time);

		$txSecret = md5($push_url_key . $live_code . $txTime);
		$safe_url = "?txSecret=" .$txSecret."&txTime=" .$txTime;		

		if($type==1){
			//$push_url = "rtmp://" . $bizid . ".livepush2.myqcloud.com/live/" .  $live_code . "?bizid=" . $bizid . "&record=flv" .$safe_url;	可录像
			//$url = "rtmp://" . $bizid .".livepush2.myqcloud.com/live/" . $live_code . "?bizid=" . $bizid . "" .$safe_url;
			$url=array(
				'cdn'=>urlencode("rtmp://{$push}/live/"),
				'stream'=>urlencode($live_code.$safe_url),
			);
		}else{
			$url = "http://{$pull}/live/" . $live_code . ".flv";
			if($ext){
				$url = "http://{$pull}/live/" . $live_code . ".".$ext;
			}

			$configpub=getConfigPub();
            
            if(strstr($configpub['site'],'https')){
                $url=str_replace('http:','https:',$url);
            }
			
			if($type==3){  //前台直播间使用
				$url_a=explode('/'.$live_code,$url);
				$url=array(
					'cdn'=>urlencode("rtmp://{$pull}/live/"),
					'stream'=>urlencode($live_code),
				);
			}
		}
		
		return $url;
	}


	/************腾讯云存储私有读写的签名验证start*************/

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

		setcaches($pathname,$signArr);

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
    
	/*************私有读写的签名验证end*****************/

	//为文件拼接存储方式,方便get_upload_path做签名处理

	function setCloudType($url){
		$configpri=getConfigPri();
		$cloudtype=$configpri['cloudtype'];

		//file_put_contents("zzza.txt", $url);

		$url=$url."%@%cloudtype=".$cloudtype;

		//file_put_contents("zzz.txt", $url);
		return $url;
	}
	
	//写入映票收入记录
    function setVoteRecord($data){
    	Db::name("votes_record")->insert($data);
    	
    }

    //写入钻石消费记录
    function setCoinRecord($data){
    	Db::name("user_coinrecord")->insert($data);
    }

    //更新用户的映票
    function changeUserVotes($uid,$votes,$type){ //type 为0 扣费 type 为1 增加

    	if(!$type){

    		$res=Db::name("user")->where("id={$uid} and votes >={$votes}")->setDec("votes",$votes);

    	}else{

    		$res=Db::name("user")->where("id={$uid}")->setInc("votes",$votes);
    		$res=Db::name("user")->where("id={$uid}")->setInc("votestotal",$votes);

    	}

    	if(!$res){
    		return 0;
    	}

    	return 1;
    	
    }

    //更新用户的钻石数
    function changeUserCoin($uid,$coin,$type=0){ //type 为0 扣费 type 为1 增加
    	if(!$type){
    		$res=Db::name("user")->where("id={$uid} and coin>={$coin}")->setDec("coin",$coin);
    		
    	}else{
    		$res=Db::name("user")->where("id={$uid}")->setInc("coin",$coin);
    	}

    	if(!$res){
    		return 0;
    	}

    	if(!$type){
    		Db::name("user")->where("id={$uid}")->setInc("consumption",$coin);
    	}

    	return 1;

    }

    //获取粉丝数量
    function getFans($uid){
    	$count=Db::name("user_attention")->where("touid={$uid}")->count();
    	return $count;
    }

    //获取用户的vip信息
    function getUserVipInfo($uid){
    	$result=array();
    	$now=time();
    	$vipInfo=Db::name("user")->where("id={$uid}")->field("vip_endtime")->find();

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

    

    /*删除极光用户*/
	function delIMUser($uid){

		//获取后台配置的极光推送app_key和master_secret

		$configPri=getConfigPri();
		$appKey = $configPri['jpush_key'];
		$masterSecret =  $configPri['jpush_secret'];

		if($appKey&&$masterSecret){
			//极光IM			
			require_once CMF_ROOT.'sdk/jmessage/autoload.php'; //导入极光IM类库

			$jm = new \JMessage\JMessage($appKey, $masterSecret);


			$user = new \JMessage\IM\User($jm);

			$before=userSendBefore(); //获取极光用户账号前缀

			$username=$before.$uid;

			$response=$user->delete($username);

		}
	}


	/* 房间管理员 */
	function getIsAdmin($uid,$showid){
		if($uid==$showid){		
			return 50;
		}
		$isuper=isSuper($uid);
		if($isuper){
			return 60;
		}
        $where['uid']=$uid;
        $where['liveuid']=$showid;
		$id=Db::name("user_livemanager")->where($where)->find();

		if($id)	{
			return 40;					
		}
		return 30;		
	}

	/* 判断账号是否超管 */
	function isSuper($uid){
        $where['uid']=$uid;
		$isexist=Db::name("user_super")->where($where)->find();
		if($isexist){
			return 1;
		}			
		return 0;
	}

	//判断用户是否注销
	function checkIsDestroy($uid){
		$user_status=Db::name("user")->where("id={$uid}")->value('user_status');
		if($user_status==3){
			return 1;
		}

		return 0;
	}
	
	
	/* 管理员操作日志 */
    function setAdminLog($action){
        $data=array(
            'adminid'=>session('ADMIN_ID'),
            'admin'=>session('name'),
            'action'=>$action,
            'ip'=>ip2long(get_client_ip(0,true)),
            'addtime'=>time(),
        );
        
        Db::name("admin_log")->insert($data);
        return !0;
    }
	
	
	//身份证检测
	function checkCardNo($cardno){
		$preg='/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/';
		$isok=preg_match($preg, $cardno);
		if($isok){
			return 1;
		}else{
			return 0;
		}
	}

	// 获取店铺商品订单详情
	function getShopOrderInfo($where,$files='*'){

		$info=Db::name("shop_order")
			->field($files)
			->where($where)
			->find();
		
		return $info;
		
	}

	// 获取店铺商品订单退款详情
	function getShopOrderRefundInfo($where,$files='*'){

		$info=Db::name("shop_order_refund")
			->field($files)
			->where($where)
			->find();
		
		return $info;
		
	}

	//获取店铺协商历史
	function getShopOrderRefundList($where){
		$list=Db::name("shop_order_refund_list")
			->where($where)
			->order("addtime desc")
			->select();
		
		return $list;
	}

	///////////////////////////////////////快递鸟物流信息查询start/////////////////////////////////////////////

	//快递鸟获取物流信息
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
            'LogisticCode'=>$express_number,
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

    ///////////////////////////////////////快递鸟物流信息查询end/////////////////////////////////////////////
	
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

	//修改商品订单
	function changeShopOrderStatus($uid,$orderid,$data){
		$res=Db::name('shop_order')
			->where("id={$orderid}")
			->update($data);

		return $res;
	}

	//更改商品库存
	function changeShopGoodsSpecNum($goodsid,$spec_id,$nums,$type){
		$goods_info=Db::name("shop_goods")
				->where("id={$goodsid}")
				->find();

		if(!$goods_info){
			return 0;
		}

		$spec_arr=json_decode($goods_info['specs'],true);
		$specid_arr=array_column($spec_arr, 'spec_id');

		if(!in_array($spec_id, $specid_arr)){
			return 0;
		}

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

		Db::name("shop_goods")->where("id={$goodsid}")->update(array('specs'=>$spec_str));

		return 1;

	}

	//写入订单操作记录
	function addShopGoodsOrderMessage($data){
		$res=Db::name("shop_order_message")->insert($data);
		return $res;
	}

	//修改用户的余额 type:0 扣除余额 1 增加余额
	function setUserBalance($uid,$type,$balance){

		$res=0;

		if($type==0){ //扣除用户余额，增加用户余额消费总额

			Db::name("user")
				->where("id={$uid} and balance>={$balance}")
				->setDec('balance',$balance);

		 	$res=Db::name("user")
				->where("id={$uid}")
				->setInc('balance_consumption',$balance);


		}else if($type==1){ //增加用户余额


			Db::name("user")
				->where("id={$uid}")
				->setInc('balance',$balance);

			$res=Db::name("user")
				->where("id={$uid}")
				->setInc('balance_total',$balance);
		}

		return $res;
	}

	//添加余额操作记录
	function addBalanceRecord($data){
		$res=Db::name("user_balance_record")->insert($data);
		return $res;
	}

	//更新商品的销量 type=0 减 type=1 增
	function changeShopGoodsSaleNums($goodsid,$type,$nums){
		if($type==0){

			$res=Db::name("shop_goods")
			->where("id={$goodsid} and sale_nums>= {$nums}")
			->setDec('sale_nums',$nums);

		}else{
			$res=Db::name("shop_goods")
			->where("id={$goodsid}")
			->setInc('sale_nums',$nums);
		}

		return $res;
		
	}

	//更新店铺的销量 type=0 减 type=1 增
	function changeShopSaleNums($uid,$type,$nums){
		if($type==0){

			$res=Db::name("shop_apply")
			->where("uid={$uid} and sale_nums>= {$nums}")
			->setDec('sale_nums',$nums);

		}else{
			$res=Db::name("shop_apply")
			->where("uid={$uid}")
			->setInc('sale_nums',$nums);
		}

		return $res;
	}

	//更改退款详情信息
    function changeGoodsOrderRefund($where,$data){
    	$res=Db::name("shop_order_refund")
    			->where($where)
    			->update($data);

    	return $res;
    }

    //写入退款协商记录
	function setGoodsOrderRefundList($data){
		$res=Db::name("shop_order_refund_list")->insert($data);
    	return $res;
	}

	/* 店铺订单支付时 处理店铺订单支付 */
    function handelShopOrder($where,$data=[]){
        $orderinfo=Db::name("shop_order")->where($where)->find();

        if(!$orderinfo){
            return 0;
        }

        if($orderinfo['status']==-1){ //已关闭
            return -1;
        }
        
        if($orderinfo['status']!=0){
            return 1;
        }

        $now=time();
        
        /* 更新 订单状态 */
        
        $data['status']=1;
        $data['paytime']= $now;
        
        Db::name("shop_order")->where("id='{$orderinfo['id']}'")->update($data);

        $uid=$orderinfo['uid'];

        $balance_consumption=Db::name("user")->where("id={$uid}")->value("balance_consumption");

        //增加用户的商城累计消费
        Db::name("user")->where("id={$uid}")->setField('balance_consumption',$balance_consumption+$orderinfo['total']);

        //增加商品销量
        changeShopGoodsSaleNums($orderinfo['goodsid'],1,$orderinfo['nums']);

        //增加店铺销量
        changeShopSaleNums($orderinfo['shop_uid'],1,$orderinfo['nums']);
        
        //写入订单信息
        $title="你的商品“".$orderinfo['goods_name']."”收到一笔新订单,订单编号:".$orderinfo['orderno'];

        $data1=array(
            'uid'=>$orderinfo['shop_uid'],
            'orderid'=>$orderinfo['id'],
            'title'=>$title,
            'addtime'=>$now,
            'type'=>'1'

        );

        addShopGoodsOrderMessage($data1);

        jMessageIM($title,$orderinfo['shop_uid'],'goodsorder_admin');

        return 2;

    }

    //极光推送
	function jPush($uid,$title){
		/* 极光推送 */
        $configpri=getConfigPri();
        $app_key = $configpri['jpush_key'];
        $master_secret = $configpri['jpush_secret'];
        if(!$app_key || !$master_secret){
            return 0; 
        }

        require_once CMF_ROOT.'sdk/JPush/autoload.php';
        // 初始化
        $client = new \JPush\Client($app_key, $master_secret,null);
        $anthorinfo=array();
                
        $map=array(
        	'uid'=>$uid
        );

        //获取用户的极光推送id
        $pushid=DB::name("user_pushid")
					->where($map)
					->value("pushid");

		$apns_production=false;
        if($configpri['jpush_sandbox']){
            $apns_production=true;
        }
        try{
	        $result = $client->push()
		        ->setPlatform('all')
		        ->addRegistrationId($pushid)
		        ->setNotificationAlert($title)
		        ->iosNotification($title, array(
		            'sound' => 'sound.caf',
		            'category' => 'jiguang',
		            'extras' => array(
		                'type' => '2',
		                'userinfo' => $anthorinfo
		            ),
		        ))
		        ->androidNotification('', array(
		            'extras' => array(
		                'type' => '2',
		                'title' => $title,
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
            file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($pushid)."\r\n",FILE_APPEND);
            file_put_contents(CMF_ROOT.'data/jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
        }

	}

	//写入系统消息
	function addSysytemInfo($uid,$title,$msg){
		

		//极光IM
		$aid=$_SESSION['ADMIN_ID'];
		$user=Db::name("user")->where("id='{$aid}'")->find();

		//向系统通知表中写入数据
		$sysInfo=array(
			'title'=>$title,
			'addtime'=>time(),
			'admin'=>$user['user_login'],
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'uid'=>$uid,
			'content'=>$msg,

		);
		$id=Db::name("system_push")->insertGetId($sysInfo);
		return $id;

	}

	//修改代售平台商品记录的信息
	function setOnsalePlatformInfo($where,$data){
		Db::name("seller_platform_goods")
		->where($where)
		->update($data);
	}

	/* ip限定 */
	function ip_limit(){
		$configpri=getConfigPri();
		if($configpri['iplimit_switch']==0){
			return 0;
		}
		$date = date("Ymd");
		$ip= ip2long($_SERVER["REMOTE_ADDR"]);
		
		$isexist=Db::name("getcode_limit_ip")
				->field("ip,date,times")
				->where("ip={$ip}") 
				->find();

		if(!$isexist){
			$data=array(
				"ip" => $ip,
				"date" => $date,
				"times" => 1,
			);
			$isexist=Db::name("getcode_limit_ip")->insert($data);
			return 0;
		}elseif($date == $isexist['date'] && $isexist['times'] >= $configpri['iplimit_times'] ){
			return 1;
		}else{
			if($date == $isexist['date']){
				$isexist=Db::name("getcode_limit_ip")
						->where("ip={$ip}")
						->setInc("times",1);

				return 0;
			}else{
				$isexist=Db::name("getcode_limit_ip")
						->where("ip={$ip}") 
						->update(array('date'=> $date ,'times'=>1));
				return 0;
			}
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

	/* 发送验证码 -- 阿里云 */
	function sendCode($mobile,$code){
        
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$config = getConfigPri();
        
        if(!$config['sendcode_switch']){
            $rs['code']=667;
			$rs['msg']='123456';
            return $rs;
        }
        
       if($config['code_switch']=='1'){//阿里云
			$res=sendCodeByAli($mobile,$code);
		}else{
			$res=sendCodeByRonglian($mobile,$code);//容联云
		}


		return $rs;
	}

	//阿里云短信
	function sendCodeByAli($mobile,$code){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $configpri = getConfigPri();
		
		require_once CMF_ROOT.'sdk/aliyunsms/AliSmsApi.php';
        
		$config  = array(
			'accessKeyId' =>$configpri['aly_keydi'], 
			'accessKeySecret' =>$configpri['aly_secret'], 
			'PhoneNumbers' => $mobile, 
			'SignName' => $configpri['aly_signName'], 
			'TemplateCode' => $configpri['aly_templateCode'], 
			'TemplateParam' => array("code"=>$code) 
		);

		 
		$go = new \AliSmsApi($config);
		$result = $go->send_sms();
		file_put_contents(CMF_ROOT.'log/sendCode_aly_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 result:'.json_encode($result)."\r\n",FILE_APPEND);
		
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


	function sendCodeByRonglian($mobile,$code){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
		$config = getConfigPri();
        require_once CMF_ROOT.'sdk/ronglianyun/CCPRestSDK.php';
        
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
         if($result->statusCode!=0) {
            $rs['code']=1002;
			$rs['msg']="获取失败";
            return $rs;
         }
        

		return $rs;
	}

	/* 检测用户是否存在 */
    function checkUser($where){
        if(!$where){
            return 0;
        }

        $isexist=Db::name('user')->field('id')->where($where)->find();
        
        if($isexist){
            return 1;
        }
        
        return 0;
    }


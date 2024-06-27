<?php
session_start();

//Braintree支付专用--start
include API_ROOT.'/../vendor/Braintree/vendor/autoload.php';
use Braintree\ClientToken;
//Braintree支付专用--start

class Api_User extends PhalApi_Api {

	public function getRules() {
		return array(
			'iftoken' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'getBaseInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),
			
			'updateAvatar' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'file' => array('name' => 'file','type' => 'file', 'min' => 0, 'max' => 1024 * 1024 * 30, 'range' => array('image/jpg', 'image/jpeg', 'image/png'), 'ext' => array('jpg', 'jpeg', 'png')),
			),
			
			'updateFields' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'fields' => array('name' => 'fields', 'type' => 'string', 'require' => true, 'desc' => '修改信息，json字符串'),
			),
			
			'setAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'isAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'isBlacked' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),

			'checkBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'content' => array('name' => 'content', 'type' => 'string', 'desc' => '私信文字内容'),
			),

			'setBlack' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getFollowsList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int',  'require' => true, 'desc' => '对方ID'),
				'key' => array('name' => 'key', 'type' => 'string', 'desc' => '搜索关键词'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getFansList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getBlackList' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),
			
			'getUserHome' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getPmUserInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),
			
			'getMultiInfo' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'uids' => array('name' => 'uids', 'type' => 'string', 'min' => 1,'require' => true, 'desc' => '用户ID，多个以逗号分割'),
			),

			'getLikeVideos'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

			'getBalance' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'desc' => '设备类型，0android，1IOS'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

			'getVip' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'type' => array('name' => 'type', 'type' => 'string', 'desc' => '设备类型，0android，1IOS'),
                'version_ios' => array('name' => 'version_ios', 'type' => 'string', 'desc' => 'IOS版本号'),
			),

			'checkLiveVipStatus'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			
			'seeDailyTasks'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'default' => '0', 'desc' => '主播ID'),
				'islive' => array('name' => 'islive', 'type' => 'int', 'default' => '0',  'desc' => '是否在直播间 0不在 1在'),
			),
			'receiveTaskReward'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'taskid' => array('name' => 'taskid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '任务ID'),
			),
			'setBeautyParams'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'params' => array('name' => 'params', 'type' => 'string', 'require' => true, 'desc' => '用户设置的美颜参数'),
			),

			'getBeautyParams'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
			),
			'setShopCash' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'accountid' => array('name' => 'accountid', 'type' => 'int', 'require' => true, 'desc' => '账号ID'),
				'money' => array('name' => 'money', 'type' => 'float', 'require' => true, 'desc' => '提现的金额'),
				'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名字符串'),
			),

			'isFirstAttent' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'require' => true, 'desc' => '对方ID'),
			),

			'getBraintreeToken' => array( 
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'desc' => '用户token'),
			),

			'BraintreeCallback'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'ordertype' => array('name' => 'ordertype', 'type' => 'string', 'require' => true, 'desc' => 'order_type 订单类型；coin_charge： 钻石充值；order_pay 商品订单支付；vip_pay：购买vip'),
				'orderno' => array('name' => 'orderno', 'type' => 'string',  'require' => true, 'desc' => '系统的订单编号'),
				'nonce' => array('name' => 'nonce', 'type' => 'string', 'require' => true, 'desc' => 'braintree返回的三方订单编号'),
				'money' => array('name' => 'money', 'type' => 'string', 'require' => true, 'desc' => '充值金额'),
				'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名字符串'),

			),

			
		);
	}
	/**
	 * 判断token
	 * @desc 用于判断token
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function iftoken() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		//获取用户信息存入app本地
		$domain=new Domain_User();
		$info=$domain->getBaseInfo($uid);
		$rs['info'][0]=$info;
		return $rs;
	}
	/**
	 * 获取用户信息
	 * @desc 用于获取单个用户基本信息
	 * @return int code 操作码，0表示成功， 1表示用户不存在
	 * @return array info 
	 * @return array info[0] 用户信息
	 * @return int info[0].id 用户ID
	 * @return string info[0].level 等级
	 * @return string info[0].lives 直播数量
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].agent_switch 分销开关
	 * @return string info[0].family_switch 家族开关
	 * @return string msg 提示信息
	 */
	public function getBaseInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$version_ios=checkNull($this->version_ios);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain = new Domain_User();
		$info = $domain->getBaseInfo($uid);

		if(!$info){
            $rs['code'] = 700;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
        }
		
		$configpub=getConfigPub();
		
		$ios_shelves=$configpub['ios_shelves'];  //ios上架版本号
		

		/* 个人中心菜单 */
		$version_ios=$version_ios;
		$list=array();
		$shelves=1;
		if($version_ios==$ios_shelves){
			$shelves=0;
		}
        
        /* 店铺信息 */
        $isshop='0';
        $shop_name='';
        $shop_thumb='';
        
        $domain2 = new Domain_Shop();
		$shop = $domain2->getShop($uid);
        if($shop){
            $isshop='1';
            $shop_name=$shop['name'];
            $shop_thumb=$shop['avatar'];
        }
        
        $info['isshop']=$isshop;
        $info['shop_name']=$shop_name;
        $info['shop_thumb']=$shop_thumb;
		
		$vipinfo=getUserVipInfo($uid);
        $info['vipinfo']=$vipinfo;        

		$rs['info'][0] = $info;

		return $rs;
	}

	/**
	 * 头像上传 (七牛)[根据后台的配置信息来决定是走七牛云存储还是走腾讯云存储]
	 * @desc 用于用户修改头像
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].avatar 用户主头像
	 * @return string list[0].avatar_thumb 用户头像缩略图
	 * @return string msg 提示信息
	 */
	public function updateAvatar() {
		$rs = array('code' => 0 , 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if (!isset($_FILES['file'])) {
			$rs['code'] = 1001;
			$rs['msg'] = T('miss upload file');
			return $rs;
		}

		if ($_FILES["file"]["error"] > 0) {
			$rs['code'] = 1002;
			$rs['msg'] = T('failed to upload file with error: {error}', array('error' => $_FILES['file']['error']));
			DI()->logger->debug('failed to upload file with error: ' . $_FILES['file']['error']);
			return $rs;
		}

		//$uptype=DI()->config->get('app.uptype');
		
		//获取后台配置的云存储方式
		$configpri=getConfigPri();
		$cloudtype=$configpri['cloudtype'];


		if($cloudtype==1){
			//七牛
			$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name'],$configpri['qiniu_accesskey'],$configpri['qiniu_secretkey'],$configpri['qiniu_bucket'],$configpri['qiniu_domain_url']);


			if (!empty($url)) {
				$avatar=  $url.'?imageView2/2/w/600/h/600'; //600 X 600
				$avatar_thumb=  $url.'?imageView2/2/w/200/h/200'; // 200 X 200

				$data=array(
					"avatar"=>setCloudType($avatar),
					"avatar_thumb"=>setCloudType($avatar_thumb),
				);

				
				/* 统一服务器 格式 */
				/* $space_host= DI()->config->get('app.Qiniu.space_host');
				$avatar2=str_replace($space_host.'/', "", $avatar);
				$avatar_thumb2=str_replace($space_host.'/', "", $avatar_thumb);
				$data2=array(
					"avatar"=>$avatar2,
					"avatar_thumb"=>$avatar_thumb2,
				); */
			}


		}else if($cloudtype==0){
			//本地上传
			//设置上传路径 设置方法参考3.2
			DI()->ucloud->set('save_path','avatar/'.date("Ymd"));

			//新增修改文件名设置上传的文件名称
		   // DI()->ucloud->set('file_name', $this->uid);

			//上传表单名
			$res = DI()->ucloud->upfile($_FILES['file']);
			
			$files='../upload/'.$res['file'];
			$newfiles=str_replace(".png","_thumb.png",$files);
			$newfiles=str_replace(".jpg","_thumb.jpg",$newfiles);
			$newfiles=str_replace(".gif","_thumb.gif",$newfiles); 
			$PhalApi_Image = new Image_Lite();
			//打开图片
			$PhalApi_Image->open($files);
			/**
			 * 可以支持其他类型的缩略图生成，设置包括下列常量或者对应的数字：
			 * IMAGE_THUMB_SCALING      //常量，标识缩略图等比例缩放类型
			 * IMAGE_THUMB_FILLED       //常量，标识缩略图缩放后填充类型
			 * IMAGE_THUMB_CENTER       //常量，标识缩略图居中裁剪类型
			 * IMAGE_THUMB_NORTHWEST    //常量，标识缩略图左上角裁剪类型
			 * IMAGE_THUMB_SOUTHEAST    //常量，标识缩略图右下角裁剪类型
			 * IMAGE_THUMB_FIXED        //常量，标识缩略图固定尺寸缩放类型
			 */

			// 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.jpg
			
			$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
			$PhalApi_Image->save($files);

			$PhalApi_Image->thumb(200, 200, IMAGE_THUMB_SCALING);
			$PhalApi_Image->save($newfiles);			
			
			$avatar=  $res['url']; //600 X 600
			
			$avatar_thumb=str_replace(".png","_thumb.png",$avatar);
			$avatar_thumb=str_replace(".jpg","_thumb.jpg",$avatar_thumb);
			$avatar_thumb=str_replace(".gif","_thumb.gif",$avatar_thumb); 

			$data=array(
				"avatar"=>$avatar,
				"avatar_thumb"=>$avatar_thumb,
			);
			
		}else if($cloudtype==2){

			//腾讯云存储
			$files["file"]=$_FILES["file"];
            $type='img';
            
            $ret=qcloud($files,$type);
            
            if($ret['code']!=0){
                $rs['code']=1002;
                $rs['msg']=$ret['message'];
                return $rs;
            }
            
            $url = $ret['data']['url'];
            
            if (!empty($url)) {
                $avatar=  $url; //600 X 600
                $avatar_thumb=  $url; // 200 X 200
                $data=array(
                    "avatar"=>setCloudType($avatar),
                    "avatar_thumb"=>setCloudType($avatar_thumb),
                );
            }
		}else if($cloudtype==3){ //亚马逊存储
			
			$files["file"]=$_FILES["file"];
            $type='img';
            
            $ret=awscloud($files,$type);

            if($ret['code']!=0){
                $rs['code']=1002;
                $rs['msg']=$ret['msg'];
                return $rs;
            }

            $url = $ret['data']['url'];
            $avatar=  $url; 
            $avatar_thumb=  $url; 

            $data=array(
                "avatar"=>setCloudType($avatar),
                "avatar_thumb"=>setCloudType($avatar_thumb),
            );
		}
		
		@unlink($_FILES['file']['tmp_name']);

		/* 清除缓存 */
		delCache("userinfo_".$uid);
		
		$domain = new Domain_User();
		$info = $domain->userUpdate($uid,$data);

		if($cloudtype==3){
			$data=array(
                "avatar"=>get_upload_path($data['avatar']),
                "avatar_thumb"=>get_upload_path($data['avatar_thumb']),
            );
		}	

		$rs['info'][0] = $data;

		return $rs;

	}
	
	/**
	 * 修改用户信息
	 * @desc 用于修改用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string list[0].msg 修改成功提示信息 
	 * @return string msg 提示信息
	 */
	public function updateFields() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$fields=urldecode($this->fields);

		$fields=json_decode($fields,true);

		
		$domain = new Domain_User();
		foreach($fields as $k=>$v){
			$fields[$k]=checkNull($v);
		}
		
		if(array_key_exists('user_nicename', $fields)){

			if($fields['user_nicename']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '昵称不能为空';
				return $rs;
			}

			//判断昵称中是否有敏感词
			$keywordsIsExist=checkSensitiveWords($fields['user_nicename']);
			if($keywordsIsExist){
				$rs['code'] = 1002;
                $rs['msg'] = '输入非法,请重新输入';
                return $rs;
			}

			$isexist = $domain->checkName($uid,$fields['user_nicename']);
			if(!$isexist){
				$rs['code'] = 1002;
				$rs['msg'] = '昵称重复,请修改';
				return $rs;
			}

			//判断昵称里是否包含已注销
			if(strstr($fields['user_nicename'], '已注销')!==false){ //昵称包含已注销三个字
				$rs['code'] = 10011;
				$rs['msg'] = '输入非法，请重新输入';
				return $rs;
			}

			$fields['user_nicename']=filterField($fields['user_nicename']);
		}


		//个性签名
		if(array_key_exists('signature', $fields)){


			//判断个性签名中是否有敏感词
			$keywordsIsExist=checkSensitiveWords($fields['signature']);
			if($keywordsIsExist){
				$rs['code'] = 1002;
                $rs['msg'] = '个性签名输入非法,请重新输入';
                return $rs;
			}

			$fields['signature']=filterField($fields['signature']);
		}


		//手机号
		if(array_key_exists('mobile', $fields)){
			if($fields['mobile']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '手机号码不能为空';
				return $rs;
			}
			$isexist = $domain->checkMobile($uid,$fields['mobile']);
			if(!$isexist){
				$rs['code'] = 1002;
				$rs['msg'] = '手机号码重复,请修改';
				return $rs;
			}
			$fields['mobile']=filterField($fields['mobile']);
		}

		//根据生日计算年龄
		if(array_key_exists('birthday', $fields)){
			if($fields['birthday']==''){
				$rs['code'] = 1002;
				$rs['msg'] = '请选择生日';
				return $rs;
			}

			$now=time();
			$time1=strtotime($fields['birthday']);

			$nowYear=date("Y",$now);
			$birthdayYear=date("Y",$time1);

			$nowMonth=date("m",$now);
			$month=date("m",$time1);

			if($nowMonth>=$month){
				$cha=0;
			}else{
				$cha=1;
			}

			$age=$nowYear-$birthdayYear-$cha;

			$fields['age']=$age;


		}
		
		
		$info = $domain->userUpdate($uid,$fields);
	 
		if($info===false){
			$rs['code'] = 1001;
			$rs['msg'] = $Think.\lang('UPDATE_FAILED');
			return $rs;
		}
		/* 清除缓存 */
		delCache("userinfo_".$uid);
		$rs['info'][0]['msg']=$Think.\lang('UPDATE_SUCCESSFULLY');
		return $rs;
	}
	
		
	/**
	 * 判断是否关注
	 * @desc 用于判断是否关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function isAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$info = isAttention($uid,$touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 关注/取消关注
	 * @desc 用于关注/取消关注
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent 关注信息，0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function setAttent() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$touid=$this->touid;

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		if($uid==$touid){
			$rs['code']=1001;
			$rs['msg']='不能关注自己';
			return $rs;	
		}

		$is_destroy=checkIsDestroyByUid($touid);
		if($is_destroy){
			$rs['code']=1001;
			$rs['msg']='该用户已注销';
			return $rs;	
		}

		$domain = new Domain_User();
		$info = $domain->setAttent($uid,$touid);
	 
		$rs['info'][0]['isattent']=(string)$info;
		return $rs;
	}			
	
	/**
	 * 判断是否拉黑
	 * @desc 用于判断是否拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isattent  拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function isBlacked() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$uid=checkNull($this->uid);
			$touid=checkNull($this->touid);

			$isBlackUser=isBlackUser($uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
			
			$info = isBlack($uid,$touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}	

	/**
	 * 私信聊天时判断私聊双方的拉黑状态
	 * @desc 用于私信聊天时判断私聊双方的拉黑状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].u2t  是否拉黑对方,0表示未拉黑，1表示已拉黑
	 * @return string info[0].t2u  是否被对方拉黑,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function checkBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());
            
            $uid=checkNull($this->uid);
            $touid=checkNull($this->touid);
            $content=checkNull($this->content);

			if(!$uid){
				$uid=0;
			}

			if(!$touid){
				$touid=0;
			}

			//判断对方是否被注销
			if($touid){
				$is_destroy=checkIsDestroyByUid($touid);
				if($is_destroy){
					$rs['code']=1001;
					$rs['msg']='对方已注销,不能发送私信';
					return $rs;
				}
			}
			
			$u2t = isBlack($uid,$touid);
			$t2u = isBlack($touid,$uid);
			$isattent=isAttention($touid,$uid);

			if($content){ //过滤敏感词
				$content=ReplaceSensitiveWords($content);
			}

		 
			$rs['info'][0]['u2t']=(string)$u2t;
			$rs['info'][0]['t2u']=(string)$t2u;
			$rs['info'][0]['isattent']=(string)$isattent;
			$rs['info'][0]['content']=$content;
			return $rs;
	}			
		
	/**
	 * 拉黑/取消拉黑
	 * @desc 用于拉黑/取消拉黑
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isblack 拉黑信息,0表示未拉黑，1表示已拉黑
	 * @return string msg 提示信息
	 */
	public function setBlack() {
			$rs = array('code' => 0, 'msg' => '', 'info' => array());

			$uid=checkNull($this->uid);
			$token=checkNull($this->token);
			$touid=checkNull($this->touid);

			$isBlackUser=isBlackUser($uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}

			$checkToken=checkToken($uid,$token);
			if($checkToken==700){
				$rs['code'] = $checkToken;
				$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
				return $rs;
			}else if($checkToken==10020){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}

			$is_destroy=checkIsDestroyByUid($touid);
			if($is_destroy){
				$rs['code'] = 1001;
				$rs['msg'] = '该用户已注销';
				return $rs;
			}
			
			$domain = new Domain_User();
			$info = $domain->setBlack($uid,$touid);
		 
			$rs['info'][0]['isblack']=(string)$info;
			return $rs;
	}		
	
	
	/**
	 * 获取用户的关注列表
	 * @desc 用于获取用户的关注列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFollowsList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);
		$key=checkNull($this->key);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		
		$domain = new Domain_User();
		$info = $domain->getFollowsList($uid,$touid,$p,$key);
	 
		$rs['info']=$info;
		return $rs;
	}		
	
	/**
	 * 获取用户的粉丝列表
	 * @desc 用于获取用户的粉丝列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].isattent 是否关注,0表示未关注，1表示已关注
	 * @return string msg 提示信息
	 */
	public function getFansList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);


		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getFansList($uid,$touid,$p);
	 
		$rs['info']=$info;
		return $rs;
	}	

	/**
	 * 获取用户的黑名单列表
	 * @desc 用于获取用户的黑名单列表
	 * @return int code 操作码，0表示成功
	 * @return array info 用户基本信息
	 * @return string msg 提示信息
	 */
	public function getBlackList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getBlackList($uid,$touid,$p);
	 
		$rs['info']=$info;
		return $rs;
	}		
	



	/**
	 * 个人主页 
	 * @desc 用于获取个人主页数据
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].id 用户id
	 * @return string info[0].avatar 用户头像
	 * @return string info[0].avatar_thumb 用户小头像
	 * @return string info[0].sex 性别
	 * @return string info[0].signature 用户签名
	 * @return array info[0].province 省份
	 * @return array info[0].city 城市
	 * @return string info[0].birthday 生日
	 * @return array info[0].age 年龄
	 * @return array info[0].praise 视频点赞总数
	 * @return array info[0].fans 粉丝数
	 * @return array info[0].follows 关注数
	 * @return array info[0].workVideos 作品数
	 * @return array info[0].likeVideos 喜欢视频数
	 * @return array info[0].isattention 是否关注
	 * @return array info[0].isshop 是否开店
	 * @return array info[0].shopname 店铺名称
	 * @return array info[0].shop_thumb 店铺封面
	 * @return array info[0].vipinfo 用户vip信息
	 * @return array info[0].vipinfo['isvip'] 用户是否开通vip
	 * @return array info[0].vipinfo['vip_endtime'] vip到期时间
	 * @return string msg 提示信息
	 */
	public function getUserHome() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);

		if($uid>0){ //非游客
			$isBlackUser=isBlackUser($uid);
			 if($isBlackUser=='0'){
				$rs['code'] = 700;
				$rs['msg'] = '该账号已被禁用';
				return $rs;
			}
		}
		
		
		$domain = new Domain_User();
		$info=$domain->getUserHome($uid,$touid);
        
        /* 店铺信息 */
        $isshop='0';
        $shop_name='';
        $shop_thumb='';

        $is_destroy=checkIsDestroyByUid($touid);

        if(!$is_destroy){ //未注销
        	$domain2 = new Domain_Shop();
			$shop = $domain2->getShop($touid);
	        if($shop){
	            $isshop='1';
	            $shop_name=$shop['name'];
	            $shop_thumb=$shop['thumb'];
	        }
        }
        
        
        
        $info['isshop']=$isshop;
        $info['shop_name']=$shop_name;
        $info['shop_thumb']=$shop_thumb;

        
        if($is_destroy){
        	$vipinfo=array(
        		'isvip'=>0,
        		'vip_endtime'=>''
        	);
        }else{
        	$vipinfo=getUserVipInfo($touid);
        }

        $info['vipinfo']=$vipinfo;
		
		$rs['info'][0]=$info;
		return $rs;
	}			
	
	/**
     * 获取其他私信用户基本信息
     * @desc 用于获取其他用户基本信息
     * @return int code 操作码，0表示成功，1表示用户不存在
     * @return array info   
     * @return string info[0].id 用户ID
     * @return string info[0].isattention 我是否关注对方，0未关注，1已关注
     * @return string info[0].isattention2 对方是否关注我，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function getPmUserInfo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $uid=checkNull($this->uid);
        $touid=checkNull($this->touid);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

        $info = getUserInfo($touid);
		 if (empty($info)) {
            $rs['code'] = 1001;
            $rs['msg'] = $Think.\lang('USER_NOT_EXIST');
            return $rs;
        }
        $info['isattention2']= (string)isAttention($touid,$uid);
        $info['isattention']= (string)isAttention($uid,$touid);
       
        $rs['info'][0] = $info;

        return $rs;
    }		

	/**
	 * 获取多用户信息
	 * @desc 用于获取多用户信息
	 * @return int code 操作码，0表示成功
	 * @return array info 排行榜列表
	 * @return string info[].utot 是否关注，0未关注，1已关注
	 * @return string info[].ttou 对方是否关注我，0未关注，1已关注
	 * @return string msg 提示信息
	 */
	public function getMultiInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$uids=checkNull($this->uids);

		$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}


		//获取用户的系统消息和官方消息【iOS专用】
		$domain=new Domain_Message();
		$message=$domain->getLastTime($uid);

		/*var_dump($message);
		die;*/

			
		$uids=explode(",",$uids);
		$configpub=getConfigPub();


		foreach ($uids as $k=>$userId) {
			if($userId){
				$userinfo= getUserInfo($userId);
				if($userinfo){
					/*$userinfo['utot']= isAttention($uid,$userId);
					
					$userinfo['ttou']= isAttention($userId,$uid);*/
					$userinfo['isattent']= isAttention($uid,$userId);

					if($userId=='dsp_admin_1'){ //官方消息【iOS专用】
						if(!empty($message['officeInfo'])){
							$userinfo['last_msg']=$message['officeInfo']['title'];
							$userinfo['last_time']=date('m-d H:i',$message['officeInfo']['addtime']);
						}else{
							$userinfo['last_msg']='欢迎入驻'.$configpub['app_name'];
							$userinfo['last_time']='';
						}
						
					}

					if($userId=='dsp_admin_2'){ //系统通知【iOS专用】
						if(!empty($message['sysInfo'])){
							$userinfo['last_msg']=$message['sysInfo']['title'];
							$userinfo['last_time']=date('m-d H:i',$message['sysInfo']['addtime']);
						}else{
							$userinfo['last_msg']='欢迎入驻'.$configpub['app_name'];
							$userinfo['last_time']='';
						}
						
					}

					if($userId=='goodsorder_admin'){ //订单消息【iOS专用】
						if(!empty($message['goodsorderInfo'])){
							$userinfo['last_msg']=$message['goodsorderInfo']['title'];
							$userinfo['last_time']=date('m-d H:i',$message['goodsorderInfo']['addtime']);
						}else{
							$userinfo['last_msg']='欢迎入驻'.$configpub['app_name'];
							$userinfo['last_time']='';
						}
						
					}
											
					$rs['info'][]=$userinfo;
																
				}					
			}
		}

		return $rs;
	}	
	

	/**
	 * 获取用户喜欢的视频
	 * @desc 用户获取用户喜欢的视频
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info
	 * @return string info[0].uid 视频发布者id
	 * @return string info[0].title 视频标题
	 * @return string info[0].thumb 视频封面图
	 * @return string info[0].thumb_s 视频封面小图
	 * @return string info[0].href 视频地址
	 * @return string info[0].likes 视频被喜欢总数
	 * @return string info[0].views 视频被浏览数
	 * @return string info[0].comments 视频评论数
	 * @return string info[0].shares 视频被分享数
	 * @return string info[0].addtime 视频发布时间
	 * @return string info[0].city 视频发布城市
	 * @return string info[0].datetime 视频发布时间（格式化为汉字形式）
	 * @return string info[0].userinfo 视频发布者信息
	 */
	public function getLikeVideos(){

		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$uid=checkNull($this->uid);
		$touid=checkNull($this->touid);
		$p=checkNull($this->p);

		$domain = new Domain_User();
		$res=$domain->getLikeVideos($uid,$touid,$p);
		if($res==1001){
			$rs['code']=0;
			$rs['msg']="暂无视频列表";
			return $rs;
		}

		$rs['info']=$res;

		return $rs;
	}

	/**
	 * 获取用户余额,充值规则 支付方式信息
	 * @desc 用于获取用户余额,充值规则 支付方式信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 用户余额
	 * @return array info[0].rules 充值规则
	 * @return string info[0].rules[].id 充值规则
	 * @return string info[0].rules[].coin 钻石
	 * @return string info[0].rules[].coin_ios ios支付钻石
	 * @return string info[0].rules[].money 价格
	 * @return string info[0].rules[].money_ios 苹果充值价格
	 * @return string info[0].rules[].product_id 苹果项目ID
	 * @return string info[0].rules[].give 赠送钻石，为0时不显示赠送
	 * @return string info[0].aliapp_partner 支付宝合作者身份ID
	 * @return string info[0].aliapp_seller_id 支付宝帐号	
	 * @return string info[0].aliapp_key_android 支付宝安卓密钥
	 * @return string info[0].aliapp_key_ios 支付宝ios密钥
	 * @return string info[0].wx_appid 开放平台账号AppID
	 * @return string info[0].wx_appsecret 微信应用appsecret
	 * @return string info[0].wx_mchid 微信商户号mchid
	 * @return string info[0].wx_key 微信密钥key
	 * @return array info[0].paylist 支付方式列表
	 * @return int info[0].paylist[].id 支付方式id
	 * @return string info[0].paylist[].name 支付方式名称
	 * @return string info[0].paylist[].thumb 支付方式图标
	 * @return string info[0].paylist[].href 支付方式链接
	 * @return string msg 提示信息
	 */
	public function getBalance() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $type=checkNull($this->type);
        $version_ios=checkNull($this->version_ios);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain = new Domain_User();
		$info = $domain->getBalance($uid);
		
		$key='getChargeRules';
		$rules=getcaches($key);



		if(!$rules){
			$rules= $domain->getChargeRules();

			setcaches($key,$rules);
		}
		$info['rules'] =$rules;
		
		$configpub=getConfigPub();
		$configpri=getConfigPri();
		
		$aliapp_switch=$configpri['aliapp_switch'];
		
		//$info['aliapp_switch']=$aliapp_switch;
		$info['aliapp_partner']=$aliapp_switch==1?$configpri['aliapp_partner']:'';
		$info['aliapp_seller_id']=$aliapp_switch==1?$configpri['aliapp_seller_id']:'';
		$info['aliapp_key']=$aliapp_switch==1?$configpri['aliapp_key_android']:'';
		$info['aliapp_key_ios']=$aliapp_switch==1?$configpri['aliapp_key_ios']:'';

        $wx_switch=$configpri['wx_switch'];
		//$info['wx_switch']=$wx_switch;
		$info['wx_appid']=$wx_switch==1?$configpri['wx_appid']:'';
		$info['wx_appsecret']=$wx_switch==1?$configpri['wx_appsecret']:'';
		$info['wx_mchid']=$wx_switch==1?$configpri['wx_mchid']:'';
		$info['wx_key']=$wx_switch==1?$configpri['wx_key']:'';
		
        $aliscan_switch=$configpri['aliscan_switch'];

        $braintree_paypal_switch=$configpri['braintree_paypal_switch'];
        /* 支付列表 */
        $shelves=1;
        $ios_shelves=$configpub['ios_shelves']; //后台填写的上架版本号
        if($version_ios && $version_ios==$ios_shelves){
			$shelves=0;
		}
        
        $paylist=[];
        
        if($aliapp_switch && $shelves){
            $paylist[]=[
                'id'=>'ali',
                'name'=>'支付宝支付',
                'thumb'=>get_upload_path("/static/app/pay/ali.png"),
                'href'=>'',
            ];
        }
        
        if($wx_switch && $shelves){
            $paylist[]=[
                'id'=>'wx',
                'name'=>'微信支付',
                'thumb'=>get_upload_path("/static/app/pay/wx.png"),
                'href'=>'',
            ];
        }
        
        $ios_switch=$configpri['ios_switch'];
        
        if( ($ios_switch || $shelves==0) && $type==1){
            $paylist[]=[
                'id'=>'apple',
                'name'=>'苹果支付',
                'thumb'=>get_upload_path("/static/app/pay/apple.png"),
                'href'=>'',
            ];
        }

        if($braintree_paypal_switch && $shelves){
            $paylist[]=[
                'id'=>'paypal',
                'name'=>'Paypal支付',
                'thumb'=>get_upload_path("/static/app/pay/paypal.png"),
                'href'=>'',
            ];
        }
        
        
        $info['paylist'] =$paylist;
        
        
     
		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 获取用户的vip信息、vip权益列表、vip充值规则
	 * @desc 用于获取用户的vip信息、vip权益列表、vip充值规则
	 * @return int code 状态码 0表示成功
	 * @return string msg 状态码 0表示成功
	 * @return array info 返回信息
	 * @return array info[0].userinfo 用户信息
	 * @return array info[0].vip_rules vip充值规则
	 * @return int info[0].vip_rules[].id vip充值规则id
	 * @return string info[0].vip_rules[].name vip充值规则名称
	 * @return floot info[0].vip_rules[].money vip充值规则安卓金额
	 * @return floot info[0].vip_rules[].money_ios vip充值规则ios金额
	 * @return string info[0].vip_rules[].product_id vip充值规则ios标识
	 * @return int info[0].vip_rules[].days vip充值规则天数
	 * @return int info[0].vip_rules[].coin vip充值规则需要支付钻石数
	 * @return array info[0].equity_lists vip权限说明
	 * @return string info[0].equity_lists[].thumb vip权限说明标识图片
	 * @return string info[0].equity_lists[].title vip权限说明标题
	 * @return string info[0].equity_lists[].des vip权限说明描述
	 * @return string info[0].aliapp_partner 支付宝合作者身份id
	 * @return string info[0].aliapp_seller_id 支付宝账号
	 * @return string info[0].aliapp_key_android 支付宝安卓秘钥
	 * @return string info[0].aliapp_key_ios 支付宝ios秘钥
	 * @return string info[0].wx_appid 微信开放平台appid
	 * @return string info[0].wx_appsecret 微信开放平台secret
	 * @return string info[0].wx_mchid 微信开放平台对应支付平台商户号
	 * @return string info[0].wx_key 微信开放平台移动应用对应的微信商户密钥key
	 * @return array info[0].paylist 支付方式列表
	 * @return int info[0].paylist[].id 支付方式id
	 * @return string info[0].paylist[].name 支付方式名称
	 * @return string info[0].paylist[].thumb 支付方式图标
	 * @return string info[0].paylist[].href 支付方式链接
	*/
	public function getVip(){
		$rs=array('code'=>0,'msg'=>'','info'=>array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$type=checkNull($this->type);
        $version_ios=checkNull($this->version_ios);
		
		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$userinfo=getUserInfo($uid);

		$info[0]['userinfo']=$userinfo;

		$domain=new Domain_User();
		$key="getVipChargeRules";
		$vip_rules=getcaches($key);

		if(!$vip_rules){
			$vip_rules=$domain->getVipRules();
			setcaches($key,$vip_rules);
		}


		$info[0]['vip_rules']=$vip_rules;


		$equity_lists[]=array(
			'thumb'=>get_upload_path('/static/app/vip/fly.png'),
			'title'=>'无限发布视频',
			'des'=>'会员用户可无限发布视频作品',
		);

		$equity_lists[]=array(
			'thumb'=>get_upload_path('/static/app/vip/time.png'),
			'title'=>'发布长视频作品',
			'des'=>'会员用户可录制或上传60s时长的视频',
		);

		$equity_lists[]=array(
			'thumb'=>get_upload_path('/static/app/vip/eye.png'),
			'title'=>'无限观看视频',
			'des'=>'会员用户可无限观看平台的视频作品',
		);

		$equity_lists[]=array(
			'thumb'=>get_upload_path('/static/app/vip/money.png'),
			'title'=>'免费观看收费视频',
			'des'=>'会员观看收费视频时不需要付费',
		);
		
		$info[0]['equity_lists']=$equity_lists;

		$configpub=getConfigPub();
		$configpri=getConfigPri();

		$vip_aliapp_switch=$configpri['vip_aliapp_switch'];

		
		$info[0]['aliapp_partner']=$vip_aliapp_switch==1?$configpri['aliapp_partner']:'';
		$info[0]['aliapp_seller_id']=$vip_aliapp_switch==1?$configpri['aliapp_seller_id']:'';
		$info[0]['aliapp_key']=$vip_aliapp_switch==1?$configpri['aliapp_key_android']:'';
		$info[0]['aliapp_key_ios']=$vip_aliapp_switch==1?$configpri['aliapp_key_ios']:'';

        $vip_wx_switch=$configpri['vip_wx_switch'];

		$info[0]['wx_appid']=$vip_wx_switch==1?$configpri['wx_appid']:'';
		$info[0]['wx_appsecret']=$vip_wx_switch==1?$configpri['wx_appsecret']:'';
		$info[0]['wx_mchid']=$vip_wx_switch==1?$configpri['wx_mchid']:'';
		$info[0]['wx_key']=$vip_wx_switch==1?$configpri['wx_key']:'';

		$vip_braintree_paypal_switch=$configpri['vip_braintree_paypal_switch'];

		//获取充值vip方式列表【在确认支付前，ios自己判断是否上架，如果上架的话，直接走苹果支付，不弹窗显示】
		$paylist=array();

		$shelves=1;
        $ios_shelves=$configpub['ios_shelves']; //后台填写的上架版本号
        /*if($version_ios && $version_ios==$ios_shelves){
			$shelves=0;
		}*/


		if($vip_aliapp_switch&& $shelves){
			$paylist[]=array(
				'id'=>'ali',
				'name'=>'支付宝支付',
				'thumb'=>get_upload_path("/static/app/pay/ali.png"),
				'href'=>''
			);
		}

		if($vip_wx_switch&& $shelves){
			$paylist[]=array(
				'id'=>'wx',
				'name'=>'微信支付',
				'thumb'=>get_upload_path("/static/app/pay/wx.png"),
				'href'=>''
			);
		}

		if($vip_braintree_paypal_switch&& $shelves){
			$paylist[]=array(
				'id'=>'paypal',
				'name'=>'Paypal支付',
				'thumb'=>get_upload_path("/static/app/pay/paypal.png"),
				'href'=>''
			);
		}

		/*忽略苹果支付if( ($configpri['vip_ios_switch'] || $shelves==0) && $type==1){
            $paylist[]=[
                'id'=>'apple',
                'name'=>'苹果支付',
                'thumb'=>get_upload_path("/static/app/pay/apple.png"),
                'href'=>'',
            ];
        }*/

        if($configpri['vip_coin_switch']&& $shelves){
			$paylist[]=array(
				'id'=>'balance',
				'name'=>'余额支付',
				'thumb'=>get_upload_path("/static/app/vip/coin.png"),
				'href'=>''
			);
		}

		$info[0]['paylist']=$paylist;

		$rs['info']=$info;

		return $rs;



	}

	/**
	 * 获取用户是否可开播、是否可上传视频、发布视频时是否可发布商品
	 * @desc 用于获取用户是否可开播、是否可上传视频、发布视频时是否可发布商品
	 * @return int code 状态码 0表示成功
	 * @return string msg 状态码 0表示成功
	 * @return array info 返回信息
	 * @return int info[0].live_status 是否可开播 0 否 1 是
	 * @return int info[0].video_status 是否可发布视频 0 否 1 是
	 * @return int info[0].setvideo_charge 发布视频时是否可设置视频收费价格 0 否 1 是
	 * @return int info[0].isshop 发布视频时是否可发布商品 0 否 1 是
	 * @return string info[0].video_msg 发布视频受限 提示语
	 * @return string info[0].live_msg 开播受限 提示语
	*/

	public function checkLiveVipStatus(){
		$rs=array('code'=>0,'msg'=>"",'info'=>array());
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);

		$checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain=new Domain_User();
		$result=$domain->checkLiveVipStatus($uid);

		if($result==1001){
			$rs['code']=1001; //此code值固定为1001，app要进行判断弹窗提示，并按钮跳转认证页面
			$rs['msg']="未认证,无法发布视频和开启直播";
			return $rs;
		}

		$domain = new Domain_Shop();
		$ishop = $domain->isShop($uid);

		//发布视频时是否可发布商品
		$result['isshop']=$ishop;

		$configpri=getConfigPri();
		$vip_switch=$configpri['vip_switch'];

		//获取用户的vip信息
		$vipinfo=getUserVipInfo($uid);

		$vip_switch=$vipinfo['vip_switch'];

		$result['vip_switch']=$vip_switch;

		if(!$vip_switch){
			$long_video_status='0';

		}else{
			if($vipinfo['isvip']){
				$long_video_status='1';
			}else{
				$long_video_status='0';
			}
		}

		$result['long_video_status']=$long_video_status;

		$result['video_msg']="您发布作品的次数已用尽\n开通会员可无限发布作品";
		$result['live_msg']="总发布视频数和粉丝数未达到标准无法开播";
		$rs['info'][0]=$result;
		return $rs;

	}

    /**
     * 用户查看每日任务的进度
     * @desc 用于用户查看每日任务的进度
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function seeDailyTasks(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $liveuid=checkNull($this->liveuid);
        $islive=checkNull($this->islive);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		
		if($islive==1){   //判断请求是否在直播间
			if($uid==$liveuid){ //主播访问
				/*观看直播计时---每日任务--取出用户进入时间*/
				$key='open_live_daily_tasks_'.$uid;
				$starttime=getcaches($key);
				if($starttime){ 
					$endtime=time();  //当前时间
					$data=[
						'type'=>'3',
						'starttime'=>$starttime,
						'endtime'=>$endtime,
					];
					dailyTasks($uid,$data);
					//删除当前存入的时间
					delcache($key);
				}	
				/*观看直播计时---用于每日任务--记录用户进入时间*/
				$enterRoom_time=time();
				setcaches($key,$enterRoom_time);
				
			}else{  //用户访问
			
				/*观看直播计时---每日任务--取出用户进入时间*/
				$key='watch_live_daily_tasks_'.$uid;
				$starttime=getcaches($key);
				if($starttime){ 
					$endtime=time();  //当前时间
					$data=[
						'type'=>'1',
						'starttime'=>$starttime,
						'endtime'=>$endtime,
					];
					dailyTasks($uid,$data);
					//删除当前存入的时间
					delcache($key);
				}	
				/*观看直播计时---用于每日任务--记录用户进入时间*/
				$enterRoom_time=time();
				setcaches($key,$enterRoom_time);

			}
		}
		
		$domain=new Domain_User();
		$info=$domain->seeDailyTasks($uid);

		$configpub=getConfigPub();
		$name_coin=$configpub['name_coin']; //钻石名称

		$rs['info'][0]['tip_m']="温馨提示：当您某个任务达成时就会获得平台奖励给您的{$name_coin}，获得的奖励需要您手动领取才可放入余额中，当日不领取次日系统会自动清零，亲爱的您一定要记得领取当日奖励哦~";
		$rs['info'][0]['list']=$info;
		return $rs;

    }
	
	
	/**
     * 领取每日任务奖励
     * @desc 用于用户领取每日任务奖励
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function receiveTaskReward(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $taskid=checkNull($this->taskid);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain=new Domain_User();
		$info=$domain->receiveTaskReward($uid,$taskid);

		
		return $info;

    }

    /**
     * 用户设置美颜参数
     * @desc 用于用户设置美颜参数
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setBeautyParams(){
    	$rs = array('code' => 0, 'msg' => $Think.\lang('SET_SUCCESSFULLYf'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $params=$this->params;

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}


		$domain=new Domain_User();
		$res=$domain->setBeautyParams($uid,$params);
		if(!$res){
			$rs['code'] = 1001;
			$rs['msg'] = '设置失败';
			return $rs;
		}

		return $rs;
    }

    /**
     * 获取用户设置的美颜参数
     * @desc 用于获取用户设置的美颜参数
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getBeautyParams(){

    	$rs = array('code' => 0, 'msg' => $Think.\lang('SET_SUCCESSFULLYf'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		$domain=new Domain_User();
		$res=$domain->getBeautyParams($uid);
		$rs['info'][0]=$res;

		return $rs;
    }

    /**
     * 用户申请店铺余额提现
     * @desc 用于用户申请店铺余额提现
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function setShopCash(){
    	$rs = array('code' => 0, 'msg' => $Think.\lang('ADMIN_CASH_INDEX_WITHDRAWAL_SUCCEED'), 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);		
        $accountid=checkNull($this->accountid);		
        $money=checkNull($this->money);
        $time=checkNull($this->time);
        $sign=checkNull($this->sign);

        if($uid<0||$token==""||!$time||!$sign){
            $rs['code']=1001;
            $rs['msg']=$Thin.lang('PARAMENTER_ERROT');
            return $rs;
        }

        $checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}

		if(!$accountid){
            $rs['code'] = 1001;
			$rs['msg'] = '请选择提现账号';
			return $rs;
        }

        if(!$money){
            $rs['code'] = 1002;
			$rs['msg'] = '请输入有效的提现金额';
			return $rs;
        }

		$now=time();
        if($now-$time>300){
            $rs['code']=1001;
            $rs['msg']=$Thin.lang('PARAMENTER_ERROT');
            return $rs;
        }

        $checkdata=array(
            'uid'=>$uid,
            'token'=>$token,
            'accountid'=>$accountid,
            'time'=>$time
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']='签名错误';
            return $rs; 
        }

        $configpri=getConfigPri();

        $data=array(
            'uid'=>$uid,
            'accountid'=>$accountid,
            'money'=>$money,
        );

        $domain=new Domain_User();
        $res = $domain->setShopCash($data);

        if($res==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '余额不足';
			return $rs;
		}else if($res==1004){
			$rs['code'] = 1004;
			$rs['msg'] = '提现最低额度为'.$configpri['balance_cash_min'].'元';
			return $rs;
		}else if($res==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '不在提现期限内，不能提现';
			return $rs;
		}else if($res==1006){
			$rs['code'] = 1006;
			$rs['msg'] = '每月只可提现'.$configpri['balance_cash_max_times'].'次,已达上限';
			return $rs;
		}else if($res==1007){
			$rs['code'] = 1007;
			$rs['msg'] = '提现账号信息不正确';
			return $rs;
		}else if(!$res){
			$rs['code'] = 1002;
			$rs['msg'] = '提现失败，请重试';
			return $rs;
		}
	 
		$rs['info'][0]['msg']=$Think.\lang('ADMIN_CASH_INDEX_WITHDRAWAL_SUCCEED');
		return $rs;

    }

    /**
     * 判断用户是否第一次关注对方
     * @desc 用于APP关注对方时判断是否要发私信自定义消息
     * @return int code 状态码，0表示成功
     * @return string msg 返回提示信息
     * @return array info 返回信息
     */
    public function isFirstAttent(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
    	$uid=checkNull($this->uid);
    	$touid=checkNull($this->touid);

    	$isBlackUser=isBlackUser($uid);
		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$domain=new Domain_User();
		$res=$domain->isFirstAttent($uid,$touid);
		
		$rs['info'][0]['is_first']=(string)$res;
		return $rs;
    }


    /**
     * 用于APP端调用Braintree支付时的token验证
     * @desc 用于APP端调用Braintree支付时的token验证
     * @return int code 状态码,0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function getBraintreeToken(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
    	$uid=checkNull($this->uid);
        $token=checkNull($this->token);

        $checkToken = checkToken($uid,$token);

		/*if($checkToken==700){
			$rs['code']=700;
			$rs['msg']='您的登陆状态失效，请重新登陆！';
			return $rs;
		}*/

		$getway_back=$this->getBrainTreeGateway();

        if($getway_back['code']!=0){
        	return $getway_back;
        }else{
        	$gateway=$getway_back['info'];
        }

		$clientToken = $gateway->clientToken()->generate();

		$rs['info'][0]['braintreeToken']=$clientToken;
		return $rs;
    }

    /**
     * 获取BrainTreeGateway
     */
    private function getBrainTreeGateway(){

    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$configpri=getConfigPri();

		$environment=$configpri['braintree_paypal_environment'];

		$merchantId='';
		$publicKey='';
		$privateKey='';

		if($environment==0){ //沙盒
			$merchantId=$configpri['braintree_merchantid_sandbox'];
			$publicKey=$configpri['braintree_publickey_sandbox'];
			$privateKey=$configpri['braintree_privatekey_sandbox'];
			$environment='sandbox';
			
		}else{ //生产

			$merchantId=$configpri['braintree_merchantid_product'];
			$publicKey=$configpri['braintree_publickey_product'];
			$privateKey=$configpri['braintree_privatekey_product'];
			$environment='production';
			
		}

		if(!$merchantId || !$publicKey ||!$privateKey){
			$rs['code']=1001;
			$rs['msg']='BraintreePaypal未配置';
			return $rs;
		}

		$gateway = new Braintree\Gateway([
			'environment' => $environment,
			'merchantId' => $merchantId,
			'publicKey' => $publicKey,
			'privateKey' => $privateKey
		]);

		$rs['info']=$gateway;
		return $rs;
    }


    /**
     * BrainTree支付回调
     * @desc BrainTree支付回调
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     */
    public function BraintreeCallback(){
    	$rs = array('code' => 0, 'msg' => '回调成功', 'info' => array());

    	$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$orderno=checkNull($this->orderno);
		$ordertype=checkNull($this->ordertype);
		$nonce=checkNull($this->nonce);
		$money=checkNull($this->money);
		$time=checkNull($this->time);
		$sign=checkNull($this->sign);


		//file_put_contents('./111111.txt',date('y-m-d H:i:s').' 提交参数信息:'.json_encode($nonce)."\r\n",FILE_APPEND);

		if(!in_array($ordertype, ['coin_charge','order_pay','vip_pay'])){
			$rs['code'] = 1001;
			$rs['msg'] = $Thin.lang('PARAMENTER_ERROT');
			return $rs;
		}

		if(!$nonce){
			$rs['code'] = 1002;
			$rs['msg'] = '三方订单编号错误';
			return $rs;
		}

		if(!$money){
			$rs['code'] = 1002;
			$rs['msg'] = '金额错误';
			return $rs;
		}

		$checkToken=checkToken($uid,$token);

		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}

		$now=time();
        if($now-$time>300){
            $rs['code']=1001;
            $rs['msg']=$Thin.lang('PARAMENTER_ERROT');
            return $rs;
        }

        $checkdata=array(
            'uid'=>$uid,
            'ordertype'=>$ordertype,
            'orderno'=>$orderno,
            'time'=>$time,
			'nonce'=>$nonce
        );

        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1002;
            $rs['msg']='签名错误';
            return $rs; 
        }

        $getway_back=$this->getBrainTreeGateway();

        if($getway_back['code']!=0){
        	return $getway_back;
        }else{
        	$gateway=$getway_back['info'];
        }

		$result = $gateway->transaction()->sale([
		    'amount' => $money,
		    'paymentMethodNonce' => $nonce,
		    'options' => [ 'submitForSettlement' => true ]
		]);

		if($result->success){

			$domain=new Domain_User();
	        $res=$domain->BraintreeCallback($uid,$orderno,$ordertype,$nonce,$money);
	        if($res==1001){
	        	$rs['code']=1001;
	            $rs['msg']=$Think.\lang('ORDER_NOT_EXIST');
	            return $rs; 
	        }

	        if($res==1002){
	        	$rs['code']=1002;
	            $rs['msg']='订单已支付';
	            return $rs; 
	        }

	        return $rs;

		}else{

			$rs['code']=1002;
            $rs['msg']='订单回调验证失败';
            return $rs;

		}

    }

}

<?php

class Api_Live extends PhalApi_Api {

	public function getRules() {
		return array(

			'getRecommendLists' => array(
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),
			
            'getGiftList' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
            ),

            'createRoom' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'title' => array('name' => 'title', 'type' => 'string','default'=>'', 'desc' => '直播标题 url编码'),
                'province' => array('name' => 'province', 'type' => 'string', 'default'=>'', 'desc' => '省份'),
                'city' => array('name' => 'city', 'type' => 'string', 'default'=>'', 'desc' => '城市'),
                'isshop' => array('name' => 'isshop', 'type' => 'int', 'default'=>'0', 'desc' => '是否开启购物车 0不  1开'),
                'deviceinfo' => array('name' => 'deviceinfo', 'type' => 'string', 'default'=>'', 'desc' => '设备信息'),
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'default'=>'0', 'desc' => '直播分类ID'),
            ),

            'changeLive' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'status' => array('name' => 'status', 'type' => 'int', 'require' => true, 'desc' => '直播状态 0关闭 1开播'),
			),

			'stopRoom' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),

			),

			'stopInfo' => array(
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),

			'checkLive' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),

			'enterRoom' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
			),
			
			'showVideo' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '上麦会员ID'),
                'pull_url' => array('name' => 'pull_url', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '连麦用户播流地址'),
            ),

			'getUserLists' => array(
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'p' => array('name' => 'p', 'type' => 'int', 'min' => 1, 'default'=>1,'desc' => '页数'),
			),

			'getPop' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),

			'sendGift' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'stream' => array('name' => 'stream', 'type' => 'string', 'require' => true, 'desc' => '流名'),
				'giftid' => array('name' => 'giftid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物ID'),
				'giftcount' => array('name' => 'giftcount', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '礼物数量'),
				'ispack' => array('name' => 'ispack', 'type' => 'int', 'default'=>'0', 'desc' => '是否背包 1是 0否'),
			),

			'setAdmin' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),

			'getAdminList' => array(
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
			),

			'setReport' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
				'content' => array('name' => 'content', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '举报内容'),
			),

			'getCoin' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),

            'setShutUp' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'min' => 1, 'require' => true, 'desc' => '用户token'),
                'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '禁言用户ID'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
                'type' => array('name' => 'type', 'type' => 'int', 'default'=>'0', 'desc' => '禁言类型,0永久，1本场'),
                'stream' => array('name' => 'stream', 'type' => 'string', 'default'=>'0', 'desc' => '流名'),
            ),

            'kicking' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
				'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户token'),
				'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'touid' => array('name' => 'touid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '对方ID'),
			),

			'superStopRoom' => array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'liveuid' => array('name' => 'liveuid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '主播ID'),
				'type' => array('name' => 'type', 'type' => 'int','default'=>0, 'desc' => '关播类型 0表示关闭当前直播 1表示禁播，2表示封禁账号'),
            ),

            'checkLiveing' => array(
				'uid' => array('name' => 'uid', 'type' => 'int','desc' => '会员ID'),
                'stream' => array('name' => 'stream', 'type' => 'string','desc' => '流名'),
            ),
			'shareLiveRoom'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),

            'setLiveGoodsIsShow'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
                'goodsid' => array('name' => 'goodsid','type' => 'int', 'require' => true, 'min' => 1, 'desc' => '商品ID'),

            ),

            'getClassLive'=>array(
                'liveclassid' => array('name' => 'liveclassid', 'type' => 'int', 'default'=>'0' ,'desc' => '直播分类ID'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

            'signOutWatchLive'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '会员ID'),
                'token' => array('name' => 'token', 'require' => true, 'min' => 1, 'desc' => '会员token'),
            ),
		);
	}


	/**
     * 获取直播参数配置 
     * @desc 用于获取直播参数配置
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return object info[0].android 安卓直播参数配置
	 * @return object info[0].ios IOS 直播参数配置
     * @return string msg 提示信息
     */

    public function getLiveParameters(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());

    	$ios=array(
	        'codingmode' => '2',  //编码 0自动，1软编，2硬编
	        'resolution' => '5',  //分辨率 3:360*640; 4:540*960; 5:720*1280 
	        'isauto' => '1',  //是否自适应 0否1是   
	        'fps' => '20',  //帧数
	        'fps_min' => '20',  //最低帧数
	        'fps_max' => '30',  //最高帧数
	        'gop' => '3',  //关键帧间隔 
	        'bitrate' => '800',  //初始码率  kbps
	        'bitrate_min' => '800',  //最低码率
	        'bitrate_max' => '1200',  //最高码率
	        'audiorate' => '44100',  //音频采样率  Hz
	        'audiobitrate' => '48',  //音频码率 kbps
	        
	        'preview_fps' => '15',  //预览帧数
	        'preview_resolution' => '1',  //预览分辨率
	    );

	    $android=array(
	        'codingmode' => '3',  //编码 1自动，3软编，2硬编
	        'resolution' => '1',  //分辨率 
	        'isauto' => '1',  //是否自适应 0否1是 
	        'fps' => '20',  //帧数
	        'fps_min' => '20',  //最低帧数
	        'fps_max' => '30',  //最高帧数
	        'gop' => '3',  //关键帧间隔 
	        'bitrate' => '500',  //初始码率  kbps
	        'bitrate_min' => '500',  //最低码率
	        'bitrate_max' => '800',  //最高码率
	        'audiorate' => '44100',  //音频采样率  Hz
	        'audiobitrate' => '48',  //音频码率 kbps        
	        'preview_fps' => '15',  //预览帧数
	        'preview_resolution' => '1',  //预览分辨率
	    );

    	$rs['info'][0]['ios']=$ios;
    	$rs['info'][0]['android']=$android;

    	/* 安卓的resolution代表的分辨率
    	360_640 = 0;
		540_960 = 1;
		720_1280 = 2;
		640_360 = 3;
		960_540 = 4;
		1280_720 = 5;
		320_480 = 6;
		180_320 = 7;
		270_480 = 8;
		320_180 = 9;
		480_270 = 10;
		240_320 = 11;
		360_480 = 12;
		480_640 = 13;
		320_240 = 14;
		480_360 = 15;
		640_480 = 16;
		480_480 = 17;
		270_270 = 18;
		160_160 = 19;
		1080_1920 = 30;
		1920_1080 = 31;
    	 */

    	return $rs;
    }


    /**
     * 获取直播推荐列表
     * @desc 用于获取直播推荐列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[].uid 主播id
     * @return string info[0]['list'][].avatar 主播头像
     * @return string info[0]['list'][].avatar_thumb 头像缩略图
     * @return string info[0]['list'][].user_nicename 直播昵称
     * @return string info[0]['list'][].title 直播标题
     * @return string info[0]['list'][].city 主播位置
     * @return string info[0]['list'][].stream 流名
     * @return string info[0]['list'][].pull 播流地址
     * @return string info[0]['list'][].nums 人数
     * @return string info[0]['list'][].thumb 直播封面
     * @return string msg 提示信息
     */
    public function getRecommendLists() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $p=$this->p;
        $domain = new Domain_Live();

		$key="getLists_".$p;
		$list=getcaches($key);
		if(!$list){
			$list = $domain->getRecommendLists($p);
			setCaches($key,$list,2); 
		}

        $rs['info'] = $list;

        return $rs;
    }
    

    /**
     * 获取礼物列表 
     * @desc 用于获取礼物列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].coin 余额
     * @return array info[0].giftlist 礼物列表
     * @return string info[0].giftlist[].id 礼物ID
     * @return string info[0].giftlist[].type 礼物类型
     * @return string info[0].giftlist[].mark 礼物标识
     * @return string info[0].giftlist[].giftname 礼物名称
     * @return string info[0].giftlist[].needcoin 礼物价格
     * @return string info[0].giftlist[].gifticon 礼物图片
     * @return string msg 提示信息
     */
    public function getGiftList() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=$this->uid;
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
        
        $domain = new Domain_Live();
        $giftlist=$domain->getGiftList();
        
        $coin=getUserCoin($uid);
        
        $rs['info'][0]['giftlist']=$giftlist;
        $rs['info'][0]['coin']=$coin;
        return $rs;
    }


    /**
     * 创建开播 
     * @desc 用于用户创建开播记录
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return object info[0].userlist_time 用户列表请求间隔（秒）
     * @return object info[0].chatserver 聊天服务器地址
	 * @return object info[0].votestotal 总的金币数
	 * @return object info[0].stream 流名
	 * @return object info[0].push 推流地址
	 * @return object info[0].pull 播流地址
	 * @return object info[0].tx_appid 腾讯云直播appid
     * @return string msg 提示信息
     */
    public function createRoom(){
    	$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid = $this->uid;
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

        $domain = new Domain_Live();

        $result = $domain->checkBan($uid);
		if($result){
			$rs['code'] = 1001;
			$rs['msg'] = '已被禁播';
			return $rs;
		}

		//检测用户是否可以开播
		$domain_user=new Domain_User();
		$status=$domain_user->checkLiveVipStatus($uid);
		if(!$status['live_status']){
			$rs['code'] = 1002;
			$rs['msg'] = '开播条件不满足,无法开播';
			return $rs;
		}

		$nowtime=time();
		$showid=$nowtime;
		$starttime=$nowtime;
		$title=checkNull($this->title);
		$province=checkNull($this->province);
		$city=checkNull($this->city);
		$isshop=checkNull($this->isshop);
		$deviceinfo=checkNull($this->deviceinfo);
		$liveclassid=checkNull($this->liveclassid);
		$stream=$uid.'_'.$nowtime;
		$push=PrivateKeyA('rtmp',$stream,1); //推流地址
		$pull=PrivateKeyA('rtmp',$stream,0); //播流地址


		//判断开播时直播标题是否包含敏感词
		$keywordsIsExist=checkSensitiveWords($title);
			if($keywordsIsExist){
				$rs['code'] = 1002;
                $rs['msg'] = '直播标题输入非法,请重新输入';
                return $rs;
			}

		if(!$city){
			$city='好像在火星';
		}

		$thumb='';
		$configpri=getConfigPri();

		if($_FILES){

			if(!checkExt($_FILES["file"]['name'])){
				$rs['code']=1004;
				$rs['msg']='图片仅能上传 jpg,png,jpeg';
				return $rs;
			}

			//获取后台配置的云存储方式
			
			$cloudtype=$configpri['cloudtype'];

			if($cloudtype==0){ //本地上传
				//设置上传路径 设置方法参考3.2
				DI()->ucloud->set('save_path','avatar/'.date("Ymd"));

				//上传表单名
				$res = DI()->ucloud->upfile($_FILES['file']);
				$files='../upload/'.$res['file'];
				$PhalApi_Image = new Image_Lite();

				//打开图片
				$PhalApi_Image->open($files);

				$PhalApi_Image->thumb(660, 660, IMAGE_THUMB_SCALING);
				$PhalApi_Image->save($files);

				$thumb=$res['url'];


			}else if($cloudtype==1){ //七牛

				$url = DI()->qiniu->uploadFile($_FILES['file']['tmp_name'],$configpri['qiniu_accesskey'],$configpri['qiniu_secretkey'],$configpri['qiniu_bucket'],$configpri['qiniu_domain_url']);

				if (!empty($url)) {
					$thumb=  $url.'?imageView2/2/w/600/h/600'; //600 X 600

					$thumb=setCloudType($thumb);
				}

			}else if($cloudtype==2){ //腾讯云
				$files["file"]=$_FILES["file"];
	            $type='img';
	            
	            $ret=qcloud($files,$type);

	            if($ret['code']!=0){
	                $rs['code']=1003;
	                $rs['msg']=$ret['message'];
	                return $rs;
	            }

	            $thumb = $ret['data']['url'];

	            $thumb=setCloudType($thumb);

			}else if($cloudtype==3){ //亚马逊

				$files["file"]=$_FILES["file"];
	            $type='img';
	            
	            $ret=awscloud($files,$type);

	            if($ret['code']!=0){
	                $rs['code']=1002;
	                $rs['msg']=$ret['msg'];
	                return $rs;
	            }

	            $thumb = $ret['data']['url'];
	            $thumb=setCloudType($thumb);

			}

			@unlink($_FILES['file']['tmp_name']);
		}

		if(!$liveclassid){
			$rs['code'] = 1001;
            $rs['msg'] = '请选择直播分类';
            return $rs;
		}

		$liveclass = getLiveClass();

		$liveclass_ids=array_column($liveclass,'id');

		if(!in_array($liveclassid, $liveclass_ids)){
			$rs['code'] = 1001;
            $rs['msg'] = '直播分类不存在';
            return $rs;
		}

		$dataroom=array(
			"uid"=>$uid,
			"showid"=>$showid,
			"islive"=>0,
			"starttime"=>$starttime,
			"title"=>$title,
			"province"=>$province,
			"city"=>$city,
			"stream"=>$stream,
			"thumb"=>$thumb,
			"pull"=>$pull,
			"isshop"=>$isshop,
			"isvideo"=>0,
			"deviceinfo"=>$deviceinfo,
			"liveclassid"=>$liveclassid,
		);
		
		$result = $domain->createRoom($uid,$dataroom);

		if(!$result){
			$rs['code'] = 1004;
			$rs['msg'] = '开播失败，请重试';
			return $rs;
		}

		$info['userlist_time']=$configpri['userlist_time'];
		$info['chatserver']=$configpri['chatserver'];

		$votestotal=getUserVotesTotal($uid);
		$info['votestotal']=$votestotal;
		$info['stream']=$stream;
		$info['push']=$push;
		$info['pull']=$pull;
		
		
		/* 守护数量 */
        $domain_guard = new Domain_Guard();
		$guard_nums = $domain_guard->getGuardNums($uid);
        $info['guard_nums']=$guard_nums;
		

		//腾讯appid
		$info['tx_appid']=$configpri['tx_appid'];

		$rs['info'][0]=$info;
		
		/* 清除连麦PK信息 */
        DI()->redis  -> hset('LiveConnect',$uid,0);
        DI()->redis  -> hset('LivePK',$uid,0);
        DI()->redis  -> hset('LivePK_gift',$uid,0);
		

		$userinfo=getUserInfo($uid);
		$userinfo['city']=$city;
		$userinfo['usertype']=50;
		$userinfo['sign']='0';

		DI()->redis  -> set($token,json_encode($userinfo));

		return $rs;

    }


    /**
	 * 修改直播状态
	 * @desc 用于主播修改直播状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 成功提示信息
	 * @return string msg 提示信息
	 */
	public function changeLive() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid = $this->uid;
		$token=checkNull($this->token);
		$stream=checkNull($this->stream);
		$status=$this->status;
		
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
		
		$domain = new Domain_Live();
		$info=$domain->changeLive($uid,$stream,$status);
        
        $configpri=getConfigPri();
        /* 极光推送 */
		$app_key = $configpri['jpush_key'];
		$master_secret = $configpri['jpush_secret'];
		$jpush_switch=$configpri['jpush_switch'];

		if($app_key && $master_secret && $status==1 && $info && $jpush_switch){
			require API_ROOT.'/public/JPush/autoload.php';
			// 初始化
			$client = new \JPush\Client($app_key, $master_secret,null);
            
            $userinfo=getUserInfo($uid);
			
			$anthorinfo=array(
				"uid"=>$info['uid'],
				"avatar"=>$userinfo['avatar'],
				"avatar_thumb"=>$userinfo['avatar_thumb'],
				"user_nicename"=>$userinfo['user_nicename'],
				"title"=>$info['title'],
				"city"=>$info['city'],
				"stream"=>$info['stream'],
				"pull"=>$info['pull'],
				"thumb"=>$info['thumb'],
				"isvideo"=>'0',
				"nums"=>0,

			);
			$title='你的好友：'.$anthorinfo['user_nicename'].'正在直播，快来围观~';
			$apns_production=false;
			if($configpri['jpush_sandbox']){
				$apns_production=true;
			}
            
            $pushids = getFansIds($uid); 
			$nums=count($pushids);	
			for($i=0;$i<$nums;){
                $alias=array_slice($pushids,$i,900);
                $i+=900;
				try{	
					$result = $client->push()
							->setPlatform('all')
							->addRegistrationId($alias)
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
									'title' => $title,
									'type' => '2',
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
					file_put_contents('./live_jpush.txt',date('y-m-d h:i:s').'提交参数信息 设备名:'.json_encode($alias)."\r\n",FILE_APPEND);
					file_put_contents('./live_jpush.txt',date('y-m-d h:i:s').'提交参数信息:'.$e."\r\n",FILE_APPEND);
				}					
			}			
		}
		/* 极光推送 */

		$rs['info'][0]['msg']='成功';
		return $rs;
	}

	/**
	 * 关闭直播
	 * @desc 用于用户结束直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 成功提示信息
	 * @return string msg 提示信息
	 */
	public function stopRoom() { 
		$rs = array('code' => 0, 'msg' => '关播成功', 'info' => array());

        file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 开始:'."\r\n",FILE_APPEND);
        file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 _REQUEST:'.json_encode($_REQUEST)."\r\n",FILE_APPEND);

		$uid = checkNull($this->uid);
		$token=checkNull($this->token);
		$stream=checkNull($this->stream);
		
		$key='stopRoom_'.$stream;
		$isexist=getcaches($key);

        file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 isexist:'.json_encode($isexist)."\r\n",FILE_APPEND);

		if(!$isexist ){
			$checkToken=checkToken($uid,$token);
            file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 checkToken:'.json_encode($checkToken)."\r\n",FILE_APPEND);
			if($checkToken==700){
				$rs['code'] = $checkToken;
				$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
				return $rs;
			}else if($checkToken==10020){
	            $rs['code'] = 700;
	            $rs['msg'] = '该账号已被禁用';
	            return $rs;
	        }

			setcaches($key,'1',10);

            $domain = new Domain_Live();
            $info=$domain->stopRoom($uid,$stream);

		}
		
		$rs['info'][0]['msg']='关播成功';
        file_put_contents(API_ROOT.'/Runtime/stopRoom_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 结束:'."\r\n",FILE_APPEND);

		return $rs;
	}

	/**
	 * 直播结束页面信息展示
	 * @desc 用于直播结束页面信息展示
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].nums 人数
	 * @return string info[0].length 时长
	 * @return string info[0].votes 映票数
	 * @return string msg 提示信息
	 */
	public function stopInfo() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$stream=checkNull($this->stream);
		
		$domain = new Domain_Live();
		$info=$domain->stopInfo($stream);

		$rs['info'][0]=$info;
		return $rs;
	}


	/**
	 * 用户进房间时检查直播
	 * @desc 用于用户进房间时检查直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string msg 提示信息
	 */
	public function checkLive() {
		$rs = array('code' => 0, 'msg' => '直播正常', 'info' => array());
		
		$uid=$this->uid;
		$token=checkNull($this->token);
		$liveuid=$this->liveuid;
		$stream=checkNull($this->stream);
		
        
        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = 700;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}else if($checkToken==10020){
	            $rs['code'] = 700;
	            $rs['msg'] = '该账号已被禁用';
	            return $rs;
	        }
        
        
		$isban = isBan($uid);
		if(!$isban){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;
		}
        
        
        if($uid==$liveuid){
			$rs['code'] = 1011;
			$rs['msg'] = '不能进入自己的直播间';
			return $rs;
		}
		

		$domain = new Domain_Live();
		$info=$domain->checkLive($uid,$liveuid,$stream);
		
		if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = '直播已结束';
			return $rs;
		}else if($info==1008){
            $rs['code'] = 1004;
			$rs['msg'] = '您已被踢出房间';
			return $rs;
        }

        $rs['info'][0]=$info;

		return $rs;
	}

	/**
	 * 进入直播间
	 * @desc 用于用户进入直播
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votestotal 直播的总映票
	 * @return string info[0].userlist_time 用户列表获取间隔
	 * @return string info[0].chatserver socket地址
	 * @return string info[0].isattention 是否关注主播，0表示未关注，1表示已关注
	 * @return string info[0].nums 房间人数
	 * @return string info[0].issuper 该用户是否为超管
	 * @return string info[0].coin 当前用户的钻石数
	 * @return string info[0].usertype 当前用户的身份 30普通用户 40 房间管理员 50 主播 60 超管
	 * @return array info[0].userlists 用户列表
	 * @return string msg 提示信息
	 */
	public function enterRoom() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=checkNull($this->token);
		$liveuid=$this->liveuid;
		$stream=checkNull($this->stream);
        
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
        
        
		$isban = isBan($uid);
		if(!$isban){
			$rs['code']=1001;
			$rs['msg']='该账号已被禁用';
			return $rs;
		}

		
		$domain = new Domain_Live();
        
        $domain->checkShut($uid,$liveuid); //检测此用户是否被禁言

		$userinfo=getUserInfo($uid);
		
		$issuper='0';
		if($userinfo['issuper']==1){
			$issuper='1';
			DI()->redis  -> hset('super',$userinfo['id'],'1');
		}else{
			DI()->redis  -> hDel('super',$userinfo['id']);
		}

		$usertype = isAdmin($uid,$liveuid); //判断权限

		$userinfo['usertype'] = $usertype;
        
        $stream2=explode('_',$stream);

		$showid=$stream2[1];
        
        $contribution='0';

        if($showid){
            $contribution=$domain->getContribut($uid,$liveuid,$showid);//获取该用户在本场的贡献
        }

		$userinfo['contribution'] = $contribution;
		
		
		/* 守护 */
        $domain_guard = new Domain_Guard();
		$guard_info=$domain_guard->getUserGuard($uid,$liveuid);
        
		$guard_nums=$domain_guard->getGuardNums($liveuid);
        $userinfo['guard_type']=$guard_info['type'];

		/* 最后拼接1011 防止末尾出现0 */
		$userinfo['sign']=$userinfo['contribution'].'.'.'1011';

		
		unset($userinfo['issuper']);
        
		
		DI()->redis  -> set($token,json_encode($userinfo));
		
        /* 用户列表 */
        $userlists=$this->getUserList($liveuid,$stream);
		
		 /* 用户连麦 */
		$linkmic_uid='0';
		$linkmic_pull='';
		$showVideo=DI()->redis  -> hGet('ShowVideo',$liveuid);
		// file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 liveuid:'.json_encode($liveuid)."\r\n",FILE_APPEND);
		// file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 showVideo:'.json_encode($showVideo)."\r\n",FILE_APPEND);
		if($showVideo){
            $showVideo_a=json_decode($showVideo,true);
			$linkmic_uid=$showVideo_a['uid'];
			$linkmic_pull=$this->getPullWithSign($showVideo_a['pull_url']);
		}
        
        /* 主播连麦 */
        $pkinfo=array(
            'pkuid'=>'0',
            'pkpull'=>'0',
            'ifpk'=>'0',
            'pk_time'=>'0',
            'pk_gift_liveuid'=>'0',
            'pk_gift_pkuid'=>'0',
        );
        $pkuid=DI()->redis  -> hGet('LiveConnect',$liveuid);
        //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 进房间:'."\r\n",FILE_APPEND);
        //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        //file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 liveuid:'.json_encode($liveuid)."\r\n",FILE_APPEND);
        if($pkuid){
            $pkinfo['pkuid']=$pkuid;
            /* 在连麦 */
            $pkpull=DI()->redis  -> hGet('LiveConnect_pull',$pkuid);
            $pkinfo['pkpull']=$this->getPullWithSign($pkpull);
            $ifpk=DI()->redis  -> hGet('LivePK',$liveuid);
            if($ifpk){
                $pkinfo['ifpk']='1';
                $pk_time=DI()->redis  -> hGet('LivePK_timer',$liveuid);
                if(!$pk_time){
                    $pk_time=DI()->redis  -> hGet('LivePK_timer',$pkuid);
                }
                $nowtime=time();
                if($pk_time && $pk_time >0 && $pk_time< $nowtime){
                    $cha=5*60 - ($nowtime - $pk_time);
                    $pkinfo['pk_time']=(string)$cha;
                    
                    $pk_gift_liveuid=DI()->redis  -> hGet('LivePK_gift',$liveuid);
                    if($pk_gift_liveuid){
                        $pkinfo['pk_gift_liveuid']=(string)$pk_gift_liveuid;
                    }
                    $pk_gift_pkuid=DI()->redis  -> hGet('LivePK_gift',$pkuid);
                    if($pk_gift_pkuid){
                        $pkinfo['pk_gift_pkuid']=(string)$pk_gift_pkuid;
                    }
                    
                }else{
                    $pkinfo['ifpk']='0';
                }
            }

        }
		//file_put_contents('./LiveConnect.txt',date('Y-m-d H:i:s').' 提交参数信息 pkinfo:'.json_encode($pkinfo)."\r\n",FILE_APPEND);

		$configpri=getConfigPri();

        
	    $info=array(
			'votestotal'=>NumberFormat($userlists['votestotal']),
			'userlist_time'=>$configpri['userlist_time'],
			'chatserver'=>$configpri['chatserver'],
			'linkmic_uid'=>$linkmic_uid,
			'linkmic_pull'=>$linkmic_pull,
			'nums'=>$userlists['nums'],
			'coin'=>$userinfo['coin'],
			'issuper'=>(string)$issuper,
			'usertype'=>(string)$usertype,
		);
		$info['isattention']=(string)isAttention($uid,$liveuid); //是否关注
		$info['userlists']=$userlists['userlist'];
		
		
		/* 守护 */
        $info['guard']=$guard_info;
        $info['guard_nums']=$guard_nums;
		
		
		 /* 主播连麦/PK */
        $info['pkinfo']=$pkinfo;
		
		
		//获取直播间在售商品的正在展示的商品
		$info['show_goods']=$domain->getLiveShowGoods($liveuid);
		
		
		/*观看直播计时---用于每日任务--记录用户进入时间*/
		$daily_tasks_key='watch_live_daily_tasks_'.$uid;
		$enterRoom_time=time();
		setcaches($daily_tasks_key,$enterRoom_time);
        
        
		$rs['info'][0]=$info;
		return $rs;
	}
	
	/**
     * 连麦信息
     * @desc 用于主播同意连麦 写入redis
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
		 
    public function showVideo() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=$this->uid;
		$token=checkNull($this->token);
		$touid=checkNull($this->touid);
		$pull_url=checkNull($this->pull_url);
		
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 token:'.json_encode($token)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 touid:'.json_encode($touid)."\r\n",FILE_APPEND);
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 pull_url:'.json_encode($pull_url)."\r\n",FILE_APPEND);
        
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
        
        $data=array(
            'uid'=>$touid,
            'pull_url'=>$pull_url,
        );
		
        // file_put_contents('./showVideo.txt',date('Y-m-d H:i:s').' 提交参数信息 set:'.json_encode($data)."\r\n",FILE_APPEND);
        
		DI()->redis  -> hset('ShowVideo',$uid,json_encode($data));
					
        return $rs;
    }	


	/**
	 * 获取直播间用户列表 
	 * @desc 用于直播间获取用户列表
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].userlist 用户列表
	 * @return string info[0].nums 房间人数
	 * @return string info[0].votestotal 主播映票
	 * @return string info[0].guard_type 守护类型
	 * @return string msg 提示信息
	 */
	public function getUserLists() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		$liveuid=$this->liveuid;
		$stream=checkNull($this->stream);
		$p=$this->p;

		/* 用户列表 */ 
		$info=$this->getUserList($liveuid,$stream,$p);

		$rs['info'][0]=$info;

        return $rs;
	}

	protected function getUserList($liveuid,$stream,$p=1) {
		/* 用户列表 */ 
		$n=1;
		$pnum=20;
		$start=($p-1)*$pnum;
		
		$domain_guard = new Domain_Guard();
        
		
		$key="getUserLists_".$stream.'_'.$p;
		$list=getcaches($key);
		if(!$list){ 
            $list=array();

            $uidlist=DI()->redis -> zRevRange('user_'.$stream,$start,$pnum,true);
            
            foreach($uidlist as $k=>$v){
                $userinfo=getUserInfo($k);
                $info=explode(".",$v);
                $userinfo['contribution']=(string)$info[0];
                
				/* 守护 */
                $guard_info=$domain_guard->getUserGuard($k,$liveuid);
                $userinfo['guard_type']=$guard_info['type'];
                
                $list[]=$userinfo;
            }
            
            if($list){
                setcaches($key,$list,5);
            }
		}
        
        if(!$list){
            $list=array();
        }
        
		$nums=DI()->redis->zCard('user_'.$stream);
        if(!$nums){
            $nums=0;
        }

		$rs['userlist']=$list;
		$rs['nums']=(string)$nums;

		/* 主播信息 */
		$rs['votestotal']=NumberFormat(getUserVotesTotal($liveuid));
		

        return $rs; 
    }


    /**
	 * 直播间弹窗信息
	 * @desc 用于直播间弹窗信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].votestotal 票总数
	 * @return string info[0].follows 关注数
	 * @return string info[0].fans 粉丝数
	 * @return string info[0].isattention 是否关注，0未关注，1已关注
	 * @return string info[0].action 操作显示，0表示自己，30表示普通用户，40表示管理员，501表示主播设置管理员，502表示主播取消管理员，60表示超管管理主播 
	 * @return object info[0].user_nicename 用户昵称
	 * @return object info[0].avatar 用户头像
	 * @return object info[0].avatar_thumb 用户小头像
	 * @return object info[0].sex 用户性别
	 * @return object info[0].signature 用户个性签名
	 * @return object info[0].province 用户省份
	 * @return object info[0].city 用户城市
	 * @return object info[0].birthday 用户生日
	 * @return object info[0].age 用户年龄
	 * @return object info[0].issuper 用户是否为超管
	 * @return string info[0].coin 用户的钻石数
	 * @return object info[0].praise 用户视频获赞总数
	 * @return string info[0].workVideos 用户发布视频总数
	 * @return array info[0].likeVideos 用户喜欢视频总数
	 * @return array info[0].vipinfo 用户vip信息
	 * @return array info[0].vipinfo.isvip 是否开通vip
	 * @return array info[0].vipinfo.vip_endtime 用户vip到期时间
	 * @return string msg 提示信息
	 */
	public function getPop() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$liveuid=checkNull($this->liveuid);
		$touid=checkNull($this->touid);

        $isexist=checkUserIsExist($touid);
		if(!$isexist){
			$rs['code']=1002;
			$rs['msg']='用户信息不存在';
			return $rs;
		}

		$info=getUserInfo($touid);
        
		$info['isattention']=(string)isAttention($uid,$touid);
		if($uid==$touid){
			$info['action']='0';
		}else{
			$uid_admin=isAdmin($uid,$liveuid);
			$touid_admin=isAdmin($touid,$liveuid);

			if($uid_admin==40 && $touid_admin==30){ //本人管理员 对方普通用户
				$info['action']='40'; //app【踢人、本场禁言、永久禁言】
			}else if($uid_admin==50 && $touid_admin==30){ //本人主播 对方普通用户
				$info['action']='501'; //app【踢人、本场禁言、永久禁言、设为管理、管理员列表】
			}else if($uid_admin==50 && $touid_admin==40){ //本人主播 对方管理员
				$info['action']='502'; //app【踢人、本场禁言、永久禁言、取消管理、管理员列表】
			}else if($uid_admin==60 && $touid_admin<50){ //本人超管 对方非主播
				$info['action']='40';
			}else if($uid_admin==60 && $touid_admin==50){ //本人超管 对方主播
				$info['action']='60'; //app【关闭直播、禁用直播、禁用账户】
			}else{
				$info['action']='30'; //app【点击他人：用户举报，点击自己：啥都不显示】
			}
			
		}
        
        
		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 赠送礼物 
	 * @desc 用于赠送礼物
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].gifttoken 礼物token
	 * @return string info[0].coin 用户余额
	 * @return string msg 提示信息
	 */
	public function sendGift() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$liveuid=checkNull($this->liveuid);
		$stream=checkNull($this->stream);
		$giftid=checkNull($this->giftid);
		$giftcount=checkNull($this->giftcount);
		$ispack=$this->ispack;
		
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
        
        $domain = new Domain_Live();
        $giftlist=$domain->getGiftList();
        
        $gift_info=array();
        foreach($giftlist as $k=>$v){
            if($giftid == $v['id']){
               $gift_info=$v; 
            }
        }
        if(!$gift_info){
            $rs['code']=1002;
			$rs['msg']='礼物信息不存在';
			return $rs;
        }
		
		if($gift_info['mark']==2){
            /* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info=$domain_guard->getUserGuard($uid,$liveuid);
            if($guard_info['type']!=2){
               $rs['code']=1002;
                $rs['msg']='该礼物是年守护专属礼物奥~';
                return $rs; 
            }
        }
		
        
		$domain = new Domain_Live();

		$result=$domain->sendGift($uid,$liveuid,$stream,$giftid,$giftcount,$ispack);
		
		if($result==1001){
			$rs['code']=1001;
			$rs['msg']='余额不足';
			return $rs;
		}else if($result==1003){
			$rs['code']=1003;
			$rs['msg']='背包中数量不足';
			return $rs;
		}else if($result==1002){
			$rs['code']=1002;
			$rs['msg']='礼物信息不存在';
			return $rs;
		}
		
		$rs['info'][0]['gifttoken']=$result['gifttoken'];
        $rs['info'][0]['coin']=$result['coin'];
        $rs['info'][0]['votestotal']=$result['votestotal'];
		
		unset($result['gifttoken']);

		DI()->redis  -> set($rs['info'][0]['gifttoken'],json_encode($result));
		
		
		return $rs;
	}

	/**
	 * 设置/取消管理员 
	 * @desc 用于设置/取消管理员
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].isadmin 是否是管理员，0表示不是管理员，1表示是管理员
	 * @return string msg 提示信息
	 */
	public function setAdmin() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$liveuid=checkNull($this->liveuid);
		$touid=checkNull($this->touid);
		
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
		
		if($uid!=$liveuid){
			$rs['code'] = 1001;
			$rs['msg'] = '你不是该房间主播，无权操作';
			return $rs;
		}
		
		$domain = new Domain_Live();
		$info=$domain->setAdmin($liveuid,$touid);
		
		if($info==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '操作失败，请重试';
			return $rs;
		}else if($info==1004){
			$rs['code'] = 1004;
			$rs['msg'] = '最多设置5个管理员';
			return $rs;
		}else if($info==1005){
			$rs['code'] = 1005;
			$rs['msg'] = $Think.\lang('USER_NOT_EXIST');
			return $rs;
		}
		
		$rs['info'][0]['isadmin']=(string)$info;
		return $rs;
	}

	/**
	 * 管理员列表 
	 * @desc 用于获取管理员列表
	 * @return int code 操作码，0表示成功
	 * @return array info 管理员列表
	 * @return array info[0]['list'] 管理员列表
	 * @return array info[0]['list'][].userinfo 用户信息
	 * @return string info[0]['nums'] 当前人数
	 * @return string info[0]['total'] 总数
	 * @return string msg 提示信息
	 */
	public function getAdminList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$liveuid=checkNull($this->liveuid);
		$domain = new Domain_Live();
		$info=$domain->getAdminList($liveuid);
		
		$rs['info'][0]=$info;
		return $rs;
	}

	/**
	 * 获取举报类型列表
	 * @desc 用于获取举报类型列表
	 * @return int code 操作码，0表示成功
	 * @return array info 列表
	 * @return string info[].name 类型名称
	 * @return string msg 提示信息
	 */
	public function getReportClass() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());

		$domain = new Domain_Live();
		$info=$domain->getReportClass();

		
		$rs['info']=$info;
		return $rs;
	}


	/**
	 * 直播间用户举报 
	 * @desc 用于直播间用户举报
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 举报成功
	 * @return string msg 提示信息
	 */
	public function setReport() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$touid=checkNull($this->touid);
		$content=checkNull($this->content);

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
		
		if(!$content){
			$rs['code'] = 1001;
			$rs['msg'] = '举报内容不能为空';
			return $rs;
		}

		$isexist=checkUserIsExist($touid);
		if(!$isexist){
			$rs['code'] = 1003;
			$rs['msg'] = '被举报用户不存在';
			return $rs;
		}
		
		$domain = new Domain_Live();
		$info=$domain->setReport($uid,$touid,$content);
		if($info===false){
			$rs['code'] = 1002;
			$rs['msg'] = '举报失败，请重试';
			return $rs;
		}
		
		$rs['info'][0]['msg']="举报成功";
		return $rs;
	}

	/**
	 * 获取用户余额 
	 * @desc 用于获取用户余额
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].coin 余额
	 * @return string msg 提示信息
	 */
	public function getCoin() {
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
		
		
		$coin=getUserCoin($uid);

		$rs['info'][0]['coin']=$coin;
		return $rs;
	}

	/**
     * 直播间禁言
     * @desc 用于 直播间禁言操作
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
		 
    public function setShutUp() { 
        $rs = array('code' => 0, 'msg' => '禁言成功', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$liveuid=checkNull($this->liveuid);
		$touid=checkNull($this->touid);
		$type=checkNull($this->type);
		$stream=checkNull($this->stream);

		$checkToken = checkToken($uid,$token);
		if($checkToken==700){
			$rs['code']=700;
			$rs['msg']='token已过期，请重新登陆';
			return $rs;
		}else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }
						
        $uidtype = isAdmin($uid,$liveuid);

		if($uidtype==30 ){
			$rs["code"]=1001;
			$rs["msg"]='无权操作';
			return $rs;									
		}

        $touidtype = isAdmin($touid,$liveuid);
		
		if($touidtype==60){
			$rs["code"]=1001;
			$rs["msg"]='对方是超管，不能禁言';
			return $rs;	
		}

		if($uidtype==40){ //用户是房间管理员

			if( $touidtype==50){

				$rs["code"]=1002;
				$rs["msg"]='对方是主播，不能禁言';
				return $rs;		
			}

			if($touidtype==40 ){
				$rs["code"]=1002;
				$rs["msg"]='对方是管理员，不能禁言';
				return $rs;		
			}
			
			/* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info=$domain_guard->getUserGuard($touid,$liveuid);

            if($uid != $liveuid && $guard_info && $guard_info['type']==2){
                $rs["code"]=1004;
                $rs["msg"]='对方是尊贵守护，不能禁言';
                return $rs;	
            }
            		
		}


		$showid=0;
        if($type ==1 || $stream){
            $showid=1;
        }
        $domain = new Domain_Live();
		$result = $domain->setShutUp($uid,$liveuid,$touid,$showid);
        
        if($result==1002){
            $rs["code"]=1003;
            $rs["msg"]='对方已被禁言';
            return $rs;	
            
        }else if(!$result){
            $rs["code"]=1005;
            $rs["msg"]='操作失败，请重试';
            return $rs;	
        }
        
        DI()->redis -> hSet($liveuid . 'shutup',$touid,1);
        
        return $rs;
    }

    /**
	 * 直播间踢人 
	 * @desc 用于直播间踢人
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].msg 踢出成功
	 * @return string msg 提示信息
	 */
	public function kicking() {
		$rs = array('code' => 0, 'msg' => '踢人成功', 'info' => array());
		
		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$liveuid=checkNull($this->liveuid);
		$touid=checkNull($this->touid);
		
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

		$uidtype=isAdmin($uid,$liveuid);

		if($uidtype==30){
			$rs['code']=1001;
			$rs['msg']='无权操作';
			return $rs;
		}

		$touidtype=isAdmin($touid,$liveuid);
		
		if($touidtype==60){
			$rs["code"]=1002;
			$rs["msg"]='对方是超管，不能被踢出';
			return $rs;
		}
		
		if($uidtype!=60){

			if($touidtype==50 ){
				$rs['code']=1001;
				$rs['msg']='对方是主播，不能被踢出';
				return $rs;
			} 
            
            if($touidtype==40 ){
				$rs['code']=1002;
				$rs['msg']='对方是管理员，不能被踢出';
				return $rs;
			}
			
			/* 守护 */
            $domain_guard = new Domain_Guard();
            $guard_info=$domain_guard->getUserGuard($touid,$liveuid);

            if($uid != $liveuid && $guard_info && $guard_info['type']==2){
                $rs["code"]=1004;
                $rs["msg"]='对方是尊贵守护，不能被踢出';
                return $rs;	
            }          
                     
		}		
        
        $domain = new Domain_Live();
        
		$result = $domain->kicking($uid,$liveuid,$touid);
        if($result==1002){
            $rs["code"]=1005;
			$rs["msg"]='对方已被踢出';
			return $rs;
        }else if(!$result){
            $rs["code"]=1006;
			$rs["msg"]='操作失败，请重试';
			return $rs;
        }

		$rs['info'][0]['msg']='踢出成功';
		return $rs;
	}


	/**
     * 超管关播
     * @desc 用于超管关播
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string info[0].msg 提示信息 
     * @return string msg 提示信息
     */
		
	public function superStopRoom(){

		$rs = array('code' => 0, 'msg' => '关闭成功', 'info' =>array());

		$uid=checkNull($this->uid);
		$token=checkNull($this->token);
		$liveuid=checkNull($this->liveuid);
		$type=checkNull($this->type);

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

        $issuper=isSuper($uid);
        if(!$issuper){
        	$rs['code'] = 1001;
            $rs['msg'] = '你不是超管，无权操作';
            return $rs;
        }
		
		$domain = new Domain_Live();
		
		$result = $domain->superStopRoom($uid,$token,$liveuid,$type);

		if($result==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该主播已被禁播';
            return $rs;
		}
		$rs['info'][0]['msg']='关闭成功';
 
    	return $rs;
	}


	/**
	 * 检测房间状态 
	 * @desc 用于主播实时检测房间状态
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[0].status 状态 0关闭1直播中
	 * @return string msg 提示信息
	 */
	public function checkLiveing() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		
		$uid=checkNull($this->uid);
		$stream=checkNull($this->stream);
		
		//file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 uid:'.json_encode($uid)."\r\n",FILE_APPEND);
		//file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 stream:'.json_encode($stream)."\r\n",FILE_APPEND);
        
		$domain2 = new Domain_Live();
		$info=$domain2->checkLiveing($uid,$stream);
        
        //file_put_contents(API_ROOT.'/Runtime/checkLiveing_'.date('Y-m-d').'.txt',date('Y-m-d H:i:s').' 提交参数信息 info:'.json_encode($info)."\r\n",FILE_APPEND);

		$rs['info'][0]['status']=$info;
		return $rs;
	}
	
	
	/**
	 * 用户分享直播间
	 * @desc 用于每日任务统计分享次数
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function shareLiveRoom(){
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
        
		$data=[
			'type'=>'5',
			'nums'=>'1',

		];
		dailyTasks($uid,$data);

		return $rs;	
	}
	
	
	/**
     * 获取最新流地址
     * @desc 用于连麦获取最新流地址
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string msg 提示信息
     */
		 
    protected function getPullWithSign($pull) {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		
        if($pull==''){
            return '';
        }
		$list1 = preg_split ('/\?/', $pull);
        $originalUrl=$list1[0];
        
        $list = preg_split ('/\//', $originalUrl);
        $url = preg_split ('/\./', end($list));
        
        $stream=$url[0];

        $play_url=PrivateKeyA('rtmp',$stream,0);
					
        return $play_url;
    }

    /**
	 * 直播间在售商品列表是否正在展示状态
	 * @desc 用于主播改变直播间在售商品列表是否正在展示状态
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 * @return int info[0]['status'] 商品是否展示 0 不展示 1 展示
	 * @return int info[0]['goods_type'] 商品类型 0 站内商品 1 站外商品
	 */
	public function setLiveGoodsIsShow(){
		$rs = array('code' => 0, 'msg' => $Think.\lang('SET_SUCCESSFULLYf'), 'info' => array());
		
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $goodsid=checkNull($this->goodsid);

        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}

		$domain=new Domain_Live();
		$res=$domain->setLiveGoodsIsShow($uid,$goodsid);
		if($res==1001){
			$rs['code'] = 1001;
			$rs['msg'] = '商品不存在';
			return $rs;
		}else if($res==1002){
			$rs['code'] = 1002;
			$rs['msg'] = '商品不可售';
			return $rs;
		}else if($res==1003){
			$rs['code'] = 1003;
			$rs['msg'] = '商品取消展示失败';
			return $rs;
		}else if($res==1004){
			$rs['code'] = 1004;
			$rs['msg'] = '商品设置展示失败';
			return $rs;
		}

		$rs['info'][0]=$res;

		return $rs;	
	}

	/**
     * 根据直播分类获取直播列表
     * @desc 根据直播分类获取直播列表
     * @return int code 操作码 0表示成功
     * @return string msg 提示信息 
     * @return array info
     * @return string info[].uid 主播id
     * @return string info[].avatar 主播头像
     * @return string info[].avatar_thumb 头像缩略图
     * @return string info[].user_nicename 直播昵称
     * @return string info[].title 直播标题
     * @return string info[].city 主播位置
     * @return string info[].stream 流名
     * @return string info[].pull 播流地址
     * @return string info[].nums 人数
     * @return string info[].distance 距离
     * @return string info[].thumb 直播封面
     * @return string info[].level_anchor 主播等级
     * @return string info[].type 直播类型
     * @return string info[].goodnum 靓号
     **/
    
    public function getClassLive(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $liveclassid=checkNull($this->liveclassid);
        $p=checkNull($this->p);
        
        if(!$liveclassid){
            return $rs;
        }
        $domain=new Domain_Live();
        $res=$domain->getClassLive($liveclassid,$p);

        $rs['info']=$res;
        return $rs;
    }

    /**
	 * 用户离开直播间
	 * @desc 用于每日任务统计用户观看时长
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function signOutWatchLive(){
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
        $token=checkNull($this->token);


        $checkToken=checkToken($uid,$token);
		if($checkToken==700){
			$rs['code'] = $checkToken;
			$rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
			return $rs;
		}

		$type='1';  //用户观看直播间时长任务
		/*观看直播计时---每日任务--取出用户进入时间*/
		$key='watch_live_daily_tasks_'.$uid;
		$starttime=getcaches($key);
		if($starttime){ 
			$endtime=time();  //当前时间
			$data=[
				'type'=>$type,
				'starttime'=>$starttime,
				'endtime'=>$endtime,
			];
			
			dailyTasks($uid,$data);
			
			//删除当前存入的时间
			delcache($key);
		}

		return $rs;	
	}

    
}

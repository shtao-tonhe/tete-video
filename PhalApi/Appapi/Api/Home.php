<?php

class Api_Home extends PhalApi_Api {  

	public function getRules() {
		return array(
			'search' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '用户ID'),
				'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
			),

            'videoSearch' => array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
                'key' => array('name' => 'key', 'type' => 'string', 'default'=>'' ,'desc' => '关键词'),
                'p' => array('name' => 'p', 'type' => 'int', 'default'=>'1' ,'desc' => '页数'),
            ),

		);
	}
	
    /**
     * 获取配置信息
     * @desc 用于获取配置信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0] 配置信息
     * @return string msg 提示信息
     */
    public function getConfig() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$configpri = getConfigPri();
        $info = getConfigPub();
        $info['private_letter_switch']=$configpri['private_letter_switch']; //未关注时可发送私信开关
        $info['private_letter_nums']=$configpri['private_letter_nums']; //未关注时可发送私信条数
        $info['video_audit_switch']=$configpri['video_audit_switch']; //视频审核是否开启
        

        /* 引导页 */
        $domain = new Domain_Guide();
		$guide_info = $domain->getGuide();
        
        $info['guide']=$guide_info;

        $liveclass = getLiveClass();

        array_unshift($liveclass,['id'=>'0','name'=>$info['recommend_classname'],'des'=>'','list_order'=>'0']);

        $info['liveclass']=$liveclass;

        unset($info['skin_whiting']);
        unset($info['skin_smooth']);
        unset($info['skin_tenderness']);
        unset($info['eye_brow']);
        unset($info['big_eye']);
        unset($info['eye_length']);
        unset($info['eye_corner']);
        unset($info['eye_alat']);
        unset($info['face_lift']);
        unset($info['face_shave']);
        unset($info['mouse_lift']);
        unset($info['nose_lift']);
        unset($info['chin_lift']);
        unset($info['forehead_lift']);
        unset($info['lengthen_noseLift']);
        unset($info['brightness']);

        $info['shop_system_name']=$configpri['shop_system_name']; //系统店铺名称
        $info['qiniu_domain']=$configpri['qiniu_protocol'].'://'.$configpri['qiniu_domain'].'/';//七牛云存储空间地址
        
        $rs['info'][0] = $info;

        return $rs;
    }	

    /**
     * 登录方式开关信息
     * @desc 用于获取登录方式开关信息
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return array info[0].login_type 开启的登录方式
     * @return string info[0].login_type.login_qq qq登录，0表示关闭，1表示开启
     * @return string info[0].login_type.login_wx 微信登录，0表示关闭，1表示开启
     * @return array info[0].login_alert 登录弹窗信息
     * @return array info[0].login_alert['title'] 登录弹窗标题
     * @return array info[0].login_alert['content'] 登录弹窗协议内容
     * @return array info[0].login_alert['login_title'] 登录页底部提示信息
     * @return array info[0].login_alert['message'] 登录页信息
     * @return array info[0].login_alert['message'][]['title'] 登录页弹窗信息标题
     * @return array info[0].login_alert['message'][]['url'] 登录页弹窗信息链接
     * @return string msg 提示信息
     */
    public function getLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $info = getConfigPub();

        //登录弹框那个地方
        $login_alert=array(
            'title'=>$info['login_alert_title'],
            'content'=>$info['login_alert_content'],
            'login_title'=>$info['login_clause_title'],
            'message'=>array(
                array(
                    'title'=>$info['login_service_title'],
                    'url'=>get_upload_path($info['login_service_url']),
                ),
                array(
                    'title'=>$info['login_private_title'],
                    'url'=>get_upload_path($info['login_private_url']),
                ),
            )
        );


        $login_type=$info['login_type'];

        foreach ($login_type as $k => $v) {
            if($v=='ios'){
                unset($login_type[$k]);
                break;
            }
        }

        $login_type=array_values($login_type);

        $configpri=getConfigPri();

        $sendcode_type='0'; //获取短信验证码方式 0国内 1 国外【用于登录或忘记密码时是否选择国家代号】
        $typecode_switch=$configpri['code_switch'];

        if($typecode_switch==1){ //阿里云

            $aly_sendcode_type=$configpri['aly_sendcode_type'];
            if($aly_sendcode_type==2){ //国外
                $sendcode_type='1';
            }
        }else if($typecode_switch==3){ //腾讯云
            
            $tencent_sendcode_type=$configpri['tencent_sendcode_type'];
            if($tencent_sendcode_type==2){ //国外
                $sendcode_type='1';
            }
        }

        $rs['info'][0]['login_alert'] = $login_alert;
        $rs['info'][0]['login_type'] = $login_type;
        $rs['info'][0]['login_type_ios'] = $info['login_type'];
        $rs['info'][0]['sendcode_type']=$sendcode_type;

        return $rs;
    }		
	
	
		
	/**
     * 首页搜索会员
     * @desc 用于首页搜索会员
     * @return int code 操作码，0表示成功
     * @return array info 会员列表
     * @return string info[].id 用户ID
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 头像
     * @return string info[].sex 性别
     * @return string info[].signature 签名
     * @return string info[].level 等级
     * @return string info[].isattention 是否关注，0未关注，1已关注
     * @return string msg 提示信息
     */
    public function search() {

        $rs = array('code' => 0, 'msg' => '', 'info' => array());

		$isBlackUser=isBlackUser($this->uid);

		 if($isBlackUser=='0'){
			$rs['code'] = 700;
			$rs['msg'] = '该账号已被禁用';
			return $rs;
		}
		
		$uid=checkNull($this->uid);
		$key=checkNull($this->key);
		$p=checkNull($this->p);
		if($key==''){
			$rs['code'] = 1001;
			$rs['msg'] = "请填写关键词";
			return $rs;
		}

		
		if(!$p){
			$p=1;
		}

        
		
        $domain = new Domain_Home();
        $info = $domain->search($uid,$key,$p);
        
        $rs['info'] = $info;

        return $rs;
    }	
		
	

    /**
     * 视频搜索
     * @desc 视频搜索
     * @return int code 状态码 0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return 
     */
    public function videoSearch(){


        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $isBlackUser=isBlackUser($this->uid);


         if($isBlackUser=='0'){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

  
        
        $uid=checkNull($this->uid);
        $key=checkNull($this->key);
        $p=checkNull($this->p);
        if($key==''){
            $rs['code'] = 1001;
            $rs['msg'] = "请填写关键词";
            return $rs;
        }
        
        if(!$p){
            $p=1;
        }

        $key1='videoSearch'.'_'.$key.'_'.$p;

        $info=getcache($key1);
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->videoSearch($uid,$key,$p);
            setcaches($key1,$info,2);
        }
        
        $rs['info'] = $info;

        return $rs;
    }

    /**
     * 获取本周内累计观众人数前20名的主播榜单
     * @desc 用于获取本周内累计观众人数前20名的主播榜单
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[].uid 用户ID
     * @return string info[].total 总用户数
     * @return string info[].user_nicename 用户昵称
     * @return string info[].avatar 用户头像
     * @return string info[].stream 直播间流名
     * @return string info[].islive 是否在直播 0否 1是
     */
    public function getWeekShowLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());

        $key='weekshowlists';

        $info=getcache($key);
        if(!$info){
            $domain = new Domain_Home();
            $info = $domain->getWeekShowLists();
            setcaches($key,$info,2);
        }
        
        $rs['info'] = $info;

        return $rs;
    }
	
	
} 

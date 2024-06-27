<?php
session_start();
class Api_Login extends PhalApi_Api { 
	public function getRules() {
        return array(
			'userLogin' => array(
                'user_login' => array('name' => 'user_login', 'type' => 'string', 'desc' => '账号'),
				'code' => array('name' => 'code', 'type' => 'string', 'require' => true,   'desc' => '验证码'),
				'source' => array('name' => 'source', 'type' => 'string',  'desc' => '注册来源android/ios'),
				'mobileid' => array('name' => 'mobileid', 'type' => 'string',  'desc' => '手机设备号'),
            ),
			
			
			'userLoginByThird' => array(
                'openid' => array('name' => 'openid', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方openid'),
                'type' => array('name' => 'type', 'type' => 'string', 'min' => 1, 'require' => true,   'desc' => '第三方标识'),
                'nicename' => array('name' => 'nicename', 'type' => 'string',   'default'=>'',  'desc' => '第三方昵称'),
                'avatar' => array('name' => 'avatar', 'type' => 'string',  'default'=>'', 'desc' => '第三方头像'),
                'source' => array('name' => 'source', 'type' => 'string',  'desc' => '注册来源android/ios'),
                'mobileid' => array('name' => 'mobileid', 'type' => 'string',  'desc' => '手机设备号'),
            ),
			

			'getLoginCode' => array(
                'country_code' => array('name' => 'country_code', 'type' => 'int','default'=>'86', 'require' => true,  'desc' => '国家代号'),
				'mobile' => array('name' => 'mobile', 'type' => 'string', 'min' => 1, 'require' => true,  'desc' => '手机号'),
                'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string',  'default'=>'', 'desc' => '签名'),
			),

			'upUserPush'=>array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'pushid' => array('name' => 'pushid', 'type' => 'string', 'desc' => '极光ID'),
			),

			'getCancelCondition'=>array(
            	'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
            ),

            'cancelAccount'=>array(
                'uid' => array('name' => 'uid', 'type' => 'int', 'min' => 1, 'require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string', 'require' => true, 'desc' => '用户Token'),
                'time' => array('name' => 'time', 'type' => 'string', 'desc' => '时间戳'),
                'sign' => array('name' => 'sign', 'type' => 'string', 'desc' => '签名'),
            ),
            'getCountrys'=>array(
                'field' => array('name' => 'field', 'type' => 'string', 'default'=>'', 'desc' => '搜索json串'),
            ),
        );
	}
	
    /**
     * 会员登录
     * @desc 用于用户登陆
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function userLogin() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$user_login=checkNull($this->user_login);
		$code=checkNull($this->code);
		$source=checkNull($this->source);
		$mobileid=checkNull($this->mobileid);

        if(!$user_login){
            $rs['code']=1001;
            $rs['msg']='请填写手机号';
            return $rs;
        }

        $ismobile=checkMobile($user_login);
        if(!$ismobile){
            $rs['code']=1001;
            $rs['msg']='请输入正确的手机号';
            return $rs; 
        }

		if($code==''){
			$rs['code'] = 1001;
            $rs['msg'] = '请填写验证码';
             return $rs;
		}

		if($mobileid==''){
			$rs['code'] = 1001;
            $rs['msg'] = '缺少设备码';
             return $rs;
		}

		if(!$_SESSION['login_mobile']){
			$rs['code'] = 1001;
            $rs['msg'] = '请获取验证码';
            return $rs;
		}

		if($user_login!=$_SESSION['login_mobile']){
			$rs['code'] = 1001;
            $rs['msg'] = '手机号码错误';
            return $rs;
		}
		if($code!=$_SESSION['login_mobile_code']){
			$rs['code'] = 1001;
            $rs['msg'] = '验证码错误';
            return $rs;
		}

        $domain = new Domain_Login();
        $info = $domain->userLogin($user_login,$source,$mobileid);

        if($info==1001){

			$rs['code'] = 1001;
            $rs['msg'] = '同一设备同一IP下注册账号过多';
            return $rs;	
		}

		if($info==1002){
			$rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;	
		}

		if($info==1003){
			$rs['code'] = 1003;
            $rs['msg'] = '该账号已注销';
            return $rs;	
		}

        if($info==1004){
            $rs['code'] = 1004;
            $rs['msg'] = '管理员账号无法登陆';
            return $rs; 
        }

       
        $rs['info'][0] = $info;
		
				
		
        return $rs;
    }		
   	

	
    /**
     * 第三方登录
     * @desc 用于用户使用第三方登录系统
     * @return int code 操作码，0表示成功
     * @return array info 用户信息
     * @return string info[0].id 用户ID
     * @return string info[0].user_nicename 昵称
     * @return string info[0].avatar 头像
     * @return string info[0].avatar_thumb 头像缩略图
     * @return string info[0].sex 性别
     * @return string info[0].signature 签名
     * @return string info[0].coin 用户余额
     * @return string info[0].login_type 注册类型
     * @return string info[0].level 等级
     * @return string info[0].province 省份
     * @return string info[0].city 城市
     * @return string info[0].birthday 生日
     * @return string info[0].token 用户Token
     * @return string msg 提示信息
     */
    public function userLoginByThird() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
		$openid=checkNull($this->openid);
		$type=checkNull($this->type);
		$nicename=checkNull($this->nicename);
		$avatar=checkNull($this->avatar);
		$source=checkNull($this->source);
		$mobileid=checkNull($this->mobileid);

		if($mobileid==''){
			$rs['code'] = 1001;
            $rs['msg'] = '缺少设备码';
             return $rs;
		}
		
        $domain = new Domain_Login();
        $info = $domain->userLoginByThird($openid,$type,$nicename,$avatar,$source,$mobileid);
		
        if($info==1001){

			$rs['code'] = 1001;
            $rs['msg'] = '同一设备同一IP下注册账号过多';
            return $rs;	
		}
		
        if($info==1002){
            $rs['code'] = 1002;
            $rs['msg'] = '该账号已被禁用';
            return $rs;					
		}

		if($info==1003){
			$rs['code'] = 1003;
            $rs['msg'] = '该账号已注销';
            return $rs;	
		}

        $rs['info'][0] = $info;

        
        return $rs;
    }



	/**
     * 获取登录短信验证码
     * @desc 用于登录获取短信验证码
     * @return int code 操作码，0表示成功,2发送失败
     * @return array info 
     * @return string msg 提示信息
     */
     
    public function getLoginCode() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $country_code = checkNull($this->country_code);
        $mobile = checkNull($this->mobile);
        $time=checkNull($this->time);
        $sign=checkNull($this->sign);

        if(!$mobile){
            $rs['code']=1001;
            $rs['msg']='请填写手机号';
            return $rs;
        }

        $configpri=getConfigPri();
        $code_switch=$configpri['code_switch'];

        if($code_switch==1){ //阿里云
            $aly_sendcode_type=$configpri['aly_sendcode_type'];
            if($aly_sendcode_type==1){ //国内验证码
                if($country_code!=86){
                    $rs['code']=1001;
                    $rs['msg']='平台只允许选择中国大陆';
                    return $rs;
                }

                $ismobile=checkMobile($mobile);
                if(!$ismobile){
                    $rs['code']=1001;
                    $rs['msg']='请输入正确的手机号';
                    return $rs; 
                }

            }else if($aly_sendcode_type==2){ //海外/港澳台 验证码
                if($country_code==86){
                    $rs['code']=1001;
                    $rs['msg']='平台只允许选择除中国大陆外的国家/地区';
                    return $rs;
                }
            }

        }else if($code_switch==2){ //容联云
            $ismobile=checkMobile($mobile);
            if(!$ismobile){
                $rs['code']=1001;
                $rs['msg']='请输入正确的手机号';
                return $rs; 
            }
        }

        $checkdata=array(
            'mobile'=>$mobile,
            'time'=>$time,
        );
        
        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']='签名错误';
            return $rs; 
        }

        //判断账号是否被注销
        $is_destroy=checkIsDestroyByLogin($mobile);
        if($is_destroy){
            $rs['code']=1001;
            $rs['msg']='该账号已注销';
            return $rs; 
        }

        //验证手机号是否被禁用
        $status=checkMoblieCanCode($mobile);

        if($status==0){
            $rs['code']=1001;
            $rs['msg']='该账号已被禁用';
            return $rs; 
        }

        if($_SESSION['country_code']==$country_code && $_SESSION['login_mobile']==$mobile && $_SESSION['login_mobile_expiretime']> time() ){
            $rs['code']=1002;
            $rs['msg']='验证码5分钟有效，请勿多次发送';
            return $rs;
        }
        
        $limit = ip_limit();    
        if( $limit == 1){
            $rs['code']=1003;
            $rs['msg']='您已当日发送次数过多';
            return $rs;
        }       
        $mobile_code = random(6,1);
        
        /* 发送验证码 */
        $result=sendCode($country_code,$mobile,$mobile_code);
        if($result['code']===0){
            $_SESSION['country_code'] = $country_code;
            $_SESSION['login_mobile'] = $mobile;
            $_SESSION['login_mobile_code'] = $mobile_code;
            $_SESSION['login_mobile_expiretime'] = time() +60*5;

        }else if($result['code']==667){
            $_SESSION['country_code'] = $country_code;
            $_SESSION['login_mobile'] = $mobile;
            $_SESSION['login_mobile_code'] = $result['msg'];
            $_SESSION['login_mobile_expiretime'] = time() +60*5;
            
            $rs['code']=$result['code'];
            $rs['msg']='验证码为：'.$result['msg'];

            return $rs;
        }else{
            $rs['code']=1002;
            $rs['msg']=$result['msg'];

            return $rs;
        }
        
        $rs['msg']=$Think.\lang('SENT_SUCCESSFULLY');
        return $rs;
    }



	/**
	 * 更新极光pushid
	 * @desc 用于更新极光pushid
	 * @return int code 状态码，0表示成功
	 * @return string msg 提示信息
	 * @return array info 返回信息
	 */
	public function upUserPush(){

		$rs = array('code' => 0, 'msg' => '', 'info' => array());
		$uid=checkNull($this->uid);
		$pushid=checkNull($this->pushid);

		$domain=new Domain_Login();
        $domain->upUserPush($uid,$pushid);

        return $rs;
        
	}

	/**
     * 获取注销账号的条件
     * @desc 用于获取注销账号的条件
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0]['list'] 条件数组
     * @return string info[0]['list'][]['title'] 标题
     * @return string info[0]['list'][]['content'] 内容
     * @return string info[0]['list'][]['is_ok'] 是否满足条件 0 否 1 是
     * @return string info[0]['can_cancel'] 是否可以注销账号 0 否 1 是
     */
    public function getCancelCondition(){
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

        $domain=new Domain_Login();
        $res=$domain->getCancelCondition($uid);

        $rs['info'][0]=$res;

        return $rs;
    }

    /**
     * 用户注销账号
     * @desc 用于用户注销账号
     * @return int code 状态码,0表示成功
     * @return string msg 返回提示信息
     * @return array info 返回信息
     */
    public function cancelAccount(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $time=checkNull($this->time);
        $sign=checkNull($this->sign);

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

        if(!$time||!$sign){
            $rs['code'] = 1001;
            $rs['msg'] = $Thin.lang('PARAMENTER_ERROT');
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
            'time'=>$time
        );
        
        $issign=checkSign($checkdata,$sign);
        if(!$issign){
            $rs['code']=1001;
            $rs['msg']='签名错误';
            return $rs; 
        }

        $domain=new Domain_Login();
        $res=$domain->cancelAccount($uid);

        if($res==1001){
        	$rs['code']=1001;
            $rs['msg']='相关内容不符合注销账号条件';
            return $rs;
        }

        $rs['msg']='注销成功,手机号、身份证号等信息已解除';
        return $rs;
    }

    /**
     * 获取国家列表
     * @desc 用于获取国家列表
     * string field 搜索内容
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return string name 国家中文名称
     * @return string name_name 国家英文名称
     * @return string tel 国家区号
     * @return string msg 提示信息
     */
    public function getCountrys() {
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $field=checkNull($this->field);
        
        $key='getCountrys';
        $info=getcaches($key);
        if(!$info){
            $country=API_ROOT.'/../data/config/country.json';
            // 从文件中读取数据到PHP变量 
            $json_string = file_get_contents($country); 
             // 用参数true把JSON字符串强制转成PHP数组 
            $data = json_decode($json_string, true);

            $info=$data['country']; //国家
            
            setcaches($key,$info);
        }
        if($field){
            $rs['info']=$this->country_searchs($field,$info);
            return $rs;
        }
     
        $rs['info']=$info;
        return $rs;
    }

    private function country_searchs($field,$data) {

        $arr=array();
        foreach($data as $k => $v){
        
            $lists=$v['lists'];
        
            foreach ($lists as $k => $v) {
                
                if(strstr($v['name'], $field) !== false){//英文搜索替换为：$v['name_en']
                    
                    array_push($arr, $v);
                }
            }

        
        }
        return $arr;
    }



}

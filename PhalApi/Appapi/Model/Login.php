<?php

class Model_Login extends PhalApi_Model_NotORM {
	
	protected $fields='id,user_nicename,avatar,avatar_thumb,sex,signature,coin,user_status,login_type,province,city,area,birthday,last_login_time,code,age,mobile';

	/* 会员登录 */   	
    public function userLogin($user_login,$source,$mobileid) {

    	$info1=DI()->notorm->user
				->select($this->fields)
				->where('user_login=? and user_type="1"',$user_login) 
				->fetchOne();
		if($info1){
			return 1004;
		}
		
		$info=DI()->notorm->user
				->select($this->fields)
				->where('user_login=? and user_type="2"',$user_login) 
				->fetchOne();

		$now=time();
		$nowYear=date("Y",$now);

		if(!$info){


			//获取vip
			$from_ip= ip2long($_SERVER["REMOTE_ADDR"]);
			//判断是否超过后台的配置数
			$checklimit=checkRegIpLimit($mobileid,$from_ip);
			
			if($checklimit){
				return 1001;
			}


			$birthdayYear=2000;
			
			//新注册该用户
			$user_pass='qwe123';
			$user_pass=setPass($user_pass);
			$user_login=$user_login;

			$nickname='手机用户'.substr($user_login,-4);
			
			$avatar='/default.png';
			$avatar_thumb='/default_thumb.png';

			$code=$this->createCode();
			
			
			//注册奖励
			$configpri=getConfigPri();
			$reg_reward=$configpri['reg_reward'];
			
			
			
			$data=array(
				'user_login' => $user_login,
				'user_nicename' =>$nickname,
				'user_pass' =>$user_pass,
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER['REMOTE_ADDR'],
				'create_time' => time(),
				'user_status' => 1,
				"user_type"=>2,//会员
				"code"=>$code,
				"coin"=>$reg_reward,
				"age"=>$nowYear-$birthdayYear,
				"birthday"=>'2000-01-01',
				"mobile"=>$user_login,
				"login_type"=>'phone',
				"ip"=>$from_ip,
				"mobileid"=>$mobileid,
				"is_firstlogin"=>'1'
			);
            if($source){
                $data['source']=$source;
            }
			
			$rs=DI()->notorm->user->insert($data);	
			
			//注册奖励写入消费记录
			$reg_data=array(
				'type'=>'income',
				'action'=>'reg_reward',
				'uid'=>$rs['id'],
				'touid'=>$rs['id'],
				'totalcoin'=>$reg_reward,
				'addtime'=>time()

			);
			setCoinRecord($reg_data);
		
			$info['id']=$rs['id'];
			$info['user_nicename']=$data['user_nicename'];
			$info['avatar']=get_upload_path($data['avatar']);
			$info['avatar_thumb']=get_upload_path($data['avatar_thumb']);
			$info['sex']='2';
			$info['signature']=$data['signature'];
			$info['coin']=$reg_reward;
			$info['login_type']=$data['login_type'];
			$info['province']='';
			$info['city']='';
			$info['birthday']='';
			$info['last_login_time']='';
			$info['code']=$code;
			$info['age']="0";
			$info['mobile']=$user_login;
			$info['isreg']='1'; //此参数结合后台配置参数agent_must（邀请码是否必填）,如果邀请码非必填时，只有在此参数=1时app端才会弹窗显示邀请码
			$info['hometown']='';
			
		}else{

			//重新计算用户的年龄
			$month=date("m",strtotime($info['birthday']));
			$nowMonth=date("m",$now);
			if($nowMonth>=$month){
				$cha=0;
			}else{
				$cha=1;
			}

			$birthdayYear=date("Y",strtotime($info['birthday']));
			$age=$nowYear-$birthdayYear-$cha;

			DI()->notorm->user->where("id=?",$info['id'])->update(array("age"=>$age));

			if($info['user_status']=='0'){
				return 1002;					
			}

			if($info['user_status']=='3'){
				return 1003;					
			}

			unset($info['user_status']);
			
		
			
			$info['avatar']=get_upload_path($info['avatar']);
			$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
			$info['isreg']='0'; //此参数结合后台配置参数agent_must（邀请码是否必填）,如果邀请码非必填时，只有在此参数=1时app端才会弹窗显示邀请码
			$info['hometown']=$info['province'].$info['city'].$info['area'];

		}

			$token=md5(md5($info['id'].$user_login.time()));
			$info['token']=$token;
			$this->updateToken($info['id'],$token);

			$cache=array("token_".$info['id'],"userinfo_".$info['id']);
			delcache($cache);

		
        return $info;
    }	

		
	/* 第三方会员登录 */
    public function userLoginByThird($openid,$type,$nickname,$avatar,$source,$mobileid) {			
        $info=DI()->notorm->user
            ->select($this->fields)
            ->where('openid=? and login_type=? and user_type="2"',$openid,$type)
            ->fetchOne();
		$configpri=getConfigPri();

		$now=time();
		$nowYear=date("Y",$now);

		if(!$info){


			//获取vip
			$from_ip= ip2long($_SERVER["REMOTE_ADDR"]);
			//判断是否超过后台的配置数
			$checklimit=checkRegIpLimit($mobileid,$from_ip);
			
			if($checklimit){
				return 1001;
			}


			/* 注册 */
			$birthdayYear=2000;
			$user_pass='qwe123';
			$user_pass=setPass($user_pass);
			$user_login=$type.'_'.time().rand(100,999);

			if(!$nickname){
				$nickname=$type.'用户-'.substr($openid,-4);
			}else{
				$nickname=urldecode($nickname);
			}
			if(!$avatar){
				$avatar='/default.png';
				$avatar_thumb='/default_thumb.png';
			}else{
				$avatar=urldecode($avatar);
				$avatar_thumb=$avatar;
			}

			$code=$this->createCode();
			
			//注册奖励
			$configpri=getConfigPri();
			$reg_reward=$configpri['reg_reward'];
			
			
			$data=array(
				'user_login' => $user_login,
				'user_nicename' =>$nickname,
				'user_pass' =>$user_pass,
				'signature' =>'这家伙很懒，什么都没留下',
				'avatar' =>$avatar,
				'avatar_thumb' =>$avatar_thumb,
				'last_login_ip' =>$_SERVER['REMOTE_ADDR'],
				'create_time' => time(),
				'user_status' => 1,
				'openid' => $openid,
				'login_type' => $type, 
				"user_type"=>2,//会员
				"code"=>$code,
				"age"=>$nowYear-$birthdayYear,
				"birthday"=>'2000-01-01',
				"coin"=>$reg_reward,
				"ip"=>$from_ip,
				"mobileid"=>$mobileid

			);
            if($source){
                $data['source']=$source;
            }
			
			$rs=DI()->notorm->user->insert($data);	


			//注册奖励写入消费记录
			$reg_data=array(
				'type'=>'income',
				'action'=>'reg_reward',
				'uid'=>$rs['id'],
				'touid'=>$rs['id'],
				'totalcoin'=>$reg_reward,
				'addtime'=>time()
			);
			setCoinRecord($reg_data);

			
		
			$info['id']=$rs['id'];
			$info['user_nicename']=$data['user_nicename'];
			$info['avatar']=get_upload_path($data['avatar']);
			$info['avatar_thumb']=get_upload_path($data['avatar_thumb']);
			$info['sex']='2';
			$info['signature']=$data['signature'];
			$info['coin']=$reg_reward;
			$info['login_type']=$data['login_type'];
			$info['province']='';
			$info['city']='';
			$info['birthday']='';
			$info['consumption']='0';
			$info['user_status']=1;
			$info['last_login_time']='';
			$info['isreg']='1';  //此参数结合后台配置参数agent_must（邀请码是否必填）,如果邀请码非必填时，只有在此参数=1时app端才会弹窗显示邀请码
		}else{

			if($info['user_status']=='0'){
				return 1002;					
			}

			if($info['user_status']=='3'){
				return 1003;					
			}

			//重新计算用户的年龄
			$month=date("m",strtotime($info['birthday']));
			$nowMonth=date("m",$now);
			if($nowMonth>=$month){
				$cha=0;
			}else{
				$cha=1;
			}

			$birthdayYear=date("Y",strtotime($info['birthday']));
			$age=$nowYear-$birthdayYear-$cha;

			DI()->notorm->user->where("id=?",$info['id'])->update(array("age"=>$age));

			$info['isreg']='0'; //此参数结合后台配置参数agent_must（邀请码是否必填）,如果邀请码非必填时，只有在此参数=1时app端才会弹窗显示邀请码

		}
		
		
		unset($info['user_status']);

		unset($info['last_login_time']);
		
		$token=md5(md5($info['id'].$openid.time()));
		
		$info['token']=$token;
		$info['avatar']=get_upload_path($info['avatar']);
		$info['avatar_thumb']=get_upload_path($info['avatar_thumb']);
		
		$this->updateToken($info['id'],$token);
		
		$cache=array("token_".$info['id'],"userinfo_".$info['id']);
		delcache($cache);
        return $info;
    }		
	
	/* 更新token 登陆信息 */
    public function updateToken($uid,$token) {
		$expiretime=time()+60*60*24*300;
		$nowtime=time();
		
        DI()->notorm->user
			->where('id=?',$uid)
            ->update(array('last_login_time' => time(), "last_login_ip"=>$_SERVER['REMOTE_ADDR'] ));

        $isok=DI()->notorm->user_token
			->where('user_id=?',$uid)
			->update(array("token"=>$token, "expire_time"=>$expiretime ,'create_time' => $nowtime ));

		if(!$isok){
            DI()->notorm->user_token
			->insert(array("user_id"=>$uid,"token"=>$token, "expire_time"=>$expiretime ,'create_time' => $nowtime ));
        }

        $token_info=array(
        	'uid'=>$uid,
			'token'=>$token,
			'expire_time'=>$expiretime,
        );

        setcaches("token_".$uid,$token_info);

		return 1;
    }	
	
	/* 生成邀请码 */
	public function createCode(){
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
			$oneinfo=DI()->notorm->user
					->select("id")
					->where('code=?',$d)
					->fetchOne();
			if(!$oneinfo){
				return $d;
			}
		}
        $d=$this->createCode();
		return $d;
	}

	/* 更新极光ID */
    public function upUserPush($uid,$pushid){
        
        $isexist=DI()->notorm->user_pushid
                    ->select('*')
                    ->where('uid=?',$uid)
                    ->fetchOne();
        if(!$isexist){
            DI()->notorm->user_pushid->insert(array('uid'=>$uid,'pushid'=>$pushid));
        }else if($isexist['pushid']!=$pushid){
            DI()->notorm->user_pushid->where('uid=?',$uid)->update(array('pushid'=>$pushid));
        }
        return 1;
    }


    //获取注销账号条件
    public function getCancelCondition($uid){
    	$res=array('list'=>array(),'can_cancel'=>'0');
    	$list=array(
    		'0'=>array(
    				'title'=>'1、账号内无大额未消费或未提现的财产',
    				'content'=>'你账号内无未结清的欠款、资金和虚拟权益，无正在处理的提现记录；注销后，账户中的虚拟权益等将作废无法恢复。',
    				'is_ok'=>'0'
    			),
    		
    	);

    	//获取用户的映票、钻石、余额
    	$userinfo=DI()->notorm->user->where("id=?",$uid)->select("coin,votes")->fetchOne();

    	//获取用户映票提现未处理记录
    	$votes_cashlist=DI()->notorm->user_cashrecord->where("uid=? and status=0",$uid)->fetchAll();


    	//钻石小于100，映票小于100，余额为0
    	if($userinfo['coin']<100 && $userinfo['votes']<100  && !$votes_cashlist ){
    		$list[0]['is_ok']='1';
    	}

    	if($list[0]['is_ok']==1){
    		$res['can_cancel']='1';
    	}

    	$res['list']=$list;

    	return $res;
    }

    //注销账号
    public function cancelAccount($uid){

    	

    	$condition=$this->getCancelCondition($uid);

    	if(!$condition['can_cancel']){
    		return 1001;
    	}

		//修改用户昵称
		DI()->notorm->user->where("id=?",$uid)->update(array('user_nicename'=>'用户已注销','user_status'=>3));
		//未审核的视频改为拒绝
		DI()->notorm->user_video->where("uid=? and status=0",$uid)->update(array('status'=>2));
		//上架的视频改为下架
		DI()->notorm->user_video->where("uid=? and status=1 and isdel=0",$uid)->update(array('isdel'=>1));
		//视频绑定的商品下架
		DI()->notorm->shop_goods->where("uid=?",$uid)->update(array("status"=>'-1'));
		//删除粉丝和关注
		DI()->notorm->user_attention->where("uid=? or touid=?",$uid,$uid)->delete();
		//删除用户pushid
		DI()->notorm->user_pushid->where("uid=?",$uid)->delete();
		//删除极光用户信息
		delIM($uid);

        delcache("userinfo_".$uid);
		return 1;

    }

}

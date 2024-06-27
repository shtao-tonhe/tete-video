<?php
/**
 * 消息
 *
 * @author: sunny
 */

class Api_Message extends PhalApi_Api {

	public function getRules() {
        return array(
            'fansLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'), 
            ),
            
            'praiseLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),

            'atLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'commentLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'officialLists'=>array(
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'systemnotifyLists'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),
            'getShopOrderList'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true, 'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
                'p' => array('name' => 'p', 'type' =>'int','min' => 1,'default'=>1,'desc' => '页数'),
            ),

            'getLastTime'=>array(
                'uid'=>array('name' => 'uid', 'type' => 'int','require' => true,'desc' => '用户ID'),
                'token' => array('name' => 'token', 'type' => 'string',  'require' => true, 'desc' => '用户Token'),
            ),
            
        );
	}
	
	
    /**
     * 获取粉丝关注信息列表
     * @desc 用于获取粉丝关注信息列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].addtime 关注时间
     * @return string info[0].isattention 当前用户是否关注了粉丝用户
     * @return array info[0].userinfo 粉丝用户信息
     * @return array info[0].userinfo.id 粉丝用户id
     * @return array info[0].userinfo.user_nicename 粉丝用户昵称
     * @return array info[0].userinfo.avatar 粉丝用户头像
     * @return array info[0].userinfo.age 粉丝用户年龄
     * @return array info[0].userinfo.praise 粉丝用户发布视频的被点赞总数
     * @return array info[0].userinfo.fans 粉丝用户的粉丝总数
     * @return array info[0].userinfo.follows 粉丝用户关注的用户总数
     * @return array info[0].userinfo.workVideos 粉丝用户发布的视频总数
     * @return array info[0].userinfo.likeVideos 粉丝用户喜欢的视频总数
     */
    public function fansLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

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

        $key='message_fansLists_'.$uid.'_'.$p;
        $info=getcache($key);
        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->fansLists($uid,$p);
            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无粉丝列表";
                return $rs;
            }
            
            setcaches($key,$info,2);
  
        }
        
        $rs['info']=$info;

        return $rs;

    }

    /**
     * 获取赞的列表（评论和视频获赞）
     * @desc 用于获取赞的列表（赞的评论和视频）
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回数据
     * @return string info[0].addtime 关注时间
     * @return string info[0].isattention 当前用户是否关注了粉丝用户
     * @return array info[0].userinfo 粉丝用户信息
     * @return array info[0].userinfo.id 粉丝用户id
     * @return array info[0].userinfo.user_nicename 粉丝用户昵称
     * @return array info[0].userinfo.avatar 粉丝用户头像
     * @return array info[0].userinfo.age 粉丝用户年龄
     * @return array info[0].userinfo.praise 粉丝用户发布视频的被点赞总数
     * @return array info[0].userinfo.fans 粉丝用户的粉丝总数
     * @return array info[0].userinfo.follows 粉丝用户关注的用户总数
     * @return array info[0].userinfo.workVideos 粉丝用户发布的视频总数
     * @return array info[0].userinfo.likeVideos 粉丝用户喜欢的视频总数
     */
    public function praiseLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

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

        $key='message_praiseLists_'.$uid.'_'.$p;
        $info=getcache($key);
        $info=false;
        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->praiseLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无获赞列表";
                return $rs;
            }

            setcaches($key,$info,2);
        }
        

        $rs['info']=(array)$info;

        return $rs;
    }

    /**
     * 获取用户被@的信息（发表评论时的@信息和视频帮上热门的信息）
     * @desc 用于获取用户被@的信息
     * @return int code 状态码，0表示成功
     * @return sring msg 提示信息
     * @return array info 返回信息
     * @return int info[0].uid 主动@其他人的id
     * @return int info[0].videoid 视频id
     * @return int info[0].touid 被@人的id
     * @return string info[0].addtime 添加时间
     * @return string info[0].avatar 主动@其他人的头像
     * @return string info[0].user_nicename 主动@其他人的昵称
     * @return string info[0].video_title 视频标题
     */
    public function atLists(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

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

        $key='message_atLists_'.$uid.'_'.$p;
        $info=getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->atLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            setcaches($key,$info,2);

        }

        

        $rs['info']=$info;

        return $rs;
    }

    /**
     * 获取评论信息列表
     * @desc 用于获取评论信息列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0].avatar 用户头像
     * @return string info[0].user_nicename 用户昵称
     * @return string info[0].video_title 视频标题
     * @return string info[0].video_thumb 视频封面
     * @return string info[0].addtime 添加时间
     * @return string info[0].videouid 视频发布者id
     * @return string info[0].content 视频评论内容
     */
    public function commentLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $key='message_commentLists_'.$uid.'_'.$p;

        $info=getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->commentLists($uid,$p);
            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            setcaches($key,$info,2);
        }
        
        $rs['info']=$info;

        return $rs;
    }


    /**
     * 官方通知列表
     * @desc 用于获取官方通知列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0].id 通知信息的id
     * @return string info[0].title 通知信息的标题
     * @return string info[0].synopsis 通知信息的简介
     * @return string info[0].url 通知信息的地址
     * @return string info[0].addtime 通知信息的添加时间
     */
    public function officialLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $p=checkNull($this->p);

        $key='message_officialLists_'.$p;

        $info=getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->officialLists($p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            setcaches($key,$info,2);

        }

        $rs['info']=$info;

        return $rs;
    }

    /**
     * 系统通知列表
     * @desc 用于获取系统通知列表
     * @return int code 状态码，0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return int info[0].id 通知信息的id
     * @return string info[0].title 通知信息的标题
     * @return string info[0].content 通知信息的内容
     * @return string info[0].addtime 通知信息的时间
     */
    public function systemnotifyLists(){

        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $key='message_systemnotifyLists_'.$uid.'_'.$p;

        $info=getcache($key);

        if(!$info){
            $domain=new Domain_Message();
            $info=$domain->systemnotifyLists($uid,$p);

            if($info==0){
                $rs['code']=0;
                $rs['msg']="暂无列表";
                return $rs;
            }

            setcaches($key,$info,2);

        }

        $rs['info']=$info;

        return $rs;

    }

    /**
     * 获取用户系统消息最新的时间【安卓专用】
     * @desc 用于获取用户系统消息最新的时间 
     * @return int code 状态码0表示成功
     * @return string msg 提示信息
     * @return array info 返回信息
     * @return array info[0].sysInfo 返回最新的系统消息
     * @return int info[0].sysInfo.id 返回最新的系统消息的id
     * @return string info[0].sysInfo.title 返回最新的系统消息的标题
     * @return string info[0].sysInfo.content 返回最新的系统消息的内容
     * @return array info[0].officeInfo 返回最新的后台推送消息
     * @return int info[0].officeInfo.id 返回最新的后台推送消息的id
     * @return string info[0].officeInfo.title 返回最新的后台推送消息的标题
     * @return string info[0].officeInfo.synopsis 返回最新的后台推送消息的概要
     * @return int info[0].officeInfo.type 返回最新的后台推送消息的类型
     */
    public function getLastTime(){
        $rs = array('code' => 0, 'msg' => '', 'info' =>array());
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);

        if($checkToken==700){
            $rs['code'] = $checkToken;
            $rs['msg'] = $Think.\lang('LOGIN_STATUS_INVALID');
            return $rs;
        }else if($checkToken==10020){
            $rs['code'] = 700;
            $rs['msg'] = '该账号已被禁用';
            return $rs;
        }

        $domain=new Domain_Message();
        $info=$domain->getLastTime($uid);

        if($info==1001){
            $rs['msg']="没有最新消息时间";
            return $rs;
        }

        $rs['info'][0]=(object)$info;
        return $rs;
    }

    /**
     * 获取店铺订单列表
     * @desc 用于 获取店铺订单列表
     * @return int code 操作码，0表示成功
     * @return array info 
     * @return int info[0].title 消息标题
     * @return int info[0].orderid 订单ID
     * @return int info[0].addtime 消息添加时间
     * @return int info[0].type 用户身份 0买家 1卖家
     * @return int info[0].avatar 用户头像
     * @return int info[0].status 订单状态
     * @return int info[0].is_commission 是否为佣金结算信息 0 否 1 是【如果为1时，app消息列表不显示点击查看】
     * @return string msg 提示信息
     */
    public function getShopOrderList(){
        $rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $token=checkNull($this->token);
        $p=checkNull($this->p);

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

        $domain = new Domain_Message();
        $res = $domain->getShopOrderList($uid,$p);
        $rs['info']=$res;
        return $rs;

    }
	
} 

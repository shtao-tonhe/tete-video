<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2019 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Powerless < wzxaini9@gmail.com>
// +----------------------------------------------------------------------

namespace app\user\controller;

use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

/**
 * Class AdminIndexController
 * @package app\user\controller
 *
 * @adminMenuRoot(
 *     'name'   =>'用户管理',
 *     'action' =>'default',
 *     'parent' =>'',
 *     'display'=> true,
 *     'order'  => 10,
 *     'icon'   =>'group',
 *     'remark' =>'用户管理'
 * )
 *
 * @adminMenuRoot(
 *     'name'   =>'用户组',
 *     'action' =>'default1',
 *     'parent' =>'user/AdminIndex/default',
 *     'display'=> true,
 *     'order'  => 10000,
 *     'icon'   =>'',
 *     'remark' =>'用户组'
 * )
 */
class AdminIndexController extends AdminBaseController{

    /**
     * 后台本站用户列表
     * @adminMenu(
     *     'name'   => '本站用户',
     *     'parent' => 'default1',
     *     'display'=> true,
     *     'hasView'=> true,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户',
     *     'param'  => ''
     * )
     */
    
    public function index(){

        $content = hook_one('user_admin_index_view');

        if (!empty($content)) {
            return $content;
        }

        $list = Db::name('user')
            ->where(function (Query $query) {
				
				$query->where('user_type','2');
				 
                $data = $this->request->param();

                if($data['user_status']!=''){
                    $query->where('user_status', intval($data['user_status']));
                }

                if($data['isrecommend']!=''){
                    $query->where('isrecommend', intval($data['isrecommend']));
                }

                if($data['issuper']!=''){
                    $query->where('issuper', intval($data['issuper']));
                }

                if (!empty($data['uid'])) {
                    $query->where('id', intval($data['uid']));
                }

                if (!empty($data['keyword'])) {
                    $keyword = $data['keyword'];
                    $query->where('user_login|user_nicename|user_email|mobile', 'like', "%$keyword%");
                }

            })
            ->order("create_time DESC")
            ->paginate(20);


        $now=time();

        $list->each(function($v,$k){
            $v['user_login']=m_s($v['user_login']);
            $v['mobile']=m_s($v['mobile']);
            $v['user_email']=m_s($v['user_email']);

            if($v['vip_endtime']>$now){
                $v['vip_endtime_format']=date("Y-m-d",$v['vip_endtime']);
                $v['isvip']=1;
            }else{
                $v['vip_endtime_format']='--';
                $v['isvip']=0;
            }

            $country_list=$this->getCountrys();

            foreach ($country_list as $k1 => $v1) {
                if($v['country_code']==$v1['tel']){
                    $v['country_name']=$v1['name'];
                    break; 
                }
            }

            return $v;
        }); 
			
		
		//分页-->筛选条件参数
		$data = $this->request->param();
		$list->appends($data);
		
		
        // 获取分页显示
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        // 渲染模板输出
        return $this->fetch();
    }
	
	
	/*本站用户添加*/
	public function add(){
		$this->assign('country_list', $this->getCountrys());
		// 渲染模板输出
        return $this->fetch();	
		
	}
	
	
	public function add_post(){
		 if ($this->request->isPost()) {
            
            $data = $this->request->param();
			$user= Db::name('user');
			$country_code=$data['country_code'];
			$user_login=$data['user_login'];

            if(!$country_code){
                $this->error(lang($Think.lang('SELECT_COUNTRY_AND_REGION')));
            }

            if($user_login == ''){
                $this->error($Think.lang('ENTER_MOBILE_NUMBER'));
            }else{
                if(!checkMobile($user_login)){
                    $this->error($Think.lang('ENTER_CORRECT_PHONE_NUMBER'));
                }
                
                $check = Db::name('user')
					->where(['user_login'=>$user_login,'country_code'=>$country_code])
					->find();
                if($check){
                    $this->error($Think.lang('ACCOUNT_ALREADY_EXISTS'));
                }
            }
			
			$user_nicename=$data['user_nicename'];
            if($user_nicename == ''){
                $this->error($Think.lang('ENTER_NICKNAME'));
            }else{
                $check = Db::name('user')
					->where("user_nicename='{$user_nicename}'")
					->find();
                if($check){
                    $this->error($Think.lang('NICKNAME_ALREADY_EXISTS'));
                }
            }

			$data['user_type']=2;
			$data['user_pass']=cmf_password($data['user_pass']);
			$data['code']=createCode();
			$avatar=$data['avatar'];
			
			if($avatar==''){
				$data['avatar']= '/default.png'; 
				$data['avatar_thumb']= '/default_thumb.png'; 
			}else if(strpos($avatar,'http')===0){
				/* 绝对路径 */
				$data['avatar']=  $avatar; 
				$data['avatar_thumb']=  $avatar;
			}else if(strpos($avatar,'/')===0){
				/* 本地图片 */
				$data['avatar']=  $avatar;
				$data['avatar_thumb']=  $avatar; 
			}

			$data['create_time']=time();

			$data['birthday']='2000-01-01';
			
			if(trim($data['signature'])==""){
				$data['signature']=$Think.\lang('LAZY_AND_LEFT_NOTHING');
			}
			
			$result=$user->insert($data);
			if($result!==false){
				$this->success($Think.\lang('ADD_SUCCESS'));
			}else{
				$this->error($Think.\lang('ADD_FAILED'));
			}					 


		}			
	}
	
	//编辑
	public function edit(){

        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('user')
            ->where("id={$id}")
            ->find();

        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        $this->assign('data', $data);
        $this->assign('country_list', $this->getCountrys());
        return $this->fetch();
    }

    public function editPost()
    {
        if ($this->request->isPost()) {
            $data = $this->request->param();

            $id=$data['id'];

            //获取用户的状态
            $user_status=Db::name("user")->where("id={$data['id']}")->value("user_status");

            $country_code=$data['country_code'];
            if(!$country_code){
                $this->error(lang($Think.\lang('SELECT_COUNTRY_AND_REGION')));
            }

            /*$user_login=$data['user_login'];
            if($user_login == ''){
                $this->error('请输入手机号');
            }else{
                
                if(!checkMobile($user_login)){
                    $this->error('请输入正确手机号');
                }
                
                $check = Db::name('user')->where("user_login='{$user_login}' and id!={$id}")->find();
                if($check){
                    $this->error('该账号已存在');
                }
            }*/
            
            $user_pass=$data['user_pass'];
            if($user_pass==''){
                unset($data['user_pass']);
            }else{
                $data['user_pass'] = cmf_password($user_pass);
            }
            
            
            
            $user_nicename=$data['user_nicename'];
            if($user_nicename == ''){
                $this->error($Think.\lang('ENTER_NICKNAME'));
            }else if($user_status!=3 && strstr($user_nicename, '已注销')!==false){
                $this->error($Think.\lang('NICKNAMES_CANNOT_CAOTAIN_LOGGED_OFF_USERS'));
            }
            /*else{
                $check = Db::name('user')->where("user_nicename='{$user_nicename}' and id!={$id}")->find();
                if($check){
                    $this->error('昵称已存在');
                }
            }*/

            if($data['avatar']==''){
                $this->error($Think.\lang('UPLOAD_YOU_AVATAR_OR_COVER'));
            }

            if($data['avatar_thumb']==''){
                $this->error($Think.\lang('UPLOAD_AVATAR_THUMBNAIL'));
            }
            
            $rs = DB::name('user')->update($data);

            if($rs === false){
                $this->error($Think.\lang('EDIT_FAILED'));
            }

            // $key='userinfo_'.$id;
            // delcache($key);
            $this->success($Think.\lang('EDIT_SUCCESS'));
        }
    }
	
	
	//删除用户
	public function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('user')->where("id={$id}")->delete();
        if(!$rs){
            $this->error($Think.\lang('DELETE_FAILED'));
        }
        
        //删除关注列表
        Db::name("user_attention")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除关注消息
        Db::name("user_attention_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除用户认证信息
        Db::name("user_auth")->where("uid='{$id}'")->delete();
        //删除用户拉黑
        Db::name("user_black")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除用户提现账号
        Db::name("user_cash_account")->where("uid='{$id}'")->delete();
        //删除管理员充值用户记录
        Db::name("user_charge_admin")->where("touid='{$id}'")->delete();
        //删除用户直播记录
        Db::name("user_live")->where("uid='{$id}'")->delete();
        //删除用户禁播记录
        Db::name("user_live_ban")->where("uid='{$id}' or superid='{$id}'")->delete();
        //删除直播间踢人列表
        Db::name("user_live_kick")->where("uid='{$id}' or liveuid='{$id}'")->delete();
        //删除直播间举报
        Db::name("user_live_report")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除直播间禁言
        Db::name("user_live_shut")->where("uid='{$id}' or liveuid='{$id}'")->delete();
        //删除直播间管理员
        Db::name("user_livemanager")->where("uid='{$id}' or liveuid='{$id}'")->delete();
        //删除直播记录
        Db::name("user_liverecord")->where("uid='{$id}'")->delete();
        //删除音乐收藏
        Db::name("user_music_collection")->where("uid='{$id}'")->delete();
        //删除极光信息
        Db::name("user_pushid")->where("uid='{$id}'")->delete();
        //删除用户举报
        Db::name("user_report")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除超管
        Db::name("user_super")->where("uid='{$id}'")->delete();
        //删除用户token
        Db::name("user_token")->where("user_id='{$id}'")->delete();

        $list=Db::name("user_video")->field("id")->where("uid='{$id}'")->select()->toArray();

        foreach ($list as $k => $v) {
            //删除视频喜欢
            Db::name("user_video_like")->where("videoid='{$v['id']}'")->delete();

        }

        //删除用户视频
        Db::name("user_video")->where("uid='{$id}'")->delete();
        //删除视频评论
        Db::name("user_video_comments")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除视频评论@信息
        Db::name("user_video_comments_at_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除视频评论点赞
        Db::name("user_video_comments_like")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除视频评论信息
        Db::name("user_video_comments_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除视频喜欢
        Db::name("user_video_like")->where("uid='{$id}'")->delete();
        //删除视频付费列表
        Db::name("user_video_paylists")->where("uid='{$id}'")->delete();
        //删除视频举报
        Db::name("user_video_report")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除视频观看记录
        Db::name("user_video_view")->where("uid='{$id}'")->delete();
        //删除用户视频观看记录
        Db::name("user_video_watchlists")->where("uid='{$id}'")->delete();
        //删除用户视频观看时间记录
        Db::name("user_video_watchtime")->where("uid='{$id}'")->delete();
        //删除视频观看时长记录
        Db::name("view_reward")->where("uid='{$id}'")->delete();
        //删除邀请记录
        Db::name("agent")->where("uid='{$id}' or one='{$id}'")->delete();
        //删除邀请分成
        Db::name("agent_profit")->where("uid='{$id}' or one='{$id}'")->delete();
        //删除反馈
        Db::name("feedback")->where("uid='{$id}'")->delete();
        //删除点赞信息表
        Db::name("praise_messages")->where("uid='{$id}' or touid='{$id}'")->delete();
        //删除店铺申请
        Db::name("shop_apply")->where("uid='{$id}'")->delete();
        //删除店铺礼物
        Db::name("shop_goods")->where("uid='{$id}'")->delete();
        //删除系统消息
        Db::name("system_push")->where("uid='{$id}'")->delete();
        //删除上热门记录
        Db::name("popular_orders")->where("uid='{$id}' or touid='{$id}'")->delete();

        //删除经营类目
        Db::name("seller_goods_class")->where("uid={$id}")->delete();
        //删除店铺评分
        Db::name("shop_points")->where("shop_uid={$id}")->delete();
        //删除收货地址
        Db::name("shop_address")->where("uid={$id}")->delete();
        //删除商品访问记录
        Db::name("user_goods_visit")->where("uid={$id}")->delete();
        //删除代售商品记录
        Db::name("seller_platform_goods")->where("uid={$id}")->delete();

        //删除用户的redis
        delcache("token_".$id);

        //删除极光IM账号
        delIMUser($id);

        $this->success($Think.\lang('DELETE_SUCCESS'),url("user/adminIndex/index"));
    }

    /**
     * 本站用户禁用
     * @adminMenu(
     *     'name'   => '本站用户禁用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户禁用',
     *     'param'  => ''
     * )
     */
    public function ban(){

        $id = input('param.id', 0, 'intval');
        if ($id) {
            $result = Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('user_status', 0);
            if ($result) {
                $this->success($Think.\lang('MEMBER_DISABLED_SUCCESSFULLY'));
            } else {
                $this->error($Think.\lang('MEMBER_DISABLER_FAILED'));
            }
        } else {
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
    }

    /**
     * 本站用户启用
     * @adminMenu(
     *     'name'   => '本站用户启用',
     *     'parent' => 'index',
     *     'display'=> false,
     *     'hasView'=> false,
     *     'order'  => 10000,
     *     'icon'   => '',
     *     'remark' => '本站用户启用',
     *     'param'  => ''
     * )
     */
    public function cancelBan(){

        $id = input('param.id', 0, 'intval');
        if ($id) {
            Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('user_status', 1);
            $this->success($Think.\lang('MEMBER_ENABLED_SUCCESSFULLY'), '');
        } else {
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
    }


    //设置超管
    public function super(){
       $id = input('param.id', 0, 'intval');
        if ($id) {
            Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('issuper', 1);

            $isexist=DB::name("user_super")->where("uid={$id}")->find();
            if(!$isexist){
                DB::name("user_super")->insert(array("uid"=>$id,'addtime'=>time()));    
            }


            $this->success($Think.\lang('MEMBER_SETTING_OVERRIDE_SUCCEEDED'), '');
        } else {
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
    }

    //取消超管
    public function cancelsuper(){
        $id = input('param.id', 0, 'intval');
        if ($id) {
            Db::name("user")->where(["id" => $id, "user_type" => 2])->setField('issuper', 0);

            DB::name("user_super")->where("uid='{$id}'")->delete();

            $this->success($Think.\lang('MEMBER_CANCELES_OVERRIDE_SUCCESSFULLY'), '');
        } else {
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
    }

    //设置vip
    public function setvip(){

        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('user')
            ->field("id,vip_endtime,user_nicename")
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        $this->assign('data', $data);
        return $this->fetch();
    }

    //vip到期时间保存
    public function setvip_post(){
        $data = $this->request->param();
        $vip_endtime=$data['vip_endtime'];
        if(!$vip_endtime){
            $this->error($Think.\lang('SELECT_VIP_EXPRIATION_TIME'));
        }

        $vip_endtime=$vip_endtime." 23:59:59";

        $now=time();
        $vip_endtime_format=strtotime($vip_endtime);
        if($vip_endtime_format<=$now){
            $this->error($Think.\lang('VIP_TIME_CANNOT_LOW_CURRENT'));
        }

        $id=$data['id'];
        $result=Db::name('user')
            ->where("id={$id}")
            ->update(array("vip_endtime"=>$vip_endtime_format));

        if($result!==false){
            $this->success($Think.\lang('VIP_EXPRATION_TIME_SET_SUCCESSFULLY'));
        }else{
            $this->error($Think.\lang('VIP_EXPRATION_TIME_SET_FAILED'));
        }
    }

    public function setrecommend(){
        $data = $this->request->param();
        $id=$data['id'];
        $status=$data['status'];
        if(!$id){
            $this->error($Think.\lang('COMFIRM_THE_USER'));
        }
        $now=time();
        if($status==1){
            $res=Db::name("user")->where("id={$id}")->update(array('isrecommend'=>1,'recommend_time'=>$now));
            if($res){
                Db::name("user_live")->where("uid={$id}")->update(array('isrecommend'=>1,'recommend_time'=>$now));
                $this->success($Think.\lang('ANCHOR_RECOMMENDATION_SET_SUCCEEDED'));
            }else{
                $this->error($Think.\lang('ANCHOR_RECOMMENDATION_SET_FAILED'));
            }
            
        }else{
            $res=Db::name("user")->where("id={$id}")->update(array('isrecommend'=>0,'recommend_time'=>0));
            if($res){
                $live_info=Db::name("user_live")->where("uid={$id}")->find();
                if($live_info){
                    Db::name("user_live")->where("uid={$id}")->update(array('isrecommend'=>0,'recommend_time'=>$live_info['starttime']));   
                }
                
                $this->success($Think.\lang('CANCEL_ANCHOR_RECOMMENDATION_SUCCESSFULLY'));
            }else{
                $this->error($Think.\lang('CANCEL_ANCHOR_RECOMMENDATION_FAILED'));
            }

        }
    }

    public function getCountrys(){
        //读取国家代号
        $key='getCountrys';
        $info=getcaches($key);
        //$info=false;
        if(!$info){

            $country=CMF_ROOT.'data/config/country.json';
            // 从文件中读取数据到PHP变量 
            $json_string = file_get_contents($country); 
             // 用参数true把JSON字符串强制转成PHP数组 
            $data = json_decode($json_string, true);

            $info=$data['country']; //国家
            
            setcaches($key,$info);
        }

        $country_list=[];
        foreach ($info as $k => $v) {
            $arr=$v['lists'];
            foreach ($arr as $k1 => $v1) {
               $country_list[]=$v1;
            }
        }
        
        return $country_list;
    }

}

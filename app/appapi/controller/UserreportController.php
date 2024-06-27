<?php
/**
 * 会员举报
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;


class UserreportController extends HomebaseController {
	
	public function index(){
		
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);    
		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		}
		
		$touid=checkNull($data['touid']);

		//判断用户是否存在
		$touserinfo=Db::name("user")
			->field("user_status,user_nicename,user_login")
			->where(['id'=>$touid,'user_type'=>'2'])
			->find();

		if(!$touserinfo){
			$this->assign("reason",'举报用户不存在');
			return $this->fetch(':error');
			exit;
		}

		//判断用户是否被拉黑
		if($touserinfo['user_status']==0){
			$this->assign("reason",'该用户已被禁用');
			return $this->fetch(':error');
			exit;
		}

		//获取用户举报分类
		$classifies=Db::name("user_report_classify")->order("orderno")->select();

		$this->assign("classifies",$classifies);

		$this->assign("uid",$uid);
		$this->assign("token",$token);
		$this->assign("touid",$touid);

		$time=time();
		$this->assign("time",$time);
		return $this->fetch();
	    
	}


	public function upload(){
        
        $files["file"]=$_FILES["image"];
        $type='img';
        
        $rs=adminUploadFiles($files,$type);

        if($rs['code']!=0){
            echo json_encode(array("ret"=>0,'data'=>array(),'msg'=>$rs['msg']));
            exit;
        }

        $url=$rs['filepath'];
        $url_sign=$rs['preview_url'];

        echo json_encode(array("ret"=>200,'data'=>array("url"=>$url,"url_sign"=>$url_sign),'msg'=>''));

        exit;
	}




	public function save(){
        
        $rs=array('code'=>0,'msg'=>'提交成功','info'=>array());
        
		$data = $this->request->param();

        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);    
		if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		}

        $classify=checkNull($data['classify']);

        $data['uid']=checkNull($data['uid']);
		$data['touid']=checkNull($data['touid']);
		$data['content']=checkNull($data['content']);
		$data['thumb']=checkNull($data['thumb']);
		$data['addtime']=time();
       // $data['contact_msg']=checkNull($data['contactMsg']);


        if($data['uid']==$data['touid']){
        	$rs['code']=1001;
            $rs['msg']='自己不能举报自己';
			echo json_encode($rs);
			exit;
        }

        if($classify==''){
        	$rs['code']=1001;
            $rs['msg']='请选择举报类型';
			echo json_encode($rs);
			exit;
        }
        //判断举报类型是否存在
        $classify_info=Db::name("user_report_classify")->where(['id'=>$classify])->find();

        if(!$classify_info){
        	$rs['code']=1001;
            $rs['msg']='举报类型不存在';
			echo json_encode($rs);
			exit;
        }

        //判断被举报用户是否存在，是否被禁用
        $touserinfo=Db::name("user")->field("user_status,user_nicename,user_login")->where(['id'=>$data['touid'],'user_type'=>'2'])->find();

		if(!$touserinfo){
			
			$rs['code']=1001;
            $rs['msg']='举报用户不存在';
			echo json_encode($rs);
			exit;
		}

		//判断用户是否被拉黑
		if($touserinfo['user_status']==0){
			$rs['code']=1001;
            $rs['msg']='该用户已被禁用';
			echo json_encode($rs);
			exit;
		}

        $data['classify']=$classify_info['title'];

        if($data['content']==''){
            $rs['code']=1002;
            $rs['msg']='请输入反馈内容';
			echo json_encode($rs);
			exit;
        }

        unset($data['token']);
        unset($data['contactMsg']);

		$result=Db::name("user_report")->insert($data);
		if($result){
            echo json_encode($rs);
			exit;
		}else{
            $rs['code']=1002;
            $rs['msg']='提交失败,请重试';
            echo json_encode($rs);
			exit;
		}
	
	}
}
<?php
/**
 * 我要反馈
 */
namespace app\appapi\controller;

use cmf\controller\HomeBaseController;
use think\Db;
use think\db\Query;

class FeedbackController extends HomebaseController {

	public function index(){       
        $data = $this->request->param();
		
		
        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);
        $version=checkNull($data['version']);
        $model=checkNull($data['model']);
        
        if(checkToken($uid,$token)==700){
			$this->assign("reason",$Think.\lang('LOGIN_STATUS_INVALID'));
			return $this->fetch(':error');
			exit;
		} 
        
        $time=time();
        
        $this->assign("uid",$uid);
        $this->assign("token",$token);
        $this->assign("version",$version);
        $this->assign("model",$model);
        $this->assign("time",$time);
		
		
		return $this->fetch();
	    
	}
    
	public function upload(){
        
        
        $files["file"]=$_FILES["image"];
        $type='img';

        /*echo json_encode(array("ret"=>200,'data'=>array("url"=>"http://www.baidu.com/123.jpg","url_sign"=>"http://www.baidu.com/123.jpg%@%cloudtype=1"),'msg'=>''));
        
        exit;*/

        $rs=adminUploadFiles($files,$type);

        if($rs['code']!=0){
            echo json_encode(array("ret"=>0,'data'=>array(),'msg'=>$rs['msg']));
            exit;
        }

        $url = $rs['filepath']; //拼接了存储方式
        $url_sign = $rs['preview_url']; //进行了签名

        echo json_encode(array("ret"=>200,'data'=>array("url"=>$url,"url_sign"=>$url_sign),'msg'=>''));
        
        exit;
	}	
    
	public function save(){
        
        $rs=array('code'=>0,'msg'=>'提交成功','info'=>array());
		
		$data = $this->request->param();
		
		
        $uid=checkNull($data['uid']);
        $token=checkNull($data['token']);

        if(checkToken($uid,$token)==700){
            $rs['code']=1001;
            $rs['msg']=$Think.\lang('LOGIN_STATUS_INVALID');
			echo json_encode($rs);
			exit;
		} 
		
		
        
        $info['uid']=$uid;
		$info['version']=checkNull($data['version']);
		$info['model']=checkNull($data['model']);
		$info['content']=checkNull($data['content']);
		$info['thumb']=checkNull($data['thumb']);
		$info['addtime']=time();
        $info['contact_msg']=checkNull($data['contactMsg']);

        if($info['content']==''){
            $rs['code']=1002;
            $rs['msg']='请输入反馈内容';
			echo json_encode($rs);
			exit;
        }

		$result=Db::name("feedback")->insert($info);
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
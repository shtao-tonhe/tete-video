<?php

/**
 * 店铺申请
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class ShopapplyController extends AdminbaseController {
    protected function getStatus($k=''){
        $status=array(
            '0'=>$Think.\lang('PENDING'),
            '1'=>$Think.\lang('ADMIN_USERAUTH_AUDIT_STATUS_SUCCESS'),
            '2'=>$Think.\lang('ADMIN_USERAUTH_AUDIT_STATUS_LOSE'),
        );
        if($k==''){
            return $status;
        }
        return isset($status[$k])?$status[$k]:'';
    }
    
    function index(){
        $data = $this->request->param();
        $map=[];

        $map[]=['uid','<>',1];
        
        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }
        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }
        
        $uid=isset($data['uid']) ? $data['uid']: '';
        if($uid!=''){
           
            $map[]=['uid','=',$uid];
            
        }

    	$lists = Db::name("shop_apply")
                ->where($map)
                ->order("addtime DESC")
                ->paginate(20);
                
        $lists->each(function($v,$k){
			//$v['thumb']=get_upload_path($v['thumb']);
            $v['userinfo']= getUserInfo($v['uid']);
            $v['tel']= m_s($v['uid']);
            $v['cardno']=m_s($v['cardno']);
            $v['phone']=m_s($v['phone']);
            $v['classname']='';

            //获取商家经营类目
            $class_list=Db::name("seller_goods_class")->where("uid={$v['uid']}")->select()->toArray();
            $num=count($class_list);
            foreach ($class_list as $k1 => $v1) {
                $gc_name=Db::name("shop_goods_class")->where("gc_id={$v1['goods_classid']}")->value('gc_name');
                
                $v['classname'].=$gc_name;
                if($num>1&&$k1<($num-1)){
                    $v['classname'].=' | ';
                }
                
            }

           
            return $v;           
        });
                
        $lists->appends($data);
        $page = $lists->render();

        //判断平台店铺是否申请
        $platform_apply=1;
        $platform_info=Db::name("shop_apply")->where("uid=1")->find();
        if(!$platform_info){
        	$platform_apply=0;
        }

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("status", $this->getStatus());
    	$this->assign("platform_apply", $platform_apply);
    	
    	return $this->fetch();			
    }
    
	function del(){
        
        $id = $this->request->param('id', 0, 'intval');
        
        $rs = DB::name('shop_apply')->where("uid={$id}")->delete();
        if(!$rs){
            $this->error($Think.\lang('DELETE_FAILED'));
        }
		
		
		$action="删除店铺申请：{$id}";
        setAdminLog($action);
        
        //删除店铺总评分记录
        Db::name("shop_points")->where("shop_uid={$id}")->delete();

        //删除店铺商品
        Db::name("shop_goods")->where("uid={$id}")->delete();
		
		//删除收藏商品
        Db::name("user_goods_collect")->where("goodsuid={$id}")->delete();
        

        $this->success($Think.\lang('DELETE_SUCCESS'),url("shopapply/index"));
            
	}
	
    
    function edit(){
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('shop_apply')
            ->where("uid={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }
        
        //$data['thumb']= get_upload_path($data['thumb']);
        $data['certificate']= get_upload_path($data['certificate']);
        //$data['license']= get_upload_path($data['license']);
        $data['other']= get_upload_path($data['other']);
        $data['userinfo']= getUserInfo($data['uid']);

        //获取一级店铺分类
        $oneGoodsClass=getcaches("oneGoodsClass");

        if(!$oneGoodsClass){
            $oneGoodsClass=Db::name("shop_goods_class")->field("gc_id,gc_name,gc_isshow")->where("gc_parentid=0")->order("gc_sort")->select()->toArray();

            setcaches("oneGoodsClass",$oneGoodsClass);
        }

        //获取用户的经营类目
        $seller_class_arr=Db::name("seller_goods_class")->where("uid={$data['uid']}")->select()->toArray();
        $seller_class_arr=array_column($seller_class_arr, 'goods_classid');
		

		foreach($oneGoodsClass as $ks=>$vs){
			if(in_array($vs['gc_id'],$seller_class_arr)){
				$oneGoodsClass[$ks]['gc_isshow']=3; //已存在的类目
			}
		}
		
		
        $seller_class=implode(",",$seller_class_arr);

     
        $this->assign('data', $data);
        $this->assign('oneGoodsClass', $oneGoodsClass);
        $this->assign('seller_class', $seller_class);
        
        $this->assign("status", $this->getStatus());
        
        return $this->fetch();
        
    }
    
	function editPost(){
		if ($this->request->isPost()) {
            
            $data = $this->request->param();

            $classids=isset($data['classids'])?$data['classids']:[];
            $count=count($classids);
            if($count<1){
                $this->error($Think.\lang('SELECT_BUSINESS_CATEGORY'));
            }


            $uid=$data['uid'];
            $order_percent=$data['order_percent'];

            if($order_percent<0||!is_numeric($order_percent)||$order_percent>100){
                $this->error($Think.\lang('FILL_IN_INTEFER_ZERO_AND_HUNDRED'));
            }

            if(floor($order_percent)!=$order_percent){
                $this->error($Think.\lang('FILL_IN_INTEFER_ZERO_AND_HUNDRED'));
            }

            unset($data['classids']);

            $shop_status=$data['status'];

            $reason=$data['reason'];

            if($shop_status==2){ //审核失败
                if(trim($reason)==""){
                    $this->error($Think.\lang('FILL_IN_REASON_AUDIT_FAILURE'));
                }
            }

            $data['uptime']=time();
			$rs = DB::name('shop_apply')->update($data);
            if($rs===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }

            //更新用户经营类目
            Db::name("seller_goods_class")->where("uid={$uid}")->delete();
            foreach ($classids as $k => $v) {

                //获取一级分类的状态
                $status=Db::name("shop_goods_class")->where("gc_id={$v}")->value('gc_isshow');

                $data1=array(
                    'uid'=>$uid,
                    'goods_classid'=>$v,
                    'status'=>$status
                );
                Db::name("seller_goods_class")->where("uid={$uid}")->insert($data1);
            }

            


            if($shop_status!=1){

                //将店铺内上架的商品下架
                Db::name("shop_goods")->where("uid={$uid} and status=1")->update(array('status'=>-2));
            }

            $admin_id=get_current_admin_id();
            $user=Db::name("user")->where("id='{$admin_id}'")->find();

			$action=$Think.\lang('STORE_INFORMATION_MODIFICATION');

            if($shop_status>0){

                $title='';
                if($shop_status==1){ //审核通过
                    $title=$Think.\lang('STORE_HAS_APPROVER');
                }else if($shop_status==2){ //审核失败
                    $title=$Think.\lang('STORE_AUDIT_FAILED');
                    if($reason){
                        $title.=$Think.\lang('ADMIN_USERAUTH_CERTIFICATE_LOSE_REASON').$reason;
                    }
                }

                //向系统通知表中写入数据
                $sysInfo=array(
                    'title'=>$Think.\lang('STORE_AUDIT_REMINDER'),
                    'addtime'=>time(),
                    'admin'=>$user['user_login'],
                    'ip'=>$_SERVER['REMOTE_ADDR'],
                    'uid'=>$uid,
                    'content'=>$title

                );

                $result1=Db::name("system_push")->insert($sysInfo);

                if($result1!==false){
                    $text=$Think.\lang('ADMIN_USERAUTH_CERTIFICATE_LOSE_REASON');
                    $uid=$uid;
                    jMessageIM($text,$uid);
                }
				
				$action.=$title;

            }
			
			setAdminLog($action);

            
            
            $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
		}
	}

	//平台自营店铺信息
	function platformedit(){
		$platform_info=Db::name("shop_apply")->where("uid=1")->find();

		$this->assign("platform_info",$platform_info);
		return $this->fetch();
	}

	function platformeditPost(){
		$data=$this->request->param();
		$username=$data['username'];
		$cardno=$data['cardno'];
		$contact=$data['contact'];
		$phone=$data['phone'];
		/*$province=$data['province'];
		$city=$data['city'];
		$area=$data['area'];
		$address=$data['address'];*/
		$service_phone=$data['service_phone'];
		$receiver_phone=$data['receiver_phone'];
		$receiver_province=$data['receiver_province'];
		$receiver_city=$data['receiver_city'];
		$receiver_area=$data['receiver_area'];
		$receiver=$data['receiver'];
		$receiver_address=$data['receiver_address'];
		$certificate=$data['certificate'];
		$other=$data['other'];

		if(!$username){
			$this->error($Think.\lang('FILL_IN_NAME'));
		}
		if(!$cardno){
			$this->error($Think.\lang('FILL_IN_ID_NUMBER'));
		}

		$check_card=checkCardNo($cardno);
		if(!$check_card){
			$this->error($Think.\lang('CONFIRM_CORRECTNESS_ID_NUMBER'));
		}
		if(!$contact){
			$this->error($Think.\lang('FILL_IN_CONTACT_PERSON_OPERATOR'));
		}
		if(!$phone){
			$this->error($Think.\lang('FILL_IN_OPERATOR_CONTACT_NUM'));
		}
		/*if(!$province){
			$this->error("请填写经营者所在省份");
		}
		if(!$city){
			$this->error("请填写经营者所在市");
		}
		if(!$area){
			$this->error("请填写经营者所在地区");
		}
        if(!$address){
			$this->error("请填写经营者详细地址");
		}*/
		if(!$service_phone){
			$this->error($Think.\lang('FILL_IN_CUSTOMER_SERVICE_NUMBER'));
		}
		if(!$receiver_phone){
			$this->error($Think.\lang('FILL_IN_MOBILE_PHONE_NUM_OF_CONSIGNEE'));
		}
		if(!$receiver_province){
			$this->error($Think.\lang('SELECT_PROVINCE_CONSIGNEE'));
		}
		if(!$receiver_city){
			$this->error($Think.\lang('SELECT_CONSIGNEE_CITY'));
		}
		if(!$receiver_area){
			$this->error($Think.\lang('SELECT_CONSIGNEE_REGION'));
		}
		if(!$receiver_address){
			$this->error($Think.\lang('SELECT_DELETE_ADDRESS_CONSIGNEE'));
		}
		if(!$receiver){
			$this->error($Think.\lang('FILL_IN_CONSIGNEE'));
		}
		if(!$certificate){
			$this->error($Think.\lang('UPLOAD_BUSINESS_LICENSE'));
		}
       
		if(!$other){
			$this->error($Think.\lang('UPLOAD_OTHER_CERTIFICATES'));
		}

		$platform_info=Db::name("shop_apply")->where("uid=1")->find();
		if(!$platform_info){
            $data['province']=$data['receiver_province'];
            $data['city']=$data['receiver_city'];
            $data['area']=$data['receiver_area'];
            $data['address']=$data['receiver_address'];
			$data['uid']=1;
			$data['status']=1;
			$data['addtime']=time();
			$res=Db::name("shop_apply")->insert($data);

            $shop_points_arr=array(
                'shop_uid'=>1,
                'evaluate_total'=>0,
                'quality_points_total'=>0,
                'service_points_total'=>0,
                'express_points_total'=>0
            );
            Db::name("shop_points")->insert($shop_points_arr);
		}else{
			$data['uptime']=time();
			$res=Db::name("shop_apply")->where("uid=1")->update($data);
		}
		
		if($res===false){
			$this->error($Think.\lang('FAILED_SET_SELF_ON_PLATFORM'));
		}

		$this->success($Think.\lang('FAILED_SET_SELF_ON_PLATFORM'));
	}

}

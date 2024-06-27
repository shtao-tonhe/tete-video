<?php

/**
 * 商品订单列表
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class GoodsorderController extends AdminbaseController {
    protected function getType($k=''){
        $type=[
            '0'=>$Think.\lang('UNPAID'),
            '1'=>$Think.\lang('ALIPAY'),
            '2'=>$Think.\lang('WECHAT'),
            '3'=>$Think.\lang('BALANCE'),
            '4'=>$Think.\lang('WECHAT_APPLET'),
            '5'=>'Paypal',
            '6'=>'BrainTreePaypal',
        ];
        if($k==''){
            return $type;
        }
        return isset($type[$k]) ? $type[$k]: '';
    }
    protected function getRefundStatus($k=''){
        $mark=[
            '-2'=>$Think.\lang('BUYER_CANCELS_APPLYCATION'),
            '-1'=>$Think.\lang('FAIL'),
            '0'=>$Think.\lang('PROCESSING'),
            '1'=>$Think.\lang('SUCCESS'),
        ];
        if($k==''){
            return $mark;
        }
        return isset($mark[$k]) ? $mark[$k]: '';
    }
    
    protected function getStatus($k=''){
        $status=[
            '-1'=>$Think.\lang('CLOSED'),
            '0'=>$Think.\lang('PENDING_PAYMENT'),
            '1'=>$Think.\lang('TO_BE_SHIPPED'),
            '2'=>$Think.\lang('GOODS_TO_BE_RECEIVER'),
            '3'=>$Think.\lang('TO_BE_EVALUATED'),
            '4'=>$Think.\lang('EVALUATED'),
            '5'=>$Think.\lang('REFUND'),
        ];
        if($k==''){
            return $status;
        }
        return isset($status[$k]) ? $status[$k]: '';
    }

    protected function getDelType($k=''){
        $status=[
            '-1'=>$Think.\lang('BUYER_DELETE'),
            '-2'=>$Think.\lang('SELLER_DELETE'),
            '1'=>$Think.\lang('BUYERS_AND_SELLERS_DELETED'),
            '0'=>$Think.\lang('NOT_DELETED'),
        ];
        if($k==''){
            return $status;
        }
        return isset($status[$k]) ? $status[$k]: '';
    }
    
    function index(){

        $data = $this->request->param();
        $map=[];

        $start_time=isset($data['start_time']) ? $data['start_time']: '';
        $end_time=isset($data['end_time']) ? $data['end_time']: '';
        
        if($start_time!=""){
           $map[]=['addtime','>=',strtotime($start_time)];
        }

        if($end_time!=""){
           $map[]=['addtime','<=',strtotime($end_time) + 60*60*24];
        }


        $orderno=isset($data['orderno']) ? $data['orderno']: '';
        if($orderno!=''){
            $map[]=['orderno','like','%'.$orderno.'%'];
        }

        $trade_no=isset($data['trade_no']) ? $data['trade_no']: '';
        if($trade_no!=''){
            $map[]=['trade_no','like','%'.$trade_no.'%'];
        }

        
        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }

        $type=isset($data['type']) ? $data['type']: '';
        if($type!=''){
            if($type==0){
                $map[]=['shop_uid','=',1];
            }else{
                $map[]=['shop_uid','>',1]; 
            }
            
        }

        $share_income=isset($data['share_income']) ? $data['share_income']: '';
        if($share_income!=''){
            if($share_income==0){
                $map[]=['share_income','=',0];
            }else{
                $map[]=['share_income','>',0]; 
            }
            
        }

        $buyer_uid=isset($data['buyer_uid']) ? $data['buyer_uid']: '';

        if($buyer_uid!=''){
            
            $map[]=['uid','=',$buyer_uid];
            
        }

        $seller_uid=isset($data['seller_uid']) ? $data['seller_uid']: '';
        
        if($seller_uid!=''){
            
            $map[]=['shop_uid','=',$seller_uid];
            
        }

        $goods_name=isset($data['goods_name']) ? $data['goods_name']: '';
        if($goods_name!=''){
            $map[]=['goods_name','like','%'.$goods_name.'%'];
        }

        $phone=isset($data['phone']) ? $data['phone']: '';
        if($phone!=''){
            $map[]=['phone','like','%'.$phone.'%'];
        }

    	$lists = Db::name("shop_order")
            ->where($map)
			->order("addtime desc")
			->paginate(20);
        
        $lists->each(function($v,$k){
			$v['spec_thumb']=get_upload_path($v['spec_thumb']);
            $v['buyer_info']=getUserInfo($v['uid']);
            $v['seller_info']=getUserInfo($v['shop_uid']);
            if($v['shareuid']){
                $v['share_info']=getUserInfo($v['shareuid']);
            }
            return $v;           
        });
        
        $page = $lists->render();

    	$this->assign('lists', $lists);

    	$this->assign("page", $page);
        
    	$this->assign("status", $this->getStatus());
        $this->assign("type", $this->getType());
        $this->assign("refund_status", $this->getRefundStatus());
        $this->assign("del_type", $this->getDelType());
    	
    	return $this->fetch();
    }

    //获取商品订单详情
    public function info(){
        $id=$this->request->param('id');
        $info=Db::name("shop_order")->where("id={$id}")->find();
        if(!$info){
            $this->error($Think.\lang('PRODUCT_ORDER_NOT_EXIST'));
        }

        $info['buyer_info']=getUserInfo($info['uid']);
        $info['seller_info']=getUserInfo($info['shop_uid']);
        $info['spec_thumb']=get_upload_path($info['spec_thumb']);

        $this->assign('data',$info);
        $this->assign("status", $this->getStatus());
        $this->assign("type", $this->getType());
        $this->assign("refund_status", $this->getRefundStatus());
        $this->assign("del_type", $this->getDelType());
        return $this->fetch();

    }

    //填写物流信息
    public function setexpress(){
        $id=$this->request->param("id");
        //获取物流公司列表
        $key='getExpressList';
        $express_list=getcaches($key);

        if(!$express_list){
            $express_list=DB::name('shop_express')
            ->field("id,express_name,express_phone,express_thumb")
            ->where('express_status=1')
            ->order("list_order asc,id desc")
            ->select();
        }
        
        $this->assign("orderid",$id);
        $this->assign("express_list",$express_list);
        return $this->fetch();
    }

    //填写物流单号提交
    public function setexpressPost(){
        $data=$this->request->param();
        $express_id=$data['express_id'];
        $express_number=trim($data['express_number']);
        $orderid=trim($data['orderid']);
        //获取物流信息
        $express_info=Db::name("shop_express")->where("id='{$express_id}'")->find();
        if(!$express_info){
            $this->error($Think.\lang('LOGISTICS_COMPANY_NOT_EXIST'));
        }

        if(!$express_number){
            $this->error($Think.\lang('FILL_IN_LOGISTICS_NUM'));
        }

        if (preg_match("/[\x7f-\xff]/", $express_number)) {
            $this->error($Think.\lang('CONFIRM_LOGISTICS_ORDER_NUM_IS_CORRECT'));
        }

        $now=time();

        $data=array(
            'express_name'=>$express_info['express_name'],
            'express_phone'=>$express_info['express_phone'],
            'express_thumb'=>$express_info['express_thumb'],
            'express_code'=>$express_info['express_code'],
            'express_number'=>$express_number,
            'status'=>2,
            'shipment_time'=>$now
        );

        $res=Db::name("shop_order")->where("id='{$orderid}'")->update($data);
        if($res===false){
            $this->error($Think.\lang('FAILED_TO_LOGISTICS_INFORMATION'));
        }

        //写入订单消息列表
        $orderinfo=getShopOrderInfo(['id'=>$orderid]);

        $title="你购买的“".$orderinfo['goods_name']."”商家已经发货,物流单号为:".$express_number;

        $data1=array(
            'uid'=>$orderinfo['uid'],
            'orderid'=>$orderid,
            'title'=>$title,
            'addtime'=>$now,
            'type'=>'0'

        );

        addShopGoodsOrderMessage($data1);

        //发送极光IM
        jMessageIM($title,$orderinfo['uid'],'goodsorder_admin');

        $this->success($Think.\lang('SUCCESS_TO_LOGISTICS_INFORMATION'),'Admin/Goodsorder/index');
        //$this->success("物流信息填写成功！");

    }
    

}

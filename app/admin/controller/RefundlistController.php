<?php

/**
 * 退款申请列表
 */
namespace app\admin\controller;

use cmf\controller\AdminBaseController;
use think\Db;

class RefundlistController extends AdminbaseController {
    protected function getType($k=''){
        $type=array(
            '0'=>$Think.\lang('REFUND_ONLY'),
            '1'=>$Think.\lang('RETURN_REFUND'),
        );
        if($k==''){
            return $type;
        }
        return isset($type[$k])?$type[$k]:'';
    }

    protected function getShopResult($k=''){
        $result=array(
            '-1'=>$Think.\lang('REFUSE'),
            '0'=>$Think.\lang('PROCESSING'),
            '1'=>$Think.\lang('AGREE'),
        );
        if($k==''){
            return $result;
        }
        return isset($result[$k])?$result[$k]:'';
    }

    protected function getPlatformResult($k=''){
        $result=array(
            '-1'=>$Think.\lang('REFUSE'),
            '0'=>$Think.\lang('PENDING'),
            '1'=>$Think.\lang('AGREE'),
        );
        if($k==''){
            return $result;
        }
        return isset($result[$k])?$result[$k]:'';
    }

    protected function getStatus($k=''){
        $status=array(
            '-1'=>$Think.\lang('BUYER_CANCELLED'),
            '0'=>$Think.\lang('PROCESSING'),
            '1'=>$Think.\lang('COMPLETED'),
        );
        if($k==''){
            return $status;
        }
        return isset($status[$k])?$status[$k]:'';
    }
    
    /*退款申请列表*/
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

        $status=isset($data['status']) ? $data['status']: '';
        if($status!=''){
            $map[]=['status','=',$status];
        }

        $goods_type=isset($data['goods_type']) ? $data['goods_type']: '';
        if($goods_type!=''){
            if($goods_type==0){
                $map[]=['shop_uid','<>',1];
            }else{
                $map[]=['shop_uid','=',1];
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

        $lists = Db::name("shop_order_refund")
                ->where($map)
                ->order("addtime DESC")
                ->paginate(20);
        
        $lists->each(function($v,$k){
            $v['buyer_info']=getUserInfo($v['uid']);
            $v['seller_info']=getUserInfo($v['shop_uid']);
            $v['platform_interpose_thumb']=get_upload_path($v['platform_interpose_thumb']);
            $orderinfo=getShopOrderInfo(['id'=>$v['orderid']],'orderno');
            $v['orderno']=$orderinfo['orderno'];
            return $v;           
        });

        $lists->appends($data);
        $page = $lists->render();

        $this->assign('lists', $lists);

        $this->assign("page", $page);
        
        $this->assign("status", $this->getStatus());
        $this->assign("type", $this->getType());
        $this->assign("shop_result", $this->getShopResult());
        $this->assign("platform_result", $this->getPlatformResult());
        
        return $this->fetch();
    }
    


    /*退款编辑*/
    function edit(){
        
        $id   = $this->request->param('id', 0, 'intval');
        
        $data=Db::name('shop_order_refund')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }

        $orderinfo=getShopOrderInfo(['id'=>$data['orderid']],'orderno,phone');
        $data['orderno']=$orderinfo['orderno'];
        $data['phone']=$orderinfo['phone'];
        $data['buyer_info']=getUserInfo($data['uid']);
        $data['seller_info']=getUserInfo($data['shop_uid']);
        $data['platform_interpose_thumb']=get_upload_path($data['platform_interpose_thumb']);

        //获取平台的处理意见
        $platform_info=Db::name("shop_order_refund_list")->where("orderid={$data['orderid']} and type=3")->find();
        $data['platform_handle_desc']=isset($platform_info['handle_desc'])?$platform_info['handle_desc']:'';
        
        $this->assign("status", $this->getStatus());
        $this->assign("type", $this->getType());
        $this->assign("shop_result", $this->getShopResult());
        $this->assign("platform_result", $this->getPlatformResult());
        $this->assign('data', $data);
        return $this->fetch();
    }

    /*退款编辑提交*/
    function edit_post(){
        if ($this->request->isPost()){
            
            $data = $this->request->param();
            
            $platform_result=$data['platform_result'];
            $id=$data['id'];
            $desc=trim($data['desc']);
            $orderid=$data['orderid'];

            //判断订单是否存在
            $order_info=getShopOrderInfo(['id'=>$orderid],"*");

            if(!$order_info){
                $this->error($Think.\lang('ORDER_NOT_EXIST'));
            }

            $status=$order_info['status'];

            /*if($status!=5){
                $this->error("订单未申请退款");
            }*/

            $refund_info=getShopOrderRefundInfo(['orderid'=>$orderid]);

            $refund_status=$refund_info['status'];

            if($refund_status==-1){
                $this->error($Think.\lang('BUYER_CANCELLED_REFUND_APPLICATION'));
            }

            if($refund_status==1){ //已经完成
                $this->error($Think.\lang('REFUND_PROCESSED'));
            }

            if($platform_result==""){
                $this->error($Think.\lang('SELECT_PROCESSING_RESULT'));
            }

            if($desc){
                if(mb_strlen($desc)>300){
                    $this->error($Think.\lang('HANDLING_OPINIOS_WITH_WORDS'));
                }
            }

            $now=time();

            if(!$platform_result){
            	$platform_result=-1;
            }

            $data['platform_process_time']=$now;
            $data['platform_result']=$platform_result;
            $data['admin']=cmf_get_current_admin_id();
            $data['ip']= ip2long($_SERVER["REMOTE_ADDR"]) ;
            $data['status']=1;

            unset($data['orderid']);
            unset($data['desc']);
            
            $res = DB::name('shop_order_refund')->update($data);

            if($res===false){
                $this->error($Think.\lang('UPDATE_FAILED'));
            }

            
            if($platform_result==1){ //平台同意退款

                //将钱退给买家
                setUserBalance($order_info['uid'],1,$order_info['total']);

                //添加余额操作记录
                $data1=array(
                    'uid'=>$order_info['uid'],
                    'touid'=>$order_info['shop_uid'],
                    'balance'=>$order_info['total'],
                    'type'=>1,
                    'action'=>6, //买家发起退款，平台介入后同意
                    'orderid'=>$orderid,
                    'addtime'=>$now

                );

                addBalanceRecord($data1);

                //处理订单状态
                $data2=array(
                    'refund_status'=>1,
                    'refund_endtime'=>$now,
                );

                changeShopOrderStatus($order_info['uid'],$orderid,$data2);

                //减去商品的销量
                changeShopGoodsSaleNums($order_info['goodsid'],0,$order_info['nums']);
                //减去店铺销量
                changeShopSaleNums($order_info['shop_uid'],0,$order_info['nums']);

                //给买家发送消息
                $title=$Think.\lang('YOUR_GOODS').$order_info['goods_name'].$Think.\lang('THE_PLATFORM_REFUND_INITATER');

                //写入订单消息列表
                $data3=array(
                    'title'=>$title,
                    'orderid'=>$orderid,
                    'uid'=>$order_info['uid'],
                    'addtime'=>$now,
                    'type'=>'0'
                );

                addShopGoodsOrderMessage($data3);
                //发送极光IM
                jMessageIM($title,$order_info['uid'],'goodsorder_admin');


                //给卖家发送消息
                $title1=$Think.\lang('BUYER_GOODS').$order_info['goods_name'].$Think.\lang('THE_PLATFORM_REFUND_INITATER');

                $data4=array(
                    'title'=>$title1,
                    'orderid'=>$orderid,
                    'uid'=>$order_info['shop_uid'],
                    'addtime'=>$now,
                    'type'=>'1'
                );

                addShopGoodsOrderMessage($data4);
                //发送极光IM
                jMessageIM($title,$order_info['shop_uid'],'goodsorder_admin');


                //退款协商记录
                $refund_history_data=array(
                    'orderid'=>$orderid,
                    'type'=>3, //平台
                    'addtime'=>$now,
                    'desc'=>$Think.\lang('THE_PLATFORM_AGREES_REFUND'),
                    'handle_desc'=>$desc
                );
				
				
				$action='编辑退款列表ID: '.$orderid.'--平台同意';
				setAdminLog($action);

            }else{ //平台拒绝

                //修改订单状态
                $data1=array(
                    'refund_status'=>-1, //退款失败
                    'refund_endtime'=>$now,
                );

                if($order_info['receive_time']>0){

                    $data1['status']=3; //待评价
                    
                }else{

                    if($order_info['shipment_time']>0){
                        $data1['status']=2; //待收货
                    }else{
                        $data1['status']=1; //待发货
                    } 
                }

                changeShopOrderStatus($order_info['uid'],$orderid,$data1);


                //给买家发送消息
                
                $title=$Think.\lang('YOUR_GOODS').$order_info['goods_name'].$Think.\lang('REJECTED_BY_THE_PLATFORM');

                //写入订单消息列表
                $data2=array(
                    'title'=>$title,
                    'orderid'=>$orderid,
                    'uid'=>$order_info['uid'],
                    'addtime'=>$now,
                    'type'=>'0'
                );

                addShopGoodsOrderMessage($data2);
                //发送极光IM
                jMessageIM($title,$order_info['uid'],'goodsorder_admin');

                //给卖家发送消息
                $title1=$Think.\lang('BUYER_GOODS').$order_info['goods_name'].$Think.\lang('REJECTED_BY_THE_PLATFORM');

                $data3=array(
                    'title'=>$title1,
                    'orderid'=>$orderid,
                    'uid'=>$order_info['shop_uid'],
                    'addtime'=>$now,
                    'type'=>'1'
                );

                addShopGoodsOrderMessage($data3);
                //发送极光IM
                jMessageIM($title,$order_info['shop_uid'],'goodsorder_admin');


                //退款协商记录
                $refund_history_data=array(
                    'orderid'=>$orderid,
                    'type'=>3,
                    'addtime'=>$now,
                    'desc'=>$Think.\lang('THE_PLATFORM_REFUSED_REFUND'),
                    'handle_desc'=>$desc
                );
				
				$action='编辑退款列表ID: '.$orderid.'--平台拒绝';
				setAdminLog($action);

            }

            //写入退款协商记录
            setGoodsOrderRefundList($refund_history_data);
            
            $this->success($Think.\lang('REFUND_PROCESSED_SUCESSFUNLLY'));
            
        }

    }

    //平台自营处理订单退款
    public function platformedit(){
        $data=$this->request->param();
        $id=$data['id'];

        $data=Db::name('shop_order_refund')
            ->where("id={$id}")
            ->find();
        if(!$data){
            $this->error($Think.\lang('INFORMATION_ERROR'));
        }

        //获取订单详情
        $orderinfo=getShopOrderInfo(['id'=>$data['orderid']],'orderno,phone');
        $data['orderno']=$orderinfo['orderno'];
        $data['phone']=$orderinfo['phone'];
        $data['buyer_info']=getUserInfo($data['uid']);

        //获取卖家拒绝退款原因
        $key='getRefundRefuseReason';
        $refuse_reason=getcaches($key);
        if(!$refuse_reason){
            $refuse_reason=DB::name('shop_refuse_reason')
                ->field("id,name")
                ->where('status=1')
                ->order("list_order asc,id desc")
                ->select();
        }
        
        
        $this->assign("status", $this->getStatus());
        $this->assign("type", $this->getType());
        $this->assign('data', $data);
        $this->assign('refuse_reason', $refuse_reason);
        return $this->fetch();

    }

    //平台自营处理退款
    public function platformedit_post(){

        $platform_info=Db::name("shop_apply")->where("uid=1")->find();
        if(!$platform_info){
            $this->error($Think.\lang('FILL_IN_STORE_INFORMATION'));
        }

        $data=$this->request->param();
        $id=$data['id'];
        $orderid=$data['orderid'];
        $type=$data['type'];
        $refuse_reason=$data['reason'];
        $refuse_desc=$data['refuse_desc'];

        //判断订单信息
        $where=array(
            'id'=>$orderid
        );

        $order_info=getShopOrderInfo($where);

        if(!$order_info){
            $this->error($Think.\lang('ORDER_NOT_EXIST'));
        }

        $status=$order_info['status'];
        if($status!=5){
            $this->error($Think.\lang('NOT_REFUND_REQUESTER_FOR_ORDER'));
        }

        //获取退款详情
        $where1=array(
            'orderid'=>$orderid
        );

        $refund_info=getShopOrderRefundInfo($where1);

        $refund_status=$refund_info['status'];
        $is_platform_interpose=$refund_info['is_platform_interpose'];
        $platform_result=$refund_info['platform_result'];
        $shop_result=$refund_info['shop_result'];
        $shop_process_num=$refund_info['shop_process_num'];

        if($refund_status==-1){
            $this->error($Think.\lang('BUYER_CANCELLED_REFUND_APPLICATION'));
        }

        if($refund_status==1){ //已经完成

            if($is_platform_interpose==1){ //平台介入

                if($platform_result==1){ //平台同意
                    $this->error($Think.\lang('PLATFORM_AGREED_TO_REFUND'));

                }elseif($is_platform_interpose==-1){ //平台拒绝
                    $this->error($Think.\lang('INTERVENTION_REFUSED_REFUND'));
                }

            }else{

                if($shop_result==1){ //卖家同意
                    $this->error($Think.\lang('PLATFORM_AGREED_REFUND'));
                }elseif($shop_result==-1){
                    $this->error($Think.\lang('PLATFORM_REFUSED_REFUND'));
                }

            }

        }else{

            if($shop_result==-1){
                $this->error($Think.\lang('PLATFORM_REJECTER_AND_CANNOT_AGAIN'));
            }elseif($shop_result==1){
                $this->error($Think.\lang('PLATFORM_AGREED_AND_CANNOT_AGAIN'));
            }

            if($shop_process_num>=3){
                $this->error($Think.\lang('PLATFORM_REJECTED').$shop_process_num.$Think.\lang('CANNOT_BE_AGAIN'));
            }

        }

        if($type==0){ //拒绝
            if(!$refuse_reason){
                $this->error($Think.\lang('SELECT_REASON_REJECTION'));
            }
            if(mb_strlen($refuse_desc)>300){
                $this->error($Think.\lang('THE_DETAILED_REASONS_WHTH_WORDS'));
            }

            //更新退款信息
            $where=array(
                'orderid'=>$orderid
            );

            $data=array(
                'shop_result'=>-1,
                'shop_process_time'=>time(),
                'shop_process_num'=>$shop_process_num+1,
            );

            $res=changeGoodsOrderRefund($where,$data);

            if(!$res){
                $rs['code']=1001;
                $rs['msg']=$Think.\lang('REFUND_PROCESSING_FAILED');
                return $rs;
            }

            //修改订单信息
            $data1=array(
                'refund_shop_result'=>-1 //卖家处理状态为拒绝
            );

            changeShopOrderStatus(1,$orderid,$data1);

            $title=$Think.\lang('YOUR_GOODS').$order_info['goods_name'].$Think.\lang('SELLER_REFUSED_REFUND');

            //写入订单消息列表
            $data3=array(
                'title'=>$title,
                'orderid'=>$orderid,
                'uid'=>$order_info['uid'],
                'addtime'=>time(),
                'type'=>'0'
            );

            addShopGoodsOrderMessage($data3);
            //发送极光IM
            jMessageIM($title,$order_info['uid'],'goodsorder_admin');

            $refund_history_data=array(
                'orderid'=>$orderid,
                'type'=>2,
                'addtime'=>time(),
                'desc'=>$Think.\lang('SELLER_REFUSED_REFUND'),
                'refuse_reason'=>$refuse_reason,
                'handle_desc'=>$refuse_desc
            );

        }else{ //自营店铺同意退款

            //更改退款信息
            
            $where=array(
                'orderid'=>$orderid
            );

            $data=array(
                'shop_result'=>1,
                'shop_process_time'=>time(),
                'status'=>1
            );

            $res=changeGoodsOrderRefund($where,$data);
            if(!$res){
                $this->error($Think.\lang('REFUND_PROCESSING_FAILED'));
            }

            //更改订单状态
            $data1=array(
                'refund_status'=>1,
                'refund_shop_result'=>1,
                'refund_endtime'=>time(),
            );

            changeShopOrderStatus(1,$orderid,$data1);

            //给买家退钱
            setUserBalance($order_info['uid'],1,$order_info['total']);

            //添加余额操作记录
            $data2=array(
                'uid'=>$order_info['uid'],
                'touid'=>$order_info['shop_uid'],
                'balance'=>$order_info['total'],
                'type'=>1,
                'action'=>5, //买家发起退款，卖家同意
                'orderid'=>$orderid,
                'addtime'=>time()

            );

            addBalanceRecord($data2);

            //减去商品销量
            changeShopGoodsSaleNums($order_info['goodsid'],0,$order_info['nums']);

            //减去店铺销量
            changeShopSaleNums($order_info['shop_uid'],0,$order_info['nums']);

            //商品规格库存回增
            changeShopGoodsSpecNum($order_info['goodsid'],$order_info['spec_id'],$order_info['nums'],1);

            $title=$Think.\lang('YOUR_GOODS').$order_info['goods_name'].$Think.\lang('SELLER_AGREED_REFUND');

            //写入订单消息列表
            $data3=array(
                'title'=>$title,
                'orderid'=>$orderid,
                'uid'=>$order_info['uid'],
                'addtime'=>time(),
                'type'=>'0'
            );

            addShopGoodsOrderMessage($data3);
            //发送极光IM
            jMessageIM($title,$order_info['uid'],'goodsorder_admin');
            

            $refund_history_data=array(
                'orderid'=>$orderid,
                'type'=>2,
                'addtime'=>time(),
                'desc'=>$Think.\lang('SELLER_AGREED_REFUND')
            );


        }

        //添加退款处理历史记录
        setGoodsOrderRefundList($refund_history_data);

        $this->success($Think.\lang('REFUND_PROCESSING_COMPLETED'));
    }


}

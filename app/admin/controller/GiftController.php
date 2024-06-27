<?php

/**
 * 礼物列表
 */
namespace app\admin\controller;
use cmf\controller\AdminBaseController;
use think\Db;
use think\db\Query;

class GiftController extends AdminbaseController {


    protected function getTypes($k=''){
        $type=[
            '0'=>$Think.\lang('NOMAL_GIFT'),
            '1'=>$Think.\lang('LUXURY_GIFT'),
            '2'=>$Think.\lang('HAND_PAINTED_GIFT'),
        ];
        if($k==''){
            return $type;
        }
        return isset($type[$k]) ? $type[$k]: '';
    }
	
	 protected function getMark($k=''){
        $mark=[
            '0'=>$Think.\lang('NORMAL'),
            '2'=>$Think.\lang('GUARDIAN'),
        ];
        if($k==''){
            return $mark;
        }
        return isset($mark[$k]) ? $mark[$k]: '';
    }

    protected function getSwftype($k=''){
        $swftype=[
            '0'=>'GIF',
            '1'=>'SVGA',
        ];
        if($k==''){
            return $swftype;
        }
        return isset($swftype[$k]) ? $swftype[$k]: '';
    }

    function index(){

        $lists = Db::name('gift')
            ->where(function (Query $query) {
                $data = $this->request->param();
                
                $keyword=isset($data['keyword']) ? $data['keyword']: '';

                if (!empty($keyword)) {
                    $query->where('giftname', 'like', "%$keyword%");
                }
            })
            ->order("orderno,addtime DESC")
            ->paginate(20);


        $lists->each(function($v,$k){
            $v['gifticon']=get_upload_path($v['gifticon']);

            $v['swf']=get_upload_path($v['swf']);
            
            return $v;
            
        });

        //分页-->筛选条件参数
        $data = $this->request->param();
        $lists->appends($data);
            
        // 获取分页显示
        $page = $lists->render();
            
        $this->assign('lists', $lists);
        $this->assign("page", $page);
        $this->assign('type',  $this->getTypes());
        $this->assign('mark',  $this->getMark());
        $this->assign('swftype', $this->getSwftype());
 	
    	return $this->fetch();
    }
		
	public function del(){

        $id=$this->request->param('id',0,'intval');
        if($id){
            $result=Db::name("gift")->where(["id"=>$id])->delete();
            if($result){
                $this->resetcache();
                $this->success($Think.\lang('DELETE_SUCCESS'));
            }else{
                $this->error($Think.\lang('DELETE_FAILED'));
            }			
        }else{				
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }								  			
	}

    //排序
    public function listordersset() { 
        
        $ids=$this->request->param('listorders');

        foreach ($ids as $key => $r) {
            $data['orderno'] = $r;
            Db::name("gift")->where(array('id' => $key))->update($data);
        }
                
        $status = true;
        if ($status) {

            $this->resetcache();
            $this->success($Think.\lang('SORTING_UPDATE_SUCCEEDED'));
        } else {
            $this->error($Think.\lang('SORTING_UPDATE_FAILED'));
        }
    }

    public function add(){

        $this->assign("type",$this->getTypes());
        $this->assign("mark",$this->getMark());
        $this->assign("swftype",$this->getSwftype());
        $this->assign("time",time());
        return $this->fetch();
    }

    public function add_post(){

        if($this->request->ispost()){

            $data=$this->request->param();
            $giftname=$data['giftname'];
            $type=$data['type'];
            $swftype=$data['swftype'];
            $needcoin=$data['needcoin'];
            $gifticon=$data['gifticon'];

            if(!$giftname){
                $this->error($Think.\lang('FILL_IN_NAME_OF_GIFT'));
            }

            if(!is_numeric($needcoin)){
                $this->error($Think.\lang('NUM_OF_GIFT_MUST_BE_NUM'));
            }

            if($needcoin<1){
                $this->error($Think.\lang('NUM_MUST_GREATER_THAN_ONE'));
            }

            if(floor($needcoin)!=$needcoin){
                $this->error($Think.\lang('GIFT_MUST_POSITIVE_INREGER'));
            }

            /*if(!$gifticon){
                $this->error("请上传礼物封面");
            }*/

            if($type==1 && $swftype==1){
                if($_FILES){
                        
                    $files["file"]=$_FILES["file"];

                    $type='image';

                    $uploadSetting = cmf_get_upload_setting();
                    $extensions=$uploadSetting['file_types']['file']['extensions'];
                    $allow=explode(",",$extensions);

                    if (!get_file_suffix($files['file']['name'],$allow)){
                        $this->error($Think.\lang('UPLOAD_THE_CORRECT_FORMAT'));
                    }
                    
                    $rs=adminUploadFiles($files,$type);

                    if($rs['code']!=0){
                        $this->error($rs['msg']);
                    }

                    $data['swf']=$rs['filepath'];

                }

            }

            unset($data['file']);

            $data['addtime']=time();


            $result=Db::name("gift")->insert($data);
            if($result){
                $this->resetcache();
                $this->success($Think.\lang('ADD_SUCCESS'));
            }else{
                $this->error($Think.\lang('ADD_FAILED'));
            }
        }
    }

    public function edit(){
        $id=$this->request->param('id',0,'intval');
		
		$gift=Db::name("gift")->where(['id'=>$id])->find();
		
		if(!$gift){
            $this->error($Think.\lang('DATA_TRANSFER_IN_FAILED'));
        }
			
		$this->assign("type",$this->getTypes());
		$this->assign("mark",$this->getMark());
		$this->assign("swftype",$this->getSwftype());
	
		$this->assign("time",time());
		$this->assign('gift', $gift);
       

        return $this->fetch();
    }


    public function edit_post(){
        if($this->request->ispost()){
            $data=$this->request->param();

            $type=$data['type'];
            $swftype=$data['swftype'];
            $giftname=$data['giftname'];
            $needcoin=$data['needcoin'];
            $gifticon=$data['gifticon'];

            if(!$giftname){
                $this->error($Think.\lang('FILL_IN_NAME_OF_GIFT'));
            }

            if(!is_numeric($needcoin)){
                $this->error($Think.\lang('NUM_OF_GIFT_MUST_BE_NUM'));
            }

            if($needcoin<1){
                $this->error($Think.\lang('NUM_MUST_GREATER_THAN_ONE'));
            }

            if(floor($needcoin)!=$needcoin){
                $this->error($Think.\lang('GIFT_MUST_POSITIVE_INREGER'));
            }

            if(!$gifticon){
                $this->error($Think.\lang('UPLOAD_GIFT_COVER'));
            }

            if($type==1 && $swftype==1){

                if($_FILES){
                        
                    $files["file"]=$_FILES["file"];
                    $type='image';
                    
                    $rs=adminUploadFiles($files,$type);

                    //var_dump($rs);

                    if($rs['code']!=0){
                        $this->error($rs['msg']);
                    }

                    $data['swf']=$rs['filepath'];

                }

            }

            unset($data['file']);

            $result=Db::name("gift")->update($data);
            if($result!==false){
                $this->resetcache();
                $this->success($Think.\lang('UPDATE_SUCCESSFULLY'));
            }else{
                $this->error($Think.\lang('UPDATE_FAILED'));
            }
        }
    }

    public function resetcache(){
        $key='getGiftList';
        
        $rs=Db::name('gift')
            ->field("id,type,mark,giftname,needcoin,gifticon,swftype,swf,swftime")
            ->order("orderno asc,addtime desc")
            ->select();

        $rs->each(function($v,$k){
            $v['gifticon']=get_upload_path($v['gifticon']);
            $v['swf']=get_upload_path($v['swf']);
            return $v;
        });


        if($rs){
            setcaches($key,$rs);
        }   
        return 1;
    }

    
}

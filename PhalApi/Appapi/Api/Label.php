<?php

class Api_Label extends PhalApi_Api {  

	public function getRules() {
		return array(
            'searchLabel' => array(
				'key' => array('name' => 'key', 'type' => 'string', 'desc' => '关键词'),
                'p' => array('name' => 'p', 'type' => 'int','default'=>'1', 'desc' => '页码'),
			),
            
            'getLabel' => array(
				'uid' => array('name' => 'uid', 'type' => 'int', 'desc' => '用户ID'),
				'labelid' => array('name' => 'labelid', 'type' => 'int', 'desc' => '标签ID'),
                'p' => array('name' => 'p', 'type' => 'int','default'=>'1', 'desc' => '页码'),
			),

		);
	}

	/**
	 * 获取视频话题标签列表 
	 * @desc 用于获取视频话题标签列表 
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id
	 * @return string info[].name 标题
	 * @return string msg 提示信息
	 */
	public function getList() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        

        $domain = new Domain_Label();
        $info = $domain->getList();

		$rs['info']=$info;

		return $rs;
	}

	/**
	 * 搜索视频话题标签
	 * @desc 用于搜索视频话题标签
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return string info[].id
	 * @return string info[].name 标题
	 * @return string msg 提示信息
	 */
	public function searchLabel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $key=checkNull($this->key);
        $p=checkNull($this->p);
        
        if($key==''){
            $rs['code'] = 1001;
            $rs['msg'] = "请输入话题标签";
            return $rs;
            
        }
        $domain = new Domain_Label();
        $info = $domain->searchLabel($key,$p);

		$rs['info']=$info;

		return $rs;
	}
    
	/**
	 * 获取视频话题标签信息
	 * @desc 用于获取标签信息
	 * @return int code 操作码，0表示成功
	 * @return array info 
	 * @return object info[0].label
	 * @return string info[0].label.id
	 * @return string info[0].label.name 名称
	 * @return string info[0].label.des 描述
	 * @return string info[0].label.thumb 封面
	 * @return string info[0].count  标签下视频总数
	 * @return array info[0].list  视频列表
	 * @return string info[0].list[].isshop 是否商品视频
	 * @return string msg 提示信息
	 */
	public function getLabel() {
		$rs = array('code' => 0, 'msg' => '', 'info' => array());
        
        $uid=checkNull($this->uid);
        $labelid=checkNull($this->labelid);
        $p=checkNull($this->p);
        
        if($labelid<1){
            $rs['code'] = 1001;
            $rs['msg'] = $Think.\lang('INFORMATION_ERROR');
            return $rs;
        }
        
        $domain = new Domain_Label();
        $info = $domain->getLabel($labelid);
		if(!$info){
			$rs['code'] = 1001;
            $rs['msg'] = "话题已被删除~";
            return $rs;
		}
        
        $count = $domain->getVideos($labelid);
        
        
        $domain2 = new Domain_Video();
        $list = $domain2->getLabelVideoList($uid,$labelid,$p);

		$rs['info'][0]['label']=$info;
		$rs['info'][0]['count']=$count;
		$rs['info'][0]['list']=$list;

		return $rs;
	}

} 

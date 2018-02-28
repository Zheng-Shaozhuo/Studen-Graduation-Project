<?php
	namespace Ucenter\Controller;
	use Think\Controller;
	class BaseController extends Controller {
    
    function _initialize(){
    	
		$flag = session('FLAG') ? true : false;
		if(!$flag){
			$this->error("请先行登陆", U('Index/index'));
		}else{
			$this->assign('usrName', session('NAME'));
		}
    }
}
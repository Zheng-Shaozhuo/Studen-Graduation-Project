<?php
    namespace Admin\Controller;
    use Think\Controller;
    class BaseController extends Controller {
        function _initialize(){
            $flag = session("flag") ? true : false;

            if(!$flag){
                $this->error("请先行登陆", U("Index/index"));
            }else{
                layout("Public/tplLayout");

                $flag = array();
                $flag['prt'] = strtolower(CONTROLLER_NAME);
                $flag['son'] = strtolower(CONTROLLER_NAME) . '_' . strtolower(ACTION_NAME);
                $this->assign('flag', $flag);
            }
        }
    }
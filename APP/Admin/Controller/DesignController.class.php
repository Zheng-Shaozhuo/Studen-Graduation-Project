<?php
    namespace Admin\Controller;
    use Think\Controller;
    class DesignController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:15:54
         *  后台管理中心-毕设列表
         */
        public function index(){
            $titles = array();
            $titles['prt'] = "毕设";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "登录信息";
            $this->assign("titles", $titles);

            $this->display();
        }

        /*
         *  2015年3月8日22:16:09
         *  后台管理中心-回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "毕设";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "登录信息";
            $this->assign("titles", $titles);

            $this->display();
        }
    }
<?php
    namespace Admin\Controller;
    use Think\Controller;
    class MsgController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:16:30
         *  后台管理中心-消息列表
         */
        public function index(){
            $titles = array();
            $titles['prt'] = "消息";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "消息列表";
            $this->assign("titles", $titles);

            $this->display();
        }

        /*
         *  2015年3月8日22:10:47
         *  后台管理中心-回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "消息";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "回收站";
            $this->assign("titles", $titles);

            $this->display();
        }
    }
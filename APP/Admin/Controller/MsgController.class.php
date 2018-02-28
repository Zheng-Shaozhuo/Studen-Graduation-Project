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
        public function index($keys = null, $receive = null){
            $titles = array();
            $titles['prt'] = "消息";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "消息列表";
            $this->assign("titles", $titles);

            $where = array();
            $seachData['keys'] = $keys;
            if(isset($keys) && !empty($keys)){
                $where['msgContent'] = array("like", "%{$keys}%");
            }
            $seachData['receive'] = $receive;
            if(isset($receive) && !empty($receive)){
                switch ($receive) {
                    case 1:
                        $where['message.state'] = array("in", array(2, -2));
                        break;
                    case 2:
                        $where['message.state'] = -1;
                        break;
                    case 3:
                        $where['message.state'] = 1;
                        break;
                }
            }
            $this->assign("seachData", $seachData);

            $obj    = M('message'); // 实例化User对象
            $count  = $obj->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $List = $obj->field('msgId, msgFromId, msgAccessId, msgContent, createTime, state')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
           
            foreach ($List as &$v) {
                if($v['state'] == 2){
                    $v['msgFromId'] = "管理员";
                    $v['msgAccessId'] = "所有学生";
                }else if($v['state'] == -2){
                    $v['msgFromId'] = "管理员";
                    $v['msgAccessId'] = "所有教师";
                }else if($v['state'] == 1){
                    unset($info);
                    $info = $obj->join("left join teacher on teacher.thrId = message.msgAccessId")->join("left join student on student.stuId = message.msgFromId")->field("thrRealName, stuRealName")->where(array('msgId' => $v['msgId']))->find();
                    $v['msgFromId'] = $info['stuRealName'];
                    $v['msgAccessId'] = $info['thrRealName'];
                }else if($v['state'] == -1){
                    unset($info);
                    $info = $obj->join("left join teacher on teacher.thrId = message.msgFromId")->join("left join student on student.stuId = message.msgAccessId")->field("thrRealName, stuRealName")->where(array('msgId' => $v['msgId']))->find();
                    $v['msgFromId'] = $info['thrRealName'];
                    $v['msgAccessId'] = $info['stuRealName'];
                }
            }

            $this->assign('List', $List);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

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


        /*
         *  2015年4月18日17:37:16
         *  新增消息
         */
        public function addMsg(){
            if(IS_POST){
                $data['msgParentId'] = 0;
                $data['msgFromId'] = -999;
                $data['msgAccessId'] = -999;
                $f = I("post.receive");
                $data['msgContent'] = I("post.content");
                $data['createTime'] = time();
                $data['showStu'] = 1;
                $data['showThr'] = 1;

                $obj = M('message');
                if($f == 1){
                    $data['state'] = -2;
                    if($obj->add($data)){
                        $data['state'] = 2;
                        $flag = $obj->add($data);
                    }else{
                        $flag = false;
                    }
                }else if($f == 2){
                    $data['state'] = -2;
                    $flag = $obj->add($data);
                }else if($f == 3){
                    $data['state'] = 2;
                    $flag = $obj->add($data);

                }

                if($flag){
                    $this->success("消息发送成功");
                }else{
                    $this->error("消息发送失败");
                }
            }
        }

        /*
         *  2015年4月18日18:32:39
         *  中心删除消息
         */
        public function delMsg($id = 0){
            if($id != 0){
                $where["msgId"] = $id;

                $obj = M("message");
                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success("消息删除成功");
                }else{
                    $this->error("消息删除失败");
                }
            }else{
                $this->error("操作失败，请检查");
            }
        }
    }
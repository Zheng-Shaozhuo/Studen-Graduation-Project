<?php
    namespace Admin\Controller;
    use Think\Controller;
    class UsrController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:18:06
         *  后台管理中心-用户列表
         */
        public function index(){
            $titles = array();
            $titles['prt'] = "用户";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "用户列表";
            $this->assign("titles", $titles);

            $obj = M('admin');
            // $where['state'] = 1;
            $usrList = $obj->field('adminId, adminName, adminRealName, adminSex, adminPhone, adminEmail, state')->where($where)->select();
            $this->assign("usrList", $usrList);
            
            $this->display();
        }

        /*
         *  2015年3月8日22:18:22
         *  后台管理中心-新增用户
         */
        public function add(){
            $titles = array();
            $titles['prt'] = "用户";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "新增用户";
            $this->assign("titles", $titles);

            $this->display();
        }

        /*
         *  2015年3月8日22:18:43
         *  后台管理中心-回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "用户";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "回收站";
            $this->assign("titles", $titles);

            $obj = M('admin');
            $where['state'] = -1;
            $usrList = $obj->field('adminId, adminName, adminRealName, adminSex, adminPhone, adminEmail')->where($where)->select();
            $this->assign("usrList", $usrList);

            $this->display();
        }


        /*
         *  2015年3月10日10:35:49
         *  新增用户，写入数据库
         */
        public function addusr(){

            if(IS_POST){
                $data = array();
                $data['adminName']      = I('post.name');
                $data['adminPwd']       = md5(I('post.pwd'));
                $data['adminRealName']  = I('post.realname');
                $data['adminSex']       = I('post.sex');
                $data['adminAge']       = I('post.age');
                $data['adminPhone']     = I('post.phone') ? I('post.phone') : null;
                $data['adminEmail']     = I('post.email') ? I('post.email') : null;
                $data['adminAddress']   = I('post.address') ? I('post.address') : null;
                $tmpTime = time();
                $data['createTime']     = $tmpTime;
                $data['updateTime']     = $tmpTime;
                $data['state']          = I("post.state");

                $obj = M('admin');
                $flag = $obj->add($data);
                if($flag){
                    $this->success("新用户添加成功");
                }else{
                    $this->error("用户添加失败，请检查");
                }
            }
        }

        /*
         *  2015年3月10日10:35:49
         *  重置用户密码
         */
        public function reset($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('admin');
                $where['adminId'] = $id;

                $data['adminPwd'] = md5(123456);

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('用户密码重置成功');
                }else{
                    $this->error('用户密码重置失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日13:34:25
         *  将用户移动至回收站
         */
        public function toRecycle($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('admin');
                $where['adminId'] = $id;

                //目前，用户级别仅有系统管理员一级
                $data['state'] = -1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('删除成功，已将该用户移动至回收站');
                }else{
                    $this->error('删除失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:13:23
         *  将用户状态恢复
         */
        public function recoverOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('admin');
                $where['adminId'] = $id;

                //目前，用户级别仅有系统管理员一级
                $data['state'] = 1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('恢复成功，已将该用户恢复至正常');
                }else{
                    $this->error('恢复失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:14:12
         *  将用户物理删除
         */
        public function clearOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('admin');
                $where['adminId'] = $id;

                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success('清除成功成功');
                }else{
                    $this->error('清除失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:35:33
         *  查看用户详情
         */
        public function checkDetail(){
            if(IS_POST){
                $id = I('post.id');
                $where['adminId'] = isset($id) ? $id : 0;

                $obj = M('admin');
                $usrDetail = $obj->field('adminId, adminName, adminRealName, adminSex, adminAge, adminPhone, adminEmail, adminAddress, state')->where($where)->find();
                if(is_array($usrDetail) && !empty($usrDetail)){
                    echo json_encode(array('state' => true, 'detail' => $usrDetail));
                }else{
                    echo json_encode(array('state' => false, 'detail' => array()));
                }
            }
        }
    }
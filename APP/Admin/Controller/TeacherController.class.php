<?php
    namespace Admin\Controller;
    use Think\Controller;
    class TeacherController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:11:36
         *  后台管理中心-教师列表
         */
        public function index($thrCard = null, $thrName = null, $thrSex = null, $thrMajor = null){
            $titles = array();
            $titles['prt'] = "教师";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "教师列表";
            $this->assign("titles", $titles);

            $obj = M("major");
            $majorList = $obj->select();
            $this->assign("majorList", $majorList);

            $where = array();
            $where['state'] = 1;
            $seachData['thrCard'] = $thrCard;
            if(isset($thrCard) && !empty($thrCard)){
                $where['thrName'] = array("like", "%{$thrCard}%");
            }
            $seachData['thrName'] = $thrName;
            if(isset($thrName) && !empty($thrName)){
                $where['thrRealName'] = $thrName;
            }
            $seachData['thrSex'] = $thrSex;
            if(isset($thrSex) && !empty($thrSex)){
                $where['thrSex'] = $thrSex;
            }
            $this->assign("seachData", $seachData);
            
            $obj    = M('teacher'); // 实例化User对象
            $count  = $obj->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $usrList = $obj->field('thrId, thrName, thrRealName, thrSex, thrStudy, thrPhone')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('usrList', $usrList);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出

            $this->display();
        }

        /*
         *  2015年3月8日22:10:47
         *  后台管理中心-新增教师
         */
        public function add(){
            $titles = array();
            $titles['prt'] = "教师";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "新增教师";
            $this->assign("titles", $titles);

            $this->display();
        }

        /*
         *  2015年3月8日22:10:47
         *  后台管理中心-教师数据回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "教师";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "回收站";
            $this->assign("titles", $titles);

            $obj = M('teacher');
            $usrList = $obj->field('thrId, thrName, thrRealName, thrSex, thrStudy, thrPhone')->where(array('state' => -1))->select();
            $this->assign('usrList', $usrList);


            $this->display();
        }

        /*
         *  2015年3月11日9:54:13
         *  后台管理中心-数据库插入教师数据
         */
        public function addUsr(){
            if(IS_POST){
                $data['thrName'] = I('post.name');
                $data['thrRealName'] = I('post.realName');
                $data['thrSex'] = I('post.sex');

                $pwd = I('post.pwd');
                $obj = M('teacher');

                if($obj->where($data)->Count() == 1){
                    $this->error("已存在与之姓名、性别相同的教师，本次插入失败");
                    return ;
                }
                $data['thrPwd'] = isset($pwd) && $pwd != "" ? md5($pwd) : md5(888888);
                $time = time();
                $data['updateTime'] = $time;
                $data['createTime'] = $time;

                if($obj->add($data)){
                    $this->success("教师信息新增成功");
                }else{
                    $this->error("教师信息新增失败，请检查");
                }
            }
        }

        /*
         *  2015年3月10日14:35:33
         *  查看教师详情
         */
        public function checkDetail(){
            if(IS_POST){
                $id = I('post.id');
                $where['thrId'] = isset($id) ? $id : 0;

                $obj = M('teacher');
                $usrDetail = $obj->field('thrId, thrName, thrRealName, thrSex, thrAge, thrStudy, thrPhone, thrEmail, thrAddress')->where($where)->find();
                if(is_array($usrDetail) && !empty($usrDetail)){
                    echo json_encode(array('state' => true, 'detail' => $usrDetail));
                }else{
                    echo json_encode(array('state' => false, 'detail' => array()));
                }
            }
        }

        /*
         *  2015年3月10日10:35:49
         *  重置教师密码
         */
        public function reset($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('teacher');
                $where['thrId'] = $id;

                $data['thrPwd'] = md5(888888);

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('教师密码重置成功');
                }else{
                    $this->error('教师密码重置失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日13:34:25
         *  将教师移动至回收站
         */
        public function toRecycle($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('teacher');
                $where['thrId'] = $id;

                $data['state'] = -1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('删除成功，已将该教师移动至回收站');
                }else{
                    $this->error('删除失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:13:23
         *  将教师状态恢复
         */
        public function recoverOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('teacher');
                $where['thrId'] = $id;

                $data['state'] = 1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('恢复成功，已将该教师恢复至正常');
                }else{
                    $this->error('恢复失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:14:12
         *  将教师物理删除
         */
        public function clearOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('teacher');
                $where['thrId'] = $id;

                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success('清除成功成功');
                }else{
                    $this->error('清除失败，请检查');
                }
            }
        }
    }
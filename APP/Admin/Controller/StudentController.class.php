<?php
    namespace Admin\Controller;
    use Think\Controller;
    class StudentController extends BaseController {
        function __construct(){
            parent::__construct();
        }

        /*
         *  2015年3月8日22:14:22
         *  后台管理中心-新增教师
         */
        public function index($stuCard = null, $stuName = null, $stuSex = null, $stuMajor = null){
            $titles = array();
            $titles['prt'] = "学生";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "学生列表";
            $this->assign("titles", $titles);

            $obj = M("major");
            $majorList = $obj->select();
            $this->assign("majorList", $majorList);

            $where = array();
            $where['state'] = 1;
            $seachData['stuCard'] = $stuCard;
            if(isset($stuCard) && !empty($stuCard)){
                $where['stuCard'] = array("like", "%{$stuCard}%");
            }
            $seachData['stuName'] = $stuName;
            if(isset($stuName) && !empty($stuName)){
                $where['stuRealName'] = $stuName;
            }
            $seachData['stuSex'] = $stuSex;
            if(isset($stuSex) && !empty($stuSex)){
                $where['stuSex'] = $stuSex;
            }
            $seachData['stuMajor'] = $stuMajor;
            if(isset($stuMajor) && !empty($stuMajor)){
                $where['major.majorId'] = $stuMajor;
            }
            $this->assign("seachData", $seachData);
            
            $obj    = M('student'); // 实例化User对象
            $count  = $obj->where($where)->Count();// 查询满足要求的总记录数
            $Page   = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show   = $Page->show();// 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $usrList = $obj->join('left join major on student.stuMajor = major.majorId')->field('stuId, stuCard, stuRealName, stuSex, stuPhone, major.majorName')->order('stuCard asc')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('usrList', $usrList);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
            $this->display(); // 输出模板
        }

        /*
         *  2015年3月8日22:14:57
         *  后台管理中心-新增学生
         */
        public function add(){
            $titles = array();
            $titles['prt'] = "学生";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "新增学生";
            $this->assign("titles", $titles);

            $this->display();
        }

        /*
         *  2015年3月8日22:15:02
         *  后台管理中心-回收站
         */
        public function recycle(){
            $titles = array();
            $titles['prt'] = "学生";
            $titles['prtLink'] = CONTROLLER_NAME;
            $titles['son'] = "回收站";
            $this->assign("titles", $titles);

            $obj = M('student');
            $usrList = $obj->join('left join major on student.stuMajor = major.majorId')->field('stuId, stuCard, stuRealName, stuSex, stuPhone, major.majorName')->order('stuCard asc')->where(array('state' => -1))->select();
            $this->assign('usrList', $usrList);

            $this->display();
        }

        /*
         *  2015年3月10日15:49:35
         *  后台管理中心-数据库插入学生数据
         */
        public function addUsr(){
            if(IS_POST){
                $flag = I("post.account");
                if($flag == 'single'){
                    $account = I("post.singleAccount");
                    if(!is_numeric($account)){
                        $this->error('登陆账号为学号，纯数字，请检查你的输入');
                        return ;
                    }


                    $data['stuCard'] = $account;
                    $pwd = I("post.pwd");
                    $data['stuPwd'] = isset($pwd) && $pwd != "" ? md5($pwd) : md5(666666);
                    $data['stuRealName'] = I("post.name");
                    $data['stuSex'] = I("post.sex");
                    $data['state'] = 1;
                    $time = time();
                    $data['updateTime'] = $time;
                    $data['createTime'] = $time;

                    $obj = M('student');
                    if($obj->where(array('stuCard' => $account))->Count() == 1){
                        $this->error('该账号已存在于数据库，无法进行添加操作');
                        return;
                    }


                    $flag = $obj->add($data);
                    if($flag){
                        $this->success("学生新增成功");
                    }else{
                        $this->error("学生新增失败");
                    }
                }else{
                    $account = I("post.mulAccount");
                    $cardArray = explode("-", $account);

                    if(count($cardArray) == 2 && is_numeric($cardArray[0]) && is_numeric($cardArray[1]) && $cardArray[1] - $cardArray[0] <= 200){
                        $pwd = I("post.pwd");
                        $data['stuPwd'] = isset($pwd) && $pwd != "" ? md5($pwd) : md5(666666);
                        $data['stuSex'] = I("post.sex");
                        $data['state'] = 1;

                        $repeatCount = 0;
                        $state = true;
                        $obj = M('student');
                        $obj->startTrans();
                        for($i = $cardArray[0]; $i <= $cardArray[1]; $i++){
                            $data['stuCard'] = $i;
                            $time = time();
                            $data['updateTime'] = $time;
                            $data['createTime'] = $time;

                            if($obj->where(array('stuCard' => $i))->Count() == 1){
                                $repeatCount++;
                                continue;
                            }

                            $flag = $obj->add($data);
                            if(!$flag){
                                $state = false;
                            }
                        }

                        if($state){
                            $obj->commit();
                            $this->success("批量添加学生成功, 重复数量共{$repeatCount}, 已跳过");
                        }else{
                            $obj->rollback();
                            $this->error("批量添学生失败");
                        }

                    }else{
                        $this->error("登陆账号格式或者范围有误，请检查");
                    }
                }
            }
        }

        /*
         *  2015年3月10日14:35:33
         *  查看学生详情
         */
        public function checkDetail(){
            if(IS_POST){
                $id = I('post.id');
                $where['stuId'] = isset($id) ? $id : 0;

                $obj = M('student');
                $usrDetail = $obj->join('left join major on student.stuMajor = major.majorId')->field('stuCard, stuRealName, major.majorName, stuSex, stuAge, stuPhone, stuEmail')->where($where)->find();
                if(is_array($usrDetail) && !empty($usrDetail)){
                    echo json_encode(array('state' => true, 'detail' => $usrDetail));
                }else{
                    echo json_encode(array('state' => false, 'detail' => array()));
                }
            }
        }

        /*
         *  2015年3月10日10:35:49
         *  重置学生密码
         */
        public function reset($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('student');
                $where['stuId'] = $id;

                $data['stuPwd'] = md5(666666);

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('学生密码重置成功');
                }else{
                    $this->error('学生密码重置失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日13:34:25
         *  将学生移动至回收站
         */
        public function toRecycle($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('student');
                $where['stuId'] = $id;

                $data['state'] = -1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('删除成功，已将该学生移动至回收站');
                }else{
                    $this->error('删除失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:13:23
         *  将学生状态恢复
         */
        public function recoverOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('student');
                $where['stuId'] = $id;

                $data['state'] = 1;

                $flag = $obj->where($where)->save($data);
                if($flag){
                    $this->success('恢复成功，已将该学生恢复至正常');
                }else{
                    $this->error('恢复失败，请检查');
                }
            }
        }

        /*
         *  2015年3月10日14:14:12
         *  将学生物理删除
         */
        public function clearOne($id = 0){
            if($id == 0){
                $this->error('操作错误，请检查您的操作');
            }else{
                $obj = M('student');
                $where['stuId'] = $id;

                $flag = $obj->where($where)->delete();
                if($flag){
                    $this->success('清除成功成功');
                }else{
                    $this->error('清除失败，请检查');
                }
            }
        }
    }
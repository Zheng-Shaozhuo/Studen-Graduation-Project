## Studen-Graduation-Project
# 本科生毕业设计 基于Thinkphp3.2的毕设选题系统 

摘   要
目前，大部分高校已开始应用较为完善的管理系统，如教务管理系统、学生选课管理系统等信息化管理系统，但是针对于学生毕业设计的相关管理操作，部分院校仍使用传统的工作模式，为提高管理的效率，我们设计开发“毕业设计网上管理系统”。该系统基于B/S架构进行设计搭建，整体开发使用MVC设计模式，所使用的动态网页开发语言为时下流行的PHP语言，前台采用HTML5、CSS3即Media Query技术开发的管理系统，B/S架构避免了因操作系统差异而导致的跨平台问题，MVC设计模式使得逻辑操作、数据处理和页面展示相分离，在一定程度上提升系统开发效率。
本系统由3大模块构成，分别是系统管理模块、教师操作模块以及学生操作模块。系统管理模块可对教师、学生个人信息和消息进行管理，对教师申报课题进行审核，并且可发送系统消息，便于通知；教师操作模块可以申报课题，浏览已通过审核课题的学生选题状况，查看已选题学生的进度情况及消息通知；学生操作模块可以选题，查看该课题教师可公布的联系方式，对已选但未确定的课题进行退选操作，浏览课题情况，提交毕设进度及消息通知等。该系统的使用将提高毕业设计这一环节的工作效率。

关键词：B/S架构；MVC设计框架；毕业设计选题；管理系统

Abstract

Nowadays, most colleges and universities have been in use for more perfect management system, such as educational administration management system, student course selection management system of information management system, but in view of the student of graduation design related management operation, some colleges and universities are still using the traditional working mode, to provide the efficiency of information management, we designed and developed "graduation design online management system". The system based on B / S architecture was designed and built. The overall development using the MVC design pattern, the use of dynamic web development language too popular PHP language, the front desk using the HTML 5 and CSS 3 Media Query technology development management system management system, B/S structure to avoid the problem caused by different operating system cross-platform, MVC design pattern makes the logic operation, data processing and the page display phase separation, in a certain extent, improve the efficiency of system development.
This system has three modules, respectively, the administrator module, teacher module and student module. Administrators can manage personal information and message to teachers and students, the teachers declare project audit, and can send messages to inform; Teacher module can declare topic, browse has passed the audit subject subject status of students, view has been selected topic the student's progress, and notification; Topic selection, students can view the subject teachers can be released by the contact information, for the selected topic for withdrawal operation, but has not yet been determined through project, submit the project schedule and alerts, etc. The use of the system will improve the graduation design of this part of the work efficiency.

Keywords：B / S architecture; MVC design framework; graduate design topics; management system

## 1需求分析
1.1 教师需求  
毕业设计选题环节中，教师的需求为：用户个人信息管理；新课题申报，未通过审核课题的删除、修改、重新申报等操作；课题已选学生的选定；课题进度查看以及消息管理。  
1.2 学生需求
毕业设计选题环节中，学生的需求为：用户个人信息管理；课题进行浏览查看、选择及退选等操作；如课题已被确定，课题详情查看；课题进度管理；消息发送、接受等管理操作。  
1.3 管理员需求  
毕业设计选题环节中，系统管理员的需求为：学生管理（新增学生、删除学生及学生登录系统数据的初始化操作）、教师管理（新增教师、删除教师及教师系统登录数据的初始化操作）、课题管理（对教师新提交课题进行审核处理、对违规课题进行删除操作）、用户管理（新增各权限级的管理员、删除管理员、对子集权限组管理员系统登录数据的初始化操作）以及消息通知（发布不同对象的系统消息、对所有消息进行查看及删除操作）。
## 2设计模式  
2.1 MVC设计模式  
MVC（Model View Controller）设计模式，是模型-视图-控制器的缩写，作为一种分层设计理念，它的目的是实现一种动态的、可分离的程序设计，在后续对于程序的修改和扩展时更加简化，并且提高程序某部分的重用率 。  
2.2 RBAC权限模型  
RBAC（Role-Based Access Control）基于角色的访问控制，系统权限与用户角色相关联，用户通过成为适当角色的成员从而得到对应的权限，毕业设计选题管理系统系统管理员权限控制基于RBAC模型，以角色为基础的访问控制模型是一套较强制访问控制以及自由选定访问控制更为中性且更具灵活性的访问控制技术。  
## 3系统总体设计  
3.1 总体功能模块
![系统结构图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/xitongjiegoutu.jpg) 
3.1.1 教师模块  
教师模块主要页面有个人管理、新增课题、课题列表管理、消息管理、进度列表管理。  
3.1.2 学生模块  
学生模块主要页面有个人管理、我的课题、课题列表、课题选择、消息管理、进度管理。  
3.1.3 管理员模块  
管理员模块主要页面有个人管理、学生列表管理、教师列表管理、课题列表管理、消息列表管理、用户列表管理。  
3.2 界面设计  
3.2.1 教师界面  
教师界面设计布局为横向导航条、面包屑导航及页面内容构成，横向导航条鼠标悬浮其背景将变色，选中栏目的背景色异于未选中背景色，面包屑导航当前栏目不可操作，可直接返回值父级栏目，使用Media Query技术，实现响应式布局显示，如图所示，为教师成功登录后页面。
![教师成功登录图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/jiaoshichenggongdenglutu.png)
3.2.2 学生界面  
学生界面设计布局为侧边栏导航、面包屑导航及页面内容构成，侧边栏导航条鼠标悬浮其背景将变色，选中栏目的背景色异于未选中背景色，面包屑导航当前栏目不可操作，可直接返回值父级栏目，使用布局定位及JS技术，实现侧边栏导航的滑动显示及隐藏，页面可实现响应式布局，如图所示，为学生成功登录后页面。  
![学生成功登录图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/xueshengchenggongdenglutu.png)  
3.2.3 管理员界面  
后台管理员界面设计布局为组合导航（横向导航栏为各一级栏目，侧边栏导航为相应一级栏目下的子栏目）、面包屑导航及页面内容构成，选中一级、二级栏目的背景色异于未选中背景色，面包屑导航当前栏目不可操作，可直接返回值父级栏目，页面为响应式布局，如图所示，为超级管理员成功登录后页面。  
![超级管理员成功登录图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/chaojiguanliyuanchenggongdenglutu.png)  
3.3 数据库设计  
3.3.1 数据库实体关系图  
根据系统的需求分析，毕业论文选题系统数据实体关系图，如图所示：  
![数据库实体关系ER图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/shujukuertu.jpg)  
3.3.2 数据库约束关系图  
毕业论文选题系统数据库应包含的数据表主要有： 系统管理员表、 管理员组权限表、专业信息表、 教师信息表、 学生信息表、课题进度表、 课题信息表、选题信息表、消息表，各表之间的约束关系如图所示：  
![数据库表约束关系图](https://github.com/Zheng-Shaozhuo/github-readme.md-resource/blob/master/Studen-Graduation-Project/imgs/shujukubiaoyueshutu.png)  
3.3.3 数据表结构
该系统应用了的数据表有，管理员表、学生表、教师表、课题信息表、消息表、权限表、专业表、课题进度表、选题表，以下对各表做一简单概述：
1、管理员表与权限表相关联构成具有不同管理权限的管理员角色，该表设计包括：编号、登陆账号、登陆密码等字段，如表所示：  
管理员表  
序号	列名	数据类型	主键	允许空值	备注  
1	adminId	int	是	否	主键ID  
2	adminName	Varchar(32)	否	否	登陆账号  
3	adminPwd	Varchar(32)	否	否	登录密码  
4	adminRealName	Varchar(32)	否	是	真实姓名  
5	adminSex	Tinyint	否	是	用户性别  
6	adminAge	Tinyint	否	是	用户年龄  
7	adminPhone	Varchar(11)	否	是	联系方式  
8	adminEmail	Varchar(32)	否	是	邮件地址  
9	adminAddress	Varchar(256)	否	是	住址  
10	createTime	Varchar(12)	否	否	创建时间  
11	updateTime	Varchar(12)	否	否	更新时间  
12	state	Tinyint	否	否	权限组  
2、学生表主要用来存储学生的个人信息以及学业相关信息，与专业表相关联可获取学生专业信息，该表设计包括：编号、登陆账号、登陆密码等字段，如表所示：  
学生表  
序号	列名	数据类型	主键	允许空值	备注  
1	stuId	int	是	否	主键ID  
2	stuCard	Varchar(32)	否	否	登陆账号   
3	stuPwd	Varchar(32)	否	否	登录密码  
4	stuRealName	Varchar(32)	否	是	真实姓名  
5	stuSex	Tinyint	否	是	用户性别  
6	stuAge	Tinyint	否	是	用户年龄  
7	stuPhone	Varchar(11)	否	是	联系方式  
8	stuEmail	Varchar(32)	否	是	邮件地址  
9	stuMajor	Tinyint	否	是	专业信息  
10	createTime	Varchar(12)	否	否	创建时间  
11	updateTime	Varchar(12)	否	否	更新时间  
12	state	Tinyint	否	否	  
3、教师表主要用来存储教师的个人信息，与课题信息表相关联，该表设计包括：编号、登陆账号、登陆密码等字段，如表所示：  
学生表  
序号	列名	数据类型	主键	允许空值	备注  
1	thrId	int	是	否	主键ID  
2	thrName	Varchar(32)	否	否	登陆账号  
3	thrPwd	Varchar(32)	否	否	登录密码  
4	thrRealName	Varchar(32)	否	是	真实姓名  
5	thrSex	Tinyint	否	是	用户性别  
6	thrAge	Tinyint	否	是	用户年龄  
7	thrStudy	Varchar(128)	否	是	研究方向  
8	thrPhone	Varchar(11)	否	是	联系方式  
9	thrEmail	Varchar(32)	否	是	邮件地址  
10	thrAddress	Varchar(256)	否	是	办公地址  
11	showState	Char(4)	否	是	可选显示  
12	createTime	Varchar(12)	否	否	创建时间  
13	updateTime	Varchar(12)	否	否	更新时间  
14	state	Tinyint	否	否	  
4、课题表主要用来存储课题相关信息，与教师表相关联，该表设计包括：编号、教师ID、课题标题等字段，如表所示：  
课题表  
序号	列名	数据类型	主键	允许空值	备注  
1	gpId	int	是	否	主键ID  
2	gpThrId	int	否	否	教师ID  
3	gpTitle	Varchar(128)	否	否	课题标题  
4	gpContent	Varchar(512)	否	否	课题内容  
5	gpAim	Varchar(128)	否	否	课题目的  
6	gpRequest	Varchar(128)	否	否	课题要求  
7	gpMust	Varchar(128)	否	否	必备知识  
8	gpFormal	Varchar(128)	否	否	提交形式  
9	gpOthers	Varchar(512)	否	是	其他  
10	gpSHState	tinyint	否	否	软/硬件  
11	createTime	Varchar(12)	否	否	创建时间  
12	updateTime	Varchar(12)	否	否	更新时间  
13	state	Tinyint	否	否	课题状态  

##### 其他表结构请自行查看sql脚本

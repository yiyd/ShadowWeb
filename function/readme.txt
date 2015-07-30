<有些函数参数已经更新>

简单函数介绍

1、登陆
function login($username, $passwd)
返回TRUE FALSE  用以判断是否登录


2、展示事项类型 用户  用下拉框

 function get_item_types()
 function get_users()

 返回的都是已经整理好的数组


3、新建事项函数
function new_one_item($item_name, $item_follower, $item_description, $item_type_id, $item_state, $item_follow_mark)
目前需要这么多参数

$item_follower 你要给我char 的name
$item_type_id 你要给我ID


4、显示新建结果
 function display_selected_item ()

5、 登陆之后你要保存当前用户名
$_session['current_user'] = $user_name;

6、设置提醒
function set_notify ($date, $auto_type, $user_id)

7、新建修改日志
function log_update($change_field)
$change_field为数组，保存更改项的 name, old_value, new_value 
即 $change_field['name'], ...


8、查询条件设置

	(1) 定义事项查询条件：$condition数组
	包括$condition['name']   $condition['value']
	查询事项时用到的关键字定义，就是name
		事项名称 item_name （模糊查询）
		事项创建人 item_creator_id
		事项描述 item_description （模糊查询）
		事项跟踪人 item_follower_id
		事项类型 item_type_id 
		事项状态 item_state
		前后两个时间段 
			起始时间： start_time 
			终止时间： end_time
		事项跟踪备注 item_follow_mark (模糊查询)


	(2) 定义角色查询条件 $condition数组
	包括$condition['name']   $condition['value']
	查询角色时用到的关键字定义，就是name
		角色名称 role_name 模糊查询 （需求书暂时就这么写的）

	(3) 定义用户查询条件 $condition数组
	包括$condition['name']   $condition['value']
	查询用户时用到的关键字定义，就是name
		用户名称 user_name 模糊查询
  		用户角色 role_id   直接选中输入
  		用户邮箱 user_mail 模糊查询			
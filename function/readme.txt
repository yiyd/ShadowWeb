

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

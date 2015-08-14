<?php
/**
 * Coder: ZX
 * Date: 2015/7/29
 * Time: 11:00
 */
	require_once('function/shadow_fns.php');
	
	session_start();
	check_valid_user();

	$items = array();
	$items['item_name'] = $_POST['item_name'];
	$items['item_follower_id'] = $_POST['item_follower'];
	$items['item_description'] = $_POST['item_description'];
	$items['item_type_id'] = $_POST['item_type'];

	$items['item_state'] = "PROCESSING";

	$notify_number = $_POST['notify_number'];
	
	$follow_mark_number = $_POST['follow_mark_number'];
	
	try {
		new_one_item($items);
		if ($follow_mark_number != 0) {
			$item_follow_mark = $_POST['item_follow_mark'];
			$mark_create_time = $_POST['mark_create_time'];
			new_follow_mark($item_follow_mark, $mark_create_time);
		}
		if ($notify_number != 0) {
			$notify_array = array();
			for ($i = 0 ; $i < $notify_number; $i++) { 
				$notify_row = array();
				$notify_row['auto_type'] = $_POST['notifyRows'.$i.'notify_type'];
				$notify_row['user_id'] = $_POST['notifyRows'.$i.'notify_user'];
				$notify_row['auto_date'] = $_POST['notifyRows'.$i.'notify_date'];
				$notify_array[$i] = $notify_row;
			}
			set_notify($notify_array);			
		}		
	}
	catch(Exception $e) {
		do_html_header('');
		echo $e->getMessage();
		do_html_footer();
		exit;
	}
?>
	<script>
		window.parent.$.messager.show({
            title:"新建",
            msg:"新建事项成功",
            timeout:5000,
            showType:'slide'
        });

		window.parent.reloadItems();
		window.parent.updateTab('display_item.php', '<?php echo $items['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
	</script>
<?php
	
?>
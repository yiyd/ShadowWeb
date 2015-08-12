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

	$date = $_POST['auto_notify_date'];
	$auto_type = $_POST['auto_notify'];
	$users = array();
	$users[0] = $_POST['auto_notify_user'];

	$follow_mark_number = $_POST['follow_mark_number'];
	
	try {
		new_one_item($items);
		set_notify($date, $auto_type, $users);
		if ($follow_mark_number != 0) {
			$item_follow_mark = $_POST['item_follow_mark'];
			$mark_create_time = $_POST['mark_create_time'];
			new_follow_mark($item_follow_mark, $mark_create_time);
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
		window.parent.reloadItems();
		window.parent.updateTab('display_item.php', '<?php echo $items['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
	</script>
<?php
	
?>
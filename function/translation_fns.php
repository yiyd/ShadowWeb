<?php
/**
 * User: ZX
 * Date: 2015/8/19
 * Time: 8:50
 */

    function notify_type_translation($type) {
        switch ($type) {
            case 'ONCE':
                return '单次提醒';
                break;
            case 'DAILY':
                return '每日提醒';
                break;
            case 'WEEKLY':
                return '每周提醒';
                break;
            case 'MONTHLY':
                return '每月提醒';
                break;
            case 'QUARTERLY':
                return '每季度提醒';
                break;
            case 'YEARLY':
                return '每年提醒';
                break;
            default:
            	return;
                break;
	    }
    }

    function item_state_translation($state){
        switch ($state) {
            case 'PROCESSING':
                return "进行中";
                break;
            case 'FINISH':
                return "已完成";
                break;
            default:
                return;
                break;
        }
    }
?>
/**
 * Coder: ZX
 * Date: 2015/8/13
 * Time: 9:30
 */
function validateFollowMarkData() {
    $('#dg').datagrid('acceptChanges');

    lastIndex = $('#dg').datagrid('getRows').length - 1;
    
    followMarkRows = $('#dg').datagrid('getRows');

    if ('' == $.trim(followMarkRows[lastIndex].mark_content) || strlen($.trim(followMarkRows[lastIndex].mark_content)) > 255) {
        $.messager.alert('提示信息', '跟踪备注不能为空，且不能超过255个字符，其中一个汉字是2个字符');
        return false;
    }

    return true;
}

function saveFollowMark() {
    $('#dg').datagrid('acceptChanges');

    lastIndex = $('#dg').datagrid('getRows').length - 1;
    
    followMarkRows = $('#dg').datagrid('getRows');

    $.ajax({
        url:"ajax_php/new_follow_mark.php",
        type:"POST",
        data:{item_follow_mark:followMarkRows[lastIndex].mark_content, 
            mark_create_time:followMarkRows[lastIndex].create_time},
    });

}
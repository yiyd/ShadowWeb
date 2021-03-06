<?php
/**
 * User: ZX
 * Date: 2015/8/14
 * Time: 16:12
 */
    require_once('item_fns.php');

    function display_update_item($users_array, $item_types_array,$row, $auto_notify_result, $follow_mark_result, $item_priorities_array) {
?>
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <link rel="stylesheet" type="type/css" href="resources/common/css/style.css">
    <script type="text/javascript" src="resources/common/js/public.js"></script>
    <script type="text/javascript" src="resources/common/js/datagrid.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>


    <script>
        $(function(){
            $('#dg').datagrid({
                toolbar:[
                    {
                        id:'add',
                        text:'增加跟踪备注',
                        iconCls:'icon-add',
                        disabled:false,
                        handler:function(){
                            var time = getTime();
                            lastIndex = $('#dg').datagrid('getRows').length;
                            if (lastIndex == rows) {
                                $('#dg').datagrid('appendRow',{
                                    mark_content:'',
                                    mark_creator:'<?php 
                                        $user_id = $_SESSION['current_user_id'];
                                        try {
                                            $user_name = get_user_name($user_id);          
                                        } catch (Exception $e) {
                                            echo '';
                                        }
                                        echo $user_name;    
                                    ?>',
                                    create_time:time
                                });
                                $('#dg').datagrid('selectRow', lastIndex);
                                $('#dg').datagrid('beginEdit', lastIndex);
                                isSave = false;
                                $('#save').linkbutton('enable');
                                $('#delete').linkbutton('enable');
                                $('#add').linkbutton('disable');
                            }
                            
                        }
                    },'-',
                    {
                        id:'save',
                        text:'保存所增备注',
                        iconCls:'icon-save',
                        disabled:true,
                        handler:function(){
                            lastIndex = $('#dg').datagrid('getRows').length - 1;
                            if (lastIndex == rows) {
                                if (validateFollowMarkData()) {
                                    $('#dg').datagrid('unselectRow', lastIndex);
                                    $('#dg').datagrid('endEdit', lastIndex);
                                    isSave = true;
                                    saveFollowMark();
                                    $('#save').linkbutton('disable');
                                    $('#delete').linkbutton('disable');
                                    $('#add').linkbutton('disable');
                                }else{
                                    $('#dg').datagrid('selectRow', lastIndex);
                                    $('#dg').datagrid('beginEdit', lastIndex);
                                }
                                
                            }
                            
                        }
                    },'-',
                    {
                        id:'delete',
                        text:'删除所增备注',
                        iconCls:'icon-remove',
                        disabled:true,
                        handler:function(){
                            lastIndex = $('#dg').datagrid('getRows').length - 1;
                            if (lastIndex == rows && !isSave) {
                                $('#dg').datagrid('deleteRow', lastIndex);
                                $('#save').linkbutton('disable');
                                $('#delete').linkbutton('disable');
                                $('#add').linkbutton('enable');
                            }
                            
                        }
                    }
                ]
            });

            $('#an').datagrid({
                toolbar:[
                    {
                        text:'增加自动提醒',
                        iconCls:'icon-add',

                        handler:function(){                            
                            $('#an').datagrid('appendRow',{
                                id:'null',
                                delect_check:'',
                                notify_type:'',
                                notify_user:'',
                                notify_date:''
                            });
                            lastIndex = $('#an').datagrid('getRows').length - 1;
                            $('#an').datagrid('selectRow', lastIndex);
                            $('#an').datagrid('beginEdit', lastIndex);
                        }
                    },'-',
                    
                    {
                        text:'删除所选提醒',
                        iconCls:'icon-remove',
                        handler:function(){
                            var rows = $('#an').datagrid('getChecked');
                            for (var i = rows.length - 1; i >= 0; i--) {
                                var rowIndex = $('#an').datagrid('getRowIndex',rows[i]);
                                $('#an').datagrid('deleteRow',rowIndex);
                            };                            
                        }
                    }
                ],
            });


            $('#item_name').val("<?php echo $row['item_name']; ?>");
            $("#item_follower option[value='<?php echo $row['item_follower_id'] ?>']").attr('selected', true);
            $("#item_type option[value='<?php echo $row['item_type_id'] ?>']").attr('selected', true);
            $("#item_priorities option[value='<?php echo $row['item_priority_id'] ?>']").attr('selected', true);
            $('#item_description').val("<?php echo $row['item_description']; ?>");

            <?php
                $count = count($follow_mark_result);
                for ($i = 0; $i < $count; $i++)
                {
                    $follow_mark_row = $follow_mark_result[$i];
                    $follow_mark_row['mark_creator_name'] = get_user_name($follow_mark_row['mark_creator_id']);
                    echo "$('#dg').datagrid('appendRow',{";
                        echo "mark_content:'".$follow_mark_row['item_follow_mark']."',";
                        echo "mark_creator:'".$follow_mark_row['mark_creator_name']."',";
                        echo "create_time:'".$follow_mark_row['mark_create_time']."'";
                    echo "});";
                }
                
                $notify_number = count($auto_notify_result);
                if ($notify_number > 0) {
                    for ($i = 0; $i < $notify_number; $i++) { 
                        $notify_row = $auto_notify_result[$i];
                        echo "$('#an').datagrid('appendRow',{";
                            echo "id:'".$notify_row['auto_id']."',";
                            echo "delect_check:'',";
                            echo "notify_type:'".$notify_row['auto_type']."',";
                            echo "notify_user:'".$notify_row['user_id']."',";
                            echo "notify_date:'".$notify_row['auto_date']."'";
                        echo "});";
                    }
                }
            ?>
            
            lastIndex = $('#an').datagrid('getRows').length - 1;
            for (var i = 0; i <= lastIndex; i++) {
                $('#an').datagrid('selectRow', i);
                $('#an').datagrid('beginEdit', i);
            };


            var rows = $('#dg').datagrid('getRows').length;
            var isSave = false; 
        });
        
        var change_field_array = [];
        function save()
        {
            if (!validateData()) {
                return;
            }

            if (!validateRelationData()) {
                return;
            }

            lastIndex = $('#an').datagrid('getRows').length;

            if (lastIndex > 0) {
                notifyRows = $('#an').datagrid('getRows');
                var notify_array = [];

                for (var i = 0; i < lastIndex; i++) {
                    var notify_row = [];
                    notify_row[0] = notifyRows[i].id;
                    notify_row[1] = notifyRows[i].notify_type;
                    notify_row[2] = notifyRows[i].notify_user;
                    notify_row[3] = notifyRows[i].notify_date;
                    var length = notify_array.length;
                    notify_array[length] = notify_row;
                    
                };

                $.ajax({
                    url:"ajax_php/update_notify_interface.php",
                    type:"POST",
                    data:{
                        notify:notify_array
                    },
                    success:function(data){
                        
                    }
                });
            }

            makeUpChangeField();

            if (change_field_array.length > 0) {
                $.ajax({
                    url:"ajax_php/update_item_interface.php",
                    type:"POST",
                    data:{
                        change_field:change_field_array
                    },
                    success:function(){
                        window.parent.$.messager.show({
                                title:"修改",
                                msg:"修改事项成功",
                                timeout:5000,
                                showType:'slide'
                        });

                        window.parent.updateTab('display_item.php', '<?php echo $row['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
                    }
                });

            }else {
                window.parent.updateTab('display_item.php', '<?php echo $row['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
            }
        }

        function cancel(){
            window.parent.updateTab('display_item.php', '<?php echo $row['item_name'] ?>', '<?php echo $_SESSION['current_item_id'] ?>');
        }

        function validateData() {
            var item_name = $.trim($('#item_name').val());
            if ('' == item_name || strlen(item_name) > 200) {
                $.messager.alert('提示信息', '事项名称不能为空，不能超过200位，其中一个汉字是2位');
                $('#item_name').focus();
                return false;
            }

            var item_description = $.trim($('#item_description').val());
            if (strlen(item_description) > 255) {
                $.messager.alert('提示信息', '事项描述不能超过255位，其中一个汉字是2位');
                $('#item_description').focus();
                return false;
            }
            return true;
        }

        function validateRelationData() {
            $('#an').datagrid('acceptChanges');

            lastIndex = $('#an').datagrid('getRows').length;
            
            notifyRows = $('#an').datagrid('getRows');

            for (var i = 0; i < lastIndex; i++) {
                if ('' == $.trim(notifyRows[i].notify_type)) {
                    $.messager.alert('提示信息', '自动提醒类型不能为空');
                    $('#an').datagrid('selectRow', i);
                    $('#an').datagrid('beginEdit', i);
                    return false;
                }
                if ('' == $.trim(notifyRows[i].notify_user)) {
                    $.messager.alert('提示信息', '自动提醒人员不能为空');
                    $('#an').datagrid('selectRow', i);
                    $('#an').datagrid('beginEdit', i);
                    return false;
                }

                if ('' == $.trim(notifyRows[i].notify_date)) {
                    $.messager.alert('提示信息', '自动提醒时间不能为空');
                    $('#an').datagrid('selectRow', i);
                    $('#an').datagrid('beginEdit', i);                    
                    return false;
                }
                
            };
            
            return true;
        }

        function makeUpChangeField(){
            var item_name = $.trim($('#item_name').val());
            var old_item_name = '<?php echo $row['item_name'] ?>';
            if (item_name != old_item_name) {
                var change_field_row = [];
                change_field_row[0] = 'item_name';
                change_field_row[1] = item_name;
                change_field_row[2] = old_item_name;
                var length = change_field_array.length;
                change_field_array[length] = change_field_row;
            }

            var item_description = $.trim($('#item_description').val());
            var old_item_description = '<?php echo $row['item_description'] ?>';
            if (item_description != old_item_description) {
                var change_field_row = [];
                change_field_row[0] = 'item_description';
                change_field_row[1] = item_description;
                change_field_row[2] = old_item_description;
                var length = change_field_array.length;
                change_field_array[length] = change_field_row;
            }

            var item_follower = $('#item_follower').val();
            var old_item_follower = '<?php echo $row['item_follower_id'] ?>';
            if (item_follower != old_item_follower) {
                var change_field_row = [];
                change_field_row[0] = 'item_follower_id';
                change_field_row[1] = item_follower;
                change_field_row[2] = old_item_follower;
                var length = change_field_array.length;
                change_field_array[length] = change_field_row;
            }

            var item_type = $('#item_type').val();
            var old_item_type = '<?php echo $row['item_type_id'] ?>';
            if (item_type != old_item_type) {
                var change_field_row = [];
                change_field_row[0] = 'item_type_id';
                change_field_row[1] = item_type;
                change_field_row[2] = old_item_type;
                var length = change_field_array.length;
                change_field_array[length] = change_field_row;
            }

            var item_priority = $('#item_priorities').val();
            var old_item_priority = '<?php echo $row['item_priority_id'] ?>';
            if (item_priority != old_item_priority) {
                var change_field_row = [];
                change_field_row[0] = 'item_priority_id';
                change_field_row[1] = item_priority;
                change_field_row[2] = old_item_priority;
                var length = change_field_array.length;
                change_field_array[length] = change_field_row;
            }
        }
    </script>

    </head>
    <body>
    
        <form id="ff">
            <div class="easyui-panel" style="height:auto;" data-options="title:'事项基本信息',collapsible:true">
                <table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr height="26">
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项编号：
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <?php
                                    echo $row['item_id'];
                                ?>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                创建人：
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <?php
                                    echo $row['item_creator_name'];
                                ?>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                创建时间：
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <?php
                                    echo $row['item_create_time'];
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr height="26">
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项名称：<font color="red">*</font>
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <input id="item_name" type="text" name="item_name" maxlength="200" size="30"></input>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                跟踪人：<font color="red">*</font>
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <select id="item_follower" name="item_follower">
                                    <?php
                                        foreach ($users_array as $user) {
                                            echo "<option value=\"".$user['user_id']."\" >".$user['user_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项类型：<font color="red">*</font>
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <select id="item_type" name="item_type">
                                    <?php
                                        foreach ($item_types_array as $type) {
                                            echo "<option value=\"".$type['para_value_id']."\" >".$type['para_value_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr height="26">
                        <td nowrap="nowrap">
                            <div align="right" style="padding-right=2px;">
                                事项状态：
                            </div>
                        </td>
                        <td>
                            <div align="left" style="padding-left:2px;">
                                <?php
                                    echo item_state_translation($row['item_state']);
                                ?>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项优先级：<font color="red">*</font>
                            </div>
                        </td>
                        <td width="23%">
                            <div align="left" style="padding-left:2px;">
                                <select id="item_priorities" name="item_priorities">
                                    <?php
                                        foreach ($item_priorities_array as $priority) {
                                            echo "<option value=\"".$priority['para_value_id']."\" >".$priority['para_value_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td height="26"> 
                            <div align="right" style="padding-right:2px;">事项描述：</div>

                        </td>
                        <td colspan="2">
                            <div align="left" style="margin:5px 0 5px 0;">
                                <textarea id="item_description" name="item_description" rows="6" cols="40" wrap="virtual" maxlength="255"></textarea>
                            </div>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </table>
            </div>
            <div>
                <table id="an" class="easyui-datagrid" title="自动提醒" data-options="collapsible:true,rownumbers:true">
                    <thead>
                        <tr>
                            <th data-options="field:'id', hidden:true"></th>
                            <th data-options="field:'delect_check',checkbox:true"></th>
                            <th id="notify_type" width="15%" data-options="field:'notify_type',editor:{
                                type:'combobox',
                                options:{
                                    valueField:'type_id',
                                    textField:'type_name',
                                    url:'ajax_php/notify_types_array.php',
                                    editable:false,
                                    panelHeight:'auto'
                                }

                            }">自动提醒类型</th>
                            <th width="15%" data-options="field:'notify_user',editor:{
                                type:'combobox',
                                options:{
                                    valueField:'user_id',
                                    textField:'user_name',
                                    url:'ajax_php/users_array.php',
                                    editable:false,
                                    panelHeight:'auto'
                                }

                            }">自动提醒人员</th>
                            <th width="20%" data-options="field:'notify_date',editor:{
                                type:'datetimebox',
                                options:{
                                    editable:false
                                }

                            }">自动提醒时间</th>
                        </tr>
                    </thead>
                </table>   
            </div>
            <div>
                <table id="dg" class="easyui-datagrid" title="事务跟踪备注(新增备注后请点击‘保存所增备注’按钮，否则备注不予保存)" data-options="collapsible:true,rownumbers:true,nowrap:false">
                    <thead>
                        <tr>
                            <th width="65%" data-options="field:'mark_content',editor:'textarea'">跟踪备注</th>
                            <th width="8%" data-options="field:'mark_creator'">创建人</th>
                            <th width="15%" data-options="field:'create_time'">创建时间</th>
                        </tr>
                    </thead>
                </table>   
            </div>

            <div style="padding-top:10px;text-align:center;height:40px">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="save()" data-options="iconCls:'icon-save'">保存修改</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="cancel()" data-options="iconCls:'icon-undo'">取消</a>
            </div>
            
        </form>
    </body>
</html>    
<?php
    }
<?php
/**
 * User: ZX
 * Date: 2015/8/5
 * Time: 15:30
 */
    require_once('item_fns.php');
	function display_new_item_form($users_array, $item_types_array) {
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
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>


    <script>
        function newItem() {
            if (!validateData()) {
                return;
            }
            if (!validateRelationData()) {
                return;
            }

            $('#ff').submit();
            
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
            $('#dg').datagrid('acceptChanges');

            var inputHTML = "";

            lastIndex = $('#dg').datagrid('getRows').length;

            inputHTML += '<input type="hidden" name="follow_mark_number" value="' + lastIndex + '" />';
            
            followMarkRows = $('#dg').datagrid('getRows');

            if (lastIndex == 1) {
                if ('' == $.trim(followMarkRows[0].mark_content) || strlen($.trim(followMarkRows[0].mark_content)) > 255) {
                    $.messager.alert('提示信息', '跟踪备注不能为空，且不能超过255个字符，其中一个汉字是2个字符');
                    $('#dg').datagrid('selectRow', 0);
                    $('#dg').datagrid('beginEdit', 0);
                    return false;
                }
                inputHTML += '<input type="hidden" name="item_follow_mark" value="' + followMarkRows[0].mark_content + '" />';
                inputHTML += '<input type="hidden" name="mark_create_time" value="' + followMarkRows[0].create_time + '" />';
            }

            $('#an').datagrid('acceptChanges');

            lastIndex = $('#an').datagrid('getRows').length;

            inputHTML += '<input type="hidden" name="notify_number" value="' + lastIndex + '" />';
            
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
                inputHTML += '<input type="hidden" name="notifyRows' + i + 'notify_type" value="' + notifyRows[i].notify_type + '" />';
                inputHTML += '<input type="hidden" name="notifyRows' + i + 'notify_user" value="' + notifyRows[i].notify_user + '" />';
                inputHTML += '<input type="hidden" name="notifyRows' + i + 'notify_date" value="' + notifyRows[i].notify_date + '" />';
            };

            $('#hiddenText').html(inputHTML);


            return true;
        }

        $(function(){
            $('#an').datagrid({
                toolbar:[
                    {
                        text:'增加自动提醒',
                        iconCls:'icon-add',

                        handler:function(){                            
                            $('#an').datagrid('appendRow',{
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

            $('#dg').datagrid({
                toolbar:[
                    {
                        text:'增加跟踪备注',
                        iconCls:'icon-add',

                        handler:function(){
                            var time = getTime();
                            lastIndex = $('#dg').datagrid('getRows').length;
                            if (lastIndex == 0) {
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
                            }
                            
                        }
                    },'-',
                    {
                        text:'删除所增备注',
                        iconCls:'icon-remove',
                        handler:function(){
                            lastIndex = $('#dg').datagrid('getRows').length - 1;
                            if (lastIndex >= 0) {
                                $('#dg').datagrid('deleteRow', lastIndex);
                            }
                            
                        }
                    }
                ]

            });   
        })

    </script>

    </head>
    <body>
    
    	<form id="ff" method="post" action="new_item.php" >
    		<div class="easyui-panel" style="height:auto;" data-options="title:'事项基本信息',collapsible:true">
    			<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
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
    							<select name="item_follower">
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
    							<select name="item_type">
    								<?php
    									foreach ($item_types_array as $type) {
            								echo "<option value=\"".$type['para_value_id']."\" >".$type['para_value_name']."</option>";
        								}
        							?>
    							</select>
    						</div>
    					</td>
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
                            <th data-options="field:'delect_check',checkbox:true"></th>
                            <th width="15%" data-options="field:'notify_type',editor:{
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
                <table id="dg" class="easyui-datagrid" title="事务跟踪备注" data-options="collapsible:true,rownumbers:true">
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
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="newItem()" data-options="iconCls:'icon-save'">新建事项</a>

            </div>
            <div id="hiddenText"></div>
    	</form>
    </body>
</html>


<?php
	}



?>
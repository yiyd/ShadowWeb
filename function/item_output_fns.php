<?php
/**
 * User: ZX
 * Date: 2015/8/5
 * Time: 15:30
 */

	function display_new_item_form($users_array, $item_types_array) {
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">

        <title>中银开放平台-事项跟踪工具</title>
        <link rel="stylesheet" type="text/css" href="resources/common/css/style.css" />
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
        <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
        <script type="text/javascript" src="resources/common/js/public.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    </head>			

    <body>
    	<form id="ff" method="post" action="new_item.php">
    		<div class="easyui-panel" style="height:auto;" data-options="title:'事项基本信息',collapsible:true">
    			<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" >
    				<tr height="26">
    					<td nowrap="nowrap" width="10%">
    						<div align="right" style="padding-right=2px;">
    							事项名称：<font color="red">*</font>
    						</div>
    					</td>
    					<td width="23%">
    						<div align="left" style="padding-left:2px;">
    							<input id="item_name" type="text" name="item_name" maxlength="200"></input>
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
    				<tr height="26">
    					<td nowrap="nowrap">
    						<div align="right" style="padding-right=2px;">
    							自动提醒类型：<font color="red">*</font>
    						</div>
    					</td>
    					<td>
    						<div align="left" style="padding-left:2px;">
    							<select name="auto_notify">
    								<option value="ONCE">单次提醒</option>
    								<option value="DAILY">每日提醒</option>
    								<option value="WEEKLY">每周提醒</option>
    								<option value="MONTHLY">每月提醒</option>
    								<option value="QUARTERLY">每季度提醒</option>
    								<option value="YEARLY">每年提醒</option>
    							</select>
    						</div>
    					</td>
    					<td nowrap="nowrap">
    						<div align="right" style="padding-right=2px;">
    							自动提醒人员：<font color="red">*</font>
    						</div>
    					</td>
    					<td>
    						<div align="left" style="padding-left:2px;">
    							<select name="auto_notify_user">
    								<?php
    									foreach ($users_array as $user) {
            								echo "<option value=\"".$user['user_id']."\" >".$user['user_name']."</option>";
        								}
        							?>
    							</select>
    						</div>
    					</td>
    					<td nowrap="nowrap">
    						<div align="right" style="padding-right=2px;">
    							自动提醒时间：<font color="red">*</font>
    						</div>
    					</td>
    					<td>
    						<div align="left" style="padding-left:2px;">
    							<input id="auto_notify_date" name="auto_notify_date" class="easyui-datetimebox" style="width:200px" data-options="editable:false">
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
                <table id="dg" class="easyui-datagrid" title="事务跟踪备注" data-options="collapsible:true,rownumbers:true">
                    <thead>
                        <tr>
                            <th width="65%" data-options="field:'mark_content',editor:'text'">跟踪备注</th>
                            <th width="8%" data-options="field:'mark_creator'">创建人</th>
                            <th width="15%" data-options="field:'create_time'">创建时间</th>
                        </tr>
                    </thead>
                </table>   
            </div>

            <div style="padding-top:10px;text-align:center;height:40px">
                <a href="javascript:void(0)" class="easyui-linkbutton" onclick="newItem()" data-options="iconCls:'icon-save'">新建</a>

            </div>
            <div id="hiddenText"></div>
    	</form>
    </body>
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
            
            var auto_notify_date = $('#auto_notify_date').datetimebox('getValue');
            if ('' == auto_notify_date) {
                $.messager.alert('提示信息', '自动提醒时间不能为空');
                $('#auto_notify_date').focus();
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
            if (lastIndex == 1) {
                followMarkRows = $('#dg').datagrid('getRows');
                if ('' == $.trim(followMarkRows[0].mark_content) || strlen($.trim(followMarkRows[0].mark_content)) > 255) {
                    $.messager.alert('提示信息', '跟踪备注不能为空，且不能超过255个字符，其中一个汉字是2个字符');
                    return false;
                }
                inputHTML += '<input type="hidden" name="item_follow_mark" value="' + followMarkRows[0].mark_content + '" />';
                inputHTML += '<input type="hidden" name="mark_create_time" value="' + followMarkRows[0].create_time + '" />';
            }

            $('#hiddenText').html(inputHTML);

            return true;
        }
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
                },
                // {
                //     text:'保存所增备注',
                //     iconCls:'icon-save',
                //     handler:function(){
                //         lastIndex = $('#dg').datagrid('getRows').length - 1;
                //         if (lastIndex >= 0) {
                //             $('#dg').datagrid('unselectRow', lastIndex);
                //             $('#dg').datagrid('endEdit', lastIndex);
                //         }
                        
                //     }
                // },
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
    


    </script>
</html>
<?php
	}
?>
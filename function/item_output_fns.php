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
        <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
        <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    </head>			

    <body>
    	<form method="post" action="new_item.php">
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
    							<input type="text" name="item_name" maxlength="200"></input>
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
    							<input name="auto_notify_date" class="easyui-datetimebox" style="width:200px">
    						</div>
    					</td>
    				</tr>
                    <tr>
                        <td height="26">
                            <div align="right" style="padding-right:2px;">事项描述：</div>

                        </td>
                        <td colspan="2">
                            <div align="left" style="margin:5px 0 5px 0;">
                                <textarea name="item_description" rows="6" cols="40" wrap="virtual" maxlength="255"></textarea>
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
                            <th width="10%" data-options="field:'item_name'">事务名称</th>
                            <th width="85%" data-options="field:'mark_content'">跟踪备注</th>
                        </tr>
                    </thead>
                </table>   
            </div>

    	</form>
    </body>
    <script>
        $('#dg').datagrid({
            toolbar:[
                {
                    text:'增加跟踪备注',
                    iconCls:'icon-add',
                    handler:function(){
                        $('#dg').datagrid('appendRow',{
                            item_name:'test',
                            mark_content:'testtoo'
                        });
                    }
                }
            ]
        });
    </script>
</html>
<?php
	}
?>
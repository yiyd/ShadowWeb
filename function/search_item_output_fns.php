<?php
/**
 * User: ZX
 * Date: 2015/8/17
 * Time: 14:30
 */
	function display_search_item_form($users_array, $item_types_array) {
?>
<!DOCTYPE html>
<html>
    <head>

    <meta charset="UTF-8">

    <title>中银开放平台-事项跟踪工具</title>
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="type/css" href="resources/jquery-easyui/themes/icon.css">
    <script type="text/javascript" src="resources/common/js/public.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="resources/jquery-easyui/locale/easyui-lang-zh_CN.js"></script>
    <script>
        $(function(){
            $('#dg').datagrid({
                onSelect:function(rowIndex, rowData){
                    
                    $.ajax({
                        url:"ajax_php/change_current_item_id.php",
                        type:"POST",
                        data:{current_item_id:rowData.item_id},
                    });

                    window.parent.updateTab('display_item.php', rowData.item_name, rowData.item_id);
                }
            });
        });
        (function($){
            function pagerFilter(data){
                if ($.isArray(data)){   // is array
                    data = {
                        total: data.length,
                        rows: data
                    }
                }
                var dg = $(this);
                var state = dg.data('datagrid');
                var opts = dg.datagrid('options');
                if (!state.allRows){
                    state.allRows = (data.rows);
                }
                var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
                var end = start + parseInt(opts.pageSize);
                data.rows = $.extend(true,[],state.allRows.slice(start, end));
                return data;
            }

            var loadDataMethod = $.fn.datagrid.methods.loadData;
            $.extend($.fn.datagrid.methods, {
                clientPaging: function(jq){
                    return jq.each(function(){
                        var dg = $(this);
                        var state = dg.data('datagrid');
                        var opts = state.options;
                        opts.loadFilter = pagerFilter;
                        var onBeforeLoad = opts.onBeforeLoad;
                        opts.onBeforeLoad = function(param){
                            state.allRows = null;
                            return onBeforeLoad.call(this, param);
                        }
                        dg.datagrid('getPager').pagination({
                            onSelectPage:function(pageNum, pageSize){
                                opts.pageNumber = pageNum;
                                opts.pageSize = pageSize;
                                $(this).pagination('refresh',{
                                    pageNumber:pageNum,
                                    pageSize:pageSize
                                });
                                dg.datagrid('loadData',state.allRows);
                            }
                        });
                        $(this).datagrid('loadData', state.data);
                        if (opts.url){
                            $(this).datagrid('reload');
                        }
                    });
                },
                loadData: function(jq, data){
                    jq.each(function(){
                        $(this).data('datagrid').allRows = null;
                    });
                    return loadDataMethod.call($.fn.datagrid.methods, jq, data);
                },
                getAllRows: function(jq){
                    return jq.data('datagrid').allRows;
                }
            })
        })(jQuery);

        function searchItem()
        {
            var condition_array = [];
            var item_name = $.trim($('#item_name').val());
            if ('' != item_name) {
                if (strlen(item_name) > 200) {
                    $.messager.alert('提示信息', '事项名称不能超过200位，其中一个汉字是2位');
                    $('#item_name').focus();
                    return;
                }
                var condition_row = [];
                condition_row[0] = 'item_name';
                condition_row[1] = item_name; 
                var length = condition_array.length;
                condition_array[length] = condition_row; 
            }

            var item_creator_id = $('#item_creator').val();
            if ('' != item_creator_id) {
                var condition_row = [];
                condition_row[0] = 'item_creator_id';
                condition_row[1] = item_creator_id;
                var length = condition_array.length;
                condition_array[length] = condition_row;   
            }

            var item_follower_id = $('#item_follower').val();
            if ('' != item_follower_id) {
                var condition_row = [];
                condition_row[0] = 'item_follower_id';
                condition_row[1] = item_follower_id;
                var length = condition_array.length;
                condition_array[length] = condition_row;  

            }

            var item_type_id = $('#item_type').val();
            if ('' != item_type_id) {
                var condition_row = [];
                condition_row[0] = 'item_type_id';
                condition_row[1] = item_type_id;
                var length = condition_array.length;
                condition_array[length] = condition_row;  
            }

            var item_state = $('#item_state').val();
            if ('' != item_state) {
                var condition_row = [];
                condition_row[0] = 'item_state';
                condition_row[1] = item_state;
                var length = condition_array.length;
                condition_array[length] = condition_row;  
            }

            var start_time = $('#start_time').datetimebox('getValue');
            if ('' != start_time) {
                var condition_row = [];
                condition_row[0] = 'start_time';
                condition_row[1] = start_time;
                var length = condition_array.length;
                condition_array[length] = condition_row; 
            }

            var end_time = $('#end_time').datetimebox('getValue');
            if ('' != end_time) {
                var condition_row = [];
                condition_row[0] = 'end_time';
                condition_row[1] = end_time;
                var length = condition_array.length;
                condition_array[length] = condition_row; 
            }

            var item_description = $.trim($('#item_description').val());
            if ('' != item_description) {
                if (strlen(item_description) > 255) {
                    $.messager.alert('提示信息', '事项描述不能超过255位，其中一个汉字是2位');
                    $('#item_description').focus();
                    return;
                }
                var condition_row = [];
                condition_row[0] = 'item_description';
                condition_row[1] = item_description;
                var length = condition_array.length;
                condition_array[length] = condition_row;  
            }

            if (condition_array.length > 0) {
                // alert(condition_array.length);
                $.ajax({
                    url:"ajax_php/search_item.php",
                    type:"POST",
                    data:{
                        condition:condition_array
                    },
                    success:function(json){
                        // alert(json);      
                        $('#dg').datagrid('loadData', getData(json)).datagrid('clientPaging');
                    }
                }); 
            }
            
        }

        function getData(json){
            var rows = [];
            $.each($.parseJSON(json), function(idx,item){
                var state;
                switch(item.item_state)
                {
                    case 'PROCESSING':
                        state = '进行中';
                        break;
                    case 'FINISH':
                        state = '已完成';
                        break;
                    default:
                        break;
                }
                rows.push({
                    item_id:item.item_id,
                    item_name: item.item_name,
                    item_creator: item.item_creator,
                    item_follower: item.item_follower,
                    item_type: item.item_type,
                    item_state: state,
                    item_create_time: item.item_create_time,
                    item_description: item.item_description
                });

            });
            return rows;
        }
    </script>
    </head>
    <body>
    
    	<form id="search_form">
    		<div class="easyui-panel" style="height:auto;" data-options="title:'查询事项信息'">
    			<table class="table_list" width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
    				<tr height="26">
    					<td nowrap="nowrap" width="10%">
    						<div align="right" style="padding-right=2px;">
    							事项名称：
    						</div>
    					</td>
    					<td width="15%">
    						<div align="left" style="padding-left:2px;">
    							<input id="item_name" type="text" name="item_name" maxlength="200"></input>
    						</div>
    					</td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                创建人：
                            </div>
                        </td>
                        <td width="15%">
                            <div align="left" style="padding-left:2px;">
                                <select id="item_creator" name="item_creator">
                                    <option value="" >---请选择---</options>
                                    <?php
                                        foreach ($users_array as $user) {
                                            echo "<option value=\"".$user['user_id']."\" >".$user['user_name']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
    					<td nowrap="nowrap" width="10%">
    						<div align="right" style="padding-right=2px;">
    							跟踪人：
    						</div>
    					</td>
    					<td width="15%">
    						<div align="left" style="padding-left:2px;">
    							<select id="item_follower" name="item_follower">
                                    <option value="" >---请选择---</options>
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
    							事项类型：
    						</div>
    					</td>
    					<td width="15%">
    						<div align="left" style="padding-left:2px;">
    							<select id="item_type" name="item_type">
                                    <option value="" >---请选择---</options>
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
                        
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项描述：
                            </div>
                        </td>
                        <td width="15%">
                            <div align="left" style="padding-left:2px;">
                                <input id="item_description" type="text" name="item_description" maxlength="200"></input>
                            </div>
                        </td>
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                事项状态：
                            </div>
                        </td>
                        <td width="15%">
                            <div align="left" style="padding-left:2px;">
                                <select id="item_state" name="item_state">
                                    <option value="" >---请选择---</options>
                                    <option value="PROCESSING" >进行中</options>
                                    <option value="FINISH" >已完成</options>
                                </select>
                            </div>
                        </td>
                        
                    </tr>
                    <tr>
                        
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                创建时间范围（大于）：
                            </div>
                        </td>
                        <td width="15%">
                            <div align="left" style="padding-left:2px;">
                                <input id="start_time" class="easyui-datetimebox" name="start_time"></input>  
                            </div>
                        </td>
                        
                        <td nowrap="nowrap" width="10%">
                            <div align="right" style="padding-right=2px;">
                                创建时间范围(小于)：
                            </div>
                        </td>
                        <td width="15%">
                            <div align="left" style="padding-left:2px;">
                                <input id="end_time" class="easyui-datetimebox" name="end_time"></input>  
                            </div>
                        </td>
                    </tr>
                    <tr>
                        
                        
                        
                    </tr>
    			</table>
                <div style="padding-top:10px;text-align:center;height:40px">
                    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="searchItem()" data-options="iconCls:'icon-search'">查询</a>

                </div>
    		</div>
            
            <table id="dg" class="easyui-datagrid" title="查询结果" data-options="rownumbers:true,singleSelect:true,pagination:true,pageSize:10,pageList:[5,10,15,20]">
                <thead>
                    <tr>
                        <th field="item_id", hidden="true"></th>
                        <th field="item_name" width="15%">事项名称</th>
                        <th field="item_creator" width="10%">创建人</th>
                        <th field="item_follower" width="10%">跟踪人</th>
                        <th field="item_type" width="10%">事项类型</th>
                        <th field="item_state" width="10%">事项状态</th>
                        <th field="item_create_time" width="15%">创建时间</th>
                        <th field="item_description" width="25%">事项描述</th>
                    </tr>
                </thead>
            </table>


    	</form>
    </body>

</html>


<?php
	}



?>
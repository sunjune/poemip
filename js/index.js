//为菜单按钮添加click事件监听
/*
 * 访问接口的post数据格式示例
 * opt={"act":"select", "obj":"project", "data":{}}
 * 
 */
function view_button_submit_click(obj){
	var postdata = {
		"api" : "", 
		"act" : "",
		"obj" : "",
		"data" : ""
	};
	postdata['api'] = $(obj).attr('api');
	postdata['act'] = $(obj).attr('act');
	postdata['obj'] = $(obj).attr('obj');
	postdata['data'] = JSON.parse($(obj).attr('data'));

	close_form_dialog();
	
	//访问接口，根据返回结果进行相应处理
	visit_api_for_result(postdata);
}

//定义一个全局变量供定时程序调用
var for_api_postdata = {
	"api" : "", 
	"act" : "",
	"obj" : "",
	"data" : {}
};

//定义问题类型常量
const ISSUE_TYPE = {
		"0":"招标/签约阶段",
		"1":"设计阶段",
		"2":"生产阶段",
		"3":"运输阶段",
		"4":"现场存储阶段",
		"5":"安装测试阶段",
		"6":"验收阶段",
		"7":"质保阶段"
};

//访问接口，根据返回结果进行相应处理
function visit_api_for_result(postdata){
	if(postdata['api'] == 'p1'){
		
		switch(postdata['act']){
			case "select":
				// 访问数据接口，读取相应的数据列表
				$.post("/" + postdata['api'] + ".php", 'opt=' + JSON.stringify(postdata), function(result) {
					format_data_to_html(result);	//主功能导航数据展示
				}, "json");
				break;
			case "create":
			case "modify":
				create_data_form(postdata, postdata['act']);	//构造相应的数据表单
				break;
			case "insert":
			case "link":
			case "update":
				$.post("/" + postdata['api'] + ".php", 'opt=' + JSON.stringify(postdata), function(result) {
					view_insert_result(result);	//根据插入数据的执行结果做相应处理
				}, "json");
				break;
			default:
				
		}
	}
	else{
		alert('此功能尚未开放，请稍候。')
		return false;
	}
}

//根据接口返回数据，展示插入数据执行结果
function view_insert_result(result){
	if(typeof(result['data']['errmsg']) != "undefined"){
		alert(result['data']['errmsg']);
	}
	else{
		$('.form_window .message_text').html(result['data']['optinfo']);	//显示接口返回操作信息
		var postdata = {
			"api":"p1",
			"act":"select",
			"obj":result['obj'],
			"data":{}
		};
		switch(result['obj']){
			case "project":
				break;
			case "equipment":
				postdata["data"]["project_id"] = result["data"]["project_id"];
				break;
			case "supplier":
				postdata["data"]["project_id"] = result["data"]["project_id"];
				postdata["data"]["equipment_id"] = result["data"]["equipment_id"];
				break;
			case "issue":
				postdata["data"]["project_id"] = result["data"]["project_id"];
				postdata["data"]["equipment_id"] = result["data"]["equipment_id"];
				postdata["data"]["supplier_id"] = result["data"]["supplier_id"];
				postdata["data"]["issue_type"] = '';
				break;
			default:
				
		}
		for_api_postdata = postdata;
		setTimeout("close_form_dialog();visit_api_for_result(for_api_postdata);", 1000);
	}
}

//为添加新的数据构造相应的表单
function create_data_form(postdata, act){
	//显示表单所在对话框图层，清空现有内容
	$('.area_right .info_dialog').css("display", "block");
	$('.form_window .form_title .title_text').html('');	//清除对话框标题
	$('.form_window .form_title .message_text').html('');	//清除提示信息
	$('.form_window .form_body').html('');	//清除表单区域
	
	switch(postdata['obj']){
		case 'project':
			view_project_form(postdata, act);
			break;
		case 'equipment':
			view_equipment_form(postdata, act);
			break;
		case 'supplier':
			view_supplier_form(postdata, act);
			break;
		case 'issue':
			view_issue_form(postdata, act);
			break;
		default:
	}

	//关闭表单对话框
	$('.form_window .form_body .col_button .cancel').click(function(){
		close_form_dialog();
	});
}

//关闭表单对话框
function close_form_dialog(){
	$('.form_window .form_title .title_text').html('');	//清除对话框标题
	$('.form_window .form_title .message_text').html('');	//清除提示信息
	$('.form_window .form_body').html('');	//清除表单区域
	$('.area_right .info_dialog').css("display", "none");
}

//添加项目数据的表单
function view_project_form(postdata, act){
	var htmltitle = (act=='create')?'添加项目':'修改项目信息';
	var htmlform = '';
	
	htmlform += '<form id="form1">';
	htmlform += '<ul>';
	htmlform += '  <li><div class="col_left">项目名称：</div><div class="col_right"><input type="text" id="project_name" name="project_name" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">项目描述：</div><div class="col_right"><textarea id="project_desc" name="project_desc" class="text_desc" /></textarea></div></li>';
	htmlform += '</ul>';
	htmlform += '<div class="col_button"><input type="button" id="btn_submit" value=" 提 交 " /> &nbsp; <input type="button" class="cancel" value=" 取 消 " /></div>';
	htmlform += '</form>';
	
	$('.form_window .form_title .title_text').html(htmltitle);
	$('.form_window .form_body').html(htmlform);
	
	if(act == "modify"){
		//读取当前项目信息
		var tmpdata = {
			'api':postdata['api'],
			'act':'selectbyid',
			'obj':postdata['obj'],
			'data':{"project_id":postdata['data']['project_id']}
		};
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充下拉菜单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				$('#project_name').val(result['data'][0]['project_name']);
				$('#project_desc').html(result['data'][0]['project_desc']);
			}
		}, "json");
	}

	$('#btn_submit').click(function(){
		if(act == "modify"){
			postdata['act'] = 'update';
		}
		else{
			postdata['act'] = 'insert';
		}
		postdata['data']['project_name']=$('#project_name').val();
		postdata['data']['project_desc']=$('#project_desc').val();
		visit_api_for_result(postdata);	//提交数据给接口
	});
}

//添加设备数据的表单
function view_equipment_form(postdata, act){
	var htmltitle = (act=='create')?'添加设备':'修改设备信息';
	var htmlform = '';
	
	htmlform += '<form id="form1">';
	if(act == "create"){
		htmlform += '<div class="act_select"><input type="radio" id="radio_0" name="act" value="insert_new" checked="true" /> <label for="radio_0">新增设备</label> <input type="radio" id="radio_1" name="act" value="insert_link" /> <label for="radio_1">选择设备</label></div>';
	}
	htmlform += '<ul id="insert_new">';
	htmlform += '  <li><div class="col_left">设备名称：</div><div class="col_right"><input type="text" id="equipment_name" name="equipment_name" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">设备型号：</div><div class="col_right"><input type="text" id="equipment_model" name="equipment_model" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">设备规格：</div><div class="col_right"><input type="text" id="equipment_spec" name="equipment_spec" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">设备参数：</div><div class="col_right"><input type="text" id="equipment_param" name="equipment_param" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">设备描述：</div><div class="col_right"><textarea id="equipment_desc" name="equipment_desc" class="text_desc" /></textarea></div></li>';
	htmlform += '</ul>';
	htmlform += '<ul id="insert_link" class="default_hidden">';
	htmlform += '  <li><div class="col_left">选择设备：</div><select class="select_list" name="equipment_id" id="equipment_id"></select></li>';
	htmlform += '</ul>';
	htmlform += '<div class="col_button"><input type="button" id="btn_submit" value=" 提 交 " /> &nbsp; <input type="button" class="cancel" value=" 取 消 " /></div>';
	htmlform += '</form>';
	
	$('.form_window .form_title .title_text').html(htmltitle);
	$('.form_window .form_body').html(htmlform);
	
	if(act == "modify"){
		//读取当前项目信息
		var tmpdata = {
			'api':postdata['api'],
			'act':'selectbyid',
			'obj':postdata['obj'],
			'data':{"equipment_id":postdata['data']['equipment_id']}
		};
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充表单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				$('#equipment_name').val(result['data'][0]['equipment_name']);
				$('#equipment_model').val(result['data'][0]['equipment_model']);
				$('#equipment_spec').val(result['data'][0]['equipment_spec']);
				$('#equipment_param').val(result['data'][0]['equipment_param']);
				$('#equipment_desc').html(result['data'][0]['equipment_desc']);
			}
		}, "json");
	}
	
	if(act == "create"){
		//读取当前项目未关联的设备列表
		var tmpdata = postdata;
		tmpdata['act'] = 'unselect';
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充下拉菜单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				var html_select = '<option value="-1">请选择设备</option>';
				for(i in result['data']){
					html_select += '<option value="' + result['data'][i]['equipment_id'] + '">' + result['data'][i]['equipment_name'] + '</option>';
				}
				$('#equipment_id').html(html_select);
			}
		}, "json");

		//默认act值为传参值
		postdata['act'] = act;
	
		//给单选框加上点击动作
		$('#form1 input[name="act"]').click(function(){
			if($('#form1 input[name="act"]:checked').val() == 'insert_new'){
				$('#insert_new').css('display', 'block');
				$('#insert_link').css('display', 'none');
				postdata['act'] = 'insert';
			}
			else{
				$('#insert_new').css('display', 'none');
				$('#insert_link').css('display', 'block');
				postdata['act'] = 'link';
			}
		});
	}
	
	$('#btn_submit').click(function(){
		if(postdata['act'] == 'link'){
			postdata['data']['equipment_id'] = $('#equipment_id').val();
		}
		else{
			if(act == "modify"){
				postdata['act'] = 'update';
			}
			else{
				postdata['act'] = 'insert';
			}

			//act不是link的时候，可能是insert或modify
			postdata['data']['equipment_name']  = $('#equipment_name').val();
			postdata['data']['equipment_model'] = $('#equipment_model').val();
			postdata['data']['equipment_spec']  = $('#equipment_spec').val();
			postdata['data']['equipment_param'] = $('#equipment_param').val();
			postdata['data']['equipment_desc']  = $('#equipment_desc').val();
		}
		visit_api_for_result(postdata);	//提交数据给接口
	});
}

//添加供应商数据的表单
function view_supplier_form(postdata, act){
	var htmltitle = (act=='create')?'添加供应商':'修改供应商信息';
	var htmlform = '';
	
	htmlform += '<form id="form1">';
	if(act == "create"){
		htmlform += '<div class="act_select"><input type="radio" id="radio_0" name="act" value="insert_new" checked="true" /> <label for="radio_0">新增供应商</label> <input type="radio" id="radio_1" name="act" value="insert_link" /> <label for="radio_1">选择供应商</label></div>';
	}
	htmlform += '<ul id="insert_new">';
	htmlform += '  <li><div class="col_left">供应商名称：</div><div class="col_right"><input type="text" id="supplier_name" name="supplier_name" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">供应商描述：</div><div class="col_right"><textarea id="supplier_desc" name="supplier_desc" class="text_desc" /></textarea></div></li>';
	htmlform += '</ul>';
	htmlform += '<ul id="insert_link" class="default_hidden">';
	htmlform += '  <li><div class="col_left">选择供应商：</div><select class="select_list" name="supplier_id" id="supplier_id"></select></li>';
	htmlform += '</ul>';
	htmlform += '<div class="col_button"><input type="button" id="btn_submit" value=" 提 交 " /> &nbsp; <input type="button" class="cancel" value=" 取 消 " /></div>';
	htmlform += '</form>';
	
	$('.form_window .form_title .title_text').html(htmltitle);
	$('.form_window .form_body').html(htmlform);
	
	if(act == "modify"){
		//读取当前项目信息
		var tmpdata = {
			'api':postdata['api'],
			'act':'selectbyid',
			'obj':postdata['obj'],
			'data':{"supplier_id":postdata['data']['supplier_id']}
		};
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充表单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				$('#supplier_name').val(result['data'][0]['supplier_name']);
				$('#supplier_desc').html(result['data'][0]['supplier_desc']);
			}
		}, "json");
	}

	if(act == "create"){
		//读取当前项目未关联的设备列表
		var tmpdata = postdata;
		tmpdata['act'] = 'unselect';
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充下拉菜单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				var html_select = '<option value="-1">请选择设备</option>';
				for(i in result['data']){
					html_select += '<option value="' + result['data'][i]['supplier_id'] + '">' + result['data'][i]['supplier_name'] + '</option>';
				}
				$('#supplier_id').html(html_select);
			}
		}, "json");

		//默认act值为传参值
		postdata['act'] = act;
	
		//给单选框加上点击动作
		$('#form1 input[name="act"]').click(function(){
			if($('#form1 input[name="act"]:checked').val() == 'insert_new'){
				$('#insert_new').css('display', 'block');
				$('#insert_link').css('display', 'none');
				postdata['act'] = 'insert';
			}
			else{
				$('#insert_new').css('display', 'none');
				$('#insert_link').css('display', 'block');
				postdata['act'] = 'link';
			}
		});
	}

	$('#btn_submit').click(function(){
		if(postdata['act'] == 'link'){
			postdata['data']['supplier_id'] = $('#supplier_id').val();
		}
		else{
			if(act == "modify"){
				postdata['act'] = 'update';
			}
			else{
				postdata['act'] = 'insert';
			}
			//act不是link的时候，可能是insert或modify
			postdata['data']['supplier_name']  = $('#supplier_name').val();
			postdata['data']['supplier_desc']  = $('#supplier_desc').val();
		}
		visit_api_for_result(postdata);	//提交数据给接口
	});
}

//添加问题数据的表单
function view_issue_form(postdata, act){
	var htmltitle = (act=='create')?'添加问题':'修改问题信息';
	var htmlform = '';
	/*
	 * 0:招标/签约阶段 1:设计阶段 2:生产阶段 3:运输阶段 4:现场存储阶段 5:安装测试阶段 6:验收阶段 7:质保阶段
	 * 
	 * */
	htmlform += '<form id="form1">';
	htmlform += '<ul>';
	htmlform += '  <li><div class="col_left">问题描述：</div><div class="col_right"><input type="text" id="issue_desc" name="issue_desc" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li><div class="col_left">所属凭单号：</div><div class="col_right"><input type="text" id="issue_voucher_no" name="issue_voucher_no" maxlength="100" class="text_name" /></div></li>';
	htmlform += '  <li>';
	htmlform += '	<div class="col_left">凭单日期：</div>';
	htmlform += '	<div class="col_mid"><input type="date" id="issue_voucher_date" name="issue_voucher_date" class="text_date" /></div>';
	htmlform += '	<div class="col_left">所在阶段：</div>';
	htmlform += '	<div class="col_right">';
		htmlform += '	<select id="issue_type" name="issue_type" class="select_list">';
		htmlform += '	<option value="-1">请选择所在阶段</option>';
		for(i in ISSUE_TYPE){
			htmlform += '	<option value="' + i + '">' + ISSUE_TYPE[i] + '</option>';
		}
		htmlform += '	</select>';
	htmlform += '	</div>';
	htmlform += '  </li>';
	htmlform += '  <li><div class="col_left">成因分析：</div><div class="col_right"><textarea id="issue_causes" name="issue_causes" class="text_desc" /></textarea></div></li>';
	htmlform += '  <li><div class="col_left">解决过程：</div><div class="col_right"><textarea id="issue_solve" name="issue_solve" class="text_desc" /></textarea></div></li>';
	htmlform += '</ul>';
	htmlform += '<div class="col_button"><input type="button" id="btn_submit" value=" 提 交 " /> &nbsp; <input type="button" class="cancel" value=" 取 消 " /></div>';
	htmlform += '</form>';
	
	$('.form_window .form_title .title_text').html(htmltitle);
	$('.form_window .form_body').html(htmlform);
	
	if(act == "modify"){
		//读取当前项目信息
		var tmpdata = {
			'api':postdata['api'],
			'act':'selectbyid',
			'obj':postdata['obj'],
			'data':{"issue_id":postdata['data']['issue_id']}
		};
	
		$.post("/" + tmpdata['api'] + ".php", 'opt=' + JSON.stringify(tmpdata), function(result) {
			//根据返回结果填充表单
			if(typeof(result['errmsg']) != "undefined"){
				alert(result["errmsg"]);
			}
			else{
				$('#issue_desc').val(result['data'][0]['issue_desc']);
				$('#issue_voucher_no').val(result['data'][0]['issue_voucher_no']);
				$('#issue_type').get(0).selectedIndex = parseInt(result['data'][0]['issue_type'])+1;
				$('#issue_voucher_date').val(result['data'][0]['issue_voucher_date']);
				$('#issue_causes').html(result['data'][0]['issue_causes']);
				$('#issue_solve').html(result['data'][0]['issue_solve']);
			}
		}, "json");
	}
	
	$('#btn_submit').click(function(){
		if(act == "modify"){
			postdata['act'] = 'update';
		}
		else{
			postdata['act'] = 'insert';
		}
		postdata['data']['issue_desc']  = $('#issue_desc').val();
		postdata['data']['issue_voucher_no']  = $('#issue_voucher_no').val();
		postdata['data']['issue_voucher_date']  = $('#issue_voucher_date').val();
		postdata['data']['issue_type']  = $('#issue_type').val();
		postdata['data']['issue_causes']  = $('#issue_causes').val();
		postdata['data']['issue_solve']  = $('#issue_solve').val();
		visit_api_for_result(postdata);	//提交数据给接口
	});
}

//点击左侧导航主功能按钮，读取相应数据并在右侧数据信息区展示
/*
 * 数据和信息展示区域
 * .area_right .info_head
 * .area_right .info_body
 * .area_right .info_foot
 * 
 * 读取问题列表的返回数据格式示例
 * {"obj":"issue","act":"select","title":"\u95ee\u9898\u5217\u8868","data":[{"issue_id":"4","issue_desc":"\u95ee\u98983\u63cf\u8ff0\u4fee\u6539","issue_voucher_no":"\u95ee\u98983\u6240\u5c5e\u51ed\u5355\u53f7\u4fee\u6539","issue_voucher_date":"2017-05-10 00:00:00","issue_causes":"\u95ee\u98983\u6210\u56e0\u5206\u6790\u4fee\u6539","issue_solve":"\u95ee\u98983\u89e3\u51b3\u8fc7\u7a0b\u4fee\u6539","user_name":"\u7528\u62371\u540d\u79f0","last_update":"2017-06-02 18:49:21","create_date":"2017-05-31 17:31:42","project_id":"10","equipment_id":"7","supplier_id":"5","issue_type":"3"}],"post":{"project_id":"10","equipment_id":"7","supplier_id":"5","issue_type":""}}
 * 
 */
function format_data_to_html(result){
	//对返回数据进行解析
	var rData = result['data'];

	switch(result['obj']){
		case 'project':
			view_project_list(result);	// 列表展示从接口取得的数据
			break;
		case 'equipment':
			view_equipment_list(result);	// 列表展示从接口取得的数据
			break;
		case 'supplier':
			view_supplier_list(result);	// 列表展示从接口取得的数据
			break;
		case 'issue':
			view_issue_list(result);	// 列表展示从接口取得的数据
			break;
		default:
	}

	//给所有带有.submit样式的按钮重置onclick事件
	$('.area_right .submit').unbind( "click" );
	$('.area_right .submit').click(function(){
		return view_button_submit_click(this);
	});

}

//显示项目数据列表
function view_project_list(result){
	var rData = result['data'];
	var total_num = 0;
	var html_infohead_left = '';
	var html_infohead_middle = '';
	var html_infohead_right = '';
	var html_infoview = '';
	var html_s = '<ul>';
	var html_m = '';
	var html_e = '</ul>';
	
	//遍历返回数组，格式化展示内容
	for(i in rData){
		html_m += '<li>';
		html_m += '  <div class="info_block">';
		html_m += '    <div class="info_title">';
		if(rData[i]['total']){
			html_m += '      <a class="submit" api="p1" obj="equipment" act="select" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\"}\'>' + rData[i]['project_name'] + '</a>';
		}
		else{
			html_m += '      <a class="submit" api="p1" obj="equipment" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"project_name\":\"' + rData[i]['project_name'] + '\"}\'>' + rData[i]['project_name'] + '</a>';
		}
		html_m += '    </div>';
		html_m += '    <div class="option_nav">';
		html_m += '    <div class="img_icon"><img class="submit" src="/css/img/ico_modify.png" title="编辑项目信息" api="p1" obj="project" act="modify" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\"}\' /></div>';
		if(rData[i]['total']){
			html_m += '      <div class="nolinks">设备<span class="info_num">' + rData[i]['total'] + '</span></div>';
		}
		else{
			html_m += '      <a class="submit" api="p1" act="create" obj="equipment" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"project_name\":\"' + rData[i]['project_name'] + '\"}\'>添加设备</a>';
		}
		
		html_m += '    </div>';
		html_m += '  </div>';
		html_m += '</li>';
		total_num++;	//计数
	}
    
	//搜索框

    html_infohead_left += '  <span class="result_desc">查看' + result['title'] + '</span>';
	html_infohead_left += '  <span class="result_count">总计：'+total_num + '</span>';

	html_infohead_right += '  <a class="submit" api="p1" obj="project" act="create" data="{}">添加项目</a>';
	
	html_infoview = html_s + html_m + html_e;
	
	$('.area_right .info_head .head_left').html(html_infohead_left);
	$('.area_right .info_head .head_right').html(html_infohead_right);
	$('.area_right .info_body').html(html_infoview);
    
	html_infohead_middle += '<input type="text" id="searchkey" name="searchkey" class="searchbox" maxlength="50" placeholder="搜索设备、供应商、问题..." />';
	html_infohead_middle += '<input type="button" class="searchbtn" value=" 搜索 " />';
	$('.area_right .info_head .head_middle').html(html_infohead_middle);
}

//显示设备数据列表
function view_equipment_list(result){
	var rData = result['data'];
	var total_num = 0;
	var html_infohead_left = '';
	var html_infohead_right = '';
	var html_infoview = '';
	var html_s = '<ul>';
	var html_m = '';
	var html_e = '</ul>';
	
	//遍历返回数组，格式化展示内容
	for(i in rData){
		html_m += '<li class="datalist">';
		html_m += '  <div class="info_block">';
		html_m += '    <div class="info_title">';
		if(rData[i]['total']){
			html_m += '      <a class="submit" api="p1" obj="supplier" act="select" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\"}\'>' + rData[i]['equipment_name'] + '</a>';
		}
		else{
			html_m += '      <a class="submit" api="p1" obj="supplier" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"equipment_name\":\"' + rData[i]['equipment_name'] + '\"}\'>' + rData[i]['equipment_name'] + '</a>';
		}
		html_m += '    </div>';
		html_m += '    <div class="option_nav">';
		html_m += '    <div class="img_icon"><img class="submit" src="/css/img/ico_modify.png" title="编辑设备信息" api="p1" obj="equipment" act="modify" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\"}\' /></div>';
		if(rData[i]['total']){
			html_m += '      <div class="nolinks">供应商<span class="info_num">' + rData[i]['total'] + '</span></div>';
		}
		else{
			html_m += '      <a class="submit" api="p1" obj="supplier" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"equipment_name\":\"' + rData[i]['equipment_name'] + '\"}\'>添加供应商</a>';
		}
		
		html_m += '    </div>';
		html_m += '  </div>';
		html_m += '</li>';
		total_num++;	//计数
	}
	html_infohead_left += '  <span class="result_desc"><a class="submit" api="p1" obj="project" act="select" data="{}">查看项目</a> -> 查看' + result['title'] + '</span>';
	html_infohead_left += '  <span class="result_count">总计：'+total_num + '</span>';

	html_infohead_right += '  <a class="submit" api="p1" obj="equipment" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\"}\'>添加设备</a>';
	
	html_infoview = html_s + html_m + html_e;
	
	$('.area_right .info_head .head_left').html(html_infohead_left);
	$('.area_right .info_head .head_right').html(html_infohead_right);
	$('.area_right .info_body').html(html_infoview);
}


//显示供应商数据列表
function view_supplier_list(result){
	var rData = result['data'];
	var total_num = 0;
	var html_infohead_left = '';
	var html_infohead_right = '';
	var html_infoview = '';
	var html_s = '<ul>';
	var html_m = '';
	var html_e = '</ul>';
	
	//遍历返回数组，格式化展示内容
	for(i in rData){
		html_m += '<li>';
		html_m += '  <div class="info_row">';
		html_m += '    <div class="info_title">';
		if(rData[i]['total']){
			html_m += '      <a class="submit" api="p1" obj="issue" act="select" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\",\"issue_type\":\"\"}\'>' + rData[i]['supplier_name'] + '</a>';
		}
		else{
			html_m += '      <a class="submit" api="p1" obj="issue" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\",\"supplier_name\":\"' + rData[i]['supplier_name'] + '\",\"issue_type\":\"\"}\'>' + rData[i]['supplier_name'] + '</a>';			
		}
		html_m += '    </div>';
		html_m += '    <div class="option_nav">';
		html_m += '    <div class="img_icon"><img class="submit" src="/css/img/ico_modify.png" title="编辑供应商信息" api="p1" obj="supplier" act="modify" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\"}\' /></div>';
		html_m += '			<a class="">评价</a>';
		if(rData[i]['total']){
			html_m += '      <div class="nolinks">问题<span class="info_num">' + rData[i]['total'] + '</span></div>';
		}
		else{
			html_m += '      <a class="submit" api="p1" obj="issue" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\",\"supplier_name\":\"' + rData[i]['supplier_name'] + '\",\"issue_type\":\"\"}\'>添加问题</a>';
		}
		
		html_m += '    </div>';
		html_m += '  </div>';
		html_m += '</li>';
		total_num++;	//计数
	}
	html_infohead_left += '  <span class="result_desc"><a class="submit" api="p1" obj="project" act="select" data="{}">查看项目</a> -> <a class="submit" api="p1" obj="equipment" act="select" data=\'{\"project_id\":\"' + result['post']['project_id'] + '\"}\'>查看设备</a> -> ' + result['title'] + '</span>';
	html_infohead_left += '  <span class="result_count">总计：'+total_num + '</span>';

	html_infohead_right += '  <a class="submit" api="p1" obj="supplier" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\"}\'>添加供应商</a>';
	
	html_infoview = html_s + html_m + html_e;
	
	$('.area_right .info_head .head_left').html(html_infohead_left);
	$('.area_right .info_head .head_right').html(html_infohead_right);
	$('.area_right .info_body').html(html_infoview);
}

//显示问题数据列表
function view_issue_list(result){
	var rData = result['data'];
	var total_num = 0;
	var html_infohead_left = '';
    var html_infohead_middle = '';
	var html_infohead_right = '';
	var html_infoview = '';
	var html_s = '<ul>';
	var html_m = '';
	var html_e = '</ul>';

	//遍历返回数组，格式化展示内容
	for(i in rData){
		html_m += '<li>';
		html_m += '  <div class="info_block">';
		html_m += '    <div class="info_title">';
		html_m += '      <p><span class="issue_id">#' + rData[i]['issue_id'] + '</span>' + rData[i]['issue_desc'] + '</p>';
		html_m += '    </div>';
		html_m += '    <div class="option_nav">';
		html_m += '      <ul>';
		html_m += '        <li>';
		html_m += '			 <span class="col_left">提交人：</span><span class="col_user">' + rData[i]['user_name'] + '</span>';
		html_m += '			 <span class="col_right">';
		html_m += '            <span class="col_tag">最后修改日期：</span>';
		if(rData[i]['last_update']==null){
			html_m += rData[i]['create_date'].substr(0,10);
		}
		else{
			html_m += rData[i]['last_update'].substr(0,10);
		}
		html_m += '			 </span></li>';
		//html_m += '        <li><span class="col_left">问题描述：</span><span class="col_body">' + rData[i]['issue_desc'] + '</span></li>';
		html_m += '        <li><span class="col_left">凭单号：</span><span class="col_body">' + rData[i]['issue_voucher_no'] + '</span></li>';
		html_m += '        <li><span class="col_left">凭单日期：</span><span class="col_date">' + rData[i]['issue_voucher_date'].substr(0,10) + '</span>';
		html_m += '			 <span class="col_right">';
		html_m += '            <span class="col_tag">所属阶段：</span>';
					html_m += ISSUE_TYPE[rData[i]['issue_type']];
		html_m += '			 </span></li>';
		html_m += '        <li><span class="col_left">成因分析：</span><span class="col_body">' + rData[i]['issue_causes'] + '</span></li>';
		html_m += '        <li><span class="col_left">解决过程：</span><span class="col_body">' + rData[i]['issue_solve'] + '</span></li>';
		html_m += '		 </ul>';
		html_m += '      <div class="img_icon nofloat"><img class="submit" src="/css/img/ico_modify.png" title="编辑问题信息" api="p1" obj="issue" act="modify" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\",\"issue_id\":\"' + rData[i]['issue_id'] + '\"}\' /></div>';
		html_m += '    </div>';
		html_m += '  </div>';
		html_m += '</li>';
		total_num++;	//计数
	}

	html_infohead_left += '  <span class="result_desc"><span class="result_desc"><a class="submit" api="p1" obj="project" act="select" data="{}">查看项目</a> -> <a class="submit" api="p1" obj="equipment" act="select" data=\'{\"project_id\":\"' + result['post']['project_id'] + '\"}\'>查看设备</a> -> <a class="submit" api="p1" obj="supplier" act="select" data=\'{\"project_id\":\"' + result['post']['project_id'] + '\",\"equipment_id\":\"' + result['post']['equipment_id'] + '\"}\'>查看供应商</a> -> ' + result['title'] + '</span>';
	html_infohead_left += '  <span class="result_count">总计：'+total_num + '</span>';

	html_infohead_right += '  <a class="submit" api="p1" obj="issue" act="create" data=\'{\"project_id\":\"' + rData[i]['project_id'] + '\",\"equipment_id\":\"' + rData[i]['equipment_id'] + '\",\"supplier_id\":\"' + rData[i]['supplier_id'] + '\"}\'>添加问题</a>';
	
	html_infoview = html_s + html_m + html_e;
	
	$('.area_right .info_head .head_left').html(html_infohead_left);
	$('.area_right .info_head .head_right').html(html_infohead_right);
	$('.area_right .info_body').html(html_infoview);
}

//展示首页布局
function returnhome(){
	//清空各内容区域
	$('.area_right .info_head .head_left').html('');
	$('.area_right .info_head .head_right').html('');
	$('.area_right .info_body').html('');

	var html_infohead_middle = '<input type="text" id="searchkey" name="searchkey" class="searchbox widerbox" maxlength="50" placeholder="填写搜索关键词..." /><input type="button" class="searchbtn" value="搜索" /><input type="button" class="statistics" value=" 统计 " />';
	$('.area_right .info_head .head_middle').html(html_infohead_middle);
}
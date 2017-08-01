<?php
header('Content-Type:application/json;charset=utf-8');

//调用全局包含模块和数据库连接模块
require_once 'include/global_config.php';
require_once 'include/db_connect.php';
require_once 'include/user_login.php';

//调用问题汇总所用的功能模块
require_once 'model/p1_data.php';

//如果有post数据，解析并进行相应处理
/* 
 * -- select --
 * $_POST['opt'] = {
 *      'act': 'select',
 *      'obj': (操作对象'project' / 'equipment' / 'supplier' / 'issue')
 *      'data':{
 *          'project_id': (项目id),
 *          'equipment_id': (设备id),
 *          'supplier_id': (供应商id),
 *          'issue_type': (问题所在阶段编号)
 *      } 
 * }
 * 
 * -- insert --
 * $_POST['opt'] = {
 *      'act': 'insert',
 *      'obj': 'project',
 *      'data':{
 *          'project_name': (项目名称),
 *          'project_desc': (项目描述)
 *      } 
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'insert',
 *      'obj': 'equipment',
 *      'data':{
 *          'equipment_name': (设备名称),
 *          'equipment_desc': (设备描述),
 *          'equipment_model': (设备型号),
 *          'equipment_spec': (设备规格),
 *          'equipment_param': (设备参数),
 *          'project_id': (所在项目id)
 *      } 
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'link',
 *      'obj': 'equipment',
 *      'data':{
 *          'equipment_id': (设备id),
 *          'project_id': (所在项目id)
 *      } 
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'insert',
 *      'obj': 'supplier',
 *      'data':{
 *          'supplier_name': (供应商名称),
 *          'supplier_desc': (供应商描述),
 *          'equipment_id': (所在设备id),
 *          'project_id': (所在项目id)
 *      }
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'link',
 *      'obj': 'supplier',
 *      'data':{
 *          'supplier_id': (供应商id),
 *          'equipment_id': (设备id),
 *          'project_id': (所在项目id)
 *      } 
 * }
 *  
 * $_POST['opt'] = {
 *      'act': 'insert',
 *      'obj': 'issue',
 *      'data':{
 *          'issue_desc': (问题描述),
 *          'issue_voucher_no': (问题所属凭单号),
 *          'issue_voucher_date': (问题所属凭单日期),
 *          'issue_causes': (问题成因分析),
 *          'issue_solve': (问题解决过程),
 *          'issue_type': (问题所属阶段id),
 *          'supplier_id': (所属供应商id),
 *          'equipment_id': (所在设备id),
 *          'project_id': (所在项目id)
 *      }
 * }
 * 
 * -- update --
 * 
 * $_POST['opt'] = {
 *      'act': 'update',
 *      'obj': 'project',
 *      'data':{
 *          'project_id': (项目id),
 *          'project_name': (项目名称),
 *          'project_desc': (项目描述)
 *      } 
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'update',
 *      'obj': 'equipment',
 *      'data':{
 *          'equipment_id': (设备id),
 *          'equipment_name': (设备名称),
 *          'equipment_desc': (设备描述),
 *          'equipment_model': (设备型号),
 *          'equipment_spec': (设备规格),
 *          'equipment_param': (设备参数)
 *      }
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'update',
 *      'obj': 'supplier',
 *      'data':{
 *          'supplier_id': (供应商id),
 *          'supplier_name': (供应商名称),
 *          'supplier_desc': (供应商描述)
 *      }
 * }
 * 
 * $_POST['opt'] = {
 *      'act': 'update',
 *      'obj': 'issue',
 *      'data':{
 *          'issue_id': (问题id),
 *          'issue_desc': (问题描述 ),
 *          'issue_voucher_no': (所属凭单号),
 *          'issue_voucher_date': (凭单号日期),
 *          'issue_causes': (问题成因分析),
 *          'issue_solve': (问题解决过程),
 *          'issue_type': (问题类型)
 *      }
 * }
 * 
 * */
if(count($_POST) > 0){
  if(!empty($_POST['opt'])){
      $postdata = json_decode($_POST['opt'], true);
      $result = array(
          "obj" => "",
          "act" => "",
          "title" => "",
          "data" => ""
      );
      //根据obj参数筛选要操作的数据对象
      switch ($postdata['obj']){
          case 'project':
              //根据act参数进行相应的读写操作
              $result['obj'] = 'project';
              switch ($postdata['act']){
                  case 'selectbyid':
                      $result['act'] = 'selectbyid';
                      $result['title'] = '项目信息';
                      $result['post'] = $postdata['data'];
                      // 读取指定id的项目信息
                      $result['data'] = get_project_by_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'select':
                      $result['act'] = 'select';
                      $result['title'] = '项目列表';
                      // 读取所有项目列表，默认按id倒序排序
                      $result['data'] = get_project_list($PoemDB);
                      echo(json_encode($result));
                      break;
                  case 'insert':
                      $result['act'] = 'insert';
                      $result['title'] = '添加项目';
                      
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          
                          $result['data'] = insert_new_project_item($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  case 'update':
                      $result['act'] = 'update';
                      $result['title'] = '更新项目信息';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['last_update'] = date("Y-m-d H:i:s");

                          $result['data'] = update_project_item($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  default:
                      
              }
              break;

          case 'equipment':
              $result['obj'] = 'equipment';
              switch ($postdata['act']){
                  case 'selectbyid':
                      $result['act'] = 'selectbyid';
                      $result['title'] = '设备信息';
                      $result['post'] = $postdata['data'];
                      // 读取指定项目id下所有设备，默认按id倒序排序
                      $result['data'] = get_equipment_by_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'select':
                      $result['act'] = 'select';
                      $result['title'] = '设备列表';
                      $result['post'] = $postdata['data'];
                      // 读取指定项目id下所有设备，默认按id倒序排序
                      $result['data'] = get_equipment_list_by_project_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'unselect':
                      $result['act'] = 'unselect';
                      $result['title'] = '设备列表';
                      $result['post'] = $postdata['data'];
                      // 读取不属于指定项目id下的所有设备，默认按id倒序排序
                      $result['data'] = get_equipment_list_not_for_project_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'insert':
                      $result['act'] = 'insert';
                      $result['title'] = '添加设备';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          $result['data'] = insert_new_equipment($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                          exit();
                      }
                      break;
                  case 'link':
                      $result['act'] = 'link';
                      $result['title'] = '添加项目和设备的关联';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          $result['data'] = insert_new_project_equipment_link($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  case 'update':
                      $result['act'] = 'update';
                      $result['title'] = '更新设备信息';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['last_update'] = date("Y-m-d H:i:s");
                          $result['data'] = update_equipment_item($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  default:
                      
              }
              break;

          case 'supplier':
              $result['obj'] = 'supplier';
              switch ($postdata['act']){
                  case 'selectbyid':
                      $result['act'] = 'selectbyid';
                      $result['title'] = '供应商信息';
                      $result['post'] = $postdata['data'];
                      // 读取指定id下的供应商信息
                      $result['data'] = get_supplier_by_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'select':
                      $result['act'] = 'select';
                      $result['title'] = '供应商列表';
                      $result['post'] = $postdata['data'];
                      // 读取指定项目和设备id下所有供应商，默认按id倒序排序
                      $result['data'] = get_supplier_list_by_full_path($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'unselect':
                      $result['act'] = 'unselect';
                      $result['title'] = '供应商列表';
                      $result['post'] = $postdata['data'];
                      // 读取指定项目和设备id下所有供应商，默认按id倒序排序
                      $result['data'] = get_supplier_not_for_full_path($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'insert':
                      $result['act'] = 'insert';
                      $result['title'] = '添加供应商';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          $result['data'] = insert_new_supplier($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  case 'link':
                      $result['act'] = 'link';
                      $result['title'] = '添加项目、设备和供应商的关联';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          $result['data'] = insert_new_project_equipment_supplier($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  case 'update':
                      $result['act'] = 'update';
                      $result['title'] = '更新供应商信息';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['last_update'] = date("Y-m-d H:i:s");
                          $result['data'] = update_supplier_item($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  default:
                      
              }
              break;

          case 'issue':
              $result['obj'] = 'issue';
              switch ($postdata['act']){
                  case 'selectbyid':
                      $result['act'] = 'selectbyid';
                      $result['title'] = '问题信息';
                      $result['post'] = $postdata['data'];
                      // 读取指定id的问题信息
                      $result['data'] = get_issue_by_id($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'select':
                      $result['act'] = 'select';
                      $result['title'] = '问题列表';
                      $result['post'] = $postdata['data'];
                      // 读取指定项目、设备、供应商id以及问题类型下所有问题，默认按id倒序排序
                      $result['data'] = get_issue_list_by_full_path($PoemDB, $postdata['data']);
                      echo(json_encode($result));
                      break;
                  case 'insert':
                      $result['act'] = 'insert';
                      $result['title'] = '添加问题';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['create_date'] = date("Y-m-d H:i:s");
                          $result['data'] = insert_new_issue($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  case 'update':
                      $result['act'] = 'update';
                      $result['title'] = '更新问题信息';
                      //获得当前登录用户信息
                      $userinfo = fn_login_getUserInfo();
                      if(empty($userinfo['errmsg'])){
                          $postdata['data']['user_id'] = $userinfo['user_id'];
                          $postdata['data']['user_name'] = $userinfo['user_name'];
                          $postdata['data']['last_update'] = date("Y-m-d H:i:s");
                          $result['data'] = update_issue_item($PoemDB, $postdata['data']);
                          echo(json_encode($result));
                      }
                      else{
                          echo('{"errmsg":' . $userinfo['errmsg'] .'}');
                      }
                      break;
                  default:
                      
              }
              break;

          default:
              
      }
  }
}
exit();


//接收post内容和接口调用类型

// 读取所有项目列表
// print_r(get_project_list($PoemDB));

// 读取指定id的项目下所有设备
// print_r(get_equipment_list_by_project_id($PoemDB, 9));

// 读取指定全路径下所有供应商
// print_r(get_supplier_list_by_full_path($PoemDB, 9, 4));

// 读取指定全路径下所有问题
// print_r(get_issue_list_by_full_path($PoemDB, 9, 4, 3, 1));

// 新建一条项目数据
/*
 * $project_data = array(
 * 'project_name' => '项目名称2',
 * 'project_desc' => '项目2的描述信息',
 * 'user_id' => '2',
 * 'user_name' => '用户名2',
 * 'create_date' => date("Y-m-d H:i:s")
 * );
 *
 * $result = insert_new_project_item($PoemDB, $project_data);
 *
 * if($result['errmsg']){
 * echo('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
 * }
 * else{
 * echo($result . ' 条数据创建成功');
 * }
 */

// 新建一条设备数据
/*
 * $postdata = array(
 * 'equipment_name' => '设备2名称',
 * 'equipment_desc' => '设备2描述',
 * 'equipment_model' => '设备2型号',
 * 'equipment_spec' => '设备2规格',
 * 'equipment_param' => '设备2参数',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '1',
 * 'user_name' => '用户1名称',
 * 'project_id' => '9'
 * );
 *
 * $result = insert_new_equipment($PoemDB, $postdata);
 *
 * if($result['errmsg']){
 * echo('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
 * }
 * else{
 * echo($result . ' 条数据创建成功');
 * }
 */

// 新建一条项目和设备关联数据
/*
$postdata = array(
    'create_date' => date("Y-m-d H:i:s"),
    'user_id' => '1',
    'user_name' => '用户1名称',
    'equipment_id' => '6',
    'project_id' => '9'
);

$result = insert_new_project_equipment($PoemDB, $postdata);

if ($result['errmsg']) {
    echo ('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
} else {
    echo ($result . ' 条数据创建成功');
}
*/

// 新建一条供应商数据
/*
 * $postdata = array(
 * 'supplier_name' => '供应商2名称',
 * 'supplier_desc' => '供应商2描述',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '1',
 * 'user_name' => '用户1名称',
 * 'project_id' => '9',
 * 'equipment_id' => '4'
 * );
 *
 * $result = insert_new_supplier($PoemDB, $postdata);
 *
 * if($result['errmsg']){
 * echo('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
 * }
 * else{
 * echo($result . ' 条数据创建成功');
 * }
 */

// 新建一条项目、设备和供应商关联数据
/*
$postdata = array(
    'create_date' => date("Y-m-d H:i:s"),
    'user_id' => '1',
    'user_name' => '用户1名称',
    'supplier_id' => '4',
    'equipment_id' => '6',
    'project_id' => '9'
);

$result = insert_new_project_equipment_supplier($PoemDB, $postdata);

if ($result['errmsg']) {
    echo ('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
} else {
    echo ($result . ' 条数据创建成功');
}
*/

// 新建一条问题数据
/*
 * $postdata = array(
 * 'issue_desc' => '问题2描述',
 * 'issue_voucher_no' => '问题2所属凭单号',
 * 'issue_voucher_date' => '2017-03-15',
 * 'issue_causes' => '问题2成因分析',
 * 'issue_solve' => '问题2解决过程',
 * 'issue_type' => '4',
 * 'supplier_id' => '4',
 * 'equipment_id' => '4',
 * 'project_id' => '9',
 * 'create_date' => date("Y-m-d H:i:s"),
 * 'user_id' => '1',
 * 'user_name' => '用户1名称'
 * );
 *
 * $result = insert_new_issue($PoemDB, $postdata);
 *
 * if($result['errmsg']){
 * echo('数据创建失败，错误信息：【' . $result['errmsg'] . '】');
 * }
 * else{
 * echo($result . ' 条数据创建成功');
 * }
 */

?>
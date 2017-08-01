<?php
function fn_login_getUserInfo(){
    $userinfo = array();

    //读取当前登录用户信息
    $userinfo['user_id'] = 1;
    $userinfo['user_name'] = '用户1名称';
    
    return $userinfo;
}
?>
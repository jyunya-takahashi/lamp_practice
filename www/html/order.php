<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'order.php';
session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
// トークンの生成
$token = get_csrf_token();

// orderテーブル取得
if($user['type'] === 2){
  $orders = get_user_orders($db, $user['user_id']);
}else{
  $orders = get_user_orders_admin($db);
}


include_once VIEW_PATH . 'order_view.php';
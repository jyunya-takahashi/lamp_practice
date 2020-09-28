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
// POST処理
$order_id = get_post('order_id');

// トークンの生成
$token = get_csrf_token();

// orderテーブル取得
$order_detail_top = get_order_detail_top($db, $order_id);

// order_detailsテーブル取得
$order_details = get_order_details($db, $order_id);

include_once VIEW_PATH . 'order_details_view.php';
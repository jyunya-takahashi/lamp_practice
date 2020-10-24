<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);



// トークンの生成
$token = get_csrf_token();

// 購入数ランキングを取得
$rankings = get_ranking($db);

// 並べ替え機能
if(isset($_GET['sort'])){
  $sort = get_get("sort");
  $items = get_sort_open_items($db, $sort);
} else {
  $sort = 'created_desc';
  $items = get_sort_open_items($db, $sort);
}

include_once VIEW_PATH . 'index_view.php';

?>
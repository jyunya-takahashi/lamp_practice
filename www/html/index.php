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

// ページネーション
// 全アイテム数を取得
$all_item_num_array = get_open_item_count($db);
$all_item_num = $all_item_num_array['all_item_count'];

// ページ数を算出
$pagenation = $all_item_num / PAGENATION_LIMIT;
$pagenation = ceil($all_item_num / PAGENATION_LIMIT);

// GETで現在のページ数を取得する（未入力の場合は1を挿入）
if (isset($_GET['page'])) {
	$page = (int)$_GET['page'];
} else {
	$page = 1;
}

// スタートのポジションを計算する
if ($page > 1) {
	// 例：２ページ目の場合は、『(2 × 8) - 8 = 8』
	$start = ($page * PAGENATION_LIMIT) - PAGENATION_LIMIT;
} else {
	$start = 0;
}

// 並べ替えに沿ったitemを取得
if(isset($_GET['sort'])){
  $sort = get_get("sort");
  $items = get_sort_open_items($db, $sort, $start);
} else {
  $sort = 'created_desc';
  $items = get_sort_open_items($db, $sort, $start);
}


include_once VIEW_PATH . 'index_view.php';

?>
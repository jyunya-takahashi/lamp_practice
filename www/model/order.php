<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// orderテーブル取得用のSQL生成(購入履歴用)
function get_user_orders($db, $user_id){
  // execute時に使用する変数を格納
  $params = array('user_id'=>$user_id);
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      sum(order_details.order_price * order_details.quantity) as total_price
    FROM
      orders
      LEFT JOIN order_details
      ON orders.order_id = order_details.order_id
    WHERE
      user_id = :user_id
    GROUP BY
      order_id;
  ";
  return fetch_all_query($db, $sql, $params);
}

// orderテーブル取得用のSQL生成(購入履歴管理者用)
function get_user_orders_admin($db){
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,
      sum(order_details.order_price * order_details.quantity) as total_price
    FROM
      orders
      LEFT JOIN order_details
      ON orders.order_id = order_details.order_id
    GROUP BY
      order_id;
  ";
  return fetch_all_query($db, $sql);
}


// orderテーブル取得用のSQL生成(購入詳細TOP用)
function get_order_detail_top($db, $order_id){
  // execute時に使用する変数を格納
  $params = array('order_id'=>$order_id);
  $sql = "
    SELECT
      orders.order_id,
      orders.order_date,  
      sum(order_details.order_price * order_details.quantity) as total_price
    FROM
      orders
      LEFT JOIN order_details
      ON orders.order_id = order_details.order_id
    WHERE
      orders.order_id = :order_id
    GROUP BY
      orders.order_id;
  ";
  return fetch_all_query($db, $sql, $params);
}


// order_detailsテーブル取得
function get_order_details($db, $order_id = array()){
  $params = array('order_id'=>$order_id);
  $sql = "
  SELECT
    order_details.order_price,
    order_details.quantity,
    items.name
  FROM
    order_details
    LEFT OUTER JOIN items
    ON order_details.item_id = items.item_id
  WHERE
    order_id = :order_id;
  ";
  return fetch_all_query($db, $sql, $params);
}

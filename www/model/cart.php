<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  // execute時に使用する変数を格納
  $params = array('user_id'=>$user_id);
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
  ";
  return fetch_all_query($db, $sql, $params);
}

function get_user_cart($db, $user_id, $item_id){
  // execute時に使用する変数を格納
  $params = array('user_id'=>$user_id, 'item_id'=>$item_id);
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = :user_id
    AND
      items.item_id = :item_id
  ";

  return fetch_query($db, $sql, $params);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  // execute時に使用する変数を格納
  $params = array('user_id'=>$user_id, 'item_id'=>$item_id, 'amount'=>$amount);
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(:item_id, :user_id, :amount)
  ";

  return execute_query($db, $sql, $params);
}

function update_cart_amount($db, $cart_id, $amount){
  // execute時に使用する変数を格納
  $params = array('cart_id'=>$cart_id, 'amount'=>$amount);
  $sql = "
    UPDATE
      carts
    SET
      amount = :amount
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";
  return execute_query($db, $sql, $params);
}

function delete_cart($db, $cart_id){
  // execute時に使用する変数を格納
  $params = array('cart_id'=>$cart_id);
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = :cart_id
    LIMIT 1
  ";

  return execute_query($db, $sql, $params);
}

function purchase_carts($db, $carts){
  // カート内の商品チェック
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  // 処理日時の取得
  $log = date('Y-m-d H:i:s');
  
  // トランザクション開始
  $db->beginTransaction();
  try {
    // カートの商品分繰り返し処理
    foreach($carts as $cart){
      // ストック数の変更
      if(update_item_stock(
            $db, 
            $cart['item_id'], 
            $cart['stock'] - $cart['amount']
        ) === false){
        set_error($cart['name'] . 'の購入に失敗しました。');
      }
    }

    // ordersテーブル登録
    if(purchase_insert_orders($db, $carts, $log) === false) {
      set_error('購入に失敗しました。');
    }

    // 直前に登録したorder_idの取得
    $order_id = $db->lastinsertid();

    // order_detail_idテーブル登録
    // カートの商品分繰り返し処理
    foreach($carts as $cart){
      if(purchase_insert_order_detail(
        $db,
        $order_id,
        $cart['item_id'], 
        $cart['amount'],
        $cart['price']
      ) === false){
        set_error($cart['name'] . 'の購入に失敗しました。');
      }
    }

    // カート内削除処理
    delete_user_carts($db, $carts[0]['user_id']);
    
    // エラーが存在したか確認
    if(empty(get_errors()) === true){
      // エラーなしの場合コミット処理
      $db->commit();
    }else{
      // エラーの場合ロールバック処理
      $db->rollback();
      set_error('購入に失敗しました。');
    }

  } catch (PDOException $e) {
  }
}

function delete_user_carts($db, $user_id){
  // execute時に使用する変数を格納
  $params = array('user_id'=>$user_id);
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = :user_id
  ";

  execute_query($db, $sql, $params);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}
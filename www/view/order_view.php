<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文合計金額</th>
            <th>購入履歴詳細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order){ ?>
          <tr>
            <td><?php print h($order['order_id']); ?></td>
            <td><?php print h($order['order_date']); ?></td>
            <!-- 3桁のカンマ区切り -->
            <td><?php print h(number_format($order['total_price'])); ?>円</td>
            <td>
              <form method="post" action="order_details.php">
                <!-- トークン埋め込み -->
                <input type="hidden" name="token" value="<?=$token?>">
                <input type="submit" value="詳細" class="btn btn-info detail">
                <input type="hidden" name="order_id" value="<?php print($order['order_id']); ?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>
<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'order_details.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>
      <!-- 注文履歴概要 -->
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>注文合計金額</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php print h($order_detail_top['order_id']); ?></td>
            <td><?php print h($order_detail_top['order_date']); ?></td>
            <!-- 3桁のカンマ区切り -->
            <td><?php print h(number_format($order_detail_top['total_price'])); ?>円</td>
          </tr>
        </tbody>
      </table>
      <!-- 注文履歴詳細 -->
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($order_details as $order_detail){ ?>
          <tr>
            <td><?php print h($order_detail['name']); ?></td>
            <td><?php print h(number_format($order_detail['order_price'])); ?>円</td>
            <td><?php print h($order_detail['quantity']); ?>個</td>
            <!-- 3桁のカンマ区切り -->
            <td><?php print h(number_format($order_detail['quantity'] * $order_detail['order_price'])); ?>円</td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
  </div>
</body>
</html>


CREATE TABLE orders (
  order_id INT not null AUTO_INCREMENT,
  order_date datetime not null DEFAULT CURRENT_TIMESTAMP	,
  user_id INT not null,
  total_price INT not null,
  primary key(order_id)
);

CREATE TABLE order_items (
  order_id INT not null,
  item_id INT not null,
  quantity INT not null,
  order_price INT not null
);
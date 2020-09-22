CREATE TABLE orders (
  order_id INT not null AUTO_INCREMENT,
  order_date datetime not null DEFAULT CURRENT_TIMESTAMP,
  user_id INT not null,
  primary key(order_id)
);

CREATE TABLE order_details (
  order_detail_id INT not null AUTO_INCREMENT,
  order_id INT not null,
  item_id INT not null,
  quantity INT not null,
  order_price INT not null,
  primary key(order_detail_id)
);
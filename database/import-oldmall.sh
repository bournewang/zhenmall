options="character set utf8 FIELDS TERMINATED BY ',' optionally enclosed by '\"' LINES TERMINATED by '\n'"
mysql_command="mysql -uroot -e"
newdb="zhenmall"
dir="/Users/wangxiaopei/work/zhenmall/db"

$mysql_command "
SET foreign_key_checks = 0;
load data infile '$dir/user.csv'
into table ${newdb}.users
character set utf8 FIELDS TERMINATED BY ',' optionally enclosed by '\"' LINES TERMINATED by '\n'
(@col1, @col2, @col3, @col4, @col5)
set id=@col1, mobile=@col2, balance=@col3, quota=@col4, referer_id=@col5
"

$mysql_command "
load data infile '$dir/distribution.csv'
into table ${newdb}.relation
$options
(@col1, @col2)
set user_id=@col1, path=@col2
"

$mysql_command "
load data infile '$dir/category.csv'
into table ${newdb}.categories
$options
(@col1, @col2, @col3, @col4)
set id=@col1, pid=@col2, name=@col3, status=@col4
"

$mysql_command "
load data infile '$dir/goods.csv'
into table ${newdb}.goods
$options
(@col1, @col2, @col3, @col4, @col5, @col6, @col7)
set id=@col1, category_id=@col2, name=@col3,price_purchase=@col4, price=@col5, detail=@col6, status=@col7
"

$mysql_command "
load data infile '$dir/withdraw.csv'
into table ${newdb}.withdraw
$options
(@col1, @col2, @col3, @col4)
set user_id=@col1, amount=@col2, status=@col3, account=@col4
"

$mysql_command "
load data infile '$dir/orders.csv'
into table ${newdb}.orders
$options
(@col1, @col2, @col3, @col4, @col5, @col6, @col7, @col8, @col9, @col10)
set id=@col1, user_id=@col2, order_no=@col3, amount=@col4,
status=@col5, street=@col6, contact=@col7, mobile=@col8,
created_at=@col9, updated_at=@col10
"

$mysql_command "
SET foreign_key_checks = 0;
load data infile '$dir/order-goods.csv'
into table ${newdb}.goods_order
$options
(@col1, @col2, @col3, @col4, @col5)
set order_id=@col1, goods_id=@col2, price=@col3, quantity=@col4, subtotal=@col5
"

$mysql_command "
load data infile '$dir/balance_log.csv'
into table ${newdb}.balance_log
$options
(@col1, @col2, @col3, @col4, @col5, @col6)
set user_id=@col1, type=@col2, amount=@col3, balance=@col4, comment=@col5, created_at=@col6
"

$mysql_command "
load data infile '$dir/score_log.csv'
into table ${newdb}.quota_log
$options
(@col1, @col2, @col3, @col4, @col5, @col6)
set user_id=@col1, type=@col2, amount=@col3, balance=@col4, comment=@col5, created_at=@col6
"

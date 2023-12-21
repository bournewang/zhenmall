options="character set utf8 FIELDS TERMINATED BY ',' optionally enclosed by '\"' LINES TERMINATED by '\n'"
mysql_command="mysql -uroot -e "

$mysql_command "
SELECT id, mobile, money, score, p_id as referer_id
FROM oldmall.sh_user
into outfile '/tmp/db/user.csv'
$options
"

$mysql_command "
SELECT uid, path_uid as path
FROM oldmall.sh_distribution
into outfile '/tmp/db/distribution.csv'
$options
"

$mysql_command "
SELECT id,pid,name,
case when status='normal' then 'on_shelf'
  when status='hidden' then 'off_shelf'
end as status
FROM oldmall.sh_goods_type
into outfile '/tmp/db/category.csv'
$options
"

$mysql_command "
SELECT id,cid as category_id,name,supplier_price as price_purchase,price,detail, 'on_shelf' as status
FROM oldmall.sh_goods
into outfile '/tmp/db/goods.csv'
$options
"

$mysql_command "
select id, images
from oldmall.sh_goods
into outfile '/tmp/db/goods-images.csv'
$options
"

$mysql_command "
select id, detail
from oldmall.sh_goods
into outfile '/tmp/db/goods-detail.csv'
$options
"

$mysql_command "
SELECT u_id,price as amount,
if(status=1,'grant', 'reject') as status,
FROM_UNIXTIME(createtime) as created_at
FROM oldmall.sh_money_cash
into outfile '/tmp/db/withdraw.csv'
$options
"

$mysql_command "
select cast(id as unsigned) as id, uid as user_id, order_code as order_no, price as amount,
case when status=1 then 'unpaid'
when status=2 then 'paid'
when status=4 then 'shipped'
when status=7 then 'complete'
end as status,
address as street,name as contact, phone as mobile,
FROM_UNIXTIME(createtime) as created_at, FROM_UNIXTIME(updatetime) as updated_at
from oldmall.sh_order
into outfile '/tmp/db/orders.csv'
$options
"

$mysql_command "
select cast(id as unsigned) as order_id, gid as goods_id, price, goods_num, sum_price
from oldmall.sh_order
into outfile '/tmp/db/order-goods.csv'
$options
"

$mysql_command "
select uid as user_id,
case
  when status=0 then 'deposit'
  when status=1 then 'consume'
  when status=2 then 'edit'
end as type,
money as amount, close_money as balance, pay_type as comment,
FROM_UNIXTIME(createtime) as created_at
from oldmall.sh_money_detail
into outfile '/tmp/db/balance_log.csv'
$options
"

$mysql_command "
select uid as user_id,
case
  when status=0 then 'deposit'
  when status=1 then 'consume'
  when status=2 then 'edit'
end as type,
money as amount, close_money as balance, pay_type as comment,
FROM_UNIXTIME(createtime) as created_at
from oldmall.sh_score_detail
into outfile '/tmp/db/score_log.csv'
$options
"

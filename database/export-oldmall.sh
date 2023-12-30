options="character set utf8 FIELDS TERMINATED BY ',' optionally enclosed by '\"' LINES TERMINATED by '\n'"
mysql_command="mysql -uroot -e "
db="mall"
dir="/Users/wangxiaopei/work/zhenmall/db"

$mysql_command "
SELECT id, mobile, money, score, p_id as referer_id
FROM ${db}.sh_user
into outfile '$dir/user.csv'
$options
"

$mysql_command "
SELECT uid, path_uid as path
FROM ${db}.sh_distribution
into outfile '$dir/distribution.csv'
$options
"

$mysql_command "
SELECT id,pid,name,
case when status='normal' then 'on_shelf'
  when status='hidden' then 'off_shelf'
end as status
FROM ${db}.sh_goods_type
into outfile '$dir/category.csv'
$options
"

$mysql_command "
SELECT id,cid as category_id,name,supplier_price as price_purchase,price,detail, 'on_shelf' as status
FROM ${db}.sh_goods
into outfile '$dir/goods.csv'
$options
"

$mysql_command "
select id, images
from ${db}.sh_goods
into outfile '$dir/goods-images.csv'
$options
"

$mysql_command "
select id, detail
from ${db}.sh_goods
into outfile '$dir/goods-detail.csv'
$options
"

$mysql_command "
SELECT u_id,price as amount,
if(status=1, 'completed', 'rejected') as status,
FROM_UNIXTIME(createtime) as created_at
FROM ${db}.sh_money_cash
into outfile '$dir/withdraw.csv'
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
from ${db}.sh_order
into outfile '$dir/orders.csv'
$options
"

$mysql_command "
select cast(id as unsigned) as order_id, gid as goods_id, price, goods_num, sum_price
from ${db}.sh_order
into outfile '$dir/order-goods.csv'
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
from ${db}.sh_money_detail
into outfile '$dir/balance_log.csv'
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
from ${db}.sh_score_detail
into outfile '$dir/score_log.csv'
$options
"

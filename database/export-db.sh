mysql -uroot -e "
SELECT id,cid as category_id,name,supplier_price as price_purchase,price,detail, 'on_shelf' as status
FROM oldmall.sh_goods
into outfile '/tmp/goods1.csv'
character set utf8
FIELDS TERMINATED BY ','
optionally enclosed by '\"'
LINES TERMINATED by '\n'
"


mysql -uroot -e "
SELECT id, mobile, money, score, p_id as referer_id
FROM oldmall.sh_user
into outfile '/tmp/user.csv'
character set utf8
FIELDS TERMINATED BY ','
optionally enclosed by '\"'
LINES TERMINATED by '\n'
"

mysql -uroot -e "
SELECT uid, pid, path_uid as path
FROM oldmall.sh_distribution
into outfile '/tmp/distribution.csv'
character set utf8
FIELDS TERMINATED BY ','
optionally enclosed by '\"'
LINES TERMINATED by '\n'
"




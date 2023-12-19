mysql -uroot -e "
SELECT id,cid as category_id,name,supplier_price as price_purchase,price,detail, 'on_shelf' as status
FROM oldmall.sh_goods
into outfile '/tmp/goods1.csv'
character set utf8
FIELDS TERMINATED BY ','
optionally enclosed by '\"'
LINES TERMINATED by '\n'
"

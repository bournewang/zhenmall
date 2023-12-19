mysql -uroot -e "
load data infile '/tmp/goods1.csv'
into table zhenmall.goods
character set utf8
FIELDS TERMINATED BY ','
optionally enclosed by '\"'
LINES TERMINATED by '\n'
(@col1, @col2, @col3, @col4, @col5, @col6, @col7)
set id=@col1, category_id=@col2, name=@col3,price_purchase=@col4, price=@col5, detail=@col6, status=@col7
"

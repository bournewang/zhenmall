#!/bin/sh
for f in Models/*.php;
do 
    # echo $f;
    r=${f%%.php};
    r=${r##Models/};
    # echo "checking $r";
    echo "'App\\Models\\$r' \t=> 'App\Policies\\${r}Policy',"
    # p="Policies/${r}Policy.php"
    # if [ ! -f $p ]; then
    #     cp Policies/BannerPolicy.php $p;
    #     gsed -i "s/Banner/$r/" $p
    # fi    
        
done    
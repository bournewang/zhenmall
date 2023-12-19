<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="/vendor/nova/app.css">
        <style media="screen">
            table{margin: auto; text-align: left;}
        </style>
    </head>
    <body class="text-center mt-3">
        <?php 
            $log = './storage/import-log/'.$_GET['p'].".html";
            if (!file_exists($log)) { 
        ?>
        <span class="text text-danger ">没有找到日志文件</span>
        <?php }else{ ?>
        <table class="table mt-3">
            <thead>
                <th>数据</th>
                <th>结果</th>
            </thead>
            <tbody>
            <?php include($log); ?>
            </tbody>
        </table>
        <?php } ?>
    </body>
</html>

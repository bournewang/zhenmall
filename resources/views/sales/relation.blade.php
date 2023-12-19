<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <link rel="stylesheet" href="/css/jquery.orgchart.min.css">
        <style media="screen">
            body{
                text-align: center;
            }
            .orgchart .node .title{
                width: 15em;
            }
            .orgchart .node .content {
                text-align: left;
                height: 8em;
            }
        </style>
    </head>
    <body>
        <h1>销售网络关系</h1>
        <div class="" id="chart">
            
        </div>   
        <script type="text/javascript" src="/js/jquery-3.5.1.min.js"></script>
        <script type="text/javascript" src="/js/jquery.orgchart.min.js"></script>
        <script type="text/javascript">
        $(function() {
            // var datasource = {
            //   'name': 'Lao Lao',
            //   'title': 'general manager',
            //   'children': [
            //     { 'name': 'Bo Miao', 'title': 'department manager' },
            //     { 'name': 'Su Miao', 'title': 'department manager',
            //       'children': [
            //         { 'name': 'Tie Hua', 'title': 'senior engineer' },
            //         { 'name': 'Hei Hei', 'title': 'senior engineer',
            //           'children': [
            //             { 'name': 'Dan Dan', 'title': 'engineer' }
            //           ]
            //         },
            //         { 'name': 'Pang Pang', 'title': 'senior engineer' }
            //       ]
            //     },
            //     { 'name': 'Hong Miao', 'title': 'department manager' }
            //   ]
            // };
            var datasource = {!! json_encode($orgData) !!}

            $('#chart').orgchart({
              'data' : datasource,
              'nodeContent': 'sales'
            });

            });
        </script>
    </body>
</html>
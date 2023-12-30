<?php
use App\Models\User;
return [
    'banner' => [
        // ['title' => 'iphone12美版64G', 'goods_id' => 2, 'image' => 'iphone.jpeg', 'store_id' => 1],
        // ['title' => 'ipad mini3 64G', 'goods_id' => 5, 'image' => 'ipad.jpeg', 'store_id' => 1],
    ],
    'devices' => [
        'ggz17zg9nAJ' => ['QYB_D0001', 'Graphene_G0002', 'Graphene_G0003'],
        'ggz1JU0WNFH' => ['JQR_0003', 'JQR_0004', 'JQR_0005']
    ],
    // resource to build permissions of View/Create/Update/Delete/ForceDelete
    // new resource must put here
    'resources' => [
	    // 'DeviceRental',
	    // 'MembershipCard', 'MembershipUsedItem',
	    // 'Bill', 'BillItem',
        "Address","Banner","Cart","Category","City",
        "Customer","District","Goods","Logistic","Order",
        "Province","Revenue","Salesman","Setting","Store","Supplier","User",
        "Review", "RedPacket", "BalanceLog", "QuotaLog"
        // "PurchaseOrder","SalesOrder","Stock", "StockItem", 'Health',
    ],
    // special permissions
    'permissions' => [
        // 'Deliver', 'StockImport',
        // 'AddPurchaseOrder',  'EditPurchaseOrder',
        // 'AddSalesOrder',     'EditSalesOrder',
    ],
    'roles' => [
        User::MANAGER => [
            "View Customer",
            "View Clerk",
            "View Order",
            "View ServiceOrder",
            "View Review",
            "View Category", "Create Category", "Edit Category", "Delete Category",
            "View Goods",   "Create Goods", "Edit Goods", "Delete Goods",
            'View PurchaseOrder',   'AddPurchaseOrder',  'EditPurchaseOrder',  'Delete PurchaseOrder', 'StockImport',
            'View SalesOrder',      'AddSalesOrder',     'EditSalesOrder',     'Delete SalesOrder',
            "View MembershipCard",  "Create MembershipCard", "Update MembershipCard", "Delete MembershipCard",
            "View DeviceRental",    "Create DeviceRental",  "Update DeviceRental", "Delete DeviceRental",
            "View MembershipUsedItem",
            "View Bill",
            "View BillItem",
            'View Stock',
            'View StockItem',
        ],
    ],
    'setting' => [
        "level_0_rewards_days" => 10,// 注册用户可领红包天数
        "level_0_rewards_min" => 1, // 注册用户可领红包数额下限
        "level_0_rewards_max" => 3, // 注册用户可领红包数额上限

        // 一级（99元）
        "level_1_cat_id" => 10000,
        "level_1_rewards_days" => 180,  // 用户可领红包天数
        "level_1_rewards_min" => 1,     // 用户可领红包数额下限
        "level_1_rewards_max" => 5,     // 用户可领红包数额上限
        "level_1_rewards_quota" => 300, //商品下单获得积分（额度）
        "level_1_rewards_referer" => 20,    //直推奖
        "level_1_common_wealth_level" => 5, //商品下单影响共富层级
        "level_1_common_wealth_min" => 8,  //商品下单,共富红包下限
        "level_1_common_wealth_max" => 10,  //商品下单,共富红包上限

        // 二级（499元）
        "level_2_cat_id" => 10001,
        "level_2_rewards_days" => 365,  // 用户可领红包天数
        "level_2_rewards_min" => 1,     // 用户可领红包数额下限
        "level_2_rewards_max" => 5,     // 用户可领红包数额上限
        "level_2_rewards_quota" => 2000,//商品下单用户获得积分（额度）
        "level_2_rewards_referer" => 100,   //直推奖
        "level_2_common_wealth_level" => 8, //商品下单影响共富层级
        "level_2_common_wealth_min" => 15,  //商品下单,共富红包下限
        "level_2_common_wealth_max" => 25,  //商品下单,共富红包上限
        // 'banks' => [
        //     "ICBC" 	=> "中国工商银行",
        //     "CCB" 	=> "中国建设银行",
        //     "HSBC" 	=> "汇丰银行",
        //     "BC" 	=> "中国银行",
        //     "ABC" 	=> "中国农业银行",
        //     "BCM" 	=> "交通银行",
        //     "CMB" 	=> "招商银行",
        //     "CMBC" 	=> "中国民生银行",
        //     "SPDB" 	=> "上海浦东发展银行",
        //     "CITIC" => "中信银行",
        //     "CEB" 	=> "中国光大银行",
        //     "HB" 	=> "华夏银行",
        //     "GDB" 	=> "广东发展银行",
        //     "SDB" 	=> "深圳发展银行",
        //     "CIB" 	=> "兴业银行",
        //     "CDB" 	=> "国家开发银行",
        //     "EIBC" 	=> "中国进出口银行",
        //     "ADBC" 	=> "中国农业发展银行",
        //     "OTHER" => "其他"
        // ]
    ],
    'category' => [
        ['pid' => null, 'id' => 10000, 'name' => '营养', 'status' => 'on_shelf'],
        ['pid' => null, 'id' => 10001, 'name' => '保健', 'status' => 'on_shelf'],
    ],
    'goods' => [
        ['id' => 1, 'category_id' => 10000, 'name' => '澳洲进口牛奶', 'price' => 68, 'status' => 'on_shelf'],
        ['id' => 2, 'category_id' => 10001, 'name' => '拉力器', 'price' => 88, 'status' => 'on_shelf'],
    ],
    'user' => [
        ['id' => 1, 'name' => 'System Admin', 'nickname' => 'System Admin', 'email' =>'admin@test.com',    'mobile' =>  '13811111110', 'password' => '111111', 'openid' => '111110', 'unionid' => '111110'],
        ['id' => 2, 'name' => '王业务员', 'nickname' => '王业务员','email' => 'sales@test.com',   'mobile' => '13811111111',  'password' => '111111', 'referer_id' => null, 'openid' => '111111', 'unionid' => '111111', 'type' => User::SALESMAN],
        ['id' => 3, 'name' => '张店长', 'nickname' => '张店长','email' => 'zhang@test.com',       'mobile' => '13811111112',  'password' => '111111', 'store_id' => 1, 'referer_id' => null, 'openid' => '111112', 'unionid' => '111112', 'type' => User::MANAGER, 'api_token' => '111111'],
        ['id' => 4, 'name' => '小刘/店员', 'nickname' => '小刘','email' => 'liu@test.com',        'mobile' => '13811111113',    'password' => '111111', 'store_id' => 1, 'referer_id' => null,    'openid' => '111113', 'unionid' => '111113', 'type' => User::CLERK],
        ['id' => 5, 'name' => '老赵',  'nickname' => '老赵', 'email' => 'zhao@test.com',       'mobile' => '13811111114',  'password' => '111111', 'store_id' => 1, 'referer_id' => 4,    'openid' => '111114', 'unionid' => '111114', 'type' => User::CUSTOMER],
        ['id' => 6, 'name' => '黄医生',  'nickname' => '黄医生', 'email' => 'huang@test.com',   'mobile' => '13811111115',  'password' => '111111', 'openid' => '111116', 'unionid' => '111116','referer_id' => 5, 'type' => User::EXPERT],
        ['id' => 7, 'name' => '李晓丽',  'nickname' => '财务李', 'email' => 'xiaoli@test.com',   'mobile' => '13811111116',  'password' => '111111', 'openid' => '111117', 'unionid' => '111117', 'referer_id' => 6,'type' => User::FINANCE],
        ['id' => 8, 'name' => '刘琳琳',  'nickname' => '仓管', 'email' => 'liulin@test.com',   'mobile' => '13811111117',  'password' => '111111', 'openid' => '111118', 'unionid' => '111118', 'referer_id' => 7,'type' => User::STORE_KEEPER],
        ['id' => 9, 'name' => '唐小名',  'nickname' => '副店长', 'email' => 'tang@test.com',   'mobile' => '13811111118',  'password' => '111111', 'openid' => '111119', 'unionid' => '111119', 'referer_id' => 8,'type' => User::VICE_MANAGER],
    ],
    'address' => [
        [
            'user_id' => 3,
            'contact' => '小张',
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 4,
            'contact' => '小张',
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],
        [
            'user_id' => 5,
            'contact' => '小张',
            'mobile' => '13322223333',
            'province_id' => 2,
            'city_id' => 16,
            'district_id' => 228,
            'street' => 'xxx路222号4-1',
            'default' => true
        ],

    ]
];

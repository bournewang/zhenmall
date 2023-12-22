<?php
return [
    "category" => [
        21 => [
            // "category_id" => 21,
            // "type" => "red_packet",
            // "amount" => "random",
            "rewards_quota" => 2000,    // self
            "rewards_days" => 365,      // for each order, owner can get red packet in 365 days
            "common_wealth_level" => 8, // for each order, 4 upper level referers can get red packet
            "rewards_range" => [
                "min" => 20,
                "max" => 60
            ]
        ],
        50 => [
            // "category_id" => 50,
            // "type" => "red_packet",
            // "amount" => "random",
            "rewards_quota" => 300,
            "rewards_days" => 180,
            "common_wealth_level" => 5,
            "rewards_range" => [
                "min" => 10,
                "max" => 20
            ]
        ]
    ]
];

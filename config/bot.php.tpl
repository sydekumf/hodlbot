<?php

return [
    'public_key' => env('BINANCE_PUBLIC_KEY'),
    'private_key' => env('BINANCE_PRIVATE_KEY'),
    'bots' => [
        [
            'name' => 'Bot 1',
            'id' => 'bot1',
            'start_balance' => 100,
            'coin_list' => ['ADA', 'ALGO', 'ATOM', 'BAT', 'BTT', 'CAKE', 'DASH', 'EOS', 'ETC', 'ICX', 'IOTA', 'NEO', 'OMG', 'ONT', 'QTUM', 'TRX', 'VET', 'XLM', 'XMR'],
            'bridge' => 'USDT',
            'start_date' => new \DateTime('01.01.2021', new \DateTimeZone('UTC')),
            'database' => [
                'driver' => 'sqlite',
                'url' => env('DATABASE_URL'),
                'database' => env('DATABASE_PATH_BOT_1'),
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ]
        ],
        [
            'name' => 'Bot 2',
            'id' => 'bot2',
            'start_balance' => 200,
            'coin_list' => ['BCH', 'DOT', 'ETH', 'HOT', 'LINK', 'LTC', 'MANA', 'OCEAN', 'RVN', 'SOL', 'UNI', 'XRP', 'ZIL'],
            'bridge' => 'BUSD',
            'start_date' => new \DateTime('01.01.2021', new \DateTimeZone('UTC')),
            'database' => [
                'driver' => 'sqlite',
                'url' => env('DATABASE_URL'),
                'database' => env('DATABASE_PATH_BOT_2'),
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ]
        ]
    ],
    'forecast' => [6024, 12048, 24096, 36144, 48192, 60240]
];

<?php
return [

    'components' =>
    [
        /*'vkApi' => [
            'class' => 'jumper423\VK',
            'clientId' => '11111',
            'clientSecret' => 'n9wsv98svSD867SA7dsda87',
            'delay' => 0.7, // Минимальная задержка между запросами
            'delayExecute' => 120, // Задержка между группами инструкций в очереди
            'limitExecute' => 1, // Количество инструкций на одно выполнении в очереди
            'captcha' => 'captcha', // Компонент по распознованию капчи
        ],*/

        'vkDatabase' => [
            'class'     => 'skeeks\cms\vkDatabase\VkDatabaseComponent',
        ],

        'i18n' => [
            'translations' =>
            [
                'skeeks/vkDatabase' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/vkDatabase/messages',
                    'fileMap' => [
                        'skeeks/vkDatabase' => 'main.php',
                    ],
                ]
            ]
        ]
    ],

    'modules' =>
    [
        'vkDatabase' => [
            'class'                 => 'skeeks\cms\vkDatabase\VkDatabaseModule',
            'controllerNamespace'   => 'skeeks\cms\vkDatabase\console\controllers'
        ]
    ]
];
<?php
return [

    'components' =>
    [
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
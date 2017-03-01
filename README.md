SkeekS CMS import
===================================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist skeeks/cms-vk-database "*"
```

or add

```
"skeeks/cms-vk-database": "*"
```

```
"repositories": [
    {
        "type": "git",
        "url":  "https://github.com/skeeks-cms/cms-vk-database.git"
    }
]
```

Configuration app
----------

```php

php yii vkDatabase/import/countries
php yii vkDatabase/import/regions

```


```php

'components' =>
[

    'components' =>
    [
        'cmsImport' => [
            'class'     => 'skeeks\cms\import\ImportComponent',
        ],

        'i18n' => [
            'translations' =>
            [
                'skeeks/import' => [
                    'class'             => 'yii\i18n\PhpMessageSource',
                    'basePath'          => '@skeeks/cms/import/messages',
                    'fileMap' => [
                        'skeeks/import' => 'main.php',
                    ],
                ]
            ]
        ]
    ],

    'modules' =>
    [
        'cmsImport' => [
            'class'         => 'skeeks\cms\import\ImportModule',
        ]
    ]
];

```

##Links
* [Web site (rus)](https://cms.skeeks.com)
* [Author](https://skeeks.com)
* [ChangeLog](https://github.com/skeeks-cms/cms-vk-database/blob/master/CHANGELOG.md)


___

> [![skeeks!](https://gravatar.com/userimage/74431132/13d04d83218593564422770b616e5622.jpg)](https://skeeks.com)
<i>SkeekS CMS (Yii2) — quickly, easily and effectively!</i>  
[skeeks.com](https://skeeks.com) | [cms.skeeks.com](https://cms.skeeks.com)



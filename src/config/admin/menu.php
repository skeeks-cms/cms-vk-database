<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link http://skeeks.com/
 * @copyright 2010 SkeekS (СкикС)
 * @date 15.04.2016
 */
return
[
    'exportImport' =>
    [
        "label"     => \Yii::t('skeeks/import', "Export / Import"),
        "img"       => ['\skeeks\cms\import\assets\ImportAsset', 'icons/export.png'],

        'priority'  => 400,

        'items' =>
        [
            "import" =>
            [
                "label"     => \Yii::t('skeeks/import', "Import"),
                "img"       => ['\skeeks\cms\import\assets\ImportAsset', 'icons/import.png'],
                "url"       => ["cmsImport/admin-import-task"],

                'items' =>
                [
                    [
                        "label"     => \Yii::t('skeeks/import', "All kinds of imports"),
                        "img"       => ['\skeeks\cms\import\assets\ImportAsset', 'icons/import.png'],
                        "url"       => ["cmsImport/admin-import-task"],
                    ]/*,

                    [
                        "label"     => \Yii::t('skeeks/import', "CSV"),
                        "img"       => ['\skeeks\cms\import\assets\ImportAsset', 'icons/csv.png'],
                        "url"       => ["cmsImport/admin-import-task"],
                    ],*/
                ],
            ],
        ]
    ]
];
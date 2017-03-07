<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
namespace skeeks\cms\vkDatabase\widgets\assets;

use yii\helpers\Html;
use yii\web\AssetBundle;
use yii\widgets\InputWidget;

/**
 * Class VkAutocompleteCityWidgetAsset
 *
 * @package skeeks\cms\vkDatabase\widgets\assets
 */
class VkAutocompleteCityWidgetAsset extends AssetBundle
{
    public $sourcePath = '@skeeks/cms/vkDatabase/widgets/assets/src';

    public $css = [];

    public $js =
    [
        'vk-autocomplete-city.js',
    ];

    public $depends = [
        '\skeeks\sx\assets\Core',
    ];
}
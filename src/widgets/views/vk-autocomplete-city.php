<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
/* @var $this yii\web\View */
/* @var $widget \skeeks\cms\vkDatabase\widgets\VkAutocompleteCityWidget */
/* @var $formElement string */
$widget = $this->context;
?>
<? \yii\helpers\Html::beginTag('div', $widget->wrapperOptions); ?>
    <? echo \yii\jui\AutoComplete::widget([
        'name'          => $widget->autocompleteName,
        'clientOptions' => $widget->autocompleteClientOptions,
        'options'       => $widget->autocompleteOptions
    ]); ?>
    <?= $formElement; ?>
<?
    $js = \yii\helpers\Json::encode($widget->clientOptions);
    $this->registerJs(<<<JS
new sx.classes.VkAutocompleteCity({$js});
JS
)
?>
<? \yii\helpers\Html::endTag('div'); ?>
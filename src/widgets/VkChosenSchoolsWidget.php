<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
namespace skeeks\cms\vkDatabase\widgets;

use skeeks\cms\vkDatabase\widgets\assets\VkAutocompleteCityWidgetAsset;
use skeeks\cms\vkDatabase\widgets\assets\VkChoosenSchoolsWidgetAsset;
use skeeks\widget\chosen\Chosen;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 *
 *
 * <?= $form->field($model, 'school')->widget(
    \skeeks\cms\vkDatabase\widgets\VkChosenSchoolsWidget::class,
    [
        'observeVkCityInputId' => 'sx-city',
        'allowDeselect' => false,
        'clientOptions' =>
        [
            'search_contains' => true
        ]
    ]
); ?>
*
*
 * Class VkChosenSchoolsWidget
 *
 * @package skeeks\cms\vkDatabase\widgets
 */
class VkChosenSchoolsWidget extends Chosen
{
    /**
     * Url::to(['/vkDatabase/ajax/find-schools'])
     * @var string
     */
    public $backend;

    /**
     * TODO: не реализовано
     * @var int
     */
    public $vkCityId = 1;

    /**
     * @var null
     */
    public $observeVkCityInputId = null;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        VkChoosenSchoolsWidgetAsset::register($this->view);

        if (!$this->backend)
        {
            $this->backend = Url::to(['/vkDatabase/ajax/find-schools']);
        }

        $js = Json::encode([
            'id'        => $this->id,
            'elementId' => $this->elementId,
            'observeVkCityInputId' => $this->observeVkCityInputId,
            'backend' => $this->backend
        ]);

        $this->view->registerJs(<<<JS
new sx.classes.VkChoosenSchools({$js});
JS
);
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        if ($this->hasModel())
        {
            if (!$formElementId = ArrayHelper::getValue($this->options, 'id'))
            {
                $formElementId = Html::getInputId($this->model, $this->attribute);
            }
        } else
        {
            if (!$formElementId = ArrayHelper::getValue($this->options, 'id'))
            {
                $formElementId = $this->id . "-element-id";
                $this->options['id'] = $formElementId;
            }
        }

        return $formElementId;
    }
}
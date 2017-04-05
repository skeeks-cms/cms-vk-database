<?php
/**
 * @author Semenov Alexander <semenov@skeeks.com>
 * @link https://skeeks.com/
 * @copyright 2010 SkeekS
 * @date 07.03.2017
 */
namespace skeeks\cms\vkDatabase\widgets;

use skeeks\cms\vkDatabase\models\VkCity;
use skeeks\cms\vkDatabase\widgets\assets\VkAutocompleteCityWidgetAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\InputWidget;

/**
 *
 * <?= $form->field($model, 'vk_city_id')->widget(
        \skeeks\cms\vkDatabase\widgets\VkAutocompleteCityWidget::class,
        [
            'options' =>
            [
                'id' => 'vk-city-id'
            ]
        ]
    ); ?>
 *
 *
 *
 * $("#vk-city-id").on('change', function()
    {
        sx.Schools.getByCity($(this).val());
    });
 *
 *
 * @property string $autocompleteId         read-only
 * @property string $autocompleteName       read-only
 * @property string $autocompleteValue      read-only
 *
 * @package skeeks\cms\vkDatabase\widgets
 */
class VkAutocompleteCityWidget extends InputWidget
{
    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    public $wrapperOptions = [];



    /**
     * Autocomplete widget client options
     * @var array
     */
    public $autocompleteClientOptions = [];

    /**
     * Autocomplete widget options
     * @var array
     */
    public $autocompleteOptions = [];

    /**
     * Url::to(['/vkDatabase/ajax/find-city'])
     * @var string
     */
    public $backend = '';



    /**
     * @var string
     */
    public $viewFile = "vk-autocomplete-city";

    /**
     * TODO: не реализовано
     * @var int
     */
    public $vkCountryId = 1;

    /**
     * @var bool
     */
    public $strict = true;


    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->backend)
        {
            $this->backend = Url::to(['/vkDatabase/ajax/find-city']);
        }

        $this->autocompleteOptions = ArrayHelper::merge([
            'id'            => $this->autocompleteId,
            'class'         => 'form-control',
            'placeholder'   => 'Название города'
        ], $this->autocompleteClientOptions);

        $this->autocompleteClientOptions = ArrayHelper::merge([
            'source'    =>  $this->backend,
            'autoFill'  =>  true,
            'dataType'  =>  'json',
            'minLength' =>  '0',
        ], $this->autocompleteClientOptions);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $formElement = '';

        VkAutocompleteCityWidgetAsset::register($this->view);

        if ($this->hasModel())
        {
            if (!$formElementId = ArrayHelper::getValue($this->options, 'id'))
            {
                $formElementId = Html::getInputId($this->model, $this->attribute);
            }

            $formElement = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else
        {
            if (!$formElementId = ArrayHelper::getValue($this->options, 'id'))
            {
                $formElementId = $this->id . "-element-id";
                $this->options['id'] = $formElementId;
            }

            $formElement = Html::hiddenInput($this->name, $this->value, $this->options);
        }

        $this->wrapperOptions['id']                 = $this->id;

        $this->clientOptions['id']                  = $this->id;
        $this->clientOptions['autocompleteName']    = $this->autocompleteName;
        $this->clientOptions['autocompleteValue']   = $this->autocompleteValue;
        $this->clientOptions['autocompleteId']      = $this->autocompleteId;
        $this->clientOptions['elementId']           = $formElementId;
        $this->clientOptions['strict']              = (int) $this->strict;

        $this->autocompleteClientOptions['select'] = new \yii\web\JsExpression("function( event, ui ) {
            $('#{$formElementId}').val( ui.item.id );
            $('#{$formElementId}').change();
            $('#{$this->autocompleteId}').val( ui.item.title );
            return false;
        }");

        return $this->render($this->viewFile, [
            'formElement' => $formElement
        ]);

    }

    /**
     * @return string
     */
    public function getAutocompleteValue()
    {
        if ($this->hasModel())
        {
            $value = $this->model->{$this->attribute};
        } else
        {
            $value = $this->value;
        }

        if ($value)
        {
            $city = VkCity::getOneFromApi($value);
            if ($city)
            {
                $value = $city->name;
            }
        }


        return $value;
    }

    /**
     * @return string
     */
    public function getAutocompleteName()
    {
        return $this->id . "-autocomplete";
    }

    /**
     * @return string
     */
    public function getAutocompleteId()
    {
        return $this->id . "-autocomplete-id";
    }
}
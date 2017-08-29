<?php

namespace bilginnet\cropper;


use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\InputWidget;
use yii\helpers\StringHelper;

/**
 * @author Ercan Bilgin <bilginnet@gmail.com>
 */
class Cropper extends InputWidget
{

    /**
     * width int must be specified
     * height int must be specified
     *
     * preview false | array  // default false
     *     [
     *          url @url      // set in update action // automatically will be set after crop
     *          width int     // default 100
     *          height int    // default height by aspectRatio
     *     ]
     *
     * icons array
     *     [
     *          browse
     *          crop
     *          close
     *     ]
     *
     * @var $cropperOptions []
     *
     */
    public $cropperOptions;
    private $inputOptions;

    /**
     * @var  bool | string
     */
    public $label;



    public function init()
    {
        parent::init();

        $this->i18n();
        $this->setCropperOptions();
        $this->setInputOptions();
    }

    public function run()
    {
        parent::run();

        return $this->render('cropper', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'cropperOptions' => $this->cropperOptions,
            'inputOptions' => $this->inputOptions,
        ]);
    }


    public function i18n()
    {
        if (!isset(\Yii::$app->get('i18n')->translations['cropper*'])) {
            \Yii::$app->get('i18n')->translations['cropper*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }



    private function setCropperOptions()
    {
        $options = $this->cropperOptions;

        if (!isset($options['width']) && !isset($options['height'])) {
            throw new InvalidConfigException(Yii::t('cropper', 'Either "cropWidth" and "cropHeight" properties must be specified.'));
        }

        $aspectRatio = $options['width'] / $options['height'];
        if (!isset($options['preview']['url']) || empty($options['preview']['url'])) $options['preview']['url'] = null;
        if (!isset($options['preview']['width'])) {
			$defaultPreviewWidth = 100;
			if ($options['width'] < $defaultPreviewWidth)
				$options['preview']['width'] = $options['width'];
			else 
				$options['preview']['width'] = $defaultPreviewWidth;
		}
        if (!isset($options['preview']['height'])) $options['preview']['height'] = $options['preview']['width'] / $aspectRatio;


        if (!isset($options['icons']['browse'])) $options['icons']['browse'] = '<i class="fa fa-image"></i>';
        if (!isset($options['icons']['crop'])) $options['icons']['crop'] = '<i class="fa fa-crop"></i>';
        if (!isset($options['icons']['close'])) $options['icons']['close'] = '<i class="fa fa-crop"></i>';
	if (!isset($options['icons']['zoom-in'])) $options['icons']['zoom-in'] = '<i class="fa fa-search-plus"></i>';
	if (!isset($options['icons']['zoom-out'])) $options['icons']['zoom-out'] = '<i class="fa fa-search-minus"></i>';
	if (!isset($options['icons']['rotate-left'])) $options['icons']['rotate-left'] = '<i class="fa fa-rotate-left"></i>';
	if (!isset($options['icons']['rotate-right'])) $options['icons']['rotate-right'] = '<i class="fa fa-rotate-right"></i>';
	if (!isset($options['icons']['flip-horizontal'])) $options['icons']['flip-horizontal'] = '<i class="fa fa-arrows-h"></i>';
	if (!isset($options['icons']['flip-vertical'])) $options['icons']['flip-vertical'] = '<i class="fa fa-arrows-v"></i>';
	if (!isset($options['icons']['move-left'])) $options['icons']['move-left'] = '<i class="fa fa-arrow-left"></i>';
	if (!isset($options['icons']['move-right'])) $options['icons']['move-right'] = '<i class="fa fa-arrow-right"></i>';
	if (!isset($options['icons']['move-up'])) $options['icons']['move-up'] = '<i class="fa fa-arrow-up"></i>';
	if (!isset($options['icons']['move-down'])) $options['icons']['move-down'] = '<i class="fa fa-arrow-down"></i>';

        $this->cropperOptions = $options;
    }



    private function setInputOptions()
    {
        $label = $this->label;
        if ($label === null || (is_bool($label) && $label)) {
            $label = $this->model->getAttributeLabel($this->attribute);
        }
        $className = StringHelper::basename(get_class($this->model));
        $attribute = $this->attribute;
        $inputOptions = [
            'id' => $this->options['id'],
            'name' => $className . "[$attribute]",
            'label' => $label, //$this->model->getAttributeLabel($this->attribute)
        ];
        $this->inputOptions = $inputOptions;
    }
}

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\widgets;
use kartik\widgets\Alert as Kalert;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', 'This is the message');
 * \Yii::$app->session->setFlash('success', 'This is the message');
 * \Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class Alert extends \yii\bootstrap\Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'error'   => 'alert-danger',
        'danger'  => 'alert-danger',
        'success' => 'alert-success',
        'info'    => 'alert-info',
        'warning' => 'alert-warning'
    ];

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    /* initialize css class for each alert box */
                    $this->options['class'] = $this->alertTypes[$type] . $appendCss;

                    /* assign unique id to each alert box */
                    $this->options['id'] = $this->getId() . '-' . $type . '-' . $i;

                    // echo \yii\bootstrap\Alert::widget([
                    //     'body' => $message,
                    //     'closeButton' => $this->closeButton,
                    //     'options' => $this->options,
                    // ]);

                    echo Kalert::widget([
                        'type' => $this->alertTypes[$type],
                        // 'title' => 'Well done!',
                        'icon' => $this->getIcon($this->alertTypes[$type]),
                        'body' => $message,
                        'showSeparator' => true,
                        'delay' => 2000
                    ]);
                }

                $session->removeFlash($type);
            }
        }
    }

    private function getIcon($type){
        $icon = '';
        switch ($type) {
            case Kalert::TYPE_INFO:
                $icon = 'glyphicon glyphicon-info-sign';
                break;
            case Kalert::TYPE_DANGER:
                $icon = 'glyphicon glyphicon-remove-sign';
                break; 
            case Kalert::TYPE_SUCCESS:
                $icon = 'glyphicon glyphicon-ok-sign';
                break; 
            case Kalert::TYPE_WARNING:
                $icon = 'glyphicon glyphicon-exclamation-sign';
                break; 
            case Kalert::TYPE_PRIMARY:
                $icon = 'glyphicon glyphicon-question-sign';
                break; 
            default:
                $icon = 'glyphicon glyphicon-plus-sign';
                break;
        }

        return $icon;
    }
}

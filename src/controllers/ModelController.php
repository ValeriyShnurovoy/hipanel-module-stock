<?php

/*
 * Stock Module for Hipanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-stock
 * @package   hipanel-module-stock
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\stock\controllers;

use hipanel\actions\IndexAction;
use hipanel\actions\OrientationAction;
use hipanel\actions\SmartCreateAction;
use hipanel\actions\SmartPerformAction;
use hipanel\actions\SmartUpdateAction;
use hipanel\actions\ValidateFormAction;
use hipanel\actions\ViewAction;
use hipanel\base\CrudController;
use hipanel\modules\stock\models\Model;
use Yii;

class ModelController extends CrudController
{
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class, // with_counters
                'findOptions' => ['with_counters' => 1],
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'view' => [
                'class' => ViewAction::class,
                'findOptions' => ['with_counters' => 1],
            ],
            'create' => [
                'class' => SmartCreateAction::class,
                'success' => Yii::t('hipanel:stock', 'Model has been created'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'update' => [
                'class' => SmartUpdateAction::class,
                'success' => Yii::t('hipanel:stock', 'Model has been updated'),
                'data' => function ($action) {
                    return [
                        'types' => $action->controller->getTypes(),
                        'brands' => $action->controller->getBrands(),
                    ];
                },
            ],
            'mark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Models have been marked'),
            ],
            'unmark-hidden-from-user' => [
                'class' => SmartPerformAction::class,
                'success' => Yii::t('hipanel:stock', 'Models have been marked'),
            ],
            'validate-form' => [
                'class' => ValidateFormAction::class,
            ],
        ];
    }

    public function actionSubform()
    {
        $subFormName = Yii::$app->request->post('subFormName');
        $itemNumber = Yii::$app->request->post('itemNumber');
        if ($subFormName) {
            $validFormNames = $this->getCustomType();
            if (in_array($subFormName, $validFormNames, true)) {
                return $this->renderAjax('_' . $subFormName, ['model' => new Model(), 'i' => $itemNumber]);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function getTypes()
    {
        return $this->getRefs('type,model', 'hipanel:stock');
    }

    public function getDcs()
    {
        return $this->getRefs('type,dc', 'hipanel:stock');
    }

    public function getBrands()
    {
        return $this->getRefs('type,brand', 'hipanel:stock');
    }

    public function getCustomType()
    {
        return ['server', 'chassis', 'motherboard', 'ram', 'hdd', 'cpu'];
    }
}

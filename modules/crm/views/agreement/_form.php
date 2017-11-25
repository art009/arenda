<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DateRangePicker;
use dosamigos\datepicker\DatePicker;
use yii\web\JsExpression;
use kartik\typeahead\Typeahead;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Agreement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>

        <?php // $form->field($model, 'tenant')->textInput() ?>
        <?php
        $template = '<div>' .
            '<p class="repo-name">{{value}}</p>' .
            '<p class="repo-description">{{contact}}</p></div>';
        ?>
        <?= $form->field($model, 'tenant')->hiddenInput()->label(false)?>
        <?= $form->field($model, 'tenant_name')->widget(Typeahead::classname(), [
//            'data' => $data,
            'pluginOptions' => [
                'highlight' => true,
            ],
            'options' => [
                'placeholder' => 'Введите арендатора ...'
            ],
            'scrollable' => true,
            'pluginEvents' => [
                "typeahead:select" => 'function(ev, resp) {
                    $("#agreement-tenant").val(resp.tenant);
                }',
            ],
            'dataset' => [
                [
                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                'display' => 'value',
                'remote' => [
                    'url' => Url::to(['/crm/tenant/list']) . '?q=%QUERY',
                    'wildcard' => '%QUERY'
                ],
                'templates' => [
                    'notFound' => '<div class="text-danger" style="padding:0 8px">Арендатор не найден.</div>',
                    'suggestion' => new JsExpression("Handlebars.compile('{$template}')")
                ]
            ]
            ]
        ]);?>

        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'inner_number')->textInput()?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'agreement_date')->widget(
                    DatePicker::className(),
                    [
                        'template' => '{addon}{input}',
                        'language' => 'ru',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'dd.mm.yyyy'
                        ]
                    ]
                );?>
            </div>
        </div>

        <?php // $form->field($model, 'date_from')->textInput() ?>

        <?php // $form->field($model, 'date_to')->textInput() ?>

        <?= $form->field($model, 'date_from')->widget(DateRangePicker::className(), [
            'attributeTo' => 'date_to',
            'form' => $form, // best for correct client validation
            'language' => 'ru',
            'size' => 'lg',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy'
            ]
        ]);?>

        <?php // $form->field($model, 'quarters')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-lg-6">
        <?=$this->render('_listQuarters',['model' => $model]);?>
    </div>
</div>

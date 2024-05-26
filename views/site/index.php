<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap5\Html;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use app\widgets\Alert;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/** @var yii\web\View $this */

$this->title = Yii::$app->name;

?>
<div class="site-index">

    <div class="body-content mt-sm-5">

        <?php
            Pjax::begin(['id' => 'pjax-block', 'timeout' => 0]);
        ?>

            <div id="pjax-block" class="row">

                    <?php if(Yii::$app->session->hasFlash('error')): ?>
                        <div class="alert alert-danger" role="alert" style="color: #a52025;">
                            <?= Yii::$app->session->getFlash('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mLink::findOne($mLink->id) && $mLink->validate()): ?>

                        <?php

                            $short_link = Yii::$app->request->hostInfo.'/'.$mLink->short_link;

                            $options = new QROptions(
                              [
                                'eccLevel' => QRCode::ECC_L,
                                'outputType' => QRCode::OUTPUT_MARKUP_SVG,
                                'version' => 5,
                              ]
                            );

                            $qrcode = (new QRCode($options))->render($short_link);

                        ?>

                        <div class="col-lg-4 mb-4">

                            <div class="alert alert-success">
                                <?php

                                    echo Html::img($qrcode, [
                                        'width' => '150',
                                        'height' => '150',
                                        'class' => 'qrcode',
                                    ]);

                                ?>
                            </div>
                            
                        </div>

                        <div class="col-lg-8 mb-8">
                            <?php
                                echo Html::a(
                                    $short_link, 
                                    $short_link, [
                                    'class' => 'display-6',
                                    'data-pjax' => '0'
                                ]);
                            ?>
                        </div>

                    <?php else: ?>

                        <div class="col-lg-12 mb-12">

                            <?php $form = ActiveForm::begin([
                                    'id' => 'form-link',
                                    // 'enableAjaxValidation' => true,
                                    // 'enableClientValidation' => true,
                                    'validateOnSubmit' => true,   
                                    'options' => [
                                        'class' => 'form w-50 mr-5',
                                        'data-pjax' => true
                                    ]
                                ]); ?>

                                <?= $form->field($mLink, 'link', [
                                    // 'enableAjaxValidation' => true,
                                    'template' => '{label}{input}{hint}{error}'
                                ])->textInput([
                                    // 'value' => 'https://ya.ru',
                                    'placeholder' => 'https://example.com/users/', 'class' => 'form-control'
                                ])->label('Ваш URL-адрес'); ?>

                                <div class="form-group">
                                    <?= Html::submitButton('OK', [
                                        'class' => 'btn btn-xs text-white bg-primary mb-3',
                                        'name' => 'button'
                                    ]); ?>
                                </div>

                            <?php ActiveForm::end(); ?>

                        </div>

                    <?php endif; ?>

            </div>

        <?php Pjax::end(); ?>

    </div>
</div>

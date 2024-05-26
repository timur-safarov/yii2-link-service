<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Link;
use app\models\LinkClick;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;


class UrlController extends Controller
{

	/**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['redirect'],
                'rules' => [
                    [
                        'actions' => ['redirect'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    // 'redirect' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }



    public function actionRedirect()
    {

    	$short_link = Yii::$app->request->get()['short_link'];

    	$mLink = Link::findOne(['short_link' => $short_link]);

    	if ($mLink) {

    		if ((new LinkClick)->create($mLink) instanceof LinkClick) {
    			return $this->redirect($mLink->link);
    		} else {
    			throw new BadRequestHttpException(
                    Yii::$app->session->getFlash('error')
                );
    		}

    	} else {
    		throw new NotFoundHttpException('Not found');
    	}


    }



}
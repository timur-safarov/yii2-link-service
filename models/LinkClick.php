<?php

namespace app\models;

use Yii;
use app\models\Link;

/**
 * This is the model class for table "link_click".
 *
 * @property int $id ID
 * @property int $id_link Ссылка
 * @property string $ip IP
 * @property int $view Переходы по ссылке
 * @property int $created_at Дата создания
 * @property int $updated_at Дата обновления
 *
 * @property Link $link
 */
class LinkClick extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link_click';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_link', 'ip', 'view', 'created_at', 'updated_at'], 'required'],
            [['id_link', 'view', 'created_at', 'updated_at'], 'integer'],
            [['ip'], 'string', 'max' => 32],
            [['id_link'], 'exist', 'skipOnError' => true, 'targetClass' => Link::class, 'targetAttribute' => ['id_link' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_link' => 'Ссылка',
            'ip' => 'IP',
            'view' => 'Переходы по ссылке',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Link]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(Link::class, ['id' => 'id_link']);
    }

    
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {

            if ($this->isNewRecord) {
                $this->created_at = strtotime(date('Ymd H:i:s'));
            }
            
            $this->updated_at = time();
            return true;

        } else {
            return false;
        }
    }

    /**
     * Метод для записи данных о переходе по ссылкам
     * 
     * @param Link $mLink - объект Link для которого мы сохраняем доп. параметры
     * 
     */
    public function create(Link $mLink)
    {
        try {

            $transaction = Yii::$app->db->beginTransaction();
            $mLinkClick = new $this;

            $insertData = [
                'id_link' => $mLink->id,
                'ip' => Yii::$app->request->userIP,
                'view' => 1,
                'created_at' => strtotime(date('Ymd H:i:s')),
                'updated_at' => strtotime(date('Ymd H:i:s')),
            ];

            if ($mLinkClick->load($insertData, '') && $mLinkClick->validate()) {

                $updateData = [
                    'view' => new \yii\db\Expression('link_click.view + 1'),
                    'updated_at' => strtotime(date('Ymd H:i:s')),
                ];

                if (Yii::$app->db
                    ->createCommand()
                    ->upsert('link_click', $insertData, $updateData)
                    ->execute()
                ) {

                    $transaction->commit();

                    // Возвращаем модель со всеми данными
                    return self::findOne([
                        'id_link' => $mLink->id,
                        'ip' => Yii::$app->request->userIP,
                    ]);

                } else {
                    throw new \Exception('Данные не были обновлены для модели Link.');
                }

            } else {
                throw new \Exception('Модель Link не валидна.');
            }

        } catch (\Exception $e) {

            // Показываем ошибку на клиенте, если модель не сохранилась
            Yii::$app->getSession()->setFlash('error', $e->getMessage());

            $transaction->rollBack();

        }


    }



    
}

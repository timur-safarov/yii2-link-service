<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "link".
 *
 * @property int $id ID
 * @property string $link Ссылка
 * @property string $short_link Короткая ссылка
 * @property string $hash_link Хэш ссылки
 * @property int $created_at Дата создания
 * @property int $updated_at Дата обновления
 *
 * @property LinkClick[] $linkClicks
 */
class Link extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['link', 'short_link', 'hash_link', 'created_at', 'updated_at'], 'required', 'message' => 'Заполните обязательное поле'],
            [['link'], 'url', 'defaultScheme' => 'http', 'message' => 'Ссылка не валидна'],
            [['link'], 'app\components\validators\LinkStatusValidator'],
            [['created_at', 'updated_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['link'], 'string', 'max' => 1000],
            [['short_link'], 'string', 'max' => 10],
            [['hash_link'], 'string', 'max' => 64],
            [['short_link'], 'unique'],
            [['hash_link'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'link' => 'Ссылка',
            'short_link' => 'Короткая ссылка',
            'hash_link' => 'Хэш ссылки',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[LinkClicks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLinkClicks()
    {
        return $this->hasMany(LinkClick::class, ['id_link' => 'id']);
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
     * Метод создания ссылки
     * 
     * @param Link $mLink - текущий объект Link для сохранения
     * 
     */
    public function create(Link &$mLink)
    {

        // $mLink = $this;

        //$valid = Model::validateMultiple($mImages) && $valid;

        // $mLink->load(Yii::$app->request->post());
        // $mLink->validate();
        // print_r($mLink->getErrors());

        $transaction = Yii::$app->db->beginTransaction();

        $link_key = bin2hex($mLink->link);

        // hash_link - Уникальный хэш на основе ссылки
        // Нужен просто чтобы уникальное поле было, которое ссылке соответствует
        // Так надёжней
        $mLink->hash_link = hash_hmac(
            'sha256',
            $link_key,
            Yii::$app->params['HASH_SECRET_KEY']
        );

        // Если такая ссылку уже есть в базе то просто вытаскиваем её
        $isIssetRecord = self::find()->where(['hash_link' => $mLink->hash_link])->one();

        if ($isIssetRecord) {

            $mLink = $isIssetRecord;

            return $mLink;
        }

        // В short_link у нас короткая ссылка
        // В случаном порядке чтобы потделать нельзя было
        $mLink->short_link = substr(md5(microtime()), 0, 10);

        if ($mLink->load(Yii::$app->request->post()) && $mLink->validate()) {

            try {

                if ($mLink->save(false)) {

                    $transaction->commit();

                    // Возвращаем модель со всеми данными
                    return $mLink;

                } else {
                    throw new \Exception('Данные не были обновлены для модели Link.');
                }

            } catch (\Exception $e) {

                // Показываем ошибку на клиенте, если модель не сохранилась
                Yii::$app->getSession()->setFlash('error', $e->getMessage());

                $transaction->rollBack();

            }

        }


    }


}

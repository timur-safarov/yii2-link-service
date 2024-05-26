<?php
namespace app\components\validators;

use Yii;
use models\Link;

use yii\validators\Validator;

class LinkStatusValidator extends Validator
{

    private $_client;

    public function init()
    {
        parent::init();
        $this->message = 'Url адрес не доступен.';
    }

    protected function validateLink($model)
    {

        try {

            $this->_client = new \GuzzleHttp\Client(
                [
                    'base_uri' => $model->link,
                    'timeout'  => 5.0,
                ]
            );

            $response = $this->_client->request(
                'GET', 
                '', 
                [
                    'headers' => [
                        // 'User-Agent' => 'testing/1.0',
                        // 'debug'         => false,
                        // 'Accept'        => 'application/json',
                        // 'Content-Type'  => 'application/json',
                    ],
                ]
            );

            // Поверяем какой статус страницы
            return ($response->getStatusCode() == 200);

        } catch (\Exception $e) {

            return false;

        }

    }    
    
    public function validateAttribute($model, $attribute)
    {
        if (!$this->validateLink($model)) {
            $model->addError($attribute, $this->message);
        }
    }

}

?>
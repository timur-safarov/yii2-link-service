<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic - Сервис коротких ссылок + QR</h1>
    <br>
</p>

Директории
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


Требования
------------

Linux
Apache
Минимальная версия PHP 7.4. Тестировал на версии 8.3
Php модули - Mode rewrite, Curl
Mysql
Composer
Git

# Запуск приложения

## 1). В настройках сетевого адреса или домена указать такие параметры, заменив директории на свои, если они отличаются

```
<VirtualHost *:80>
    ServerName domain.com
    ServerAlias www.domain.com
    DocumentRoot /var/www/domain.com/public/
    ErrorLog /var/www/domain.com/error.log
    CustomLog /var/www/domain.com/custom_error.log combined

    <Directory /var/www/domain.com>
        Options All
            AllowOverride All
        Order allow,deny
        Allow from all
            Require all granted
    </Directory>
</VirtualHost>
```

## 2). Убедитесь что у вас уже установлены SSH ключи для Github репозиториев.

## 3). Склонировать проект с Github
- Зайдите в корневую директорию сайта
  ```cd /var/www/public_html```

- Склонируйте репозиторий в текущую папку
  ```git clone git@github.com:whatever .```

## 4). Обновите или установите зависимости
  ```composer install```
  ```composer update```

## 5). База данных

- Изменить файл `config/db.php` на свои данные для подключения к базе:
- Можно использовать консольный редактор nano если вы работаете на удалённом сервере

```nano config/db.php```

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

## 6). Применить миграции
- Зайти в корень сайта и ввести команду
```php yii migrate```






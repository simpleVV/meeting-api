# Личный проект «Meeting-api»

Структура проекта
-------------------

      components/         содержит дополнительные компоненты приложения
      config/             содержит конфигурацию приложения
      controllers/        содержит классы веб-контроллера
      fixtures/           содержит классы и файлы для генерации тестовых данных
      migrations/         содержит файлы для создания таблиц базы данных и операций над ними
      models/             содержит классы моделей
      runtime/            содержит файлы, сгенерированные во время выполнения
      tests/              содержит различные тесты для базового приложения
      vendor/             содержит зависимые сторонние пакеты
      views/              содержит файлы просмотра для веб-приложения
      web/                содержит сценарий ввода и веб-ресурсы

Установка:
------------
1) Клонируем REST API проект из репозитория Github:
	git clone https://github.com/simpleVV/meeting-api.git
2) Переходим в папку с проектом и выполняем команду в терминале composer install
3) Создаем базу данных из schema.sql 
4) Выполняем команду php yii migrate - для создания таблиц базы данных

5) Для генерации тестовых данных необходимо выполнить следующую команду:
	php yii fixture/generate employees --count= num - сгенерировать тестовые данные сотрудников, где num - это количество записей
	php yii fixture/generate meetings --count= num - сгенерировать тестовые данные собраний, где num - это количество записей
	php yii fixture/generate timetable --count= num - сгенерировать тестовые данные собраний, где num - это количество записей

6) Для загрузки тестовых данных в базу, необходимо выполнить следующую команду:
php yii fixture/load Model, где Model название модели. Пример: php yii fixture/load Employee

Общая концепция:
----------------

«Meeting-api» — REST API с операциями CRUD для собраний, сотрудников и получения собраний для указанного сотрудника. 

Основные сценарии использования сайта:

Работа с проектом:
------------------

Необходимо открыть swagger, для этого перейти по ссылке http://localhost/site/docs

### Тестирование:
-----------------

#### Api тесты
composer test-api

#### Unit тесты
composer  unit-test

Техническое описание:
---------------------

Использованные инструменты:
1) Язык программирования PHP 8
2) База данных — MySQL 8
3) Фреймворк - Yii2


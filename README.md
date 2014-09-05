Library Web App
---------------

Требования:

- LAMP
- Sphinxsearch

Запуск:

- Разворачиваем Yii
- protected/data/library.schema.sql - схема базы
- для заполнения базы выполняем migrate (наличие protected/data/authors.data,protected/data/books.data,protected/data/relations.data желательно)
- запускаем sphinx/индексируем - sphinx.conf - в корне.

Авторизация - стандартная авторизация Yii при создании приложения (demo/admin)

5.
---------------------
Полнотекстовый поиск - осуществляется через sphinx, используется в autocompele для добавления книг автору (изменение автора)
(в силу особенностей behavior'а, реализующего поддержку связей на уровне моделей при добавлении новых связей старые удаляются; может быть решено объединением 
	старых записей - из базы и новых при сохранении)
	

6. 
--------------------
Используем связку nginx+redis(memcached), проверяем куку пользователя (nginx) , 
при отсутсвии нужной куки - инкриментируем счетчик в нашем (кэш)хранилище (nginx),
выставляем куку (на нужный период) (nginx).
По заданному интервалу (из cron'а) - забираем значение счетчика из хранилища, обнуляем счетчик.
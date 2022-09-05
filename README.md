===========================TASK=1===============================

Нужно разработать приложение на Lumen, которое будет показывать погоду для текущей геопозиции пользователя.

В приложении должен быть метод, который будет получать POST запросом координаты пользователя и дату, в ответ отдавать название города и погоду в формате JSON.

Историю обращений и ответов нужно записывать в базу данных (SQL).

Метод нужно описать в https://swagger.io/tools/swagger-ui/



●	Готовое приложение залить на git (bitbucket).
●	Фронт делать не нужно. 

_______________________________________________________________

описание метода в weather_1_swagger_schema.yaml

- в случае отсутствия данных, координаты будут вычислены по ip

===========================TASK=2===============================

Предположим, что мы делаем модуль для системы логистики, который должен возвращать возможные интервалы доставки учитывая текущую дату и время, а также направление доставки.
Нужно разработать метод, который на вход получал бы дату, время и одно из направлений [Город_1, Город_2, Город_3] GET запросом.
В ответ необходимо отдать объект в формате JSON вида:

[
‘date’=> ‘01.03.2021’,
‘day’ => ‘Понедельник’,
‘title’ => ‘1 Марта’
]

содержащий в себе следующие 21 день после полученной даты и удовлетворяющий условиям:

Если направление Город_1 или Город_2  - в ответе нужно оставить только понедельники, среды и пятницы.
-Если полученное время больше 16:00 и следующий день понедельник или среда, или пятница - нужно убрать из объекта следующий день.

Если направление Город_3  - нужно оставить в объекте только вторники, четверги и субботы
-если полученное время больше 22:00 и следующий день вторник, или четверг, или суббота - нужно убрать из объекта следующий день

--Убрать из объекта текущий день

--Убрать из объекта праздничные дни (константа - массив - [‘01.01.*’, ‘08.03.*’, ‘09.05.*’])

●	Можно дополнить первое приложение.
●	Фронт делать не нужно.

1662329380 "2022-09-04 22:09:40" 
1662460322 Tue Sep 06 2022 13:32:02 GMT+0300 
1662474722 Tue Sep 06 2022 17:32:02 GMT+0300

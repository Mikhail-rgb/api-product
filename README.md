# api-product

Данный проект реализует систему управления товарами для площадки электронной коммерции. Программа дает возможность управлять товарами с помощью RESTful API.
(Задание: https://github.com/xsolla/xsolla-school-backend-2021)

Для создания программы использовался следующий инструментарий:
- фреймворк Symfony 5.3.3;
- язык программирования PHP 7.4.3;
- набор библиотек для работы с БД Doctrine.


___
## Создание товара 

api/product

Этот запрос создает новый товар в таблице базы данных.

Адрес для отправки запроса:
http://127.0.0.1:8000/api/product

Метод запроса: POST.

Параметры запроса:

- Идентификатор товарной позиции - sku       (string),  обязательный параметр, может повторяться только у товаров с одинаковым типом.
- Имя(название) товара           - title     (string),  обязательный параметр.
- Стоимость товара               - amount    (int),     обязательный параметр, в минорных единицах, товар может быть бесплатным (amount = 0).
- Валюта стоимости товара        - currency  (string),  обязательный параметр, может принимать значение только rub, usd и eur.
- Тип товара                     - type      (string),  обязательный параметр.

Параметры ответа:

- Уникальный идентификатор созданного товара  - id      (int),    необязательный параметр.
- Код ошибки                                  - code    (int),    необязательный параметр.
- Сообщение ошибки                            - message (string), необязательный параметр.


**Все запросы и отеты в формате JSON.**

Пример запроса:

```json
{
    "sku": "UBS-PP-TST",
    "title": "Prince of Persia: The Sands of Time",
    "amount": 25000,
    "currency": "rub",
    "type": "games"
}
```

Пример успешного ответа:
```json
{
    "id": 27
}
```
Пример неуспешного ответа:

```json
{
    "code": 2,
    "message": "Product title not specified"
}
```



___
## Редактирование товара 

/api/product/

Этот запрос обновляет товар в таблице базы данных. Для определения товара, который нужно обновить, может использоваться id товара, либо sku.
Для обновления можно указать лишь те параметры, которые нужно обновить.

Адрес для отправки запроса об обновлении по id:
http://127.0.0.1:8000/api/product/id/{id}

Адрес для отправки запроса об обновлении по sku:
http://127.0.0.1:8000/api/product/sku/{sku}

Метод запроса: PUT.

Параметры запроса:

- Идентификатор товарной позиции - sku       (string),  необязательный параметр, может повторяться только у товаров с одинаковым типом.
- Имя(название) товара           - title     (string),  необязательный параметр.
- Стоимость товара               - amount    (int),     необязательный параметр, в минорных единицах, товар может быть бесплатным (amount = 0). 
- Валюта стоимости товара        - currency  (string),  необязательный параметр, может принимать значение только rub, usd и eur.
- Тип товара                     - type      (string),  необязательный параметр.

Параметры ответа:

- Уникальный идентификатор товара             - id        (int),     необязательный параметр.
- Идентификатор товарной позиции              - sku       (string),  необязательный параметр.
- Имя(название) товара                        - title     (string),  необязательный параметр.
- Стоимость товара                            - amount    (int),     необязательный параметр. 
- Валюта стоимости товара                     - currency  (string),  необязательный параметр.
- Тип товара                                  - type      (string),  необязательный параметр.
- Код ошибки                                  - code      (int),     необязательный параметр.
- Сообщение ошибки                            - message   (string),  необязательный параметр.

Пример запроса:

```json
{
    "amount": 287,
    "currency": "eur"
}
```


Пример успешного ответа:

```json
{
    "id": 27,
    "sku": "UBS-PP-TST",
    "title": "Prince of Persia: The Sands of Time",
    "amount": 287,
    "currency": "eur",
    "type": "games"
}
```


Пример неуспешного ответа:

```json
{
    "code": 4,
    "message": "Unknown currency. Expected one of this: `rub`, `usd`, `eur`. Input currency `sss`."
}
```

___
## Удаление товара

api/product/

Этот запрос удаляет товар из таблицы базы данных. Удалить товар можно по его идентификатору или sku.

Адрес для отправки запроса об удалении по id:
http://127.0.0.1:8000/api/product/id/{id}

Адрес для отправки запроса об удалении по sku:
http://127.0.0.1:8000/api/product/sku/{sku}

Метод запроса: DELETE.

Параметры ответа:

- Сообщение об успехе   - message   (string),  необязательный параметр.   
- Код ошибки            - code      (int),     необязательный параметр.
- Сообщение ошибки      - message   (string),  необязательный параметр.


Пример успешного ответа:

```json
{
    "message": "Product(s) with sku SMTH-WT-L deleted"
}
```


Пример неуспешного ответа:

```json
{
    "code": 6,
    "message": "Product sku not found"
}
```

___
## Получение информации о товаре

/api/product

Этот запрос выводит информацию о товарах из таблицы базы данных. Вывести информацию о товаре можно по его идентификатору или sku.

Адрес для отправки запроса о выводе информации по id:
http://127.0.0.1:8000/api/product/id/{id}

Адрес для отправки запроса о выводе информации по sku:
http://127.0.0.1:8000/api/product/sku/{sku}

Метод запроса: GET.

Параметры ответа:

- Уникальный идентификатор товара             - id        (int),     необязательный параметр.
- Идентификатор товарной позиции              - sku       (string),  необязательный параметр.
- Имя(название) товара                        - title     (string),  необязательный параметр.
- Стоимость товара                            - amount    (int),     необязательный параметр. 
- Валюта стоимости товара                     - currency  (string),  необязательный параметр.
- Тип товара                                  - type      (string),  необязательный параметр.
- Код ошибки                                  - code      (int),     необязательный параметр.
- Сообщение ошибки                            - message   (string),  необязательный параметр.

Пример успешного ответа:

```json
{
    "id": 31,
    "sku": "DС-ETH",
    "title": "Ethereum",
    "amount": 188666,
    "currency": "usd",
    "type": "digital currency"
}
```


Пример неуспешного ответа:

```json
{
    "code": 6,
    "message": "Product id not found"
}
```

___
## Получение каталога товаров

/api/product

Этот запрос выводит информацию о товарах из таблицы базы данных. 

Адрес для отправки запроса о выводе каталога товаров:
http://127.0.0.1:8000/api/product

Метод запроса: GET.

Параметры ответа:

- Уникальный идентификатор товара             - id        (int),     необязательный параметр.
- Идентификатор товарной позиции              - sku       (string),  необязательный параметр.
- Имя(название) товара                        - title     (string),  необязательный параметр.
- Стоимость товара                            - amount    (int),     необязательный параметр. 
- Валюта стоимости товара                     - currency  (string),  необязательный параметр.
- Тип товара                                  - type      (string),  необязательный параметр.
- Код ошибки                                  - code      (int),     необязательный параметр.
- Сообщение ошибки                            - message   (string),  необязательный параметр.

Пример успешного ответа:

```json
[
    {
        "id": 27,
        "sku": "UBS-PP-TST",
        "title": "Prince of Persia: The Sands of Time",
        "amount": 287,
        "currency": "eur",
        "type": "games"
    },
    {
        "id": 28,
        "sku": "SMTH-WT-S",
        "title": "White T-Shirt",
        "amount": 1000,
        "currency": "usd",
        "type": "merch"
    },
    {
        "id": 29,
        "sku": "SMTH-WT-S",
        "title": "White T-Shirt",
        "amount": 1000,
        "currency": "usd",
        "type": "merch"
    },
    {
        "id": 31,
        "sku": "DС-ETH",
        "title": "Ethereum",
        "amount": 188666,
        "currency": "usd",
        "type": "digital currency"
    },
    {
        "id": 32,
        "sku": "DС-BTC",
        "title": "Bitcoin",
        "amount": 3161790,
        "currency": "usd",
        "type": "digital currency"
    }
]
```


С целью снижения нагрузки на сервис был предусмотрен метод вывода каталога товаров по частям (5 товаров за раз). Для этого был использован пакет "knp-paginator-bundle".
Чтобы вывести следующие товары, в запросе нужно указать номер страницы: http://127.0.0.1:8000/api/product/?page=2

Пример успешного ответа:
```json
[
    {
        "id": 33,
        "sku": "JKR-HP-CS",
        "title": "Harry Potter and Chamber of Secrets",
        "amount": 50000,
        "currency": "rub",
        "type": "books"
    },
    {
        "id": 34,
        "sku": "RG-GTA-V",
        "title": "Grand Theft Auto V",
        "amount": 1000,
        "currency": "eur",
        "type": "games"
    },
    {
        "id": 35,
        "sku": "UBS-AC-1",
        "title": "Assassin’s Creed",
        "amount": 1000,
        "currency": "eur",
        "type": "games"
    },
    {
        "id": 36,
        "sku": "SMTH-WS-58",
        "title": "White Shorts",
        "amount": 15000,
        "currency": "rub",
        "type": "merch"
    },
    {
        "id": 37,
        "sku": "EMR-TC",
        "title": "Three Comrades",
        "amount": 75000,
        "currency": "rub",
        "type": "books"
    }
]
```

Пример неуспешного ответа:
```json
{
    "code": 6,
    "message": "Products not found"
}
```

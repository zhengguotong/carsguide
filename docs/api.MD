
#### Get all Items

**URL** : `/items`
**Method** : `GET`
**Request Params** : 
* per_page: optional, max number of items to show in one page, default is 1000
* page: optional page number is request,default is 1
##### Success Response
**Code** : `200 OK`

**Response examples**

```json
{
    "data": [
        {
            "id": 4,
            "guid": "868fe36d-ce53-3d78-93de-803412d49fc9",
            "name": "Mr. Reinhold Witting",
            "email": "samir.altenwerth@example.org",
            "created_dates": {
                "created_at_human": "20 hours ago",
                "created_at": "2020-12-28T10:36:54.000000Z"
            },
            "updated_dates": {
                "updated_at_human": "20 hours ago",
                "updated_at": "2020-12-28T10:36:54.000000Z"
            }
        },
        {
            "id": 5,
            "guid": "7f634625-ffc9-3690-a136-0b488ea5d39c",
            "name": "Mr. Murl Zboncak",
            "email": "shannon76@example.org",
            "created_dates": {
                "created_at_human": "20 hours ago",
                "created_at": "2020-12-28T10:36:54.000000Z"
            },
            "updated_dates": {
                "updated_at_human": "20 hours ago",
                "updated_at": "2020-12-28T10:36:54.000000Z"
            }
        }
    ],
    "links": {
        "first": "http://docker.for.mac.localhost:8006/items?page=1",
        "last": "http://docker.for.mac.localhost:8006/items?page=53",
        "prev": null,
        "next": "http://docker.for.mac.localhost:8006/items?page=2"
    }
}
```

### Add an new item
**URL** : `/items`
**Method** : `POST`
#### Success Response
**Code** : `200 OK`

**Content examples**
```json
{
    "email":"test9@test.com",
    "name": "test1"
}
```

#### Unsuccess Response
**Code** : `422 Unprocessable Entity`

**Content examples**
```json
{
    "name": [
        "The name field is required."
    ],
    "email": [
        "The email field is required."
    ]
}
```


### get an new item given by its id
**URL** : `/items/{id}`
**Method** : `GET`

##### Success Response
**Code** : `200 OK`

**Response examples**
```json
{
    "data": {
        "id": 12,
        "guid": "95513541-4473-3b2e-b134-0a5a944dca7e",
        "name": "Danny Hand",
        "email": "schuster.danika@example.org",
        "created_dates": {
            "created_at_human": "20 hours ago",
            "created_at": "2020-12-28T10:36:54.000000Z"
        },
        "updated_dates": {
            "updated_at_human": "20 hours ago",
            "updated_at": "2020-12-28T10:36:54.000000Z"
        }
    },
    "status": "ok"
}
```


##### Unsuccess Response
**Code** : `404 NOT Found`

**Response examples**
```json
{
    "error": "Item Not Found",
    "code": 404
}
```

### Update an item given by its id 
**URL** : `/items/{id}`
**Method** : `PATCH` OR `PUT`

**Content examples**
```json
{
    "email":"testw4@test.com",
    "name": "test"
}
```

#### Success Response
**Code** : `200 OK`

**Response examples**
```json
{
    "data": {
        "id": 12,
        "guid": "95513541-4473-3b2e-b134-0a5a944dca7e",
        "name": "test",
        "email": "testw4@test.com",
        "created_dates": {
            "created_at_human": "20 hours ago",
            "created_at": "2020-12-28T10:36:54.000000Z"
        },
        "updated_dates": {
            "updated_at_human": "1 second ago",
            "updated_at": "2020-12-29T06:54:47.000000Z"
        }
    },
    "status": "ok"
}
```
#### Unsuccess Response
**Code** : `400 Bad Request`

**Content examples**
```json
{
    "error": "SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'test9@test.com' for key 'items.items_email_unique' (SQL: update `items` set `email` = test9@test.com, `items`.`updated_at` = 2020-12-29 06:56:55 where `id` = 12)",
    "code": 400
}
```

### delete an new item given by its id
**URL** : `/items/{id}`
**Method** : `DELETE`

##### Success Response
**Code** : `200 OK`

**Response examples**
```json
{
    "message": "Item 3 deleted",
    "status": "ok"
}
```


##### Unsuccess Response
**Code** : `404 NOT Found`

**Response examples**
```json
{
    "error": "Item Not Found",
    "code": 404
}
```
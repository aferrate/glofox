# Technical test glofox
### Install

run docker:
```
cd laradock
docker-compose up -d nginx mysql phpmyadmin
```

install dependencies:
```
docker-compose exec workspace bash
composer install
```

create database:
```
docker-compose exec workspace bash
php bin/console doctrine:database:create
```

migrate entities:
```
docker-compose exec workspace bash
php bin/console doctrine:migrations:migrate
```

### Run tests:
```
phpunit
```


### Endpoints
```
http://localhost/api/v1/classrooms
GET
```

```
http://localhost/api/v1/classroom/id/1
GET
```

```
http://localhost/api/v1/classroom/create
POST
{
    "name" : "test",
    "capacity" : 8,
    "start_date" : "12-06-2022",
    "end_date" : "15-06-2022"
}
```

```
http://localhost/api/v1/classroom/update/10
PUT
{
    "name" : "test",
    "capacity" : 8,
    "start_date" : "10-06-2022",
    "end_date" : "15-06-2022"
}
```

```
http://localhost/api/v1/classroom/delete/8
DELETE
```

```
http://localhost/api/v1/members
GET
```

```
http://localhost/api/v1/member/id/1
GET
```

```
http://localhost/api/v1/member/create
POST
{
    "name" : "member"
}
```

```
http://localhost/api/v1/member/update/1
PUT
{
    "name" : "member updated"
}
```

```
http://localhost/api/v1/member/delete/1
DELETE
```

```
http://localhost/api/v1/bookings
GET
```

```
http://localhost/api/v1/booking/id/1
GET
```

```
http://localhost/api/v1/booking/create
POST
{
    "idMember" : 1,
    "idClassroom" : 1,
    "date" : "14-06-2022"
}
```

```
http://localhost/api/v1/booking/update/1
PUT
{
    "idMember" : 2,
    "idClassroom" : 2,
    "date" : "15-06-2022"
}
```

```
http://localhost/api/v1/booking/delete/1
DELETE
```
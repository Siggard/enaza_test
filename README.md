REQUIREMENTS
-------------------

```
1. Redis >= 3.2 (session DB)
2. PHP >= 7.1 (Yii2)
```

INSTALL
-------------------
<p>STEP 1: download project files or run command</p>

```
git clone https://github.com/Siggard/enaza_test.git
```

<p>STEP 2: Install composer and run command from project directory</p>

```
composer update 
```

<p>STEP 3: Set document roots of your web server:</p>

```
for frontend (OPEN SIMPLE MONITOR PANEL)    /path/to/yii-application/frontend/web/  test.ru
for backend (REST API)                      /path/to/yii-application/backend/web/   api.test.ru
```

<p>STEP 4: Run console command from root project directory</p>

```
yii club/init
```

API
-------------------

```
GET /club               view details club info

PUT /club/optimize      play most popular genre music among our guests
PUT /club/play          play select genre, data in json {genre: HOUSE}
                    - - this two PUT methods require HTTP Base Auth, login/password (test/12344321) 
    
GET /guest              list of guests
```

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    models/              contains model classes used in both backend, frontend and console
        base/            ...
        data/            redis/ActiveRecord models
        factories/       ...
        interfaces/      ...
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
backend
    config/              contains backend configurations
    controllers/         contains REST API controller classes
frontend
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    views/               contains view files for the Web application
```

# PayGreen

## Features
- /docs **documentation**
- /login **authentication**
- /users **users listing** *(administrator)*
- /transactions **transaction creation** *(user and administrator)*


## Requirements

- docker : [https://docs.docker.com/get-docker/](https://docs.docker.com/get-docker/)
- docker-compose [https://docs.docker.com/compose/install/](https://docs.docker.com/compose/install/)
- make

## Run

#### Run all project containers :
```bash
make build
make up
```

#### Connect to the PHP container :

```bash
make php
```

#### First install :
```bash
composer install
bin/console doctrine:database:create
bin/console doctrine:migration:migrate --no-interaction
bin/console doctrine:fixtures:load --no-interaction
```
The api documentation is available at : [http://localhost:8080/docs](http://localhost:8080/docs)

#### Run tests :

```bash
composer install
bin/console doctrine:database:create --env=test
bin/console doctrine:migration:migrate --no-interaction --env=test
bin/phpunit
```

## Accounts
With fixtures you have two users availables :
- An administrator :
  - email : admin@paygreen.fr
  - password : admin
- A simple user :
  - email : user@paygreen.fr
  - password : user

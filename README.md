## Installation
```bash

# setting env variables
$ cp .env.docker .env

# build docker
$ docker-compose build

# installing dependencies
$ docker-compose run --rm laravel.test composer install

# start backend
$ docker-compose up


see name container by docker ps -a 


#backend 
$docker exec -it <name container> bash

php aritisan migrate

php artisan db:seed


#Fe
React
user folder npm start

Nextjs


admin
npm run watch

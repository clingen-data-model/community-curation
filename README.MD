# ClinGen Community Curation Database

The ClinGen Community Curation Database (CCDB) is a workflow management tool that supports tracking volunteers conducting gene-disease curations.

## Installation

1. `$ git clone https://github.com/clingen-data-model/community-curation.git`
2. `$ cd community-curation`
2. `$ docker-compose up -build`
3. `$ docker-compose run artisan migrate --seed`

This should result in the application running at http://localhost:8080.

## Tests:

### Setup
1. Create the database if not already created: `$ docker exec -it ccdb-db mysql -uroot -ppassword --execute='CREATE DATABASE ccdb_test'`
2. `$ docker-compose run artisan migrate --seed --database testing`

### Running
1. `$ docker exec ccdb-app vendor/bin/phpunit`

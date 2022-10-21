# ClinGen Community Curation Database

The ClinGen Community Curation Database (CCDB) is a workflow management tool that supports tracking volunteers conducting bio-curation.

## Installation
### Prerequisites
You must have the following to stand up the application locally
* Docker

The stand up a local instance of the application
1. Clone this repository
2. cd into the directory
4. `cp .env.example .env`
5. `docker-compose up -d --build`
3. `docker-compose exec app composer install`
6. `docker-compose exec app php artisan key:generate`
7. Confirm that the `.docker/data` directory exists (it can take a few minutes for the database to be setup).  When it does run `docker-compose exec app php artisan migrate --seed`
8. `docker-compose exec app php artisan migrate --seed --database=testing` to set up the testing db.

The development server is available at http://localhost:8080

Database seeding run in the last setup step has created two user/people with the following credentials
* super.user@example.com; tester
* super.admin@example.com; tester
* normal.admin@example.com; tester

To work on the front end client you will need:
* node
* npm
* vue-cli

To start the development server:
1. `cd resources/app`
2. `npm run serve`

This will start up the webpack development server which will server which you can access at http://localhost:8081.

The development server supports hot module replacement (HMR) so changes to code will be hot swapped when the dev server is running.  The dev server will proxy api requests to http://localhost:8080.  Note that the dev server's proxy does only supports xhttp requests.  The handful of regular requests (i.e. impersonation, report downloads, etc.) will require pointing you browser directly at port 8080.

For more information see [vue-cli documentation](https://cli.vuejs.org/)

## Terminology conceptual overview
### Curation Activities
### Volunteers
### Assignments
### Aptitudes
### Training
### Attestations


## Backend Architecture
The CCDB's backend is built on the Laravel MVC framework.  The implementation is mostly idiomatic Laravel with light use of the [laravel-actions](https://github.com/lorisleiva/laravel-actions) package for more recent feature development

### DX Integration
The CCDB does not currently integrate with the ClinGen DataExchange.

## Frontend client
The frontend client is built using Vuejs v2.  It leverages vuex for the global store.  The CCDB frontend is NOT a single-page-app.  Each page is effectively a self-contained vue app.

## DevOps
The demo and production instances of the GPM are hosted on UNC's [Cloudapps OpenShift cluster](https://console.cloudapps.unc.edu) in the `dept-gpm` project.  OpenShift is RedHat's value-add to the Kubernetes open source project.  You're better off referencing Kubernetes documentation for anything that is not a proprietary OpenShift thing (i.e. Builds, BuildConfigs, etc.).

### Architecture
At a high level, the project is composed of:
* MySQL server: persistent store for the application. Based on the [jward3/openshift-mysql](https://hub.docker.com/repository/docker/jward3/php) image.
* Redis server: application cache, and queue
* Laravel app, running in three "roles", web app, scheduled task runner, and queue worker. Based on the [jward3/php](https://hub.docker.com/repository/docker/jward3/php) image.
* A cronjob that backs up the database and writes to a persistent volume.
* A cronjob that cleans database backups.

This repository is built into a docker image via the *app* build config and stored in the app ImageStream.

The application image is deployed by three separate DeploymentConfigs to use the Laravel app in different contexts:
* `app` - Runs an apache web server with php.  This is deployment of the web-accessible app.
* `schuduler` - Runs `php artisan schudule:run` every minute to ensure scheduled tasks are executed. See the [Laravel scheduled docs](https://laravel.com/docs/8.x/scheduling) for details.
* `queue` - Starts a queue worker `php artisan queue:work` which processes jobs queued to the redis DeploymentConfig.  See https://laravel.com/docs/8.x/queues for details on queued jobs

The `CMD` for the image runs `.docker/start.sh` which runs the appropriate command based on the `CONTAINER_ROLE` environment variable.  Valid container roles include  `app`, `queue`, and `scheduler`.


## Installation
### Prerequisites
You must have the following to stand up the application locally
* PHP 8.0
* Composer
* Docker

The stand up a local instance of the application
1. Clone this repository
2. cd into the directory
3. `composer install`
3. `cp .env.example .env`
4. `php artisan key:generate`
3. `docker-compose up -d --build`
4. Confirm that the `.docker/data` directory exists (it can take a few minutes for the database to be setup).  When it does run `docker-compose exec app php artisan migrate --seed`

The development server is available at http://localhost:8080

Database seeding run in the last setup step has created two user/people with the following credentials
* super.user@example.com; tester
* super.admin@example.com; tester

To work on the front end client you will need:
* node
* npm
* vue-cli

To start the development server:
1. `cd resources/app`
2. `npm run serve`

This will start up the webpack development server which will server which you can access at http://localhost:8081.

The development server supports hot module replacement (HMR) so changes to code will be hot swapped when the dev server is running.  The dev server will proxy api requests to http://localhost:8080.  Note that the dev server's proxy does only supports xhttp requests.  The handful of regular requests (i.e. impersonation, report downloads, etc.) will require pointing you browser directly at port 8080.

For more information see [vue-cli documentation](https://cli.vuejs.org/)


### Setup
1. Create the database if not already created: `$ docker exec -it ccdb-db mysql -uroot -ppassword --execute='CREATE DATABASE ccdb_test'`
2. `$ docker-compose run artisan migrate --seed --database testing`

### Running
1. `$ docker exec ccdb-app vendor/bin/phpunit`

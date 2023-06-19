# ClinGen Community Curation Database

The ClinGen Community Curation Database (CCDB) is a workflow management tool that supports tracking volunteers conducting bio-curation.

## Installation
### Prerequisites
You must have the following to stand up the application locally
* Docker
* (if on a mac, you'll also need docker-deskop or colima-- for instance as in [macos-setup.md](macos-setup.md))

### Setup DOCKER_USER variable (probably only once ever)

To handle docker permissions issues in bind mounts, this project uses the convention of running
containers using the `DOCKER_USER` (formatted like `$UID:$GID`) as the user for the container.
You could set this in the `.env` file (described later on), or to avoid having to do this in other
projects that use the same convention, you can add a line like `export DOCKER_USER=$(id -u):$(id -g)`
or `export DOCKER_USER=${UID}:${GID}` to your `.zshrc`, `.bashrc` or equivalant in your home directory.

### Setup other configurable options (probably don't need to re-do often)

This project uses the convention that Laravel uses with most configurable options definable in
a `.env` file in the project root. There is an `.env.example` file to guide this...

```bash
cp .env.example .env
```

Then edit as needed... maybe you'll want to change the things that are defined as `changeme`...

### Seeding the database

Not necessarily "one-time setup", but probably not something that needs doing frequently. This needs
to be done at least once before anything will work, though.

Right now, seeding via Laravel has accumulated some technical debt, so the easiest way to do this
is from an existing database dump.

The database uses a standard mysql docker image, which runs sql or sql.gz files places in a certain
directory, but only if the container volume hasn't been initialized before. So if you have an existing
docker volume, you may need to remove it.

```bash
docker volume rm ccdb_db
```

Your database dump file (as sql or compressed as sql.gz) needs to go in the subdirectory
`.docker/mysql/db-init/` under the project root. `*.sql.gz` files in this directory are .gitignore-ed
to help avoid unintential committing of database dump files.

Then just start the database container with:

```bash
docker compose up -d db
```

This will take a minute or so-- if you watch things with `docker compose logs -f`, you'll see a
local-only server started for initialization, then it should restart listening on the docker network.

### Populate `vendor/` directory with dependencies

If you have php and composer on your host system, you could just run `composer app install`, but in
the name of reproducibility, it is probably better to run this using the php and composer versions
in the container.

**Note**: You may not need to do this initially-- the entrypoint script should take care
of running this if the vendor directory hasn't been populated. But you would need to do this whenever
dependencies get updated. This is the set of commands to run if you get errors about missing
dependencies in the PHP code.

```bash
docker compose run --no-deps --rm -it --entrypoint composer app install --no-interaction --no-plugins --no-scripts --prefer-dist --no-dev --no-suggest
docker compose run --no-deps --rm -it --entrypoint composer app dump-autoload
```

## Running

Once everything above is setup, you should be able to just run the following:

```bash
docker compose up -d
```

After some initialization (which you can watch using `docker compose logs -f`), the app should
start responding to requests at the port given by `APP_PORT` in the `.env` file. By default,
this will be reachable at `http://localhost:8011`.

## Accessing container services

The `docker-compose.yml` only exposes the nginx container to the host. The primary reason for this is
to prevent various containers in different project (e.g., mysql or redis containers) from stepping
on each others' toes by trying to open the same port. Your options for getting to those services are:

* running `docker compose exec -it db`, then using the command line utilities there to access data
* running a one-off container with `docker compose run` and a `socat` container to be on that network
  and forward from this container to the one you're trying to access. This temporary port forwarding
  is left as an exercise to the reader.

## Frontend (this section needs to be re-worked since the frontend is run by default in the container)

To work on the front end client you will need:
* node
* npm

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

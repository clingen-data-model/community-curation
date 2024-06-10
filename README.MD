# ClinGen Community Curation Database

The ClinGen Community Curation Database (CCDB) is a workflow management tool that supports
tracking volunteers who are conducting bio-curation.

## Installation
### Prerequisites
You must have the following to stand up the application locally
* Docker
* (if on a mac, you'll also need something like docker-desktop, colima or orbstack, for instance as
  in [macos-setup.md](macos-setup.md))

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

At the moment, you will be unable to build the project without a license to Backpack for Laravel
(which needs to be referenced in COMPOSER_AUTH). Removing that dependency is TBD.

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

## Creating a user

If you don't have a user in the database dump you are using to seed a local instance, you can create
one with the following artisan command:

```bash
docker compose run --no-deps --rm -it --entrypoint php artisan user:create --email test@example.com --first_name Test --last_name Administrator -s
```

Of course you can change the email and names as you see fit. The email will end up being the login
identifier. You'll be prompted for a password and password confirmation.

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

Preparing the frontend after an initial checkout is the standard `npm install`.

Building bundled javascript (i.e., to deploy) is by `npm run production`.

To start the development server, call `npm run dev` from the source root directory. This will start up
the webpack development server which will server which you can access at http://localhost:8081.

The development server supports hot module replacement (HMR) so changes to code will be hot swapped
when the dev server is running.  The dev server will proxy api requests to http://localhost:8080.
Note that the dev server's proxy does only supports xhttp requests. The handful of regular requests
(i.e. impersonation, report downloads, etc.) will require pointing you browser directly at port 8080.

TODO: the dev server is actually running on 8011... so proxying will not work... Laravel Mix should be
replaces with vite, anyway.

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
The demo and production instances of the GPM are hosted on UNC's [Cloudapps OpenShift
cluster](https://console.cloudapps.unc.edu) in the `dept-community-curation` project. OpenShift is
RedHat's value-add to the Kubernetes open source project. You're better off referencing Kubernetes
documentation for anything that is not a proprietary OpenShift thing (i.e. Builds, BuildConfigs, etc.).

Deployment is acutally by helm to that cluster, using the
[cgwi-php-helm](https://github.com/clingen-data-model/cgwi-php-helm) charts.

### Architecture
At a high level, the project is composed of:
* MySQL server: persistent store for the application. Based on the [jward3/openshift-mysql](https://hub.docker.com/repository/docker/jward3/php) image.
* Redis server: application cache (e.g., for session information), and queue
* Laravel app and related scheduler and queue workers, based on the [cgwi-php](https://github.com/clingen-data-model/cgwi-php) image:
  * `app` runs the actual app, using `./scripts/entrypoint.sh` as the entrypoint to run some
    initialization tasks and then php-fpm to respond to requests
  * The other related containers (`queue` and `scheduler`) override the entrypoint (see
    `docker-compose.yml`) to run `php artisan queue:work` and `php artisan schedule:run`, repectively.
* Database backups and cleanup of backups are performed by separate CronJobs.

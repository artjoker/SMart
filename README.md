# base

## Installation

- Clone repo
- Create config file `cp .env.example .env`
- Run `docker-compose up -d`
- Inside php container `docker-compose exec php bash`
    - `composer install`
    - `artisan key:generate`
    - `artisan migrate:fresh --seed`
    - `artisan storage:link`
- Inside node container `docker-compose run node bash`
    - `yarn`
    - `yarn dev`
- Restart docker containers with commands
    - `docker-compose down`
    - `docker-compose up -d`
- Create version description
    - `git describe --tags > version`
- Open in browser `http://localhost/`

## Testing

- Pass into container `docker-compose exec php bash`
- Run `a test`


## Doc
- Pass into container `docker-compose exec php bash`
- artisan scribe:generate

# Link minifier application base on laravel 8.x

### 1. Setup

This project uses [docker](https://docs.docker.com) and [docker-compose](https://docs.docker.com/compose). So you have to install it first.

### 2. Build

```bash
    //clone the project
    git clone git@github.com:alexivenkov/minifier.git
    cd minifier
    
    //setup docker containers
    docker-compose build
    docker-compose up -d
    
    //install dependencies via composer
    docker-compose run --rm web composer install
    //copy and modify .env file according to your needs
    cp .env.example .env

    //run project specific commands
    docker-compose run --rm web php artisan key:generate --no-interaction
```

### 3. Run
To run all containers you need just run `docker-compose up -d`. 
It will build and link all required containers automatically.

### 4. Run php commands
To run any php commands just run it in container:
```bash
    docker-compose run --rm web php <command>
```

### 5. Migrations
To migrate database schema run: 
```bash
    // local env
    docker-compose run --rm web php artisan migrate
    // for testing env create database with name `minifier_test` before
    docker-compose run --rm web php artisan migrate --env=testing
```

### 6. Unit tests
```bash
cp .env.testing.example .env.testing
```

To run tests use 
```bash
    docker-compose run --rm web vendor/bin/phpunit
```

## Application content
All available routes located inside `routes` directory on project root level (`web.php` with only one route and `api.php`)

Contollers code located in `app\Http\Controllers` directory

Application business logic located in `app\Services` directory

Database structure located in `database\migrations` directory

Unit tests code located in `tests` directory on project root level

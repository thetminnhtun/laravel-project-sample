# Laravel Ajax Pagination With JQuery

A demo Ajax pagination application using JQuery and Bootstrap 4

## Preview List

![](./screenshot.png)

## Installation

Clone the repo locally:

```sh
git clone -b spa_pagination https://github.com/thetminnhtun/laravel-project-sample.git laravel-project-sample
cd laravel-project-sample
```

Install PHP dependencies:

```sh
composer install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate --seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

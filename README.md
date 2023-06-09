# Api Imagine Apps PHP (Laravel)

In this repository we only have the API made in Laravel to be easier when creating the API.
In order to run the API you have to clone the repo in the main branch and then start running the following commands:

## Clone The Repository

```terminal
git clone https://github.com/IAmNelsonArevalo/imagine-api-php.git
```

## Install the dependencies

To run the project you have to execute the following command in the terminal, be careful you must have PHP 8.1 or higher.

```terminal
composer install
```

## Run the migrations



```terminal
php artisan migrate
```

## Run the migrations

before executing the migrations you must take into account that you must first create your schema or database locally with the same name of the .env file or you can create a new database and change the DB keys of the .env file so that you can execute the migrations migrations with the following command.

```terminal
php artisan migrate
```

## Run the seeders

```terminal
php artisan db:seed
```

## Run the project

```terminal
php artisan serve
```


## License

[MIT](https://choosealicense.com/licenses/mit/)


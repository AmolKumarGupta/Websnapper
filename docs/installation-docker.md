## Installation

clone the repository from gitub
```
git clone https://github.com/AmolKumarGupta/Websnapper.git
```

install composer packages 
```
composer install
```

run sail
```
./vendor/bin/sail up
```

install npm packages 
```
./vendor/bin/sail npm install
```

copy `.env.example` to `.env` and setup your database.
```
DB_DATABASE=websnapper
DB_USERNAME=sail
DB_PASSWORD=password
```

if APP_KEY is not preset then generate it
```
./vendor/bin/sail artisan key:generate --ansi
```

run migrations
```
./vendor/bin/sail artisan migrate
```

seed database
```
./vendor/bin/sail artisan db:seed
```

build assets

```
./vendor/bin/sail npm run build
```

for development, use 

```
./vendor/bin/sail npm run dev
```

> Also Follow the Step [Telescope Local Only](https://laravel.com/docs/10.x/telescope#local-only-installation)


## Warning
there may be a chance that videos are not being uploaded, it can be fixed with storage/app/php.ini.
any changes will be applied by using `./vendor/bin/sail up` command
```
upload_max_filesize=100M
post_max_size=105M
```

## Testing
We are using phpunit for testing, we prefer to use `.env.testing` rather than `.env` file
```
./vendor/bin/sail artisan test
```

> Tips: do ```./vendor/bin/sail artisan schema:dump``` to boost migration before running tests.
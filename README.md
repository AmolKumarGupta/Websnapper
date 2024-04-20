# Websnapper
Websnapper is a SaaS (Software as a Service) project aimed at enabling screen recording and sharing videos with ease.


<div align="center" style="display:flex; gap: 1rem;">
    <img src="https://github.com/AmolKumarGupta/Websnapper/assets/88397611/37efe3f7-4f67-4797-a7c3-c0206a6d7f16" alt="welcome-page" width="400" >
</div>


## Features
- Screen recording
- Video sharing
- Private and Public Access

## Installation

> Here is the installation steps with [Docker](./docs/installation-docker.md)

clone the repository from gitub
```
git clone https://github.com/AmolKumarGupta/Websnapper.git
```

install composer packages 

```
composer install
```

install npm packages 

```
npm install
```

copy `.env.example` to `.env` and setup your database.
if APP_KEY is not preset then generate it
```
php artisan key:generate --ansi
```

run migrations
```
php artisan migrate
```

seed database
```
php artisan db:seed
```

build assets

```
npm run build
```

for development, use 

```
npm run dev
```

start server
```
php artisan serve
```

> Also Follow the Step [Telescope Local Only](https://laravel.com/docs/10.x/telescope#local-only-installation)


## Warning
there may be a chance that videos are not being uploaded, it can be fixed with php.ini
```
upload_max_filesize=100M
post_max_size=105M
```

## Testing
We are using phpunit for testing, we prefer to use `.env.testing` rather than `.env` file
```
php artisan test
```

> Tips: do ```php artisan schema:dump``` to boost migration before running tests.



## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE.md ) file for details.



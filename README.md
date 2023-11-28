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


## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE.md ) file for details.



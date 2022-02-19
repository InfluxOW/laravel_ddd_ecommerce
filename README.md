# WIP: Laravel DDD e-Commerce

*<when I deploy the site, here will be a link>*

<div align="center">
    <p>
        <a href="https://github.com/InfluxOW/laravel_ddd_ecommerce/actions/workflows/master.yml"><img src="https://github.com/InfluxOW/laravel_ddd_ecommerce/actions/workflows/master.yml/badge.svg" alt="PHP CI"></a>
        <a href="https://codeclimate.com/github/InfluxOW/laravel_ddd_ecommerce/maintainability"><img src="https://api.codeclimate.com/v1/badges/785ee07bd777cf41ee07/maintainability" alt="Maintainability"></a>
        <a href="https://codecov.io/gh/InfluxOW/laravel_ddd_ecommerce"><img src="https://codecov.io/gh/InfluxOW/laravel_ddd_ecommerce/branch/master/graph/badge.svg?token=MS9MMW2OBX" alt="Code Coverage"></a>
    </p>
    <p>
        <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel v8.x"></a>
        <a href="https://laravel-livewire.com"><img src="https://img.shields.io/badge/Livewire-2.x-FB70A9?style=for-the-badge" alt="Livewire v2.x"></a>
        <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php" alt="PHP 8.1"></a>
        <a href="https://docker.com"><img src="https://img.shields.io/badge/Docker-20.10.x-0db7ed?style=for-the-badge&logo=docker" alt="Docker 19.03.0"></a>
        <a href="https://postgresql.org"><img src="https://img.shields.io/badge/Postgresql-14.X-0064a5?style=for-the-badge&logo=postgresql" alt="PostgreSQL 14.X"></a>
        <a href="https://swagger.io"><img src="https://img.shields.io/badge/Swagger-3.0-a9f06b?style=for-the-badge&logo=swagger" alt="Swagger 3.0"></a>
        <a href="https://redis.io/"><img src="https://img.shields.io/badge/Redis-6.2.x-D82C20?style=for-the-badge&logo=redis" alt="Redis 6.2.x"></a>
    </p>
</div>

## About

I was looking for such kind of project but found nothing, so I decided to create my own. Maybe there's not a huge
difference between generic Laravel e-Commerce and DDD e-Commerce, but anyway it's interesting to use another
architecture and look how you can modify base project structure.

I was inspired by these ([1](https://lorisleiva.com/conciliating-laravel-and-ddd/)
, [2](https://medium.com/@ibrunotome/a-domain-driven-design-ddd-approach-to-the-laravel-framework-18906b3dd473)
, [3](https://youtu.be/0Rq-yHAwYjQ?list=PLc9FxgUP0PDRFpDM7YLqHTxlSi1Q8ALyB&t=4066)) and a lot of other articles and
videos as well as my work projects. But basically every DDD article presents its own project structure so DDD is more an
idea or a team agreement than exact project structure. And that's why I've implemented my own wierd DDD structure :)

## Development Setup

1. Fill `.env` variables.
2. To setup project in Docker environment ([Laravel Sail](https://laravel.com/docs/9.x/sail)) use `make setup` command.

## Features

### Generic Features

- Swagger Documentation
- Amazing [Filament](https://filamentadmin.com/) Admin Panel
- RESTful API
- High Test Coverage

### Infrastructural Features

- DDD structure *(that I update almost every 10 commits, but anyway...)*
- Separate infrastructure for local development, CI and tests (see `docker-compose` files)
- Git hooks with code style and code quality analysis
- CI at GitHub Actions with cached Docker images and other interesting stuff *(and it works pretty fast though)*

### Project Features

- Authentication with Laravel Sanctum *(Verification email sending included)* and other auth actions - [See [**Users**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Users) domain]
- Catalog with different filters, full-text search, categories tree and other interesting stuff - [See [**Catalog**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Catalog) domain]
- Ability to add feedback, but a limited number of times per hour *(because why not)* - [See [**Feedback**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Feedback) domain]
- Carts with Redis *(there's still no API, and it still requires a lot of work but though)* - [See [**Cart**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Cart) domain]
- Full usage of PHP 8.1 enums
- Admin panel multilanguage support
- AWS S3 disk for images, responsive images generation

And a lot of other that isn't mentioned above, or will be added, or will be fixed in current code.

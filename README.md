# WIP: [Laravel DDD e-Commerce](http://laravel-ddd-ecommerce.herokuapp.com)

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
        <a href="https://redis.io/"><img src="https://img.shields.io/badge/Redis-7.x-D82C20?style=for-the-badge&logo=redis" alt="Redis 7.x"></a>
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

- [Laravel Octane](https://laravel.com/docs/9.x/octane) with Swoole as Server
- [Laravel Horizon](https://laravel.com/docs/9.x/horizon) as Queue Dashboard
- [Totem](https://github.com/codestudiohq/laravel-totem) as Schedule Dashboard
- [Prequel](https://github.com/Protoqol/Prequel) as Database Dashboard
- [Clockwork](https://underground.works/clockwork/) and [Laravel Telescope](https://laravel.com/docs/9.x/telescope) as Dev Toolkit
- [Swagger Documentation](http://laravel-ddd-ecommerce.herokuapp.com/api/documentation)
- Amazing [Admin Panel](http://laravel-ddd-ecommerce.herokuapp.com/admin) - [Filament](https://filamentadmin.com/)
- RESTful API
- Instant Parallel Tests
- Redis Cache

### Infrastructural Features

- DDD structure
- Separate [Development](https://github.com/InfluxOW/laravel_ddd_ecommerce/blob/master/docker-compose.yml), [Testing](https://github.com/InfluxOW/laravel_ddd_ecommerce/blob/master/docker-compose.test.yml) and [CI](https://github.com/InfluxOW/laravel_ddd_ecommerce/blob/master/docker-compose.ci.yml) Infrastructure
- Git Hooks with Code Style ([PHPCS](https://github.com/squizlabs/PHP_CodeSniffer)) and Code Quality ([PHPStan](https://github.com/phpstan/phpstan)) analysis
- Fast CI at GitHub Actions:
    - caching dependencies and check's results
    - building, caching and pushing Docker images to the Docker Hub
    - building [code coverage report](https://about.codecov.io/)

### Project Features

- Authentication with Laravel Sanctum, Email Verification - [[**Users**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Users)]
- Catalog with different filters, full-text search, categories tree, etc. - [[**Catalog**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Catalog)]
- Ability to add feedback, but a limited number of times per hour per IP - [[**Feedback**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Feedback)]
- **[WIP]** Carts with Redis - [[**Cart**](https://github.com/InfluxOW/laravel_ddd_ecommerce/tree/master/app/Domains/Cart)]

### Random Features

- Full usage of PHP 8.1
- Admin panel multilanguage support
- AWS S3 disk for images, responsive images generation

And a lot of other that isn't mentioned above, or will be added, or will be fixed in current code.

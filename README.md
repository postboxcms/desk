<p align="center"><img height="69" src="/art/postbox.jpg" alt="Logo PostboxCMS Desk"></p>

<p align="center">
<a href="https://packagist.org/packages/postboxcms/desk"><img src="https://img.shields.io/packagist/dt/postboxcms/desk" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/postboxcms/desk"><img src="https://img.shields.io/packagist/v/postboxcms/desk" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/postboxcms/desk"><img src="https://img.shields.io/packagist/l/postboxcms/desk" alt="License"></a>
</p>

## Introduction

Desk provides a Docker powered experience for Laravel that is compatible with macOS, Windows (WSL2), and Linux. Other than Docker, no software or libraries are required to be installed on your local computer before using Desk. Desk's simple CLI means you can start building your Laravel application without any previous Docker experience. Desk uses and installs Laravel Passport for creating new users in your application.

## Getting started

To get started with Desk, run the following commands:
* Include the package within your laravel application by running the following command `composer require postboxcms/desk --dev`
* To install Desk, simply run `php artisan desk:install`
* To get the containers up and running use the command `desk up -d`
* To setup your application with **Laravel Passport** and update environment configurations run `desk artisan cms:setup`
* To add a new user to the application run `desk cms:adduser` and follow the instructions

#### Inspiration

Desk is forked from Laravel Sail and derived from [Vessel](https://github.com/shipping-docker/vessel) by [Chris Fidao](https://github.com/fideloper). If you're looking for a thorough introduction to Docker, check out Chris' course: [Shipping Docker](https://serversforhackers.com/shipping-docker).

## Official Documentation for Laravel Sail

Documentation for Laravel Sail can be found on the [Laravel website](https://laravel.com/docs/sail).

## License

Desk is open-sourced software licensed under the [MIT license](LICENSE.md).

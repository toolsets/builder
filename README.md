## Laravel Builder
[![Build Status](https://travis-ci.org/toolsets/builder.svg?branch=develop)](https://travis-ci.org/toolsets/builder)


### A fluent application builder for Laravel 5.4 +

- This project is heavily under development and is not recommended for production use until a full stable release. 


#### Installation

- use composer command:
``` composer require toolsets/builder```
- after installing, register the service provider ```Toolsets\Builder\LaravelBuilderServiceProvider::class,``` on Laravel configs/app.php file
- Open the terminal go to your Laravel project root path, run artisan command ```php artisan builder:install``` (before running this command, make sure DB is configured in your Laravel app)
- now, run ```php artisan serve``` or go straight to your project web url and hit /builder that will open the builder app. Example http://localhost:8000/builder


## Contributing

Contributions to the Laravel Builder library are welcome. Please note the following guidelines before submiting your pull request.

- Follow [PSR-2](http://www.php-fig.org/psr/psr-2/) coding standards.
- Write tests for new functions and added features

## License

This Laravel Builder is open-source software licensed under the [MIT license](http://opensource.org/licenses/MIT)
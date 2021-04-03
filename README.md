<p style="text-align: center" align="center">
<img src="/assets/images/logo-fastdog-icon.png" alt="fast-dog-logo">
</p>

# Интерфейс администрирования для Laravel

[![Build Status](https://travis-ci.com/fast-dog/adm.svg?branch=master)](https://travis-ci.com/fast-dog/adm)
[![codecov](https://codecov.io/gh/fast-dog/adm/branch/master/graph/badge.svg?token=JRMM3HE1JW)](https://codecov.io/gh/fast-dog/adm)

## Использование

Добавить поставщика услуг (service provider) в `config/app.php`

``` php
   'providers' => [
    ...
    \FastDog\Adm\AdmServiceProvider::class
    ...
    ]
```

Выполнить консольную команду:

``` shell
   php artisan vendor:publish --provider="FastDog\Adm\AdmServiceProvider"
```
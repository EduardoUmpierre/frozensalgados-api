[![Build Status](https://travis-ci.org/EduardoUmpierre/frozensalgados-api.svg?branch=master)](https://travis-ci.org/EduardoUmpierre/frozensalgados-api) [![Coverage Status](https://coveralls.io/repos/github/EduardoUmpierre/frozensalgados-api/badge.svg?branch=master)](https://coveralls.io/github/EduardoUmpierre/frozensalgados-api?branch=master)

# Frozen Salgados

API RESTful para a [aplicação desenvolvida em Ionic 3][1].

## Como rodar a aplicação

Crie o banco de dados e instale as dependências do projeto, após isso crie as tabelas (migrate) e popule elas com dados fictícios (seed).
Por fim, rode o servidor local.

```
$ composer install
$ php artisan migrate
$ php artisan db:seed
$ php -S localhost:8000 -t public
```


[1]: https://github.com/EduardoUmpierre/frozensalgados

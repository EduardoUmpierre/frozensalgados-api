# Frozen Salgados

API RESTFull para a [aplicação desenvolvida em Ionic 3][1].

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
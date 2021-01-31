# StarWarsAPI

A responsive web-project created to consume SWAPI API.

  - Type some character on the navbar(any page) top and press [ENTER]
  - See suggestions
  - See the character's details
  - Add/remove from the team

### Installation

StarWarsAPI requires Symfony, Node.js and a Mysql database to run.

Install the dependencies and devDependencies and start the server.

```sh
$ cd starWarsAPI
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ npm run watch
$ npm run build
```



Put the database information in .ENV
```sh
DATABASE_URL="mysql://root:@127.0.0.1:3306/starwarslist?serverVersion=mariadb-10.4.11&charset=utf8"
````
# Api Framework#

Mini (micro quizas) framework para hacer APIs RESTful. Actualmente en desarrollo, utiliza php y apache.

## TODO ##

- [x] Retorno en formato JSON
- [x] Soporte para metodos GET
- [x] Soporte para metodos POST
- [ ] Soporte para metodos PUT
- [ ] Soporte para metodos DELETE
- [x] ORM basico *
- [ ] Agregar tests
- [ ] Agregar errores en caso de uri incorrecta

*Solo tiene soporte para `postgresql`*
## Modo de uso

Para poder usar el framework se debe clonar el repositorio

```
$ [sudo] git clone https://github.com/YerkoPalma/api-framework.git
```

Luego agregar archivos de acuerdo a la estructura de la API que se quiera crear.
Automaticamente se considera como *endpoint* el nombre de cada archivo `.php`, el cual debe contener, por lo menos una clase con el mismo nombre del archivo, dicha clase debe extender la clase `api` del framework.

```php
<?php
  require_once "lib/api.php";

  class posts extends api {
    /* ... */
  }
?>
```

Este archivo generará la siguiente uri para la api `www.domain.cl/api-folder/posts`
Aún no hay ni un endpoint para la api. Para crear un endpoint, simplemente se debe crear una funcion en la clase `posts` y ese será endpoint de la api.

```php
<?php
  require_once "lib/api.php";

  class posts extends api {

    function add() {
      /* .. */
    }

    function show($id){
      /* .. */
    }
  }
?>
```

Esto generará las siguientes uris para la api

```php
  www.domain.cl/api-folder/posts/add
  www.domain.cl/api-folder/posts/show/$id
```

Como se puede apreciar, si la funcion tiene parametros de entrada, estos serán los que se incluyan en la uri.

Además de lo anterior, la api posee ciertas uris predefinidas en que no se utilizá endpoint.

| Función  | Metodo | Uri |
| ------------- | ------------- | --- |
| index()  | GET  | /api-folder/filename |
| read($id)  | GET  | /api-folder/filename/$id |
| create()  | POST  | /api-folder/filename |
| update($id)  | PUT  | /api-folder/filename/$id |

## ORM ##

Dentro de cada funcion de la api que se implemente, se puede escribir cualquier código en php, sin embargo,
se implemento un ORM sencillo para conectarse con `postgresql` para usarlo se debe incluir en el archivo la clase `lib/api_db.php`

Para configurar el ORM se deben incluir en el archio `lib/db.php` los datos de la conexión que se usará.

Para usar el orm se debe utilizar la clase `model` que se obtiene de la clase `db`, de la siguiente manera

```php
<?php
  require_once "lib/api.php";

  class posts extends api {

    protected $db;

    function __construct($db) {
      parent::__construct();
      $this->db = $db;
    }

    function show($id){
      $model = $this->db->getModel("posts");
      /* .. */
    }
  }
?>
```

El ORM esta pensado para obtener un modelo por cada tabla de la base de datos, por lo tanto, se espera en el metodo `getModle()` el nombre de la tabla que se busca obtener. Actualmente existen algunos metodos como `select` o `insert` soportados por el ORM. En los archivos `usuario.php` y `mensaje.php` se puede ver algunos de sus usos.

## Contacto ##

Cualquier duda o consulta, por un issue de github :)

# OOP MVC Framework

*A small PHP framework for learning how MVC works beneath the surface.*

PHP · Composer · PSR-4 · PDO · MySQL

A hands-on learning project that explores how a PHP MVC framework works internally. It uses convention-based routing, native PHP views, Composer autoloading, and a lightweight PDO database layer.

The goal is to keep the implementation small and readable, making it easier to understand how HTTP requests move through the application and how its core components work together.

## Features

- Convention-based controller/action routing with positional URL parameters.
- Composer autoloading using PSR-4.
- Shared helper functions for files, views, and URLs.
- Native PHP views with reusable layout partials.
- PDO database access with prepared statements and named parameters.
- Reusable model operations: `all()`, `find()`, and `destroy()`.
- Explicit request dispatch through a front controller.

## Project Structure

```text
app/
├── Config/
│   └── config.php
├── Controllers/
│   ├── Controller.php
│   └── IndexController.php
├── Core/
│   ├── Database.php
│   └── Router.php
├── Helpers/
│   └── functions.php
├── Models/
│   ├── Model.php
│   └── User.php
├── Views/
│   ├── Layouts/
│   │   ├── header.php
│   │   └── footer.php
│   └── index.php
└── bootstrap.php
public/
├── .htaccess
└── index.php
composer.json
```

The application follows PSR-4 namespace conventions. For example, `App\Core\Router` maps to `app/Core/Router.php`. File and directory casing must match on case-sensitive filesystems.

## Request Lifecycle

```text
HTTP Request
     |
     v
public/index.php
     |
     v
app/bootstrap.php
     |
     v
Router::dispatch()
     |
     v
Controller Action
     |
     +------> Model / PDO
     |
     v
PHP View
     |
     v
HTTP Response
```

1. `public/index.php` acts as the application's HTTP entry point.
2. It loads `app/bootstrap.php`, which initializes Composer autoloading and application configuration.
3. The entry point creates a `Router` instance and calls `dispatch()`.
4. The router resolves the requested controller, action, and positional arguments.
5. The controller action handles the request, optionally interacting with models and rendering a view.

The separation between object creation and `dispatch()` keeps application initialization distinct from request handling.

## Autoloading and Configuration

Composer maps the `App\` namespace to the `app/` directory through PSR-4. It also loads `app/Helpers/functions.php` through the `autoload.files` configuration.

The bootstrap file loads Composer's autoloader first, then application configuration through `requireFromRoot()`. Configuration is currently defined as constants in `app/Config/config.php`, covering application metadata and database connection settings.

## Routing

The router reads the `url` query parameter, which Apache populates through rewrite rules.

Routes follow this convention:

```text
/controller/action/argument1/argument2
```

| Route | Resolved action |
|---|---|
| `/` | `IndexController::index()` |
| `/index` | `IndexController::index()` |
| `/index/index` | `IndexController::index()` |
| `/users/show/5` | `UsersController::show('5')` |

Controller names are resolved by capitalizing the controller segment and appending `Controller`. The router then checks for the corresponding controller file and action method, passing remaining URL segments as positional arguments.

The default controller and action are `IndexController` and `index`.

For example, a route such as `/users/show/5` can be handled by:

```php
<?php

namespace App\Controllers;

class UsersController extends Controller
{
    public function show(string $id): void
    {
        echo 'User ID: ' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8');
    }
}
```

## Views and Helpers

Views use native PHP and HTML. Render a view from a controller action:

```php
view('index');
```

Include a reusable partial:

```php
includeFromRoot('app/Views/Layouts/header.php');
```

Available helpers:

| Helper | Purpose |
|---|---|
| `requireFromRoot($path)` | Loads a project-relative file once. |
| `includeFromRoot($path)` | Includes a project-relative file. |
| `fileExistsFromRoot($path)` | Checks whether a project-relative path exists. |
| `view($name)` | Renders a view from `app/Views/`. |
| `getUrl()` | Returns URL segments as an array. |

File helpers resolve paths relative to the project root, while `APP_ROOT` points directly to the `app/` directory.

Pass trusted application paths to file helpers and escape user-provided output with `htmlspecialchars()` when rendering HTML.

## Database and Models

The `Database` class manages a PDO connection to MySQL using `utf8mb4`. It provides prepared statement execution and result fetching.

The base `Model` class extends `Database` and provides reusable database operations. Concrete models define their corresponding table:

```php
<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';
}
```

### Model Operations

```php
use App\Models\User;

$users = new User();

$allUsers = $users->all();
$user = $users->find(5);
$deleted = $users->destroy(5);
```

- `all()` retrieves all records as objects.
- `find($id)` retrieves a record using the configured primary key.
- `destroy($id)` deletes a record and returns the affected-row count.

The primary key defaults to `id` and can be overridden in individual models.

### Custom Queries

Models also support custom SQL queries:

```php
$user = $users
    ->query('SELECT * FROM users WHERE email = :email')
    ->getFetch(['email' => 'person@example.com']);
```

| Method | Purpose |
|---|---|
| `query($sql)` | Prepares a SQL statement. |
| `execute($params)` | Executes the prepared statement. |
| `get($params, $fetchType)` | Fetches all matching rows. |
| `getFetch($params, $fetchType)` | Fetches a single row. |
| `rowCount()` | Returns the affected-row count. |

Queries use named parameters for values, keeping user-provided data separate from SQL statements. Table and column identifiers are defined by the application.

## Project Scope

This is a learning-oriented project, not a production-ready replacement for established PHP frameworks.

Its small components are intentionally kept explicit and independent, providing a foundation for experimenting with MVC architecture, routing, autoloading, view rendering, and database access.
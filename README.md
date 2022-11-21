# CodeIgniter 4 Task

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings.

Please take care to setup the database credentials and the email server configuration.

Run ```composer install``` on the terminal to install the composer dependencies.

For development purposes a dev email server can serve to debug emails. Please see about installing [Mailcatcher](https://mailcatcher.me/).

To start the chat websocket server:
```shell
cd <PROJECT>/public
php index.php chat
```

For images to show correctly the folder `<PROJECT>/writable/uploads` should have a symlink/shortcut under the public folder.

```shell
ln -s $(pwd)/writable/uploads $(pwd)/public
```

Don not close that window. Import the database  file `database.sql` into your app database.
The accounts from the dump should have their passwords set to `password`.
 
## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)

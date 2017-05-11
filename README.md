# MvcCore Extension - Request - Apache DPI

[![Latest Stable Version](https://img.shields.io/badge/Stable-v4.1.0-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-request-apachedpi/releases)
[![License](https://img.shields.io/badge/Licence-BSD-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.3-brightgreen.svg?style=plastic)

MvcCore Request extension to correct request BasePath property  - for applications using [**Apache .htaccess [DPI] flag**](https://httpd.apache.org/docs/trunk/rewrite/flags.html#flag_dpi).

## Installation
```shell
composer require mvccore/ext-request-apachedpi
```

## Usage
I you are using more PHP applications with more different domains in one webhosting space,
you always need to route every request into different index .htaccess file.

### .htaccess in webhosting root:
```apache
# disable directory listing
Options -Indexes
# mod_rewrite
<IfModule mod_rewrite.c>
	RewriteEngine On
	# If directory with HTTP_HOST name exists in domains directory, 
	# dispatch request into this webhosting directory with requested application:
	RewriteCond %{HTTP_HOST} ^(.*)$
	RewriteCond %{DOCUMENT_ROOT}/domains/%1 -d
	RewriteRule (.*) domains/%1/$1 [DPI,L]
</IfModule>
```

### .htaccess in application root (classic):
```apache
# disable directory listing
Options -Indexes
# mod_rewrite
<IfModule mod_rewrite.c>
	RewriteEngine On
	# forbid the direct access to app directories (eg. config-files, ...)
	RewriteRule ^App/.*$ / [F,L]
	RewriteRule ^vendor/.*$ / [F,L]
	# basic zend-framework setup see: http://framework.zend.com/manual/en/zend.controller.html
	RewriteCond %{REQUEST_FILENAME} -s [OR]
	RewriteCond %{REQUEST_FILENAME} -l [OR]
	RewriteCond %{REQUEST_FILENAME} -f
	RewriteRule ^(.*)$ $1 [NC,L]
	RewriteRule ^.*$ index.php [NC,L]
</IfModule>
```

### PHP application Bootstrap.php
Put this patching code in very beginning of your application:
```php
\MvcCore::GetInstance()->SetRequestClass(\MvcCore\Ext\Request\ApacheDpi::class);
```

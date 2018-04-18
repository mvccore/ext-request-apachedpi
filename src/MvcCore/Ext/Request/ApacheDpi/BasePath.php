<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Request\ApacheDpi;

/**
 * This trait is always used for request, redirected by Apache
 * `.htaccess` file from one to another `.htaccess` file with `[DPI]` flag. 
 * Use this trat for request class to complete `\MvcCore\Request::$basePath` property correctly.
 */
trait BasePath
{	
	/**
	 * If there is used any Apache `[DPI]` flag anywhere in `.htaccess` files(s)
	 * (`[DPI]` means "discard path" to dispatch Apache request by another `.htaccess` file),
	 * then there is necessary to complete request `\MvcCore\Request::$basePath` 
	 * property more deeply with helping `.htaccess` variable bellow:
	 *
	 * It's necessary to complete this variable in `.htaccess` in redirection line by something like:
	 * RewriteCond %{HTTP_HOST}%{REQUEST_URI} ^([^/]*)/(.*)$
	 * RewriteCond %{DOCUMENT_ROOT}/domains/%1/%2 -d
	 * RewriteRule (.*) %{ENV:HTTP_PROTOCOL}//%1/%2/ [R=301,QSA,L,NE,E=REDIRECT_PATH:/domains/%1]
	 *
	 * @see http://httpd.apache.org/docs/current/rewrite/flags.html#flag_dpi
	 * @return void
	 */
	public function GetBasePath () {
		if ($this->basePath === NULL) {
			$basePath = parent::GetBasePath();
			$currentKey = 'REDIRECT_PATH';
			$server = $this->globalServer;
			$i = 0;
			while ($i < 5) {
				if (isset($server[$currentKey])) {
					$redirectPath = $server[$currentKey];
					if (mb_strpos($basePath, $redirectPath) === 0)
						$basePath = mb_substr($basePath, mb_strlen($redirectPath));
					break;
				} else {
					$currentKey = 'REDIRECT_' . $currentKey;
				}
				$i += 1;
			}
			$this->basePath = $basePath;
		}
		return $this->basePath;
	}
}

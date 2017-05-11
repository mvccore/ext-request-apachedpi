<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/4.0.0/LICENCE.md
 */

namespace MvcCore\Ext\Request;

class ApacheDpi extends \MvcCore\Request
{
	/**
	 * MvcCore Extension - Request ApacheDpi - version:
	 * Comparation by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '4.1.0';
	/**
	 * If there is used somewhere in .htaccess files structure any [DPI] flag
	 * end request is dispatched to another .htaccess with "discard path",
	 * there is necessary to complete request base path property more deeply.
	 * @see http://httpd.apache.org/docs/current/rewrite/flags.html#flag_dpi
	 * @return void
	 */
	protected function initBasePath () {
		parent::initBasePath();
		/**
		 * It's necessary to complete this variable in .htaccess in redirection line by something like:
		 * RewriteCond %{HTTP_HOST}%{REQUEST_URI} ^([^/]*)x/(.*)([^/])$
		 * RewriteCond %{DOCUMENT_ROOT}/domains/%1/%2%3 -d
		 * RewriteRule (.*) %{ENV:HTTP_PROTOCOL}//%1/%2%3/ [R=301,QSA,L,NE,E=REDIRECT_PATH:/domains/%1]
		 */
		$currentKey = 'REDIRECT_PATH';
		$i = 0;
		while ($i < 5) {
			if (isset($this->serverGlobals[$currentKey])) {
				$redirectPath = $this->serverGlobals[$currentKey];
				if (mb_strpos($this->BasePath, $redirectPath) === 0) {
					$this->BasePath = mb_substr($this->BasePath, mb_strlen($redirectPath));
				}
				break;
			} else {
				$currentKey = 'REDIRECT_' . $currentKey;
			}
			$i += 1;
		}
	}
}
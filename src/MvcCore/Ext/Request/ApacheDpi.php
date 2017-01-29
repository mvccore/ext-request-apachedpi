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
	const VERSION = '4.0.0';
	/**
	 * If there is used somewhere in .htaccess files structure any [DPI] flag
	 * end request is dispatched to another .htaccess with "discard path",
	 * there is necessary to complete request base path property more deeply.
	 * @see http://httpd.apache.org/docs/current/rewrite/flags.html#flag_dpi
	 * @return void
	 */
	protected function initBasePath () {
		parent::initBasePath();
		if (isset($this->serverGlobals['REDIRECT_URL'])) {
			// fill 'REDIRECT_URL' and 'REQUEST_URI':
			// '/domains/www.mydomain.com/requested/app/path' and '/requested/app/path'
			$redUrl = $this->serverGlobals['REDIRECT_URL'];
			$reqUrl = $this->serverGlobals['REQUEST_URI'];
			// remove possible query string from REQUEST_URI
			$reqUrlQPos = mb_strpos($reqUrl, '?');
			if ($reqUrlQPos !== FALSE) $reqUrl = mb_substr($reqUrl, 0, $reqUrlQPos);
			// check if 'REDIRECT_URL' contains 'REQUEST_URI' at the very end
			$redUrlContainsReqUrlAtTheEnd = mb_strpos($redUrl, $reqUrl) === mb_strlen($redUrl) - mb_strlen($reqUrl);
			if (!$redUrlContainsReqUrlAtTheEnd) {
				// if not and in 'REDIRECT_URL' is at the end '/index.php' - fix it back
				$redUrlWithoutIndexPhpLength = mb_strlen($redUrl) - mb_strlen($this->ScriptName);
				$redUrlScriptNameAtTheEnd = mb_strpos($redUrl, $this->ScriptName) === $redUrlWithoutIndexPhpLength;
				if ($redUrlScriptNameAtTheEnd) {
					$redUrl = mb_substr($redUrl, 0, $redUrlWithoutIndexPhpLength) . $reqUrl;
				}
			}
			// remove all 'REQUEST_URI' characters from the very end of 'REDIRECT_URL'
			$diffUrl = mb_substr($redUrl, 0, mb_strlen($redUrl) - mb_strlen($reqUrl));
			// if the rested string length is bigger than zero - fix base path 
			// remove from BasePath a sub path - the sub path between original apache document root
			// and current application root, witch is cause by Apache .htaccess [DPI] flag (discard path)
			$diffUrlLen = mb_strlen($diffUrl);
			if ($diffUrlLen > 0) {
				$this->BasePath = mb_substr($this->BasePath, $diffUrlLen);
			}
		}
	}
}
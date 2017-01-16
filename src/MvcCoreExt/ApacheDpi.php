<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view 
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flídr (https://github.com/mvccore/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/3.0.0/LICENCE.md
 */

class MvcCoreExt_ApacheDpi extends MvcCore_Request
{
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
			$redUrl = $this->serverGlobals['REDIRECT_URL'];
			$reqUrl = $this->serverGlobals['REQUEST_URI'];
			$reqUrlQPos = mb_strpos($reqUrl, '?');
			if ($reqUrlQPos !== FALSE) $reqUrl = mb_substr($reqUrl, 0, $reqUrlQPos);
			$diffUrl = mb_substr($redUrl, 0, mb_strlen($redUrl) - mb_strlen($reqUrl));
			$diffUrlLen = mb_strlen($diffUrl);
			if ($diffUrlLen > 0) {
				$this->BasePath = mb_substr($this->BasePath, $diffUrlLen);
			}
		}
	}
}
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

/**
 * This trait is always used for request, redirected by Apache
 * `.htaccess` file from one to another `.htaccess` file with `[DPI]` flag. 
 * Use this trat for request class to complete `\MvcCore\Request::$basePath` property correctly.
 */
class ApacheDpi extends \MvcCore\Request
{
	/**
	 * MvcCore Extension - Request ApacheDpi - version:
	 * Comparation by PHP function version_compare();
	 * @see http://php.net/manual/en/function.version-compare.php
	 */
	const VERSION = '5.0.0-alpha';
	
	use \MvcCore\Ext\Request\ApacheDpi\BasePath;
}
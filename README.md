# BCA-PHP-CURL

Work with remote servers via cURL much easier than using the native PHP bindings.

[![Build Status](https://secure.travis-ci.org/brodkinca/BCA-PHP-CURL.png)](http://travis-ci.org/brodkinca/BCA-PHP-CURL)

## Requirements

1. PHP 5.3+
2. libcurl

## Features

* POST/GET/PUT/DELETE requests over HTTP
* HTTP Authentication
* Follows redirects
* Returns error string
* Provides debug information
* Cookies

## Install

### Using Composer

Just add the following to the require section your composer.json file:

```
"bca/curl": "2.*"
```

Then execute `composer install` to pull down the latest release.

Package details can be found at https://packagist.org/packages/bca/curl.

### Manually via Github

You may download a specific version from https://github.com/brodkinca/BCA-PHP-CURL/tags or visit the main repository at https://github.com/brodkinca/BCA-PHP-CURL/tree/master to download unreleased code or pull down a copy via git.

## Versioning

This library will be maintained under the Semantic Versioning guidelines.

Releases will be numbered with the following format:

```
<major>.<minor>.<patch>
```

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bump the patch

Composer users who would like more granular control over upgrades should restrict their installtion to patch updates only using this require key:

```
"bca/curl": "2.1.*"
```

For more information on SemVer, please visit http://semver.org/.

## Examples

Simple requests can be constructed with just a URL and a method.
```php
$request = new CURL('http://example.com/');
$response = $request->get();
```
More complex requests build upon that concept by adding methods to the request.
```php
$request = new CURL('http://example.com/');
$response = $request
	->param('aaa', 'bbb')
	->param('xxx', 'yyy')
	->post();

echo $response;
```
Advanced requests can be built by adding even more methods.
```php
$request = new CURL('http://example.com/');
$response = $request
    ->param('aaa', 'bbb')
    ->param('xxx', 'yyy')
    ->option(CURLOPT_PROXY, '10.0.0.1')
    ->auth('username', 'password', 'digest')
    ->delete();

echo $response;
```

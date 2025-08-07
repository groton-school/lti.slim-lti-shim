# groton-school/slim-lti-shim

Shim to use `packbackbooks/lti-1p3-tool` in a Slim Skeleton app

[![Latest Version](https://img.shields.io/packagist/v/groton-school/slim-lti-shim.svg)](https://packagist.org/packages/groton-school/slim-lti-shim)

## Install

```bash
composer require groton-school/slim-lti-shim
```

## Use

1. Inject your LTI Tool configuration into settings:

   a. [Implement `SettingsInterface`](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/src/Application/Settings/SettingsInterface.php#L13)

   b. [Define the `SettingsInterface` dependency](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/app/dependencies.php#L31)

   c. [Inject the required values into your settings](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/app/settings.php#L27-L74)

2. [Define OIDC launch routes](https://github.com/groton-school/slim-skeleton/blob/0b32f964d753376ed2c2d9af4460e96342bbe919/app/routes.php#L18-L20) (note that the route group is returned by `RouteBuilder::define()` which allows you to add any necessary middleware);

3. Implement (or use an [existing implementation](https://github.com/groton-school/slim-lti-infrastructure-gae#readme) of) `CacheInterface` and `DatabaseInterface`

### groton-school/slim-skeleton@dev-lti/gae

[groton-school/slim-skeleton](https://github.com/groton-school/slim-skeleton/tree/lti/gae) is the canonical example of how this shim is meant to be used.

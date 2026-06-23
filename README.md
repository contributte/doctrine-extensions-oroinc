![](https://heatbadger.now.sh/github/readme/contributte/doctrine-extensions-oroinc/)

<p align=center>
  <a href="https://github.com/contributte/doctrine-extensions-oroinc/actions"><img src="https://badgen.net/github/checks/nettrine/extensions-oroinc/master?cache=300"></a>
  <a href="https://codecov.io/gh/contributte/doctrine-extensions-oroinc"><img src="https://badgen.net/codecov/c/github/contributte/doctrine-extensions-oroinc?cache=300"></a>
  <a href="https://packagist.org/packages/nettrine/extensions-oroinc"><img src="https://badgen.net/packagist/dm/nettrine/extensions-oroinc"></a>
  <a href="https://packagist.org/packages/nettrine/extensions-oroinc"><img src="https://badgen.net/packagist/v/nettrine/extensions-oroinc"></a>
</p>
<p align=center>
  <a href="https://packagist.org/packages/nettrine/extensions-oroinc"><img src="https://badgen.net/packagist/php/nettrine/extensions-oroinc"></a>
  <a href="https://github.com/contributte/doctrine-extensions-oroinc"><img src="https://badgen.net/github/license/contributte/doctrine-extensions-oroinc"></a>
  <a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
  <a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
  <a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Doctrine extension for Nette Framework integrating [Oroinc DoctrineExtensions](https://github.com/oroinc/doctrine-extensions) custom DQL functions and DBAL field types.

## Versions

| State       | Version | Branch   | Nette | PHP     |
|-------------|---------|----------|-------|---------|
| dev         | `^0.4`  | `master` | 3.2+  | `>=8.2` |
| stable      | `^0.3`  | `master` | 3.2+  | `>=8.2` |

## Installation

To install latest version of `nettrine/extensions-oroinc` use [Composer](https://getcomposer.org).

```bash
composer require nettrine/extensions-oroinc
```

## Usage

Register extension in your NEON configuration.

```yaml
extensions:
    nettrine.extensions.oroinc: Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension
```

## Configuration

Specify the same driver as for the Doctrine DBAL connection, all of [Oroinc DoctrineExtensions](https://github.com/oroinc/doctrine-extensions) custom DQL functions for the given driver will be registered.

```yaml
nettrine.extensions.oroinc:
    driver: mysql
    # mysql - 'mysql', 'mysql2', 'pdo_mysql'
    # postgre - 'pgsql', 'postgres', 'postgresql', 'pdo_pgsql'
```

`MoneyType` and `PercentType` are always registered to your `Connection`.

## Development

See [how to contribute](https://contributte.org) to this package. This package is currently maintained by these authors.

<a href="https://github.com/f3l1x">
    <img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?v=3&s=80">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for using this package.

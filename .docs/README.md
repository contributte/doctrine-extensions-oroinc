# Nettrine Extensions Oroinc

## Content

Doctrine ([Oroinc/DoctrineExtensions](https://github.com/oroinc/doctrine-extensions)) extension for Nette Framework

- [Setup](#setup)
- [Configuration](#configuration)

## Setup

Install package

```bash
composer require nettrine/extensions-oroinc
```

Register extension

```yaml
extensions:
    nettrine.extensions.oroinc: Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension
```

## Configuration

Specify the same driver as for the Doctrine DBAL connection, all of [Oroinc/DoctrineExtensions](https://github.com/oroinc/doctrine-extensions) custom DQL functions for the given driver will be registered.

```yaml
nettrine.extensions.oroinc:
    driver: mysql
    # mysql - 'mysql', 'mysql2', 'pdo_mysql'
    # postgre - 'pgsql', 'postgres', 'postgresql', 'pdo_pgsql'
```

[Field types](https://github.com/oroinc/doctrine-extensions#field-types) `MoneyType`, `PercentType`, `ObjectType` and `ArrayType` are always registered to your `Connection`.

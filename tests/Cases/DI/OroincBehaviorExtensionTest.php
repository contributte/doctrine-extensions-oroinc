<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension;
use ReflectionClass;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

Toolkit::setUp(static function (): void {
	$rc = new ReflectionClass(Type::class);
	$rc->setStaticPropertyValue('typeRegistry', null);
});

// Nothing
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Container::class));
});

// MySQL
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig([
				'services' => [
					Configuration::class,
				],
				'nettrine.extensions.oroinc' => [
					'driver' => 'pdo_mysql',
				],
			]);
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Configuration::class));
});

// PostgreSQL
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig([
				'services' => [
					Configuration::class,
				],
				'nettrine.extensions.oroinc' => [
					'driver' => 'pdo_pgsql',
				],
			]);
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Configuration::class));
});

// Types
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				services:
					- Doctrine\DBAL\Connection(
						[],
						Doctrine\DBAL\Driver\SQLite3\Driver()
					)
			NEON
			));
		})->build();

	$container->initialize();
	Assert::notNull($container->getByType(Connection::class));
});

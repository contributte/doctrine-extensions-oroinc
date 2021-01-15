<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Doctrine\ORM\Configuration;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension;
use Tester\Assert;
use Tester\TestCase;
use Tests\Toolkit\Tests;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class OroincBehaviorExtensionTest extends TestCase
{

	public function testNothing(): void
	{
		$loader = new ContainerLoader(Tests::TEMP_PATH, true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addDependencies([__FILE__]);
		}, __METHOD__);

		$container = new $class();
		Assert::type(Container::class, $container);
		$container->initialize();
	}

	public function testMysql(): void
	{
		$loader = new ContainerLoader(Tests::TEMP_PATH, true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addDependencies([__FILE__]);
			$compiler->addConfig([
				'services' => [
					Configuration::class,
				],
				'nettrine.extensions.oroinc' => [
					'driver' => 'pdo_mysql',
				],
			]);
		}, __METHOD__);

		$container = new $class();
		Assert::type(Container::class, $container);
		$container->initialize();

		$container->getByType(Configuration::class);
	}

	public function testPostgre(): void
	{
		$loader = new ContainerLoader(Tests::TEMP_PATH, true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addDependencies([__FILE__]);
			$compiler->addConfig([
				'services' => [
					Configuration::class,
				],
				'nettrine.extensions.oroinc' => [
					'driver' => 'pdo_pgsql',
				],
			]);
		}, __METHOD__);

		$container = new $class();
		Assert::type(Container::class, $container);
		$container->initialize();

		$container->getByType(Configuration::class);
	}

	public function testTypes(): void
	{
		$loader = new ContainerLoader(Tests::TEMP_PATH, true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addDependencies([__FILE__]);
			$compiler->addConfig([
				'services' => [
					Driver::class,
					[
						'factory' => Connection::class,
						'arguments' => [
							[],
						],
					],
				],
			]);
		}, __METHOD__);

		$container = new $class();
		Assert::type(Container::class, $container);
		$container->initialize();

		$container->getByType(Connection::class);
	}

}

(new OroincBehaviorExtensionTest())->run();

<?php declare(strict_types = 1);

namespace Tests\Nettrine\Extensions\Oroinc\Unit\DI;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOSqlite\Driver;
use Doctrine\ORM\Configuration;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
final class OroincBehaviorExtensionTest extends TestCase
{

	/**
	 * @doesNotPerformAssertions
	 */
	public function testNothing(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
		$class = $loader->load(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addDependencies([__FILE__]);
		}, __METHOD__);

		$container = new $class();
		assert($container instanceof Container);
		$container->initialize();
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testMysql(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
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
		assert($container instanceof Container);
		$container->initialize();

		$container->getByType(Configuration::class);
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testPostgre(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
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
		assert($container instanceof Container);
		$container->initialize();

		$container->getByType(Configuration::class);
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testTypes(): void
	{
		$loader = new ContainerLoader(__DIR__ . '/../../tmp', true);
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
		assert($container instanceof Container);
		$container->initialize();

		$container->getByType(Connection::class);
	}

}

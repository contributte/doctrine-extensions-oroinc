<?php declare(strict_types = 1);

namespace Tests\Cases\E2E;

use Contributte\Tester\Toolkit;
use Contributte\Tester\Utils\ContainerBuilder;
use Contributte\Tester\Utils\Neonkit;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Nette\DI\Compiler;
use Nettrine\Extensions\Oroinc\DI\OroincBehaviorExtension;
use Oro\DBAL\Types\MoneyType;
use Oro\DBAL\Types\PercentType;
use Oro\ORM\Query\AST\Functions\Cast;
use Oro\ORM\Query\AST\Functions\DateTime\ConvertTz;
use Oro\ORM\Query\AST\Functions\Numeric\Pow;
use Oro\ORM\Query\AST\Functions\Numeric\Round;
use Oro\ORM\Query\AST\Functions\Numeric\Sign;
use Oro\ORM\Query\AST\Functions\Numeric\TimestampDiff;
use Oro\ORM\Query\AST\Functions\SimpleFunction;
use Oro\ORM\Query\AST\Functions\String\ConcatWs;
use Oro\ORM\Query\AST\Functions\String\DateFormat;
use Oro\ORM\Query\AST\Functions\String\GroupConcat;
use Oro\ORM\Query\AST\Functions\String\Replace;
use ReflectionClass;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

Toolkit::setUp(static function (): void {
	$rc = new ReflectionClass(Type::class);
	$rc->setStaticPropertyValue('typeRegistry', null);
});

// Test: Custom DQL functions are registered for MySQL
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				services:
					- Doctrine\ORM\Configuration
				nettrine.extensions.oroinc:
					driver: pdo_mysql
			NEON
			));
		})
		->build();

	$container->initialize();

	/** @var Configuration $configuration */
	$configuration = $container->getByType(Configuration::class);

	// Verify datetime functions
	Assert::same(SimpleFunction::class, $configuration->getCustomDatetimeFunction('date'));
	Assert::same(SimpleFunction::class, $configuration->getCustomDatetimeFunction('time'));
	Assert::same(SimpleFunction::class, $configuration->getCustomDatetimeFunction('timestamp'));
	Assert::same(ConvertTz::class, $configuration->getCustomDatetimeFunction('convert_tz'));

	// Verify numeric functions
	Assert::same(TimestampDiff::class, $configuration->getCustomNumericFunction('timestampdiff'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('dayofyear'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('dayofmonth'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('dayofweek'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('week'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('day'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('hour'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('minute'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('month'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('quarter'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('second'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('year'));
	Assert::same(Sign::class, $configuration->getCustomNumericFunction('sign'));
	Assert::same(Pow::class, $configuration->getCustomNumericFunction('pow'));
	Assert::same(Round::class, $configuration->getCustomNumericFunction('round'));
	Assert::same(SimpleFunction::class, $configuration->getCustomNumericFunction('ceil'));

	// Verify string functions
	Assert::same(SimpleFunction::class, $configuration->getCustomStringFunction('md5'));
	Assert::same(GroupConcat::class, $configuration->getCustomStringFunction('group_concat'));
	Assert::same(ConcatWs::class, $configuration->getCustomStringFunction('concat_ws'));
	Assert::same(Cast::class, $configuration->getCustomStringFunction('cast'));
	Assert::same(Replace::class, $configuration->getCustomStringFunction('replace'));
	Assert::same(DateFormat::class, $configuration->getCustomStringFunction('date_format'));
});

// Test: Custom DQL functions are registered for PostgreSQL
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				services:
					- Doctrine\ORM\Configuration
				nettrine.extensions.oroinc:
					driver: pdo_pgsql
			NEON
			));
		})
		->build();

	$container->initialize();

	/** @var Configuration $configuration */
	$configuration = $container->getByType(Configuration::class);

	// Verify all functions are registered for PostgreSQL too
	Assert::same(SimpleFunction::class, $configuration->getCustomDatetimeFunction('date'));
	Assert::same(Round::class, $configuration->getCustomNumericFunction('round'));
	Assert::same(Cast::class, $configuration->getCustomStringFunction('cast'));
});

// Test: Custom types are registered
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
		})
		->build();

	$container->initialize();

	// Verify custom types are registered
	Assert::true(Type::hasType(MoneyType::TYPE));
	Assert::true(Type::hasType(PercentType::TYPE));
	Assert::type(MoneyType::class, Type::getType(MoneyType::TYPE));
	Assert::type(PercentType::class, Type::getType(PercentType::TYPE));
});

// Test: No functions registered without driver
Toolkit::test(static function (): void {
	$container = ContainerBuilder::of()
		->withCompiler(static function (Compiler $compiler): void {
			$compiler->addExtension('nettrine.extensions.oroinc', new OroincBehaviorExtension());
			$compiler->addConfig(Neonkit::load(<<<'NEON'
				services:
					- Doctrine\ORM\Configuration
			NEON
			));
		})
		->build();

	$container->initialize();

	/** @var Configuration $configuration */
	$configuration = $container->getByType(Configuration::class);

	// Without driver, no functions should be registered
	Assert::null($configuration->getCustomDatetimeFunction('date'));
	Assert::null($configuration->getCustomNumericFunction('round'));
	Assert::null($configuration->getCustomStringFunction('cast'));
});

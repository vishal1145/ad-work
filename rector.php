<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Laravel\Set\LaravelSetList;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

// rector process app

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74); // PhpVersion::PHP_74

    $parameters->set(Option::AUTOLOAD_PATHS, [__DIR__ . '/vendor/autoload.php']);

    $parameters->set(Option::SKIP, [
        // Rectors
        Rector\Naming\Rector\Property\UnderscoreToCamelCasePropertyNameRector::class,
        Rector\Naming\Rector\Variable\UnderscoreToCamelCaseVariableNameRector::class,
        Rector\Php70\Rector\FuncCall\NonVariableToVariableOnFunctionCallRector::class,
        Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector::class,
        Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector::class,
        Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector::class,
        Rector\Php74\Rector\Property\TypedPropertyRector::class,
        Rector\TypeDeclaration\Rector\FunctionLike\ParamTypeDeclarationRector::class,
		Rector\Transform\Rector\String_\StringToClassConstantRector::class,
        // PHP 5.6 incompatible
        Rector\Php70\Rector\If_\IfToSpaceshipRector::class,
        Rector\Php70\Rector\Ternary\TernaryToSpaceshipRector::class,
        Rector\Php71\Rector\BinaryOp\IsIterableRector::class,
        Rector\Php71\Rector\List_\ListToArrayDestructRector::class,
        Rector\Php71\Rector\TryCatch\MultiExceptionCatchRector::class,
        Rector\Php73\Rector\FuncCall\ArrayKeyFirstLastRector::class,
        Rector\Php73\Rector\BinaryOp\IsCountableRector::class,
        Rector\Php74\Rector\Assign\NullCoalescingOperatorRector::class,
        Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class,
        Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector::class,
        Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector::class,
        Rector\Php74\Rector\MethodCall\ChangeReflectionTypeToStringToGetNameRector::class,
        Rector\Php74\Rector\StaticCall\ExportToReflectionFunctionRector::class,
        // Paths
        'app\Console\Kernel.php',
        'app\Exceptions\Handler.php',
        'app\Http\Kernel.php',
    ]);

    $parameters->set(Option::PATHS, [
        'app\Auth',
        'app\Console',
        'app\Events',
        'app\Exceptions',
        'app\Helpers',
        'app\Http',
        'app\Listeners',
        'app\Mail',
        'app\Models',
        'app\Notifications',
        'app\Observers',
        'app\Providers',
        'app\Repositories',
        'app\Sanitizers',
        'app\Services',
        'app\Validators',
    ]);

    $containerConfigurator->import(LaravelSetList::LARAVEL_50);
    $containerConfigurator->import(LaravelSetList::LARAVEL_51);
    $containerConfigurator->import(LaravelSetList::LARAVEL_52);
    $containerConfigurator->import(LaravelSetList::LARAVEL_53);
    $containerConfigurator->import(LaravelSetList::LARAVEL_54);
    $containerConfigurator->import(SetList::PHP_70);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::PHP_73);
    $containerConfigurator->import(SetList::PHP_74);

    $services = $containerConfigurator->services();

    $services->set(TernaryToElvisRector::class);
};
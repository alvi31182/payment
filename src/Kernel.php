<?php

declare(strict_types=1);

namespace App;

use App\Payment\Model\Event\AsyncEventHandler;
use App\Payment\Model\Event\AsyncListenerInterface;
use App\Payment\Model\Event\AsyncMessageHelper;
use App\Payment\Model\Event\DomainEventCompilerPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
   // private AsyncMessageHelper $asyncMessageHelper;
    public function __construct(
        string $environment,
        bool $debug
    )
    {
        parent::__construct($environment, $debug);
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(pass: new DomainEventCompilerPass());
        $container->registerForAutoconfiguration(
            interface: AsyncListenerInterface::class
        )->addTag(name: 'async.handler');

        $container->registerAttributeForAutoconfiguration(
            attributeClass: AsyncEventHandler::class,
            configurator: static function (
                ChildDefinition $definition,
                AsyncEventHandler $attribute,
                \ReflectionClass $reflector
            ) {
                $className = $reflector->getName();

                $reflectionClassHandler = new \ReflectionClass($className);

                $method = $reflectionClassHandler->getMethods(filter: 1);

                foreach ($method as $reflectionMethod){

                }
            }
        );
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } elseif (is_file($path = dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }
}

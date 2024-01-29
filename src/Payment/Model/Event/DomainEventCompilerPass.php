<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;


use PhpParser\Node\Scalar\String_;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Exception\RuntimeException;

use function dump_node;

final class DomainEventCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $asyncBusIds = [];
        $defin = new Definition();
        $defin->setDecoratedService('react');
       // $container->register(id: 'async.bus');
        //  $container->addDefinitions(definitions: [$defin]);
        foreach ($container->findTaggedServiceIds(name: 'async.bus') as $serviceId => $tags ){



//            if ($container->hasDefinition(id: 'async.service_locator')){
//                dd($tags);
//            }


            foreach ($tags as $tag){
               // dd($tag);
                if (isset($tag['bus']) && !\in_array($tag['bus'], $tags, true)) {
                    throw new RuntimeException(sprintf('Invalid handler service "%s": bus "%s" specified on the tag "messenger.message_handler" does not exist (known ones are: "%s").', $serviceId, $tag['bus'], implode('", "', $busIds)));
                }

                $classNmae = $this->getClassDefinition(container: $container,serviceId: $serviceId);

                $reflectionClassFromDefinition = $container->getReflectionClass(class: $classNmae);

//                if (isset($tag['handlers'])){
//                    $handles = isset($tag['method']) ? [$tag['handles'] => $tag['method']] : [$tag['handles']];
//                } else {
//                    $handles = $this->guessHandledClasses($reflectionClassFromDefinition, $serviceId, $tag['method'] ?? '__invoke');
//                }
            }

//
//            foreach ($tags as $tag){
//                dd($tag);
//            }
        }

       // dd($tags);
//
//        $tagsIds = [];
//
//        foreach ($tags as $tag) {
//        }

        //$this->registerAsyncMessageService();
    }

    private function registerReceivers(ContainerBuilder $container, array $busIds): void
    {

    }

    public function registerAsyncMessageService(ContainerBuilder $container, array $tagsIds): void
    {
        //foreach ($container->findTaggedServiceIds(name: '')){}
    }

    private function guessHandledClasses(\ReflectionClass $handlerClass, string $serviceId, string $methodName): iterable
    {
        try {
            $method = $handlerClass->getMethod($methodName);
        } catch (\ReflectionException) {
            throw new RuntimeException(sprintf('Invalid handler service "%s": class "%s" must have an "%s()" method.', $serviceId, $handlerClass->getName(), $methodName));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $serviceId
     *
     * @return string
     */
    private function getClassDefinition(ContainerBuilder $container, string $serviceId): string
    {
        while (true) {
            $definition = $container->findDefinition($serviceId);

            if (!$definition->getClass() && $definition instanceof ChildDefinition) {
                $serviceId = $definition->getParent();

                continue;
            }

            return $definition->getClass();
        }
    }
}
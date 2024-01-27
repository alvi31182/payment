<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;


use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class DomainEventCompilerPass implements CompilerPassInterface
{
    private ?AsyncMessageHelper $asyncMessageHelper;

    public function __construct(AsyncMessageHelper $asyncMessageHelper = null)
    {
        $this->asyncMessageHelper = $asyncMessageHelper ?? new AsyncMessageHelper(
            phpAttributeGroupFactory: new PhpAttributeGroupFactory(
                ''
            )
        );
    }

    public function process(ContainerBuilder $container): void
    {
        $asyncBusIds = [];
        $defin = new Definition();
        $defin->setDecoratedService('react');

        //  $container->addDefinitions(definitions: [$defin]);
        $tags = $container->findTaggedServiceIds(name: 'async.message_handler');

        $tagsIds = [];
        dd($this->asyncMessageHelper);
        foreach ($tags as $tag) {
        }
    }

    public function registerAsyncMessageService(ContainerBuilder $container, array $tagsIds): void
    {
        //foreach ($container->findTaggedServiceIds(name: '')){}
    }
}
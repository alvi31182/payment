<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\PhpAttribute\AttributeArrayNameInliner;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\Symfony\DataProvider\ServiceMapProvider;
use Rector\Symfony\ValueObject\ServiceDefinition;

final class AsyncMessageHelper
{
    public const ASYNC_MESSAGE_HANDLER_ATTRIBUTE = '\\App\\Payment\\Model\\Event\\AsyncEventHandler';

    public string $messengerTagName = 'async.message_handle';
    public function __construct(
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private readonly AttributeArrayNameInliner $attributeArrayNameInliner,
        private readonly ServiceMapProvider $serviceMapProvider
    )
    {
    }

    public function getHandlersFromServices(): array
    {
        $serviceMap = $this->serviceMapProvider->provide();
        return $serviceMap->getServicesByTag($this->messengerTagName);
    }

    public function extractOptionsFromServiceDefinition(ServiceDefinition $serviceDefinition) : array
    {
        $options = [];
        foreach ($serviceDefinition->getTags() as $tag) {
            if ($this->messengerTagName === $tag->getName()) {
                $options = $tag->getData();
            }
        }
        if ($options['from_transport']) {
            $options['fromTransport'] = $options['from_transport'];
            unset($options['from_transport']);
        }
        return $options;
    }

    /**
     * @param Class_|ClassMethod   $node
     * @param array<string, mixed> $options
     *
     * @return Class_|ClassMethod
     */
    public function addAttribute(ClassMethod|Class_ $node, array $options = []): ClassMethod|Class_
    {

        $args = $this->phpAttributeGroupFactory->createArgsFromItems(items: $options, attributeClass: self::ASYNC_MESSAGE_HANDLER_ATTRIBUTE);
        $args = $this->attributeArrayNameInliner->inlineArrayToArgs(array: $args);
        $node->attrGroups = array_merge($node->attrGroups,
            [
                new AttributeGroup([
                    new Attribute(new FullyQualified(self::ASYNC_MESSAGE_HANDLER_ATTRIBUTE)), $args
                ])
            ]
        );

        return $node;
    }
}
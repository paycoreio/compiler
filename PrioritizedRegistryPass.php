<?php

declare(strict_types=1);

namespace Paymaxi\Component\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class PrioritizedRegistryPass extends AbstractPass
{
    protected function foreachTaggedServices(
        array $taggedServices,
        Definition $definition,
        ContainerBuilder $container
    ) {
        /**
         * @var string
         * @var array $tags
         */
        foreach ($taggedServices as $id => $tags) {
            $priority = 0;
            foreach ($tags as $tag) {
                if (isset($tag['priority'])) {
                    $priority = (int) $tag['priority'];
                }
            }

            $definition->addMethodCall(
                'register',
                [
                    $container->getDefinition($id),
                    $priority,
                ]
            );
        }
    }
}

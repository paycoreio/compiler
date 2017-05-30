<?php

declare(strict_types=1);

namespace Paymaxi\Component\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class RegistryPass extends AbstractPass
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
            $alias = null;
            foreach ($tags as $tag) {
                if (isset($tag['alias'])) {
                    $alias = $tag['alias'];
                }
            }

            if (null === $alias) {
                throw new \RuntimeException('Service should have alias.');
            }

            $definition->addMethodCall(
                'register',
                [
                    $alias,
                    $container->getDefinition($id),
                ]
            );
        }
    }
}

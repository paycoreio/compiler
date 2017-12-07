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
            $generateAlias = false;
            foreach ($tags as $tag) {
                if (isset($tag['alias'])) {
                    $alias = $tag['alias'];
                }
                if (isset($tag['generate-alias']) && true === $tag['generate-alias']) {
                     $generateAlias = true;
                }
            }

            if (null === $alias && false === $generateAlias) {
                throw new \RuntimeException('Service should have an alias or set flag to generate it manually.');
            }

            if($generateAlias){
                $alias = $id;
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

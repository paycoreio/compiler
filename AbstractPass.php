<?php

declare(strict_types=1);

namespace Paymaxi\Component\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class AbstractPass
 */
abstract class AbstractPass implements CompilerPassInterface
{
    /** @var string */
    private $containerName;

    /** @var string */
    private $tag;

    /**
     * RegistryPass constructor.
     *
     * @param string $containerName
     * @param string|null $tag
     */
    public function __construct(string $containerName, string $tag = null)
    {
        $this->containerName = $containerName;

        $this->tag = $tag ?? $containerName;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->containerName)) {
            throw new \RuntimeException(
                sprintf('Missing service registry container %s', $this->containerName)
            );
        }

        $definition = $container->findDefinition($this->containerName);
        $tagged = $container->findTaggedServiceIds($this->tag);

        $this->foreachTaggedServices($tagged, $definition, $container);
    }

    /**
     * @param array $taggedServices
     * @param Definition $definition
     * @param ContainerBuilder $container
     */
    abstract protected function foreachTaggedServices(
        array $taggedServices,
        Definition $definition,
        ContainerBuilder $container
    );
}

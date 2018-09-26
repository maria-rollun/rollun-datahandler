<?php

namespace rollun\datanadler\Filter\Factory;

use rollun\datanadler\Factory\PluginAbstractFactoryAbstract;
use Interop\Container\ContainerInterface;
use Zend\Filter\FilterInterface;

/**
 * Config example
 *
 * 'filters' => [
 *      'abstract_factory_config' => [
 *          'stringTrim'::class => [
 *              'requestedName' => [
 *                  'class' => stringTrim::class,
 *                  'options' => [],
 *              ],
 *          ],
 *      ],
 * ],
 *
 * Class SimpleFilterAbstractFactory
 * @package rollun\datanadler\Filter\Factory
 */
class SimpleFilterAbstractFactory extends PluginAbstractFactoryAbstract
{
    /**
     * Parent class for plugin. By default doesn't set
     */
    const DEFAULT_CLASS = FilterInterface::class;

    /**
     * Common namespace name for plugin config. By default doesn't set
     */
    const KEY = 'filters';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $this->getServiceConfig($container, $requestedName);
        $options = $this->getPluginOptions($serviceConfig, $options);
        $class = $this->getClass($serviceConfig);

        return new $class($options);
    }
}
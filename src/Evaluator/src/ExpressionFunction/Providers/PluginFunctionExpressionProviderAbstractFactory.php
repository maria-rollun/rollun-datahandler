<?php

namespace rollun\datahandler\Evaluator\ExpressionFunction\Providers;

use InvalidArgumentException;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Create and return instance of ExpressionFunctionProviderInterface
 *
 * This Factory depends on Container (which should return an 'config' as array)
 *
 * Config example:
 * <code>
 * PluginFunctionExpressionProviderAbstractFactory::class => [
 *      'pluginFunctionExpressionProviderServiceName1' => [
 *          'class' => PluginExpressionFunctionProvider::class, // optional
 *          'pluginServiceManager' => FilterPluginManager::class,
 *          'calledMethod' => 'filter', // optional, default '__invoke'
 *          'services' => [
 *               'digits',
 *               'rqlReplace',
 *               //...
 *           ]
 *      ],
 *      'pluginFunctionExpressionProviderServiceName2' => [
 *          //...
 *      ]
 * ]
 * </code>
 *
 * Class PluginFunctionExpressionProviderAbstractFactory
 * @package rollun\datahandler\Evaluator
 */
class PluginFunctionExpressionProviderAbstractFactory implements AbstractFactoryInterface
{
    /**
     * Config key for caused class
     */
    const CLASS_KEY = 'class';

    /**
     * Default caused class
     */
    const DEFAULT_CLASS = PluginExpressionFunctionProvider::class;

    /**
     * Config for plugin manager service
     */
    const PLUGIN_MANAGER_SERVICE_KEY = 'pluginServiceManager';

    /**
     * Config for plugin manager called method
     */
    const CALLED_METHOD_KEY = 'calledMethod';

    /**
     * Config for services, that will be called by plugin manager
     */
    const SERVICES_KEY = 'services';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return !is_null($this->getServiceConfig($container, $requestedName));
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $serviceConfig = $container->get('config')[self::class][$requestedName];

        if (!isset($serviceConfig[self::PLUGIN_MANAGER_SERVICE_KEY])) {
            throw new InvalidArgumentException("Missing 'pluginServiceManager' option in config");
        }

        if (!isset($serviceConfig[self::SERVICES_KEY])) {
            throw new InvalidArgumentException("Missing 'services' option in config");
        }

        $class = $this->getClass($serviceConfig);
        $pluginManager = $container->get($serviceConfig[self::PLUGIN_MANAGER_SERVICE_KEY]);
        $pluginServices = $serviceConfig[self::SERVICES_KEY];
        $calledMethod = $serviceConfig[self::CALLED_METHOD_KEY] ?? '__invoke';

        return new $class($pluginManager, $pluginServices, $calledMethod);
    }

    /**
     * Get caused class
     *
     * @param array $serviceConfig
     * @param bool $required
     * @return string
     */
    public function getClass(array $serviceConfig, $required = false)
    {
        if (!isset($serviceConfig[self::CLASS_KEY])) {
            if (!$required) {
                return self::DEFAULT_CLASS;
            }

            throw new \InvalidArgumentException("There is no 'class' config for plugin in config");
        } else if (!is_a($serviceConfig[self::CLASS_KEY], static::DEFAULT_CLASS, true)) {
            throw new \InvalidArgumentException(
                'Caused class must implement or extend ' . static::DEFAULT_CLASS
            );
        }

        return $serviceConfig[self::CLASS_KEY];
    }

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @return null|array
     */
    public function getServiceConfig(ContainerInterface $container, $requestedName)
    {
        $config = $container->get('config');
        return $config[self::class][$requestedName] ?? null;
    }
}

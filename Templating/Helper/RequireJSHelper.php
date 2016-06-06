<?php

/*
 * This file is part of the HearsayRequireJSBundle package.
 *
 * (c) Hearsay News Products, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hearsay\RequireJSBundle\Templating\Helper;

use MiWay\Bundle\CoreBundle\Assets\VersionStrategy\BuildNumberVersionStrategy;
use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Templating\Helper\Helper;

use Hearsay\RequireJSBundle\Configuration\ConfigurationBuilder;

/**
 * Templating helper for RequireJS inclusion.
 *
 * @author Kevin Montag <kevin@hearsay.it>
 */
class RequireJSHelper extends Helper
{
    /**
     * @var EngineInterface
     */
    protected $engine;

    /**
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;

    /**
     * @var string
     */
    protected $initializeTemplate;

    /**
     * @var string
     */
    protected $requireJsSrc;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The constructor method
     *
     * @param EngineInterface                                           $engine
     * @param ConfigurationBuilder                                      $configurationBuilder
     * @param string                                                    $initializeTemplate
     * @param string                                                    $requireJsSrc
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(
        EngineInterface $engine,
        ConfigurationBuilder $configurationBuilder,
        $initializeTemplate,
        $requireJsSrc,
        ContainerInterface $container
    ) {
        $this->engine = $engine;
        $this->configurationBuilder = $configurationBuilder;
        $this->initializeTemplate = $initializeTemplate;
        $this->requireJsSrc = $requireJsSrc;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'require_js';
    }

    /**
     * Renders the RequireJS initialization output. Available options are:
     *   main:
     *     A module to load immediately when RequireJS is available, via the
     *     data-main attribute. Defaults to nothing
     *   configure:
     *     Whether to specify the default configuration options before RequireJS
     *     is loaded.  Defaults to true, and should generally be left this way
     *     unless you need to perform Javascript logic to define the
     *     configuration (e.g. specifying a <code>ready</code> function), in
     *     which case the configuration should be specified manually either
     *     before or after RequireJS is loaded
     *
     * @param  array $options An array of options
     *
     * @return string
     * @link http://requirejs.org/docs/api.html#config
     */
    public function initialize(array $options = [])
    {
        $defaults = [
            'main' => null,
            'configure' => true,
        ];

        $options = array_merge($defaults, $options);

        $mergedOptions = array_merge(
            [
                'main' => $options['main'],
                'config' => $options['configure']
                    ? $this->configurationBuilder->getConfiguration()
                    : null,
            ],
            array_diff_key($options, $defaults)
        );

        $config = $mergedOptions['config'];
        if (!array_key_exists('urlArgs', $config) && array_key_exists('version_strategy', $config)) {
            $versioningStrategyKey = $config['version_strategy'];
            /** @var VersionStrategyInterface $versioningStrategy */
            $versioningStrategy = $this->container->get($versioningStrategyKey);
            $urlArgs = ltrim($versioningStrategy->applyVersion(null), '?');
            $config['urlArgs'] = $urlArgs;
            $mergedOptions['config'] = $config;
        }

        return $this->engine->render(
            $this->initializeTemplate,
            $mergedOptions
        );
    }

    /**
     * Gets the RequireJS src
     *
     * @return string Returns a string that represents the RequireJS src
     */
    public function src()
    {
        if ($this->engine->exists($this->requireJsSrc)
            && $this->engine->supports($this->requireJsSrc)
        ) {
            return $this->engine->render($this->requireJsSrc);
        }

        return $this->requireJsSrc;
    }
}

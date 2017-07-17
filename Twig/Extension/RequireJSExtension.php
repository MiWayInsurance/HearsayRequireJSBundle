<?php

/*
 * This file is part of the HearsayRequireJSBundle package.
 *
 * (c) Hearsay News Products, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hearsay\RequireJSBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Hearsay\RequireJSBundle\Configuration\ConfigurationBuilder;
use Hearsay\RequireJSBundle\Templating\Helper\RequireJSHelper;

/**
 * Twig extension providing RequireJS functionality.
 * @author Kevin Montag <kevin@hearsay.it>
 */
class RequireJSExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var ConfigurationBuilder
     */
    protected $configurationBuilder;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * The constructor method
     *
     * @param ContainerInterface   $container
     * @param ConfigurationBuilder $configurationBuilder
     */
    public function __construct(
        ContainerInterface $container,
        ConfigurationBuilder $configurationBuilder
    ) {
        $this->container            = $container;
        $this->configurationBuilder = $configurationBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return array(
            'require_js_initialize' => new \Twig_Function_Method(
                $this,
                'initialize',
                array(
                    'is_safe' =>
                        array(
                            'html'
                        ),
                    'needs_environment' => true
                )
            ),
            'require_js_src'        => new \Twig_Function_Method($this, 'src'),
        );
    }

    /**
     * {@inheritDoc}
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return 'require_js';
    }

    /**
     * @see Hearsay\RequireJSBundle\Templating\Helper\RequireJSHelper::initialize()
     */
    public function initialize(array $options = array())
    {
        return $this->getHelper()->initialize($options);
    }

    /**
     * @see Hearsay\RequireJSBundle\Templating\Helper\RequireJSHelper::src()
     */
    public function src()
    {
        return $this->getHelper()->src();
    }

    /**
     * @return RequireJSHelper
     */
    protected function getHelper()
    {
        return $this->container->get('hearsay_require_js.templating_helper');
    }
}

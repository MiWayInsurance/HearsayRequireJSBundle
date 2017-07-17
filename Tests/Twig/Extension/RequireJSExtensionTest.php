<?php

/*
 * This file is part of the HearsayRequireJSBundle package.
 *
 * (c) Hearsay News Products, Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hearsay\RequireJSBundle\Tests\Twig\Extension;

use Hearsay\RequireJSBundle\Twig\Extension\RequireJSExtension;

/**
 * @author Igor Timoshenko <igor.timoshenko@i.ua>
 */
class RequireJSExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $container;

    /**
     * @var RequireJSExtension
     */
    private $extension;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->container = $this->getMockBuilder('Symfony\Component\DependencyInjection\Container')
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder = $this
            ->getMockBuilder('Hearsay\RequireJSBundle\Configuration\ConfigurationBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $configurationBuilder
            ->expects($this->any())
            ->method('getConfiguration')
            ->will($this->returnValue(array()));

        $this->extension = new RequireJSExtension(
            $this->container,
            $configurationBuilder
        );
    }
}

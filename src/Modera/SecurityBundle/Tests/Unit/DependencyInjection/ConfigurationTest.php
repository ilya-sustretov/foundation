<?php

namespace Modera\SecurityBundle\Tests\Unit\DependencyInjection;

use Modera\SecurityBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2017 Modera Foundation
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testPasswordStrength()
    {
        $configuration = new Configuration();

        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, array());

        $this->assertArrayHasKey('password_strength', $config);

        $config = $config['password_strength'];
        $expectedConfig = array(
            'enabled' => false,
            'min_length' => 6,
            'number_required' => false,
            'capital_letter_required' => false,
            'rotation_period' => 90,
        );
        $this->assertSame($expectedConfig, $config);
    }
}
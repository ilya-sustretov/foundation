<?php

namespace Modera\DynamicallyConfigurableAppBundle\Contributions;

use Modera\ConfigBundle\Config\ConfigurationEntryDefinition as CED;
use Modera\TranslationsBundle\Helper\T;
use Sli\ExpanderBundle\Ext\ContributorInterface;
use Modera\DynamicallyConfigurableAppBundle\ModeraDynamicallyConfigurableAppBundle as Bundle;

/**
 * @author    Sergei Lissovski <sergei.lissovski@modera.org>
 * @copyright 2014 Modera Foundation
 */
class ConfigEntriesProvider implements ContributorInterface
{
    /**
     * @inheritDoc
     */
    public function getItems()
    {
        $yes = T::trans('yes');
        $no = T::trans('no');

        $kernelDebugServer = array(
            'handler' => 'modera_config.boolean_handler',
            'true_text' => $yes,
            'false_text' => $no,

            'update_handler' => 'modera_dynamically_configurable_app.value_handling.kernel_config_writer'
        );
        $kernelDebugClient = array(
            'xtype' => 'combo',
            'store' => [['prod', 'yes'], ['dev', 'no']]
        );

        $kernelEnvServer = array(
            'handler' => 'modera_config.dictionary_handler',
            'dictionary' => array(
                'prod' => $yes,
                'dev' => $no
            ),

            'update_handler' => 'modera_dynamically_configurable_app.value_handling.kernel_config_writer',
        );
        $kernelEnvClient = array(
            'xtype' => 'combo',
            'store' => [[true, 'yes'], [false, 'no']]
        );

        return array(
            new CED(Bundle::CONFIG_KERNEL_ENV, T::trans('Production mode'), 'prod', 'general', $kernelEnvServer, $kernelDebugClient),
            new CED(Bundle::CONFIG_KERNEL_DEBUG, T::trans('Maintenance mode'), false, 'general', $kernelDebugServer, $kernelEnvClient)
        );
    }
}
<?php

namespace Kp\Bundle\PagingBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler\ResolveDoctrineTargetEntitiesPass;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;

class KpPagingBundle extends Bundle
{
    public static function getSupportedDrivers()
    {
        return array(
            SyliusResourceBundle::DRIVER_DOCTRINE_ORM
        );
    }

    public function build(ContainerBuilder $container)
    {
        $interfaces = array(
            'Kp\Bundle\PagingBundle\Model\PagingInterface'         => 'kp.model.paging.class',
        );

        $container->addCompilerPass(new ResolveDoctrineTargetEntitiesPass('kp_paging', $interfaces));
    }
}

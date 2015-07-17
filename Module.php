<?php

namespace RoLocations;

use T4webBase\Domain\Service\BaseFinder;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as ConsoleAdapterInterface;
use Zend\ServiceManager\ServiceManager;

class Module implements AutoloaderProviderInterface, ConsoleUsageProviderInterface, ControllerProviderInterface, ServiceProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConsoleUsage(ConsoleAdapterInterface $console)
    {
        return [
            'locations init' => 'Initialize module',
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                // countries
                'RoLocations\Countries\Service\Finder' => function (ServiceManager $sm) {
                    return new BaseFinder(
                        $sm->get('RoLocations\Countries\Repository\DbRepository'),
                        $sm->get('RoLocations\Countries\Criteria\CriteriaFactory')
                    );
                },
                // regions
                'RoLocations\Regions\Service\Finder' => function (ServiceManager $sm) {
                    return new BaseFinder(
                        $sm->get('RoLocations\Regions\Repository\DbRepository'),
                        $sm->get('RoLocations\Regions\Criteria\CriteriaFactory')
                    );
                },
                // cities
                'RoLocations\Cities\Service\Finder' => function (ServiceManager $sm) {
                    return new BaseFinder(
                        $sm->get('RoLocations\Cities\Repository\DbRepository'),
                        $sm->get('RoLocations\Cities\Criteria\CriteriaFactory')
                    );
                },
            ],
            'invokables' => [
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                'RoLocations\Controller\User\IndexAjax' => function (ControllerManager $cm) {
                    $sm = $cm->getServiceLocator();

                    return new Controller\User\IndexAjaxController(
                        $sm->get('RoLocations\Countries\Service\Finder'),
                        $sm->get('RoLocations\Regions\Service\Finder'),
                        $sm->get('RoLocations\Cities\Service\Finder')
                    );
                },
            ],
            'invokables' => [
                'RoLocations\Controller\Console\Init' => 'RoLocations\Controller\Console\InitController',
            ],
        ];
    }
}

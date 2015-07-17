<?php

return [

    'router' => [
        'routes' => [
            'user-list-ajax' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/location/:action',
                    'defaults' => [
                        '__NAMESPACE__' => 'RoLocations\Controller\User',
                        'controller' => 'IndexAjax',
                        'action' => 'list',
                    ],
                ],
            ],
        ],
    ],

    'controller_action_injections' => [
        'RoLocations\Controller\User\IndexAjaxController' => [
            'getLocationCountryAction' => [
                'RoLocations\Countries\Service\Finder',
            ],
            'getLocationRegionAction' => [
                function ($serviceLocator, $targetController) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'RoLocations\Regions\Service\Finder',
            ],
            'getLocationCityAction' => [
                function ($serviceLocator, $targetController) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'RoLocations\Cities\Service\Finder',
            ],
            'getLocationsByCityAction' => [
                function ($serviceLocator, $targetController) {
                    return $serviceLocator->get('ControllerPluginManager')->get('params');
                },
                'RoLocations\Cities\Service\Finder',
                'RoLocations\Regions\Service\Finder',
                'RoLocations\Countries\Service\Finder',
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'locations-init' => [
                    'options' => [
                        'route' => 'locations init',
                        'defaults' => [
                            '__NAMESPACE__' => 'RoLocations\Controller\Console',
                            'controller' => 'Init',
                            'action' => 'run'
                        ],
                    ],
                ],
            ],
        ],
    ],

    'db' => [
        'tables' => [
            'rolocations-countries' => [
                'name' => 'countries',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'name' => 'name',
                ],
            ],
            'rolocations-regions' => [
                'name' => 'regions',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'country_id' => 'countryId',
                    'name' => 'name',
                ],
            ],
            'rolocations-cities' => [
                'name' => 'cities',
                'columnsAsAttributesMap' => [
                    'id' => 'id',
                    'region_id' => 'regionId',
                    'country_id' => 'countryId',
                    'name' => 'name',
                ],
            ],
        ],
    ],

    'criteries' => [
        'Cities' => [
            'empty' => [
                'table' => 'cities'
            ],
            'id' => [
                'field' => 'id',
                'table' => 'cities',
                'buildMethod' => 'addFilterEqual',
            ],
            'ids' => [
                'field' => 'id',
                'table' => 'cities',
                'buildMethod' => 'addFilterIn',
            ],
            'regionId' => [
                'field' => 'region_id',
                'table' => 'cities',
                'buildMethod' => 'addFilterEqual',
            ],
            'regionIds' => [
                'field' => 'region_id',
                'table' => 'cities',
                'buildMethod' => 'addFilterIn',
            ],
        ],
        'Regions' => [
            'empty' => [
                'table' => 'regions'
            ],
            'id' => [
                'field' => 'id',
                'table' => 'regions',
                'buildMethod' => 'addFilterEqual',
            ],
            'ids' => [
                'field' => 'id',
                'table' => 'regions',
                'buildMethod' => 'addFilterIn',
            ],
            'countryId' => [
                'field' => 'country_id',
                'table' => 'regions',
                'buildMethod' => 'addFilterEqual',
            ],
        ],
        'Countries' => [
            'empty' => [
                'table' => 'countries'
            ],
            'id' => [
                'field' => 'id',
                'table' => 'countries',
                'buildMethod' => 'addFilterEqual',
            ],
            'ids' => [
                'field' => 'id',
                'table' => 'countries',
                'buildMethod' => 'addFilterIn',
            ]
        ],
    ],
];

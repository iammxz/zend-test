<?php
namespace Restaurant;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\RestaurantController::class => InvokableFactory::class,
        ],
    ],

    // The following section is new and should be added to your file:
    'router' => [
        'routes' => [
            'restaurant' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/restaurant[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\RestaurantController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'restaurant' => __DIR__ . '/../view',
        ],
    ],
];
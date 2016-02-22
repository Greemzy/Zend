<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blog;

return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Categorie' => 'Blog\Controller\CategorieController',
            'Blog\Controller\Article' => 'Blog\Controller\ArticleController',
            'Blog\Controller\Comment' => 'Blog\Controller\CommentController',
            'Blog\Controller\Auth'    => 'Blog\Controller\AuthController',
            'Blog\Controller\Success' => 'Blog\Controller\SuccessController'
        ),
    ),

    'router' => array(
        'routes' => array(
            'articleCategory' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/category/:category',
                    'constraints' => array(
                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Categorie',
                        'action'     => 'index',
                    ),
                ),
            ),
            'articleSlug' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/category/:category/:name',
                    'constraints' => array(
                        'category' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'name'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Article',
                        'action'     => 'show',
                    ),
                ),
            ),
            'comment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/comment[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Comment',
                        'action'     => 'index',
                    ),
                ),
            ),

        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'blog' => __DIR__ . '/../view',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    )
);

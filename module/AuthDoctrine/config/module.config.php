<?php
namespace AuthDoctrine;

return array(
	'controllers' => array(
        'invokables' => array(
            'AuthDoctrine\Controller\Index' => 'AuthDoctrine\Controller\IndexController',
            'AuthDoctrine\Controller\Registration' => 'AuthDoctrine\Controller\RegistrationController',
            'AuthDoctrine\Controller\Admin\Index' => 'AuthDoctrine\Controller\Admin\indexController',
			'AuthDoctrine\Controller\Admin\Article' => 'AuthDoctrine\Controller\Admin\ArticleController',
			'AuthDoctrine\Controller\Admin\Comment' => 'AuthDoctrine\Controller\Admin\CommentController',
			'AuthDoctrine\Controller\Admin\User' => 'AuthDoctrine\Controller\Admin\UserController',
			'AuthDoctrine\Controller\Admin\Log' => 'AuthDoctrine\Controller\Admin\LogController',
        ),
    ),	
    'router' => array(
        'routes' => array(
			'auth-doctrine' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/auth-doctrine',
					'defaults' => array(
						'__NAMESPACE__' => 'AuthDoctrine\Controller',
						'controller'    => 'Index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
			'admin' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/admin',
					'defaults' => array(
						'__NAMESPACE__' => 'AuthDoctrine\Controller\Admin',
						'controller'    => 'index',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '/[:controller[/:action[/:id]]]',
							'constraints' => array(
								'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
								'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
							),
						),
					),
				),
			),
			'ajax' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route'    => '/ajax/videoSearch',
					'defaults' => array(
						'controller' => 'AuthDoctrine\Controller\Admin\Article',
						'action'     => 'videoSearch',
					),
				),
			),
		),
	),
    'view_manager' => array(
        'template_path_stack' => array(
            'auth-doctrine' => __DIR__ . '/../view'
        ),
		'strategies' => array(
			'ViewJsonStrategy',
		),
		'display_exceptions' => false,
    ),
	

    'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'AuthDoctrine\Entity\User',
                'identity_property' => 'usrName',
                'credential_property' => 'usrPassword',
                'credential_callable' => function(Entity\User $user, $passwordGiven) {
					if ($user->getUsrPassword() == md5('aFGQ475SDsdfsaf2342' . $passwordGiven . $user->getUsrPasswordSalt()) &&
						$user->getUsrActive() == 1) {
						return true;
					}
					else {
						return false;
					}
                },
            ),
        ),

        'driver' => array(
			__NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
					__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),

            'orm_default' => array(
                'drivers' => array(
					__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
	'static_salt' => 'aFGQ475SDsdfsaf2342',
);
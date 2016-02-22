<?php
namespace AuthDoctrine\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'usrName',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrPassword',
            'attributes' => array(
                'type'  => 'password',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));

        $this->add(array(
            'name' => 'usrEmail',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));

        $this->add(array(
            'name' => 'usrlId',
			'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'St  r',
				'value_options' => array(
					'1' => 'Public',
					'4' => 'Admin',
				),
            ),
        ));
		
        $this->add(array(
            'name' => 'usrActive',
			'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Active',
				'value_options' => array(
					'0' => 'No',
					'1' => 'Yes',
				),
            ),
        ));
		
        $this->add(array(
            'name' => 'usrPasswordSalt',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Password Salt',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrRegistrationDate',
            'attributes' => array(
                'type'  => 'Zend\Form\Element\DateTime', // 'text'
                'class' => 'form-control',
                'disabled' => 'disabled'
            ),
            'options' => array(
                'label' => 'Registration Date',
            ),
        ));	

        $this->add(array(
            'name' => 'usrRegistrationToken',
            'type'  => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Registration Token',
            ),
        ));			

        $this->add(array(
            'name' => 'usrEmailConfirmed',
			'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'E-mail was confirmed?',
				'value_options' => array(
					'0' => 'No',
					'1' => 'Yes',
				),
            ),
        ));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
                'class' => 'btn btn-success btn btn-success'
            ),
        )); 
    }
}
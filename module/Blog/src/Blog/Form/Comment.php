<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 14/02/2016
 * Time: 14:51
 */

namespace Blog\Form;

use Zend\Form\Form;

class CommentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('comment');

        //Méthode d'envoie (GET,POST)
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'Email Address',
            ),
        ));
        $this->add(array(
            'name' => 'text',
            'type' => 'Text',
            'options' => array(
                'label' => 'Email Address',
            ),
        ));
    }
}
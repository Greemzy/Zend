<?php
namespace AuthDoctrine\Form;

use Blog\Model\CategorieApi;
use Blog\Model\StarsApi;
use Blog\Model\VideoApi;
use Zend\Form\Form;

class CommentForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');

        $star = new StarsApi;
        $categorie = new CategorieApi;
        $video = new VideoApi;

        $listStar = $star->getListStars();
        $listCategorie = $categorie->getListCategorie();

        $this->add(array(
            'name' => 'comStatus',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    'waiting' => 'waiting',
                    'valid' => 'valid',
                    'cancelled' => 'cancelled',
                    'reported' => 'reported'
                ),
            ),
        ));

        $this->add(array(
            'name' => 'comText',
            'attributes' => array(
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows' => '10',
                'cols' => '50'
            ),
            'options' => array(
                'label' => 'Text',
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
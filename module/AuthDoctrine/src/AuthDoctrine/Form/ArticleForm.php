<?php
namespace AuthDoctrine\Form;

use Blog\Model\CategorieApi;
use Blog\Model\StarsApi;
use Blog\Model\VideoApi;
use Zend\Form\Form;

class ArticleForm extends Form
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
            'name' => 'star',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'stars',
                'width' => '100px',
            ),
            'options' => array(
                'label' => 'Star',
                'value_options' => $listStar,
            ),
        ));

        $this->add(array(
            'name' => 'categorie',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'categories'
            ),
            'options' => array(
                'label' => 'Categorie',
                'value_options' => $listCategorie,
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'attributes' => array(
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows' => '10',
                'cols' => '300'
            ),
            'options' => array(
                'label' => 'Description',
            ),
        ));
		
        $this->add(array(
            'name' => 'video',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'videoSelect'
            ),
            'options' => array(
                'label' => 'Video',
                'disable_inarray_validator' => true
            ),
        ));

        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Title',

            ),
        ));

        $this->add(array(
            'name' => 'introduction',
            'attributes' => array(
                'type'  => 'textarea',
                'class' => 'form-control',
                'rows' => '2',
                'cols' => '300'
            ),
            'options' => array(
                'label' => 'Introduction',

            ),
        ));

        $this->add(array(
            'name' => 'tagsInline',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
            ),
            'options' => array(
                'label' => 'Tags (separate with ; )',

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
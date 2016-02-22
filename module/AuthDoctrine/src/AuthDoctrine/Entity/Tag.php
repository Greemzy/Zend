<?php

namespace AuthDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation;
/**
 * tag
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AuthDoctrine\Entity\Repository\TagRepository")
 * @Annotation\Name("Tag")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Tag
{
    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Tag :"})
     */
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     * @ORM\JoinTable(name="articles_tags")
     */
    private $articles;

    public function __construct() {
        $this->articles = new ArrayCollection();
    }

    public function exchangeArray($data)
    {
        $this->id = (!empty($data['id'])) ? $data['id'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
    }

    /**
     * Set star
     *
     * @param string $star
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get star
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get $id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
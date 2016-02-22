<?php

namespace AuthDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Form\Annotation;


/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AuthDoctrine\Entity\Repository\ArticleRepository")
 * @Annotation\Name("article")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Article
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
     * @var string
     *
     * @ORM\Column(name="star", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Star:"})
     */
    private $star;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=100, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Categorie:"})
     */
    private $categorie;

    /**
     * @var string
     *
    @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":255}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Description:"})
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="video", type="integer", nullable=false)
     * @Annotation\Type("Zend\Form\Element\Radio")
     * @Annotation\Options({"label":"Video:"})
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb", type="string", length=255, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Thumb :"})
     */
    private $thumb;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Created Date:", "format":"Y-m-d\TH:iP"})
     */
    private $created_at;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Created Date:", "format":"Y-m-d\TH:iP"})
     */
    private $updated_at;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Slug:"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Title:"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="introduction", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Introduction:"})
     */
    private $introduction;

    /**
     * @var integer
     *
     * @ORM\Column(name="author", type="integer", nullable=true)
     * @ORM\OneToMany(targetEntity="User")
     * @ORM\JoinColumn(name="author", referencedColumnName="usr_id")
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Options({"label":" Author:"})
     */
    private $author;

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
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="articles")
     * @ORM\JoinTable(name="articles_tags")
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->slug = (!empty($data['slug'])) ? $data['slug'] : null;
        $this->star = (!empty($data['star'])) ? $data['star'] : null;
        $this->categorie  = (!empty($data['categorie'])) ? $data['categorie'] : null;
        $this->description = (!empty($data['description'])) ? $data['description'] : null;
        $this->thumb = (!empty($data['thumb'])) ? $data['thumb'] : null;
        $this->created_at = (!empty($data['created_at'])) ? $data['created_at'] : null;
        $this->updated_at = (!empty($data['updated_at'])) ? $data['updated_at'] : null;
        $this->author = (!empty($data['author'])) ? $data['author'] : null;
        $this->introduction = (!empty($data['introduction'])) ? $data['introduction'] : null;
        $this->title = (!empty($data['title'])) ? $data['title'] : null;
        $this->video = (!empty($data['video'])) ? $data['video'] : null;
    }

    /**
     * Get tags
     *
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set tags
     *
     * @param string $star
     * @return Article
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Set star
     *
     * @param string $star
     * @return Article
     */
    public function setStar($star)
    {
        $this->star = $star;

        return $this;
    }

    /**
     * Get star
     *
     * @return string
     */
    public function getStar()
    {
        return $this->star;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Article
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Article
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set video
     *
     * @param integer $video
     * @return Article
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     *
     * @return integer
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set thumb
     *
     * @param string $thumb
     * @return Article
     */
    public function setThumb($thumb)
    {
        $this->thumb = $thumb;

        return $this;
    }

    /**
     * Get thumb
     *
     * @return string
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * Set created_at
     *
     * @param string $created_at
     * @return Article
     */
    public function setCreated_at($created_at)
    {
        $this->created_at= $created_at;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return string
     */
    public function getCreated_at()
    {
        return $this->created_at->format('d/m/Y');;
    }

    /**
     * Set updated_at
     *
     * @param string $updated_at
     * @return Article
     */
    public function setUpdated_at($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return string
     */
    public function getUpdated_at()
    {
        return $this->updated_at->format('d/m/Y');;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set introduction
     *
     * @param string $introduction
     * @return Article
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Article
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
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
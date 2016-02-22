<?php

namespace AuthDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * Users
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="AuthDoctrine\Entity\Repository\CommentRepository")
 * @Annotation\Name("comment")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class comment
{
    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=100, nullable=false)
	 * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":255}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Text :"})
     */
    private $comText;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Options({"label":"Your email address:"})
     */
    private $comEmail;


    /**
     * @var integer
     *
     * @ORM\Column(name="article_id", type="integer", nullable=false)
     * @ORM\OneToMany(targetEntity="article")
     * @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     * @Annotation\Type("Zend\Form\Element\Number")
     */
    private $comArticleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="usr_id", type="integer", nullable=true)
     * @ORM\OneToMany(targetEntity="user")
     * @ORM\JoinColumn(name="usr_id", referencedColumnName="usr_id")
     * @Annotation\Type("Zend\Form\Element\Number")
     */
    private $comUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="text", nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Select")
	 * @Annotation\Options({
	 * "label":"Status :",
	 * "value_options":{ "waiting":"Waiting", "valid":"Valid", "cancelled": "Cancelled", "reported":"Reported"}})
     */
    private $comStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Create Date:", "format":"Y-m-d\TH:iP"})
     */
    private $comCreatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Update Date:", "format":"Y-m-d\TH:iP"})
     */
    private $comUpdateDate;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
     */
    private $comId;

	public function __construct()
	{
		$this->usrRegistrationDate = new \DateTime();
	}

    public function exchangeArray($data)
    {
        $this->comId = (!empty($data['comId'])) ? $data['comId'] : null;
        $this->comArticleId = (!empty($data['comArticleId'])) ? $data['comArticleId'] : null;
        $this->comEmail = (!empty($data['comEmail'])) ? $data['comEmail'] : null;
        $this->comStatus  = (!empty($data['comStatus'])) ? $data['comStatus'] : null;
        $this->comText = (!empty($data['comText'])) ? $data['comText'] : null;
        $this->comCreatedDate = (!empty($data['comCreatedDate'])) ? $data['comCreatedDate'] : null;
        $this->comUpdateDate = (!empty($data['comUpdateDate'])) ? $data['comUpdateDate'] : null;
        $this->comUserId = (!empty($data['comUserId'])) ? $data['comUserId'] : null;
    }

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
     * Set usrName
     *
     * @param string $comEmail
     * @return Comment
     */
    public function setComEmail($comEmail)
    {
        $this->comEmail = $comEmail;
    
        return $this;
    }

    /**
     * Get usrName
     *
     * @return string 
     */
    public function getComEmail()
    {
        return $this->comEmail;
    }

    /**
     * Set usrPassword
     *
     * @param string $comStatus
     * @return Comment
     */
    public function setComStatus($comStatus)
    {
        $this->comStatus = $comStatus;
    
        return $this;
    }

    /**
     * Get comStatus
     *
     * @return string 
     */
    public function getComStatus()
    {
        return $this->comStatus;
    }

    /**
     * Set comText
     *
     * @param string $comText
     * @return Comment
     */
    public function setComText($comText)
    {
        $this->comText = $comText;
    
        return $this;
    }

    /**
     * Get comText
     *
     * @return string 
     */
    public function getComText()
    {
        return $this->comText;
    }

    /**
     * Set comCreatedDate
     *
     * @param integer $comCreatedDate
     * @return Comment
     */
    public function setComCreatedDate($comCreatedDate)
    {
        $this->comCreatedDate = $comCreatedDate;
    
        return $this;
    }

    /**
     * Get comCreatedDate
     *
     * @return string
     */
    public function getComCreatedDate()
    {
        return $this->comCreatedDate;
    }

    /**
     * Set ComUpdateDate
     *
     * @param string $comUpdateDate
     * @return Comment
     */
    public function setComUpdateDate($comUpdateDate)
    {
        $this->comUpdateDate = $comUpdateDate;
    
        return $this;
    }

    /**
     * Get ComUpdateDate
     *
     * @return string
     */
    public function getComUpdateDate()
    {
        return $this->comUpdateDate;
    }

    /**
     * Set comUserId
     *
     * @param string $comUserId
     * @return Comment
     */
    public function setComUserId($comUserId)
    {
        $this->comUserId = $comUserId;

        return $this;
    }

    /**
     * Get comUserId
     *
     * @return integer
     */
    public function getComUserId()
    {
        return $this->comUserId;
    }

    /**
     * Set comArticleId
     *
     * @param string $comArticleId
     * @return Comment
     */
    public function setComArticleId($comArticleId)
    {
        $this->comArticleId = $comArticleId;

        return $this;
    }

    /**
     * Get comArticleId
     *
     * @return integer
     */
    public function getComArticleId()
    {
        return $this->comArticleId;
    }

    /**
     * Get comId
     *
     * @return integer
     */
    public function getComId()
    {
        return $this->comId;
    }



}
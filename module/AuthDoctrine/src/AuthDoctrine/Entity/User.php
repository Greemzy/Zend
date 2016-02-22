<?php

namespace AuthDoctrine\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AuthDoctrine\Entity\Repository\UserRepository")
 * @Annotation\Name("user")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="usr_name", type="string", length=100, nullable=false)
	 * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Username:"})	 
     */
    private $usrName;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_password", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"password"})
     * @Annotation\Options({"label":"Password:"})	
     */
    private $usrPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_email", type="string", length=60, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Options({"label":"Your email address:"})
     */
    private $usrEmail;

    /**
     * @var integer
     *
     * @ORM\Column(name="usrl_id", type="integer", nullable=true)
	 * @ORM\OneToMany(targetEntity="user_roles")
	 * @ORM\JoinColumn(name="usrl_id", referencedColumnName="usrl_id")
	 * @Annotation\Type("Zend\Form\Element\Select")
	 * @Annotation\Options({
	 * "label":"User Role:",
	 * "value_options":{ "0":"Select Role", "1":"Public", "2": "Member"}})
     */
    private $usrlId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_active", type="boolean", nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Radio")
	 * @Annotation\Options({
	 * "label":"User Active:",
	 * "value_options":{"1":"Yes", "0":"No"}})
     */
    private $usrActive;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_question", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Question:"})
     */
    private $usrQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_answer", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Answer:"})
     */
    private $usrAnswer;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_picture", type="string", length=255, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Picture:"})
     */
    private $usrPicture;

    /**
     * @var string
     *
     * @ORM\Column(name="usr_password_salt", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Password Salt:"})
     */
    private $usrPasswordSalt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="usr_registration_date", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d\TH:iP"})
     */
    private $usrRegistrationDate; // = '2013-07-30 00:00:00'; // new \DateTime() - coses synatx error

    /**
     * @var string
     *
     * @ORM\Column(name="usr_registration_token", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Registration Token:"})
     */
    private $usrRegistrationToken;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="usr_email_confirmed", type="boolean", nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Radio")
	 * @Annotation\Options({
	 * "label":"User confirmed email:",
	 * "value_options":{"1":"Yes", "0":"No"}})
     */
    private $usrEmailConfirmed;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="usr_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
     */
    private $usrId;

	public function __construct()
	{
		$this->usrRegistrationDate = new \DateTime();
	}

    public function exchangeArray($data)
    {
        $this->usrId = (!empty($data['usrId'])) ? $data['usrId'] : null;
        $this->usrName = (!empty($data['usrName'])) ? $data['usrName'] : null;
        $this->usrEmail = (!empty($data['usrEmail'])) ? $data['usrEmail'] : null;
        $this->usrActive  = (!empty($data['usrActive'])) ? $data['usrActive'] : null;
        $this->usrAnswer = (!empty($data['usrAnswer'])) ? $data['usrAnswer'] : null;
        $this->usrEmailConfirmed = (!empty($data['usrEmailConfirmed'])) ? $data['usrEmailConfirmed'] : null;
        $this->usrlId = (!empty($data['usrlId'])) ? $data['usrlId'] : null;
        $this->usrPassword = (!empty($data['usrPassword'])) ? $data['usrPassword'] : null;
        $this->usrPasswordSalt = (!empty($data['author'])) ? $data['usrPasswordSalt'] : null;
        $this->usrPicture = (!empty($data['usrPicture'])) ? $data['usrPicture'] : null;
        $this->usrQuestion = (!empty($data['usrQuestion'])) ? $data['usrQuestion'] : null;
        $this->usrRegistrationDate = (!empty($data['usrRegistrationDate'])) ? $data['usrRegistrationDate'] : null;
        $this->usrRegistrationToken = (!empty($data['usrRegistrationToken'])) ? $data['usrRegistrationToken'] : null;
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
     * @param string $usrName
     * @return Users
     */
    public function setUsrName($usrName)
    {
        $this->usrName = $usrName;
    
        return $this;
    }

    /**
     * Get usrName
     *
     * @return string 
     */
    public function getUsrName()
    {
        return $this->usrName;
    }

    /**
     * Set usrPassword
     *
     * @param string $usrPassword
     * @return Users
     */
    public function setUsrPassword($usrPassword)
    {
        $this->usrPassword = $usrPassword;
    
        return $this;
    }

    /**
     * Get usrPassword
     *
     * @return string 
     */
    public function getUsrPassword()
    {
        return $this->usrPassword;
    }

    /**
     * Set usrEmail
     *
     * @param string $usrEmail
     * @return Users
     */
    public function setUsrEmail($usrEmail)
    {
        $this->usrEmail = $usrEmail;
    
        return $this;
    }

    /**
     * Get usrEmail
     *
     * @return string 
     */
    public function getUsrEmail()
    {
        return $this->usrEmail;
    }

    /**
     * Set usrlId
     *
     * @param integer $usrlId
     * @return Users
     */
    public function setUsrlId($usrlId)
    {
        $this->usrlId = $usrlId;
    
        return $this;
    }

    /**
     * Get usrlId
     *
     * @return integer 
     */
    public function getUsrlId()
    {
        return $this->usrlId;
    }

    /**
     * Set usrActive
     *
     * @param boolean $usrActive
     * @return Users
     */
    public function setUsrActive($usrActive)
    {
        $this->usrActive = $usrActive;
    
        return $this;
    }

    /**
     * Get usrActive
     *
     * @return boolean 
     */
    public function getUsrActive()
    {
        return $this->usrActive;
    }

    /**
     * Set usrQuestion
     *
     * @param string $usrQuestion
     * @return Users
     */
    public function setUsrQuestion($usrQuestion)
    {
        $this->usrQuestion = $usrQuestion;
    
        return $this;
    }

    /**
     * Get usrQuestion
     *
     * @return string 
     */
    public function getUsrQuestion()
    {
        return $this->usrQuestion;
    }

    /**
     * Set usrAnswer
     *
     * @param string $usrAnswer
     * @return Users
     */
    public function setUsrAnswer($usrAnswer)
    {
        $this->usrAnswer = $usrAnswer;
    
        return $this;
    }

    /**
     * Get usrAnswer
     *
     * @return string 
     */
    public function getUsrAnswer()
    {
        return $this->usrAnswer;
    }

    /**
     * Set usrPicture
     *
     * @param string $usrPicture
     * @return Users
     */
    public function setUsrPicture($usrPicture)
    {
        $this->usrPicture = $usrPicture;
    
        return $this;
    }

    /**
     * Get usrPicture
     *
     * @return string 
     */
    public function getUsrPicture()
    {
        return $this->usrPicture;
    }

    /**
     * Set usrPasswordSalt
     *
     * @param string $usrPasswordSalt
     * @return Users
     */
    public function setUsrPasswordSalt($usrPasswordSalt)
    {
        $this->usrPasswordSalt = $usrPasswordSalt;
    
        return $this;
    }

    /**
     * Get usrPasswordSalt
     *
     * @return string 
     */
    public function getUsrPasswordSalt()
    {
        return $this->usrPasswordSalt;
    }

    /**
     * Set usrRegistrationDate
     *
     * @param string $usrRegistrationDate
     * @return Users
     */
    public function setUsrRegistrationDate($usrRegistrationDate)
    {
        $this->usrRegistrationDate = $usrRegistrationDate;
    
        return $this;
    }

    /**
     * Get usrRegistrationDate
     *
     * @return string 
     */
    public function getUsrRegistrationDate()
    {
        return $this->usrRegistrationDate;
    }

    /**
     * Set usrRegistrationToken
     *
     * @param string $usrRegistrationToken
     * @return Users
     */
    public function setUsrRegistrationToken($usrRegistrationToken)
    {
        $this->usrRegistrationToken = $usrRegistrationToken;
    
        return $this;
    }

    /**
     * Get usrRegistrationToken
     *
     * @return string 
     */
    public function getUsrRegistrationToken()
    {
        return $this->usrRegistrationToken;
    }
	
    /**
     * Set usrEmailConfirmed
     *
     * @param string $usrEmailConfirmed
     * @return Users
     */
    public function setUsrEmailConfirmed($usrEmailConfirmed)
    {
        $this->usrEmailConfirmed = $usrEmailConfirmed;
    
        return $this;
    }

    /**
     * Get usrEmailConfirmed
     *
     * @return string 
     */
    public function getUsrEmailConfirmed()
    {
        return $this->usrEmailConfirmed;
    }
	
    /**
     * Get usrId
     *
     * @return integer 
     */
    public function getUsrId()
    {
        return $this->usrId;
    }
}
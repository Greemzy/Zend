<?php

namespace AuthDoctrine\Entity;

use Application\View\Helper\CustomTools;
use Blog\Model\StarsApi;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Zend\Authentication\AuthenticationService;

use Zend\Form\Annotation;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Log
 *
 * @ORM\Table(name="log")
 * @ORM\Entity(repositoryClass="AuthDoctrine\Entity\Repository\LogRepository")
 * @Annotation\Name("log")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class Log
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime", nullable=false)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Date :", "format":"Y-m-d\TH:iP"})
     */
    private $date_time;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":" Type:"})
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=2000, nullable=false)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":" Type:"})
     */
    private $message;


    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     * @ORM\OneToMany(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="usr_id")
     * @Annotation\Options({"label":" User:"})
     */
    private $user_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     */
    private $id;

    public function __construct()
    {
        $this->date_time = new \DateTime();
    }

    public function exchangeArray($data)
    {
        $this->id     = (!empty($data['id'])) ? $data['id'] : null;
        $this->user_id = (!empty($data['user_id'])) ? $data['user_id'] : null;
        $this->date_time = (!empty($data['date_time'])) ? $data['date_time'] : null;
        $this->type  = (!empty($data['type'])) ? $data['type'] : null;
        $this->message  = (!empty($data['message'])) ? $data['message'] : null;
    }
    /**
     * Set type
     *
     * @param string $type
     * @return Log
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Log
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date_time
     *
     * @param string $date_time
     * @return Article
     */
    public function setDatetime($date_time)
    {
        $this->date_time= $date_time;

        return $this;
    }

    /**
     * Get date_time
     *
     * @return string
     */
    public function getDate_time()
    {
        return $this->date_time->format('d/m/Y');;
    }

    /**
     * Set user_id
     *
     * @param string $user_id
     * @return Log
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
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
<?php
namespace AuthDoctrine\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getRolesArray($number = 30)
    {
        return array();
    }
}
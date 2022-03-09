<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\GithubResourceOwner;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByUsername($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function findBySomething($value){
        $query=$this->createQueryBuilder('u');
        $orStatement=$query->expr()->orX();
        $orStatement->add(
            $query->expr()->like('LOWER(u.username)',':val')
        );
        $orStatement->add(
            $query->expr()->like('LOWER(u.email)',':val')
        );
        $orStatement->add(
            $query->expr()->like('LOWER(u.Telephone)',':val')
        );
        $query->andWhere($orStatement);
        $query->setParameter('val','%'.strtoupper($value).'%');
        return $query->getQuery()->getResult();
    }

    public function findOrCreateByGithubAuth(GithubResourceOwner $owner){
        /** @var User $user */
        $user=$this->createQueryBuilder('u')
            ->where('u.githubId = :gitid')
            //->orWhere('u.email = :email')
            ->setParameters([
               // 'email'=> $owner->getEmail(),
                'gitid'=> $owner->getId()
            ])
            ->getQuery()
            ->getOneOrNullResult();

        if ($user){
            if ($user->getGithubId()===null){
                $user->setGithubId($owner->getId());
                $this->getEntityManager()->flush();
            }

            return $user;
        }
        $user=(new User())
            ->setGithubId($owner->getId())
            ->setUsername($owner->getNickname())
            ->setRoles("ROLE_USER")
            ->setIsVerified(true)
            ->setEmail($owner->getEmail());

        $em=$this->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $user;

    }
}

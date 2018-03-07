<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;


class PostRepository extends EntityRepository
{
    public function findAllOrderedByLatest()
    {

        $repository = $this->getEntityManager()->getRepository(Post::class);

        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        return $query->execute();
    }
}

<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping;


class PostRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(Post::class));
    }

    public function findAllOrderedByLatest()
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery();

        return $query->execute();
    }

    /**
     * @param Post $post
     * @param User $author
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createNewPost(Post $post, User $author)
    {
        $post->setAuthor($author);
        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Post $post
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deletePost(Post $post)
    {
        $this->getEntityManager()->remove($post);
        $this->getEntityManager()->flush();
    }

}

<?php
declare(strict_types=1);
namespace AppBundle\Service;

use AppBundle\Entity\Post;
use AppBundle\Entity\User;
use Xgc\CoreBundle\Service\ContainerService;

/**
 * Class PostService
 * @package AppBundle\Service
 */
class PostService extends ContainerService
{
    /**
     * @param User $user
     * @param string $title
     * @param string $text
     * @return Post
     */
    public function create(User $user, string $title, string $text): Post
    {
        $slug = $this->container->get('slugify')->slugify($title);

        $post = new Post();
        $post->setAuthor($user);
        $post->setSlug($slug);
        $post->setTitle($title);
        $post->setText($text);

        $this->container->get('doctrine')->flush($post);

        return $post;
    }
}

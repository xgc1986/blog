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
        $slug = $this->container->get('cocur_slugify');
        var_dump(get_class($slug));

    }
}

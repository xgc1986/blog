<?php
declare(strict_types=1);
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Xgc\CoreBundle\Entity\Entity;

class Post extends Entity
{

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function __getType(): string
    {
        return 'post';
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text)
    {
        $this->text = $text;
    }

}

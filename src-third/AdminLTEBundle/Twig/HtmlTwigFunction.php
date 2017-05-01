<?php
declare(strict_types=1);
namespace Xgc\AdminLTEBundle\Twig;

class HtmlTwigFunction extends \Twig_Function
{
    public function __construct($name, $callable = null, array $options = [])
    {
        $options['is_safe'] = $options['is_safe'] ?? ['html'];
        $options['needs_environment'] = $options['needs_environment'] ?? true;
        parent::__construct($name, $callable, $options);
    }
}

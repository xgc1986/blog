<?php
declare(strict_types=1);
namespace Xgc\AdminLTEBundle\Twig\Extension;

use Xgc\AdminLTEBundle\Twig\HtmlTwigFunction;

class AdminLTEExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_lte';
    }

    public function getFunctions()
    {
        return [
            new HtmlTwigFunction('admin_resource', [$this, 'getResources']),
            // COMPONENTS
            new HtmlTwigFunction('admin_box', [$this, 'getBox']),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function getResources(\Twig_Environment $twig)
    {
        return $twig->render('XgcAdminLTEBundle::client.html.twig');
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function getBox(\Twig_Environment $twig, string $style="default")
    {
        return $twig->render('XgcAdminLTEBundle:component:box.html.twig', [
            'style' => $style
        ]);
    }




}

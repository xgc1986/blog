<?php
declare(strict_types=1);
namespace Xgc\SphinxBundle\Twig;

use Xgc\SphinxBundle\Search\Sphinx;

/**
 * Twig extension for Sphinxsearch bundle
 */
class SphinxTwigExtension extends \Twig_Extension
{
    protected $searchd;

    public function __construct(Sphinx $searchd)
    {
        $this->searchd = $searchd;
    }

    /**
     * Highlight $text for the $query using $index
     * @param string $text Text content
     * @param string $index Sphinx index name
     * @param string $query Query to search
     * @param array[optional] $options Options to pass to SphinxAPI
     *
     * @return string
     */
    public function sphinxHighlight(string $text, string $index, $query, array $options = [])
    {
        $result = $this->searchd->getClient()->BuildExcerpts([$text], $index, $query, $options);

        if (!empty($result[0])) {
            return $result[0];
        } else {
            return '';
        }
    }

    /**
     * Filters list
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('sphinx_highlight', [$this, 'sphinxHighlight'], ['is_safe' => array('html')])
        ];
    }

    /**
     * Implement getName() method
     * @return string
     */
    public function getName()
    {
        return 'xgc_sphinx_twig_extension';
    }
}

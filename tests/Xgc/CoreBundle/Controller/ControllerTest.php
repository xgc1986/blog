<?php
declare(strict_types=1);
namespace Test\Xgc\CoreBundle\Controller;

use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Xgc\CoreBundle\Controller\Controller;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Test\KernelTestCase;
use Xgc\CoreBundle\Test\Stub\Controller\ControllerStub;

/**
 * @codeCoverageIgnore
 */
class ControllerTest extends KernelTestCase
{
    public function testRequestController()
    {
        $request = new Request;
        $requestStackMock = $this->createMock(RequestStack::class);
        $requestStackMock->method('getCurrentRequest')
            ->will(self::returnValue($request))
        ;

        self::loadKernel();
        $container = self::$kernel->getContainer();
        $firewall = new FirewallMap($container, []);
        $container->set('request_stack', $requestStackMock);
        $container->set('security.firewall.map', $firewall);

        $controller = new ControllerStub;

        $controller->setContainer(self::$kernel->getContainer());
        self::assertNotNull($controller->getRequest());
    }

    public function testToArray()
    {
        $obj = new Class Extends Entity {
            public function getId(): int
            {
                return 3;
            }
        };

        $ctlr = new Class extends Controller {};
        self::assertEquals([
            'id' => 3,
        ], $ctlr->toArray($obj));
    }
}

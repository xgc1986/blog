<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Client as SymfonyClient;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserClient extends Client
{

    protected $username;
    protected $password;

    public function __construct(string $username, string $password, SymfonyClient $client)
    {
        parent::__construct($client);
        $this->username = $username;
        $this->password = $password;

    }

    public function logIn()
    {
        $this->post('app_api_user_login', ['username' => $this->username, 'password' => $this->password]);
        $this->check(200);
    }
}

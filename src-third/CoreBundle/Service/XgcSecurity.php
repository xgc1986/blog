<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Xgc\CoreBundle\Entity\User;
use Xgc\CoreBundle\Exception\Http\AccessDeniedException;
use Xgc\CoreBundle\Exception\Http\AccountBeingCreatedException;
use Xgc\CoreBundle\Exception\Http\AccountDissabledException;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\CoreBundle\Exception\Http\ResourceNoLongerAvailableException;
use Xgc\CoreBundle\Exception\Http\ResourceNotFoundException;
use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\Text;

/**
 * Class Security
 * @package Xgc\CoreBundle\Service
 */
class XgcSecurity
{

    /** @var ContainerInterface */
    protected $container;

    /** @var Doctrine */
    protected $doctrine;

    /** @var Request */
    protected $request;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var UserPasswordEncoderInterface */
    protected $encoder;

    /** @var String */
    protected $secret;


    public function __construct(
        ContainerInterface $container,
        Registry $doctrine,
        Request $request,
        TokenStorage $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        UserPasswordEncoderInterface $encoder,
        String $secret
    ) {
        $this->container       = $container;
        $this->doctrine        = $doctrine;
        $this->request         = $request;
        $this->tokenStorage    = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->encoder         = $encoder;
        $this->secret          = $secret;
    }

    public function login(string $fqn, string $firewall, string $username, string $password, bool $remember): User
    {
        $user =
            $this->doctrine->getRepository($fqn)->findOneBy(['username' => $username]) ??
            $this->doctrine->getRepository($fqn)->findOneBy(['email' => $username]);

        if (!$user) {
            throw new AccessDeniedException();
        }

        $user = $this->castToUser($user);

        if (!$this->hasPassword($user, $password)) {
            throw new AccessDeniedException();
        }

        if (!$user->isEnabled()) {
            throw new AccountBeingCreatedException();
        }

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        $securityTokenStorage = $this->tokenStorage;

        if ($remember) {
            $token = new RememberMeToken($user, $firewall, $this->secret);
        } else {
            $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        }

        $securityTokenStorage->setToken($token);
        //$request = $this->request->getOriginal();
        //$event   = new InteractiveLoginEvent($request, $token);
        //$this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

        return $user;
    }

    public function checkUser()
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        if (!$user->isEnabled()) {
            throw new AccountBeingCreatedException();
        }

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        return $user;
    }

    public function getUser(): ?User
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }
        /** @var User $user */
        $user = $token->getUser();
        if (!$user || is_string($user)) {
            return null;
        }

        if (!$user->isEnabled()) {
            throw new AccountBeingCreatedException();
        }

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        return $user;
    }

    public function changePasswords(User $user, string $password): User
    {
        $minLength  = $this->container->getParameter('xgc.security.password.minlength');
        $symbols    = $this->container->getParameter('xgc.security.password.symbols');
        $numbers    = $this->container->getParameter('xgc.security.password.numbers');
        $uppercases = $this->container->getParameter('xgc.security.password.uppercases');

        if (!Text::validatePassword($password, $minLength, true, $numbers, $uppercases, $symbols)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        $user->setPassword($password);

        $this->doctrine->flush($user);

        return $user;
    }

    public function resetPassword(string $fqn, string $token, string $password, string $password2): User
    {
        $user = $this->castToUser($this->doctrine->getRepository($fqn)->findOneBy(['resetPasswordToken' => $token]));

        if (!$user) {
            throw new ResourceNotFoundException('token');
        }

        if (!$user->getResetPasswordTokenAt()) {
            throw new ResourceNoLongerAvailableException('token');
        }

        if ($user->getResetPasswordTokenAt()->getRelativeTime() >= -24 * 60 * 60) {
            // If 1 day have passed
            $user->setResetPasswordTokenAt(null);
            $user->setResetPasswordToken(null);
            $this->doctrine->getManager()->flush();
            throw new ResourceNoLongerAvailableException('token');
        }

        if ($password !== $password2) {
            throw new PreconditionFailedException("Passwords missmatch");
        }

        $minLength  = $this->container->getParameter('xgc.security.password.minlength');
        $symbols    = $this->container->getParameter('xgc.security.password.symbols');
        $numbers    = $this->container->getParameter('xgc.security.password.numbers');
        $uppercases = $this->container->getParameter('xgc.security.password.uppercases');

        if (!Text::validatePassword($password, $minLength, true, $numbers, $uppercases, $symbols)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        $user->setPassword($password);
        $user->setResetPasswordTokenAt(null);
        $user->setResetPasswordToken(null);

        $this->doctrine->getManager()->flush();

        return $user;
    }

    private function castToUser($object): User
    {
        return $object;
    }

    public function logout(): void
    {
        $this->tokenStorage->setToken(null);
        $this->request->getSession()->invalidate();
    }

    public function enable(string $fqn, string $token): User
    {
        $user = $this->castToUser($this->doctrine->getRepository($fqn)->findOneBy(['registerToken' => $token]));

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        if (!$user) {
            throw new ResourceNotFoundException('token');
        }

        if (!$user->getRegisterTokenAt()) {
            throw new ResourceNoLongerAvailableException('token');
        }

        if ($user->getRegisterTokenAt()->getRelativeTime() >= -24 * 60 * 60) {
            // If 1 day have passed
            $user->setRegisterTokenAt(null);
            $user->setRegisterToken(null);
            $this->doctrine->getManager()->flush();
            throw new ResourceNoLongerAvailableException('token');
        }

        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function addResetPasswordToken(string $fqn, string $email): User
    {
        $user = $this->castToUser($this->doctrine->getRepository($fqn)->findOneBy(['registerToken' => $email]));

        if (!$user) {
            throw new ResourceNotFoundException('email');
        }

        $user->setResetPasswordToken(Text::rstr(32));
        $user->setResetPasswordTokenAt(new DateTime());

        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function addRegisterToken(string $fqn, string $email): User
    {
        $user = $this->castToUser($this->doctrine->getRepository($fqn)->findOneBy(['registerToken' => $email]));

        if (!$user) {
            throw new ResourceNotFoundException('token');
        }

        if ($user->isEnabled()) {
            throw new PreconditionFailedException("Account is already enabled");
        }

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        $user->setRegisterToken(Text::rstr(32));
        $user->setRegisterTokenAt(new DateTime());

        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function hasPassword(User $user, string $password): bool
    {
        return $this->encoder->isPasswordValid($user, $password);
    }

    public function setPassword(string $password): User
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        $minLength  = $this->container->getParameter('xgc.security.password.minlength');
        $symbols    = $this->container->getParameter('xgc.security.password.symbols');
        $numbers    = $this->container->getParameter('xgc.security.password.numbers');
        $uppercases = $this->container->getParameter('xgc.security.password.uppercases');

        if (!Text::validatePassword($password, $minLength, true, $numbers, $uppercases, $symbols)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);

        $this->doctrine->getManager()->flush();

        return $user;
    }

    public function deleteUser(User $user)
    {
        $this->doctrine->flush($user);
    }
}

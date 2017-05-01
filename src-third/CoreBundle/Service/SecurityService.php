<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
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

class SecurityService
{
    protected $doctrine;
    protected $request;
    protected $tokenStorage;
    protected $eventDispatcher;
    protected $encoder;
    protected $secret;


    public function __construct(
        Registry $doctrine,
        RequestService $request,
        TokenStorage $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        UserPasswordEncoderInterface $encoder,
        String $secret
    ) {
        $this->doctrine = $doctrine;
        $this->request = $request;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->encoder = $encoder;
        $this->secret = $secret;
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

        if (!$this->hasPassword($password, $user)) {
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
        $request = $this->request->getCurrentRequest();
        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

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
        $user = $token->getUser();
        if (!$user || is_string($user)) {
            return null;
        }

        return $user;
    }

    public function changePasswords(string $oldPassword, string $password, string $password2): User
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        if ($password !== $password2) {
            throw new PreconditionFailedException("Passwords missmatch");
        }

        if (!Text::validatePassword($password, 8, true, true, true, false)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        if (!$this->hasPassword($oldPassword)) {
            throw new PreconditionFailedException("Password is not correct");
        }

        $user->setPassword($password);

        $this->doctrine->getManager()->flush();

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

        if (!Text::validatePassword($password, 8, true, true, true, false)) {
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
        $request = $this->request->getCurrentRequest();
        $this->tokenStorage->setToken(null);
        $request->getSession()->invalidate();
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

    public function hasPassword(string $password, ?User $user = null): bool
    {
        $user = $user ?? $this->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        return $this->encoder->isPasswordValid($user, $password);
    }

    public function setPassword(string $password): User
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException();
        }

        $password = $this->encoder->encodePassword($user, $password);
        $user->setPassword($password);

        $this->doctrine->getManager()->flush();

        return $user;
    }
}

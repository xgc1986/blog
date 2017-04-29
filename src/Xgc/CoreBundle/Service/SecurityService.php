<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Intervention\Image\ImageManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\RememberMeToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Xgc\CoreBundle\Entity\User;
use Xgc\CoreBundle\Exception\Http\AccessDeniedException;
use Xgc\CoreBundle\Exception\Http\AccountBeingCreatedException;
use Xgc\CoreBundle\Exception\Http\AccountDissabledException;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\PreconditionFailedException;
use Xgc\CoreBundle\Exception\Http\RequestBodyTooLargeException;
use Xgc\CoreBundle\Exception\Http\ResourceNoLongerAvailableException;
use Xgc\CoreBundle\Exception\Http\ResourceNotFoundException;
use Xgc\CoreBundle\Exception\Http\UnsupportedMediaTypeException;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\UtilsBundle\Helper\DateTime;
use Xgc\UtilsBundle\Helper\File as FileHelper;
use Xgc\UtilsBundle\Helper\Text;

class SecurityService
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function login(string $fqn, string $firewall, string $username, string $password, bool $remember): User
    {
        $doctrine = $this->container->get('doctrine');

        $user =
            $doctrine->getRepository($fqn)->findOneBy(['username' => $username]) ??
            $doctrine->getRepository($fqn)->findOneBy(['email' => $username]);

        if (!$user) {
            throw new AccessDeniedException();
        }

        if (!$user->hasPassword($password)) {
            throw new AccessDeniedException();
        }

        if (!$user->isEnabled()) {
            throw new AccountBeingCreatedException();
        }

        if ($user->isLocked()) {
            throw new AccountDissabledException();
        }

        $securityTokenStorage = $this->container->get('security.token_storage');
        $eventDispatcher = $this->container->get('event_dispatcher');

        if ($remember) {
            $token = new RememberMeToken($user, $firewall, $this->container->getParameter('secret'));
        } else {
            $token = new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
        }

        $securityTokenStorage->setToken($token);
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $event = new InteractiveLoginEvent($request, $token);
        $eventDispatcher->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);

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
        $token = $this->container->get('security.token_storage')->getToken();
        if (!$token) {
            return null;
        }
        $user = $token->getUser();
        if (!$user || is_string($user)) {
            return null;
        }

        return $user;
    }

    public function changePasswords(User $user, string $oldPassword, string $password, string $password2): User
    {
        if ($password !== $password2) {
            throw new PreconditionFailedException("Passwords missmatch");
        }

        if (!Text::validatePassword($password, 8, true, true, true, false)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        if (!$user->hasPassword($oldPassword)) {
            throw new PreconditionFailedException("Password is not correct");
        }

        $user->setPassword($password);

        $this->container->get('doctrine')->getManager()->flush();

        return $user;
    }

    public function resetPassword(string $fqn, string $token, string $password, string $password2): User
    {
        $doctrine = $this->container->get('doctrine');
        $user = $this->castToUser($doctrine->getRepository($fqn)->findOneBy(['resetPasswordToken' => $token]));

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
            $doctrine->getManager()->flush();
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

        $doctrine->getManager()->flush();

        return $user;
    }

    private function castToUser($object): User
    {
        return $object;
    }

    public function logout(): void
    {
        $request = $this->container->get('request_stack')->getCurrentRequest();
        $this->container->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
    }

    public function enable(string $fqn, string $token): User
    {
        $doctrine = $this->container->get('doctrine');
        $user = $this->castToUser($doctrine->getRepository($fqn)->findOneBy(['registerToken' => $token]));

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
            $doctrine->getManager()->flush();
            throw new ResourceNoLongerAvailableException('token');
        }

        $doctrine->getManager()->flush();

        return $user;
    }

    public function addResetPasswordToken(string $fqn, string $email): User
    {
        $doctrine = $this->container->get('doctrine');
        $user = $this->castToUser($doctrine->getRepository($fqn)->findOneBy(['registerToken' => $email]));

        if (!$user) {
            throw new ResourceNotFoundException('email');
        }

        $user->setResetPasswordToken(Text::rstr(32));
        $user->setResetPasswordTokenAt(new DateTime());

        $doctrine->getManager()->flush();

        return $user;
    }

    public function addRegisterToken(string $fqn, string $email): User
    {
        $doctrine = $this->container->get('doctrine');
        $user = $this->castToUser($doctrine->getRepository($fqn)->findOneBy(['registerToken' => $email]));

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

        $doctrine->getManager()->flush();

        return $user;
    }

    public function uploadAvatar(File $file, string $path, string $name)
    {

        if ($file->getSize() === false) {
            throw new RequestBodyTooLargeException();
        }

        if (!FileHelper::isImage($file->getRealPath())) {
            throw new UnsupportedMediaTypeException();
        }

        $user = $this->checkUser();
        $root = SymfonyHelper::getInstance()->getRoot();

        $size = 256;

        $extension = $file->guessExtension() ?? $file->guessExtension();

        $manager = new ImageManager(['driver' => 'Gd']);
        $image = $manager->make($file->getRealPath());

        if ($image->getHeight() > $image->getWidth()) {
            $newHeight = $image->getHeight() / ($image->getWidth() / $size);
            $image->resize($size, (int)$newHeight);
        } else {
            $newWidth = $image->getWidth() / ($image->getHeight() / $size);
            $image->resize((int)$newWidth, $size);
        }

        $x = max(($image->getWidth() - $size), 0) / 2;
        $y = max(($image->getHeight() - $size), 0) / 2;

        $image->crop($size, $size, $x, $y);
        $image->save("$root/web$path/$name.$extension", 100);

        $user->setAvatar("$path/$name.$extension");

        $this->container->get('doctrine')->getManager()->flush();
    }
}

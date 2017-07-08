<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Intervention\Image\ImageManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Xgc\CoreBundle\Entity\User;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\RequestBodyTooLargeException;
use Xgc\CoreBundle\Exception\Http\UnsupportedMediaTypeException;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\UtilsBundle\Helper\File as FileHelper;
use Xgc\UtilsBundle\Helper\Text;

class UserService
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setPassword(User $user, string $password)
    {

        $encoder = $this->container->get('security.password_encoder');

        $minLength  = $this->container->getParameter('xgc.security.password.minlength');
        $symbols    = $this->container->getParameter('xgc.security.password.symbols');
        $numbers    = $this->container->getParameter('xgc.security.password.numbers');
        $uppercases = $this->container->getParameter('xgc.security.password.uppercases');

        if (!Text::validatePassword($password, $minLength, true, $numbers, $uppercases, $symbols)) {
            throw new InvalidParamException('password', "Password insecure");
        }

        $password = $encoder->encodePassword($user, $password);
        $user->setPassword($password);

        return $user;
    }

    public function uploadAvatar(User $user, File $file, string $path, string $name)
    {

        if ($file->getSize() === false) {
            throw new RequestBodyTooLargeException();
        }

        if (!FileHelper::isImage($file->getRealPath())) {
            throw new UnsupportedMediaTypeException();
        }

        $root = SymfonyHelper::getInstance()->getRoot();

        $size = 256;

        $extension = $file->guessExtension() ?? $file->guessExtension();

        $manager = new ImageManager(['driver' => 'Gd']);
        $image   = $manager->make($file->getRealPath());

        if ($image->getHeight() > $image->getWidth()) {
            $newHeight = $image->getHeight() / ($image->getWidth() / $size);
            $image->resize($size, (int)$newHeight);
        } else {
            $newWidth = $image->getWidth() / ($image->getHeight() / $size);
            $image->resize((int)$newWidth, $size);
        }

        $x = max(($image->getWidth() - $size), 0) / 2;
        $y = max(($image->getHeight() - $size), 0) / 2;

        if (!file_exists("$root/web$path")) {
            mkdir("$root/web$path", $mode = 0777, true);
        }

        $image->crop($size, $size, $x, $y);
        $image->save("$root/web$path/$name.$extension", 100);

        $user->setAvatar("$path/$name.$extension");

        $this->container->get('doctrine')->getManager()->flush();
    }

    public function find(string $username): ?User
    {
        $repo = $this->container->get('doctrine')->getRepository(\AppBundle\Entity\User::class);

        return $repo->findOneBy(['username' => $username]) ??
               $repo->findOneBy(['email' => $username]);
    }
}

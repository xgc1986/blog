<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Xgc\CoreBundle\Helper\SymfonyHelper;
use Xgc\UtilsBundle\Helper\DateTime;

/**
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
abstract class User extends Entity implements AdvancedUserInterface, \Serializable
{
    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 4,
     *      max = 32
     * )
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 50,
     *      max = 256
     * )
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\Email
     * @Assert\Length(
     *      max = 255
     * )
     */
    protected $email;

    /**
     * @var string
     */
    protected $avatar;

    /**
     * @var ArrayCollection
     */
    protected $roles;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * @var bool
     */
    protected $locked;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 32,
     *      max = 32
     * )
     */
    protected $registerToken;

    /**
     * @var DateTime
     *
     * @Assert\DateTime()
     */
    protected $registerTokenAt;

    /**
     * @var string
     *
     * @Assert\Length(
     *      min = 32,
     *      max = 32
     * )
     */
    protected $resetPasswordToken;

    /**
     * @var DateTime
     *
     * @Assert\DateTime()
     */
    protected $resetPasswordTokenAt;

    /**
     * @var string
     * @Assert\Ip
     */
    protected $clientIp;

    function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->setLocked(false);
        $this->setEnabled(false);
        $this->setAvatar('/bundles/xgccore/img/avatar.png');
        $this->setCreatedAt(new DateTime());
    }

    function __toArray(): array
    {
        $ret = parent::__toArray();

        $ret['username'] = $this->getUsername();
        $ret['avatar'] = $this->getAvatar();
        $ret['createdAt'] = $this->getCreatedAt();

        $user = SymfonyHelper::getInstance()->getUser();
        if ($user === $this) {
            $ret['email'] = $this->getEmail();
            $ret['ip'] = $this->getClientIp();
        }

        return $ret;
    }

    public function __getType(): string
    {
        return 'user';
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize(): string
    {
        return serialize(
            [
                $this->getId(),
                $this->getPassword(),
                $this->getUsername(),
                $this->isEnabled(),
                $this->isLocked(),
                $this->getClientIp(),
            ]
        );
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return User
     * @since 5.1.0
     */
    public function unserialize($serialized): User
    {
        list (
            $this->id,
            $this->password,
            $this->username,
            $this->enabled,
            $this->locked,
            $this->clientIp
            ) = unserialize($serialized);

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     * @return array|ArrayCollection [] The user roles
     */
    public function getRoles(): array
    {
        return ($this->roles ?? new ArrayCollection())->toArray();
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return "";
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): User
    {
        return $this;
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired(): bool
    {
        return true;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked(): bool
    {
        return !$this->locked;
    }

    /**
     * @param bool $locked
     * @return $this
     */
    public function setLocked(bool $locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLocked(): bool
    {
        return $this->locked;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    /**
     * Checks whether the user is enabled.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a DisabledException and prevent login.
     *
     * @return bool true if the user is enabled, false otherwise
     *
     * @see DisabledException
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return User
     */
    public function setEnabled(bool $enabled): User
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getRegisterToken(): ?string
    {
        return $this->registerToken;
    }

    /**
     * @param string $registerToken
     * @return User
     */
    public function setRegisterToken(?string $registerToken): User
    {
        $this->registerToken = $registerToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    /**
     * @param string $resetPasswordToken
     * @return User
     */
    public function setResetPasswordToken(?string $resetPasswordToken): User
    {
        $this->resetPasswordToken = $resetPasswordToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * @param string $clientIp
     */
    public function setClientIp(string $clientIp)
    {
        $this->clientIp = $clientIp;
    }

    /**
     * @return DateTime
     */
    public function getResetPasswordTokenAt(): DateTime
    {
        return $this->resetPasswordTokenAt;
    }

    /**
     * @param DateTime $resetPasswordTokenAt
     */
    public function setResetPasswordTokenAt(?DateTime $resetPasswordTokenAt)
    {
        $this->resetPasswordTokenAt = $resetPasswordTokenAt;
    }

    /**
     * @return DateTime
     */
    public function getRegisterTokenAt(): DateTime
    {
        return $this->registerTokenAt;
    }

    /**
     * @param DateTime $registerTokenAt
     */
    public function setRegisterTokenAt(?DateTime $registerTokenAt)
    {
        $this->registerTokenAt = $registerTokenAt;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;
    }
}

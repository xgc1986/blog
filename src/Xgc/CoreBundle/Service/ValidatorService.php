<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\Service;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Xgc\CoreBundle\Entity\Entity;
use Xgc\CoreBundle\Exception\ExceptionHandler;
use Xgc\CoreBundle\Exception\Http\InternalErrorException;
use Xgc\CoreBundle\Exception\Http\InvalidParamException;
use Xgc\CoreBundle\Exception\Http\ResourceAlreadyExistsException;

class ValidatorService
{

    protected $validator;

    public function __construct(ValidatorInterface $val)
    {
        $this->validator = $val;
    }

    public function validate(Entity $entity): void
    {
        $errors = $this->validator->validate($entity);

        if (count($errors)) {
            foreach ($errors as $error) {
                $this->handle($error);
            }
        }
    }

    protected function handle(ConstraintViolation $error) {
        if ($error->getConstraint() instanceof UniqueEntity) {
            throw new ResourceAlreadyExistsException($error->getPropertyPath(), $error->getInvalidValue());
        } else if ($error->getConstraint() instanceof Length) {
            throw new InvalidParamException($error->getPropertyPath(), $error->getMessage());
        } else if ($error->getConstraint() instanceof Email) {
            throw new InvalidParamException($error->getPropertyPath(), $error->getMessage());
        } else {
            throw new InternalErrorException(null, new \Exception("Unmanaged constraint " . get_class($error->getConstraint())));
        }
    }
}

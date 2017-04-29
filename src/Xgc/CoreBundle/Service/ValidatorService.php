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

class ValidatorService
{

    protected $validator;

    public function __construct(ValidatorInterface $val)
    {
        $this->validator = $val;
    }

    public function validate(Entity $entity, ExceptionHandler $handler): void
    {
        $errors = $this->validator->validate($entity);

        if (count($errors)) {
            foreach ($errors as $error) {
                $this->handle($error, $handler);
            }
        }

    }

    protected function handle(ConstraintViolation $error, ExceptionHandler $handler) {
        if ($error->getConstraint() instanceof UniqueEntity) {
            $handler->throwResourceAlreadyExists($error->getPropertyPath(), $error->getInvalidValue());
        } else if ($error->getConstraint() instanceof Length) {
            $handler->throwInvalidParam($error->getPropertyPath(), $error->getMessage());
        } else if ($error->getConstraint() instanceof Email) {
            $handler->throwInvalidParam($error->getPropertyPath(), $error->getMessage());
        } else {
            $handler->throwInternalServerError(new \Exception("Unmanaged constraint " . get_class($error->getConstraint())));
        }
    }
}

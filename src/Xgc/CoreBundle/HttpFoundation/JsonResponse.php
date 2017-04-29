<?php
declare(strict_types=1);
namespace Xgc\CoreBundle\HttpFoundation;

class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    public function __construct($data = [], $status = 200, array $headers = [], $json = false)
    {
        $data['status'] = $status;
        ksort($data);
        parent::__construct($data, $status, $headers, $json);
    }
}

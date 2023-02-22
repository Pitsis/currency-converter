<?php

namespace App\Serializer;

use App\Entity\Currency;
use Symfony\Component\Routing\RouterInterface;

class CircularReferenceHandler
{
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router) 
    {
        $this->router = $router;
    }

    public function __invoke($object) 
    {
        if ($object instanceof Currency) {
            return $this->router->generate('get_currency', ['id' => $object->getId()]);
        }
    }
}

?>
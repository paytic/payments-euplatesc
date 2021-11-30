<?php

namespace Paytic\Payments\Euplatesc\Tests\Message;

use Paytic\Payments\Euplatesc\Message\ServerCompletePurchaseRequest;
use Paytic\Payments\Euplatesc\Message\ServerCompletePurchaseResponse;
use Paytic\Payments\Tests\Gateways\Message\ServerCompletePurchaseResponseTrait;
use Paytic\Payments\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ServerCompletePurchaseResponseTest
 * @package Paytic\Payments\Euplatesc\Tests\Message
 */
class ServerCompletePurchaseResponseTest extends AbstractTest
{
    use ServerCompletePurchaseResponseTrait;

    /**
     * @return ServerCompletePurchaseResponse
     */
    protected function getNewResponse()
    {
        $request = new ServerCompletePurchaseRequest($this->client, new Request());

        return new ServerCompletePurchaseResponse($request, []);
    }
}

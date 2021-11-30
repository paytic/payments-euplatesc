<?php

namespace Paytic\Payments\Euplatesc\Tests\Message;

use Paytic\Payments\Euplatesc\Message\CompletePurchaseResponse;
use Paytic\Payments\Euplatesc\Message\CompletePurchaseRequest;
use Paytic\Payments\Tests\AbstractTest;
use Paytic\Payments\Tests\Gateways\Message\CompletePurchaseResponseTestTrait;
use Symfony\Component\HttpFoundation\Request;

class CompletePurchaseResponseTest extends AbstractTest
{
    use CompletePurchaseResponseTestTrait;

    protected function getNewResponse()
    {
        $request = new CompletePurchaseRequest($this->client, new Request());

        return new CompletePurchaseResponse($request, []);
    }
}

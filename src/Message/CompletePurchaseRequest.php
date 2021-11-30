<?php

namespace Paytic\Payments\Euplatesc\Message;

use Paytic\Omnipay\Euplatesc\Message\CompletePurchaseRequest as AbstractCompletePurchaseRequest;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;
use Paytic\Payments\Euplatesc\Message\Traits\CompletePurchaseTrait;

/**
 * Class PurchaseResponse
 * @package Paytic\Payments\Euplatesc\Message
 */
class CompletePurchaseRequest extends AbstractCompletePurchaseRequest
{
    use HasModelRequest;
    use CompletePurchaseTrait;
}

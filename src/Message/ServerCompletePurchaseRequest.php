<?php

namespace Paytic\Payments\Euplatesc\Message;

use Paytic\Omnipay\Euplatesc\Message\ServerCompletePurchaseRequest as AbstractServerCompletePurchaseRequest;
use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasModelRequest;
use Paytic\Payments\Euplatesc\Message\Traits\CompletePurchaseTrait;

/**
 * Class ServerCompletePurchaseRequest
 * @package Paytic\Payments\Euplatesc\Message
 */
class ServerCompletePurchaseRequest extends AbstractServerCompletePurchaseRequest
{
    use HasModelRequest;
    use CompletePurchaseTrait;
}

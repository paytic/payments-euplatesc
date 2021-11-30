<?php

namespace Paytic\Payments\Euplatesc\Message\Traits;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Message\Traits\HasGatewayRequestTrait;
use Paytic\Payments\Euplatesc\Gateway;
use ByTIC\Payments\Models\Purchase\Traits\IsPurchasableModelTrait;

/**
 * Trait CompletePurchaseTrait
 * @package Paytic\Payments\Euplatesc\Message\Traits
 */
trait CompletePurchaseTrait
{
    use HasGatewayRequestTrait;

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $return = parent::getData();
        // Add model only if has data
        if (count($return)) {
            $return['model'] = $this->getModel();
        }

        return $return;
    }

    /**
     * @inheritdoc
     */
    protected function parseNotification()
    {
        if ($this->validateModel()) {
            $model = $this->getModel();
            $this->updateParametersFromPurchase($model);
        }

        return parent::parseNotification();
    }

    /**
     * @param Gateway $model
     */
    protected function updateParametersFromGateway($gateway)
    {
        $this->setMid($gateway->getMid());
        $this->setKey($gateway->getKey());
    }
}

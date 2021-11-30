<?php

namespace Paytic\Payments\Euplatesc;

use ByTIC\Payments\Gateways\Providers\AbstractGateway\Form as AbstractForm;

/**
 * Class Form
 * @package Paytic\Payments\Euplatesc
 */
class Form extends AbstractForm
{
    public function initElements()
    {
        $this->addInput('mid', 'MID');
        $this->addInput('key', 'Key');
    }
}

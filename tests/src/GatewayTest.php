<?php

namespace Paytic\Payments\Euplatesc\Tests;

use Paytic\Omnipay\Euplatesc\Message\PurchaseResponse;
use Paytic\Payments\Euplatesc\Message\CompletePurchaseResponse;
use Paytic\Payments\Euplatesc\Tests\Fixtures\Gateway\EuplatescData;
use Paytic\Payments\Tests\Fixtures\Records\PaymentMethods\PaymentMethod;

use Paytic\Payments\Tests\Gateways\GatewayTest as AbstractGatewayTest;
use Http\Discovery\Psr17FactoryDiscovery;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class TraitsTest
 * @package Paytic\Payments\Euplatesc\Tests
 */
class GatewayTest extends AbstractGatewayTest
{
    public function testPurchaseResponse()
    {
        //        Debug::debug($this->gateway->getParameters());
        $request = $this->gateway->purchaseFromModel($this->purchase);

        /** @var PurchaseResponse $response */
//        Debug::debug($request->getParameters());
        $response = $request->send();
        self::assertInstanceOf(PurchaseResponse::class, $response);

        $data = $response->getRedirectData();
//        Debug::debug($data);
        self::assertGreaterThan(18, count($data));
        self::assertSame('44840981287', $data['merch_id']);

        $gatewayResponse = $this->client->request(
            'POST',
            $response->getRedirectUrl(),
            ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'],
            Psr17FactoryDiscovery::findStreamFactory()->createStream(http_build_query($data, '', '&'))
        );
        self::assertSame(200, $gatewayResponse->getStatusCode());

        //Validate first Response
        $body = $gatewayResponse->getBody();

        if (strpos($body, '<META HTTP-EQUIV=') === false) {
            $crawler = new Crawler('<body>'.$body.'</body>', $gatewayResponse->getEffectiveUrl());
            $form = $crawler->filter('form')->form();

            self::assertSame('https://secure2.euplatesc.ro/tdsprocess/tranzactd.php', $form->getUri());
            self::assertCount(14, $form->getValues());

            $gatewaySecondResponse = $this->client->request(
                'POST',
                $response->getUri(),
                ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'],
                Psr17FactoryDiscovery::findStreamFactory()->createStream(http_build_query($form->getValues(), '', '&'))
            );
        } else {
            $uri = str_replace("<META HTTP-EQUIV='Refresh' CONTENT='0;URL=", '', $body);
            $uri = str_replace("'>", '', $uri);

            $gatewaySecondResponse = $this->client->request('GET', $uri);
        }

        //Validate first Response
        $body = $gatewaySecondResponse->getBody()->__toString();

        self::assertMatchesRegularExpression('/cart_id=/', $body);
//        self::assertMatchesRegularExpression('/name="mid"/', $body);
    }

    public function testCompletePurchaseResponse()
    {
        $httpRequest = EuplatescData::getCompletePurchaseRequest();
        $this->checkGenericCompletePurchaseResponse('completePurchase', $httpRequest);
    }

    public function testCompletePurchaseResponseError()
    {
        /** @var CompletePurchaseResponse $response */
        $response = $this->gatewayManager->detectItemFromHttpRequest(
            $this->purchaseManager,
            'completePurchase',
            EuplatescData::getCompletePurchaseRequestError()
        );
        self::assertInstanceOf(CompletePurchaseResponse::class, $response);
        self::assertEquals(3, $response->getCode());
        self::assertFalse($response->isSuccessful());
        self::assertFalse($response->isPending());
        self::assertFalse($response->isCancelled());
        self::assertSame('error', $response->getModelResponseStatus());
    }

    public function testServerCompletePurchaseAuthorizedResponse()
    {
        $httpRequest = EuplatescData::getServerCompletePurchaseRequest();
        $this->checkGenericCompletePurchaseResponse('serverCompletePurchase', $httpRequest);
    }

    /**
     * @param $type
     * @param $httpRequest
     */
    protected function checkGenericCompletePurchaseResponse($type, $httpRequest)
    {
        /** @var CompletePurchaseResponse $response */
        $response = $this->gatewayManager->detectItemFromHttpRequest(
            $this->purchaseManager,
            $type,
            $httpRequest
        );

//        self::assertInstanceOf(CompletePurchaseResponse::class, $response);
        self::assertEquals(0, $response->getCode());
        self::assertEquals('2016-02-17 14:32:52', $response->getTransactionDate());
        self::assertTrue($response->isSuccessful());
        self::assertEquals('active', $response->getModelResponseStatus());
        self::assertEquals($response->getTransactionId(), '24669');
    }

    protected function setUp() : void
    {
        parent::setUp();

        /** @var PaymentMethod $paymentMethod */
        $paymentMethod = $this->purchase->getPaymentMethod();
        $paymentMethod->options = trim(EuplatescData::getMethodOptions());

        $this->purchase->created = date('Y-m-d H:i:s');

        $this->gateway = $paymentMethod->getType()->getGateway();
    }
}

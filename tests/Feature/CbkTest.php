<?php

namespace Vector\Tests\Feature;

use Orchestra\Testbench\TestCase as Orchestra;
use Vector\LaravelMultiPaymentMethods\Facade\Payment;
use Vector\LaravelMultiPaymentMethods\Methods\Managers\PaymentManager;
use Vector\Tests\TestCase;

class CbkTest extends TestCase
{
    protected string $driver = "cbk";
    /**
     * Upayments Tests.
     *
     * @author Vector <mo.khaled.yousef@gmail.com>
     */

    /** @test * */
    public function test_user_can_pay(): void
    {

        $paymentDetails = ["transaction" => $this->getTransactionDetails(), "customer" => $this->getCustomerDetails(), "items" => $this->getItemsDetails()];
        $payment = new PaymentManager;
        $payment = (object)$payment->driver($this->driver)->pay($paymentDetails);
        dd($payment);
        $this->assertTrue($payment->success);
        $this->assertNotNull($payment->payment_url);
        $this->assertEquals(200, $payment->code);
    }

}

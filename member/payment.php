<?php

use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PayPalConnectionException;

require '../src/start.php';

$payer = new Payer();
$details = new Details();
$amount = new Amount();
$transation = new Transaction();
$payment = new Payment();
$redirectUrls = new RedirectUrls();

// Payer
$payer->setPaymentMethod('paypal');

$details->setShipping(2.00);

$details->setShipping(2.00)
    ->setTax(0.00)
    ->setSubtotal(20.00);

$amount->setCurrency('ILS')
    ->setTotal(22.00)
    ->setDetails($details);

$transation->setAmount($amount)
    ->setDescription('Membership');

$payment->setIntent('sale')
    ->setPayer($payer)
    ->setTransactions([$transation]);

$redirectUrls->setReturnUrl('http://local.paypal.co.il:8080/paypal/pay.php?approved=true')
    ->setCancelUrl('http://local.paypal.co.il:8080/paypal/pay.php?approved=false');

$payment->setRedirectUrls($redirectUrls);

try {
    $payment->create($api);

    $hash = md5($payment->getId());
    $_SESSION['paypal_hash'] = $hash;

    $store = $db->prepare("
        INSERT INTO transactions_paypal (user_id, payment_id, hash, complete)
        VALUES (:user_id, :payment_id, :hash, 0)
    ");

    $store->execute([
        'user_id' => $_SESSION['user_id'],
        'payment_id' => $payment->getId(),
        'hash' => $hash
    ]);
} catch (PayPalConnectionException $e) {
    header('location: ../paypal/error.php');
}

foreach ($payment->getLinks() as $link) {
    if ($link->getRel() == 'approval_url') {
        $redirectUrl = $link->getHref();
    }
}

header('Location:' . $redirectUrl);

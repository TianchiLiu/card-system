<?php
namespace App\Library\Pay\BTC; use App\Library\CurlRequest; use App\Library\Pay\ApiInterface; use Illuminate\Support\Facades\Log; class Api implements ApiInterface { private $url_notify = ''; private $url_return = ''; public function __construct($spe00284) { $this->url_notify = SYS_URL_API . '/pay/notify/' . $spe00284; $this->url_return = SYS_URL . '/pay/return/' . $spe00284; } function goPay($spc27de0, $spba04f6, $sp9f49de, $sp224c81, $sp6956b3) { $spd08fa9 = CurlRequest::get('https://api.blockchain.info/tobtc?currency=CNY&value=' . sprintf('%.2f', $sp6956b3 / 100)); if (!$spd08fa9) { Log::error('Pay.BTC.goPay, get price error:' . @$spd08fa9); throw new \Exception('获取BTC价格失败，请联系客服'); } $sp0f0547 = CurlRequest::get('https://api.blockchain.info/v2/receive?xpub=' . $spc27de0['xpub'] . '&callback=' . urlencode($this->url_notify . '?secret=' . $spc27de0['secret']) . '&key=' . $spc27de0['key']); $sp2a7a4a = @json_decode($sp0f0547, true); if (!$sp2a7a4a || !isset($sp2a7a4a['address'])) { if ($sp2a7a4a['description'] === 'Gap between last used address and next address too large. This might make funds inaccessible.') { throw new \Exception('钱包地址到达限制, 请等待之前的用户完成付款'); } Log::error('Pay.BTC.goPay, get address error:' . @$sp0f0547); throw new \Exception('获取BTC地址失败，请联系客服'); } $sp9c791f = 'bitcoin:' . $sp2a7a4a['address'] . '?amount=' . $spd08fa9; if (\App\Order::wherePayTradeNo($sp9c791f)->exists()) { throw new \Exception('支付失败, 当前钱包地址重复'); } \App\Order::whereOrderNo($spba04f6)->update(array('pay_trade_no' => $sp9c791f)); header('location: /qrcode/pay/' . $spba04f6 . '/btc?url=' . urlencode(json_encode(array('address' => $sp2a7a4a['address'], 'amount' => $spd08fa9)))); die; } function verify($spc27de0, $sp4294a3) { $spb2acff = isset($spc27de0['isNotify']) && $spc27de0['isNotify']; if ($spb2acff) { if (@$_GET['secret'] !== $spc27de0['secret']) { echo 'error'; return false; } if (isset($_GET['confirmations'])) { $spac227e = $_GET['address']; $sp9c791f = 'bitcoin:' . $spac227e . '?amount=' . rtrim(rtrim(sprintf('%.8f', $_GET['value'] / 100000000.0), '0'), '.'); $sp7dcca7 = \App\Order::wherePayTradeNo($sp9c791f)->first(); if (!$sp7dcca7) { echo 'error'; Log::error('Pay.BTC.verify, cannot find order:' . json_encode(array('url' => $sp9c791f, 'params' => $_GET))); return false; } $spefaf7d = $sp9c791f; $sp4294a3($sp7dcca7->order_no, $sp7dcca7->paid, $spefaf7d); } echo '*ok*'; return true; } else { return false; } } }
<?php
namespace App\Library\Pay\Qf; use App\Library\CurlRequest as Request; use App\Library\Pay\ApiInterface; use Illuminate\Support\Facades\Log; class Api implements ApiInterface { private $url_notify = ''; private $url_return = ''; public function __construct($spe00284) { $this->url_notify = SYS_URL_API . '/pay/notify/' . $spe00284; $this->url_return = SYS_URL . '/pay/return/' . $spe00284; } function goPay($spc27de0, $spba04f6, $sp9f49de, $sp224c81, $sp6956b3) { $spef6530 = strtolower($spc27de0['payway']); if (!isset($spc27de0['id'])) { throw new \Exception('请设置 id'); } $spdf7b97 = array(); if ($spef6530 == 'qq') { $spdf7b97 = array('User-Agent' => 'Mozilla/5.0 Mobile MQQBrowser/6.2 QQ/7.2.5.3305'); } elseif ($spef6530 == 'alipay') { $spdf7b97 = array('User-Agent' => 'Mozilla/5.0 AlipayChannelId/5136 AlipayDefined(nt:WIFI,ws:411|0|2.625) AliApp(AP/10.1.10.1226101) AlipayClient/10.1.10.1226101'); } $spcbe183 = Request::get('https://o2.qfpay.com/q/info?code=&huid=' . $spc27de0['id'] . '&opuid=&reqid=' . $spba04f6, $spdf7b97); $spd660f1 = static::str_between($spcbe183, 'reqid":"', '"'); $sp8bd560 = static::str_between($spcbe183, 'currency":"', '"'); if ($spd660f1 == '' || $sp8bd560 == '') { Log::error('qfpay pay, 获取支付金额失败 - ' . $spcbe183); throw new \Exception('获取支付请求id失败'); } $spe54c5c = Request::post('https://o2.qfpay.com/q/payment', 'txamt=' . $sp6956b3 . '&openid=&appid=&huid=' . $spc27de0['id'] . '&opuid=&reqid=' . $spd660f1 . '&balance=0&currency=' . $sp8bd560, $spdf7b97); $spb34b01 = json_decode($spe54c5c, true); $sp5deb2d = static::str_between($spe54c5c, 'syssn":"', '"'); if (!$spb34b01 || $sp5deb2d == '') { Log::error('qfpay pay, 生成支付单号失败#1 - ' . $spe54c5c); throw new \Exception('生成支付单号失败#1'); } if ($spb34b01['respcd'] !== '0000') { if (isset($spb34b01['respmsg']) && $spb34b01['respmsg'] !== '') { throw new \Exception($spb34b01['respmsg']); } Log::error('qfpay pay, 生成支付单号失败#2 - ' . $spe54c5c); throw new \Exception('生成支付单号失败#2'); } \App\Order::whereOrderNo($spba04f6)->update(array('pay_trade_no' => $sp5deb2d)); header('location: /qrcode/pay/' . $spba04f6 . '/qf_' . $spef6530 . '?url=' . urlencode(json_encode($spb34b01['data']['pay_params']))); } function verify($spc27de0, $sp4294a3) { $sp7dcca7 = \App\Order::whereOrderNo($spc27de0['out_trade_no'])->firstOrFail(); $sp5deb2d = $sp7dcca7->pay_trade_no; $sp37059f = Request::get('https://marketing.qfpay.com/v1/mkw/activity?syssn=' . $sp5deb2d); $sp0b52fc = json_decode($sp37059f, true); if (!$sp37059f) { throw new \Exception('query error'); } if (!isset($sp0b52fc['respcd'])) { Log::error('qfpay query, 获取支付结果失败 - ' . $sp37059f); throw new \Exception('获取支付结果失败'); } if ($sp0b52fc['respcd'] !== '0000') { return false; } $spddc088 = (int) static::str_between($sp37059f, 'trade_amt":', ','); if ($spddc088 === 0) { $spddc088 = (int) static::str_between($sp37059f, 'txamt":', ','); if ($spddc088 === 0) { Log::error('qfpay query, 获取支付金额失败 - ' . $sp37059f); throw new \Exception('获取支付金额失败'); } } if ($sp0b52fc['respcd'] === '0000') { $sp4294a3($spc27de0['out_trade_no'], $spddc088, $sp5deb2d); return true; } return false; } public static function str_between($sp3868ff, $sp5d78d8, $sp17cc54) { $spd3a47a = stripos($sp3868ff, $sp5d78d8); if ($spd3a47a === false) { return ''; } $sp7171e5 = stripos($sp3868ff, $sp17cc54, $spd3a47a + strlen($sp5d78d8)); if ($sp7171e5 === false || $spd3a47a >= $sp7171e5) { return ''; } $sp1c9539 = strlen($sp5d78d8); $sp29a775 = substr($sp3868ff, $spd3a47a + $sp1c9539, $sp7171e5 - $spd3a47a - $sp1c9539); return $sp29a775; } }
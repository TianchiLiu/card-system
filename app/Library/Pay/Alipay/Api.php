<?php
namespace App\Library\Pay\Alipay; use App\Library\Pay\ApiInterface; class Api implements ApiInterface { private $url_notify = ''; private $url_return = ''; private $pay_id; public function __construct($spe00284) { $this->url_notify = SYS_URL_API . '/pay/notify/' . $spe00284; $this->url_return = SYS_URL . '/pay/return/' . $spe00284; $this->pay_id = $spe00284; } function goPay($spc27de0, $spba04f6, $sp9f49de, $sp224c81, $sp6956b3) { $sp9b2bb6 = sprintf('%.2f', $sp6956b3 / 100); $spcdbaf5 = $this->buildAliConfig($spc27de0); require_once __DIR__ . '/sdk/alipay_submit.class.php'; $spcdbaf5['notify_url'] = $this->url_notify; $spcdbaf5['return_url'] = $this->url_return . '/' . $spba04f6; $sp18b39b = array('service' => $spcdbaf5['service'], 'partner' => $spcdbaf5['partner'], 'seller_id' => $spcdbaf5['seller_id'], 'payment_type' => $spcdbaf5['payment_type'], 'notify_url' => $spcdbaf5['notify_url'], 'return_url' => $spcdbaf5['return_url'], 'out_trade_no' => $spba04f6, 'total_fee' => $sp9b2bb6, 'subject' => $sp9f49de, 'body' => $sp224c81, 'show_url' => config('app.url'), 'app_pay' => 'Y', '_input_charset' => 'utf-8'); $spb37eaa = new \AlipaySubmit($spcdbaf5); $sp997379 = $spb37eaa->buildRequestForm($sp18b39b, 'get', '确认'); echo '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>正在跳转到支付渠道...</title></head><body><h1 style="text-align: center">正在跳转到支付渠道...</h1>'; echo $sp997379; } function verify($spc27de0, $sp4294a3) { $spb2acff = isset($spc27de0['isNotify']) && $spc27de0['isNotify']; $spcdbaf5 = $this->buildAliConfig($spc27de0); require __DIR__ . '/sdk/alipay_notify.class.php'; $spd33029 = new \AlipayNotify($spcdbaf5); if ($spb2acff) { $spb34b01 = $spd33029->verifyNotify(); } else { $spb34b01 = $spd33029->verifyReturn(); } if ($spb34b01) { $spba04f6 = $_REQUEST['out_trade_no']; $spa24b71 = $_REQUEST['trade_no']; $spff1a8e = $_REQUEST['trade_status']; $sp36f78e = (int) round($_REQUEST['total_fee'] * 100); if ($spff1a8e == 'TRADE_FINISHED' || $spff1a8e == 'TRADE_SUCCESS') { $sp4294a3($spba04f6, $sp36f78e, $spa24b71); } if ($spb2acff) { echo 'success'; } return true; } else { if ($spb2acff) { echo 'fail'; $spff31ad = 'payNotify pay_id: ' . $this->pay_id . ',Alipay'; } else { $spff31ad = 'payReturn pay_id: ' . $this->pay_id . ',Alipay'; } \Log::error($spff31ad . ' Alipay.Api.verify failed'); return false; } } private function buildAliConfig($spc27de0) { return array('partner' => $spc27de0['partner'], 'seller_id' => $spc27de0['partner'], 'key' => $spc27de0['key'], 'sign_type' => 'MD5', 'input_charset' => 'utf-8', 'cacert' => __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem', 'transport' => 'https', 'payment_type' => '1', 'service' => $spc27de0['payway'] === 'wap' ? 'alipay.wap.create.direct.pay.by.user' : 'create_direct_pay_by_user'); } }
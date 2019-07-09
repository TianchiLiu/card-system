<?php
namespace App\Library\Pay\Payjs; use App\Library\Pay\ApiInterface; require_once __DIR__ . '/sdk/Payjs.php'; use Xhat\Payjs\Payjs; class Api implements ApiInterface { private $url_notify = ''; private $url_return = ''; public function __construct($spe00284) { $this->url_notify = SYS_URL_API . '/pay/notify/' . $spe00284; $this->url_return = SYS_URL . '/pay/return/' . $spe00284; } function goPay($spc27de0, $spba04f6, $sp9f49de, $sp224c81, $sp6956b3) { if (!isset($spc27de0['mchid'])) { throw new \Exception('请填写mchid'); } if (!isset($spc27de0['key'])) { throw new \Exception('请填写key'); } $sp045e10 = new Payjs($spc27de0); $spd53b1c = strtolower($spc27de0['payway']); $spcb019a = array('total_fee' => $sp6956b3, 'out_trade_no' => $spba04f6, 'body' => $spba04f6, 'notify_url' => $this->url_notify, 'callback_url' => SYS_URL . '/pay/result/' . $spba04f6); if ($spd53b1c === 'native') { $spece3a7 = $sp045e10->native($spcb019a); if (@(int) $spece3a7['return_code'] !== 1) { die('<h1>支付渠道出错: ' . $spece3a7['msg'] . '</h1>'); } header('location: /qrcode/pay/' . $spba04f6 . '/payjs/' . base64_encode($spece3a7['code_url'])); } elseif ($spd53b1c === 'cashier') { $spece3a7 = $sp045e10->cashier($spcb019a); header('Location: ' . $spece3a7); } else { die('<h1>请填写支付方式</h1>'); } echo '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>正在跳转到支付渠道...</title></head><body><h1 style="text-align: center">正在跳转到支付渠道...</h1>'; die; } function verify($spc27de0, $sp4294a3) { $spb2acff = isset($spc27de0['isNotify']) && $spc27de0['isNotify']; $sp045e10 = new Payjs($spc27de0); if ($spb2acff) { $spb34b01 = $sp045e10->checkSign($_POST); echo $spb34b01 ? 'success' : 'fail'; } else { $spb34b01 = false; } if ($spb34b01) { $spba04f6 = $_REQUEST['out_trade_no']; $sp36f78e = $_REQUEST['total_fee']; $sp9c010a = $_REQUEST['payjs_order_id']; $sp4294a3($spba04f6, $sp36f78e, $sp9c010a); return true; } return false; } }
<?php
namespace App\Library\Pay\WeChat; require_once __DIR__ . '/lib/WxPay.Api.php'; require_once __DIR__ . '/lib/WxPay.Notify.php'; require_once 'WxLog.php'; class PayNotifyCallBack extends \WxPayNotify { private $successCallback = null; public function __construct($sp4294a3) { $this->successCallback = $sp4294a3; } public function QueryOrder($spb4e2d2) { $spd72f9e = new \WxPayOrderQuery(); $spd72f9e->SetTransaction_id($spb4e2d2); $spb34b01 = \WxPayApi::orderQuery($spd72f9e); \WxLog::DEBUG('query:' . json_encode($spb34b01)); if (array_key_exists('return_code', $spb34b01) && array_key_exists('result_code', $spb34b01) && $spb34b01['return_code'] == 'SUCCESS' && $spb34b01['result_code'] == 'SUCCESS') { return true; } return false; } public function NotifyProcess($spcb019a, &$sp2af324) { \WxLog::DEBUG('call back:' . json_encode($spcb019a)); if (!array_key_exists('transaction_id', $spcb019a)) { $sp2af324 = '输入参数不正确'; \WxLog::DEBUG('begin process 输入参数不正确'); return false; } if (!$this->QueryOrder($spcb019a['transaction_id'])) { $sp2af324 = '订单查询失败'; \WxLog::DEBUG('begin process 订单查询失败'); return false; } if ($this->successCallback) { call_user_func_array($this->successCallback, array($spcb019a['out_trade_no'], $spcb019a['total_fee'], $spcb019a['transaction_id'])); } return true; } }
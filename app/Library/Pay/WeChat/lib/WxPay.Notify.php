<?php
class WxPayNotify extends WxPayNotifyReply { public final function Handle($sp84d80a = true) { $spb34b01 = WxpayApi::notify(array($this, 'NotifyCallBack'), $sp2af324); if ($spb34b01 == false) { $this->SetReturn_code('FAIL'); $this->SetReturn_msg($sp2af324); $this->ReplyNotify(false); return; } else { $this->SetReturn_code('SUCCESS'); $this->SetReturn_msg('OK'); } $this->ReplyNotify($sp84d80a); } public function NotifyProcess($spcb019a, &$sp2af324) { return true; } public final function NotifyCallBack($spcb019a) { $sp2af324 = 'OK'; $spb34b01 = $this->NotifyProcess($spcb019a, $sp2af324); if ($spb34b01 == true) { $this->SetReturn_code('SUCCESS'); $this->SetReturn_msg('OK'); } else { $this->SetReturn_code('FAIL'); $this->SetReturn_msg($sp2af324); } return $spb34b01; } private final function ReplyNotify($sp84d80a = true) { if ($sp84d80a == true && $this->GetReturn_code() == 'SUCCESS') { $this->SetSign(); } WxpayApi::replyNotify($this->ToXml()); } }
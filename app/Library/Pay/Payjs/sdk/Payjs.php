<?php
namespace Xhat\Payjs; class Payjs { private $mchid; private $key; private $api_url_native; private $api_url_cashier; private $api_url_refund; private $api_url_close; private $api_url_check; private $api_url_user; private $api_url_info; private $api_url_bank; public function __construct($spc27de0 = null) { if (!$spc27de0) { die('config needed'); } $this->mchid = $spc27de0['mchid']; $this->key = $spc27de0['key']; $spf02c14 = isset($spc27de0['api_url']) ? $spc27de0['api_url'] : 'https://payjs.cn/api/'; $this->api_url_native = $spf02c14 . 'native'; $this->api_url_cashier = $spf02c14 . 'cashier'; $this->api_url_refund = $spf02c14 . 'refund'; $this->api_url_close = $spf02c14 . 'close'; $this->api_url_check = $spf02c14 . 'check'; $this->api_url_user = $spf02c14 . 'user'; $this->api_url_info = $spf02c14 . 'info'; $this->api_url_bank = $spf02c14 . 'bank'; } public function native(array $spcb019a) { $this->url = $this->api_url_native; return $this->post($spcb019a); } public function cashier(array $spcb019a) { $this->url = $this->api_url_cashier; $spcb019a = $this->sign($spcb019a); $sp3ae187 = $this->url . '?' . http_build_query($spcb019a); return $sp3ae187; } public function refund($sp9c010a) { $this->url = $this->api_url_refund; $spcb019a = array('payjs_order_id' => $sp9c010a); return $this->post($spcb019a); } public function close($sp9c010a) { $this->url = $this->api_url_close; $spcb019a = array('payjs_order_id' => $sp9c010a); return $this->post($spcb019a); } public function check($sp9c010a) { $this->url = $this->api_url_check; $spcb019a = array('payjs_order_id' => $sp9c010a); return $this->post($spcb019a); } public function user($sp2a473b) { $this->url = $this->api_url_user; $spcb019a = array('openid' => $sp2a473b); return $this->post($spcb019a); } public function info() { $this->url = $this->api_url_info; $spcb019a = array(); return $this->post($spcb019a); } public function bank($spcc609a) { $this->url = $this->api_url_bank; $spcb019a = array('bank' => $spcc609a); return $this->post($spcb019a); } public function notify() { $spcb019a = $_POST; if ($this->checkSign($spcb019a) === true) { return $spcb019a; } else { return '验签失败'; } } public function sign(array $spcb019a) { $spcb019a['mchid'] = $this->mchid; array_filter($spcb019a); ksort($spcb019a); $spcb019a['sign'] = strtoupper(md5(urldecode(http_build_query($spcb019a) . '&key=' . $this->key))); return $spcb019a; } public function checkSign($spcb019a) { $spe1cc1e = $spcb019a['sign']; unset($spcb019a['sign']); array_filter($spcb019a); ksort($spcb019a); $sp75e4cc = strtoupper(md5(urldecode(http_build_query($spcb019a) . '&key=' . $this->key))); return $spe1cc1e == $sp75e4cc ? true : false; } private static function curl_post($sp3ae187, $sp4aa83c = '') { $spdf7b97['Accept'] = '*/*'; $spdf7b97['Referer'] = $sp3ae187; $spdf7b97['Content-Type'] = 'application/x-www-form-urlencoded'; $spdf7b97['User-Agent'] = 'ni shi sha bi ba, yi ge sdk hai you di san fang ku'; $sp3c23e5 = array(); foreach ($spdf7b97 as $spee3fdf => $sp3c80c1) { $sp3c23e5[] = $spee3fdf . ': ' . $sp3c80c1; } $sp3c23e5[] = 'Expect:'; $sp4e752c = curl_init(); curl_setopt($sp4e752c, CURLOPT_URL, $sp3ae187); curl_setopt($sp4e752c, CURLOPT_POST, 1); curl_setopt($sp4e752c, CURLOPT_POSTFIELDS, $sp4aa83c); curl_setopt($sp4e752c, CURLOPT_TIMEOUT, 10); curl_setopt($sp4e752c, CURLOPT_CONNECTTIMEOUT, 10); curl_setopt($sp4e752c, CURLOPT_RETURNTRANSFER, 1); curl_setopt($sp4e752c, CURLOPT_HEADER, 1); curl_setopt($sp4e752c, CURLOPT_SSL_VERIFYHOST, 0); curl_setopt($sp4e752c, CURLOPT_SSL_VERIFYPEER, 0); curl_setopt($sp4e752c, CURLOPT_HTTPHEADER, $sp3c23e5); $spad32e6 = curl_exec($sp4e752c); $sp5ec78e = curl_getinfo($sp4e752c, CURLINFO_HEADER_SIZE); $sp224c81 = substr($spad32e6, $sp5ec78e); curl_close($sp4e752c); return $sp224c81; } public function post($spcb019a) { $spcb019a = $this->sign($spcb019a); return json_decode(self::curl_post($this->url, http_build_query($spcb019a)), true); } }
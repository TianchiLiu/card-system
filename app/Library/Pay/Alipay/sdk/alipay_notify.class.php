<?php
require_once 'alipay_core.function.php'; class AlipayNotify { var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&'; var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?'; var $alipay_config; function __construct($spcdbaf5) { $this->alipay_config = $spcdbaf5; } function AlipayNotify($spcdbaf5) { $this->__construct($spcdbaf5); } function verifyNotify() { if (empty($_POST)) { return false; } else { $sp2f21fb = $this->getSignVeryfy($_POST, $_POST['sign']); $sp4598be = 'false'; if (!empty($_POST['notify_id'])) { $sp4598be = $this->getResponse($_POST['notify_id']); } if (preg_match('/true$/i', $sp4598be) && $sp2f21fb) { return true; } else { return false; } } } function verifyReturn() { if (empty($_GET)) { return false; } else { $sp2f21fb = $this->getSignVeryfy($_GET, $_GET['sign']); $sp4598be = 'false'; if (!empty($_GET['notify_id'])) { $sp4598be = $this->getResponse($_GET['notify_id']); } if (preg_match('/true$/i', $sp4598be) && $sp2f21fb) { return true; } else { return false; } } } function getSignVeryfy($spa6f8bd, $sp75e4cc) { $sp126299 = paraFilter($spa6f8bd); $sp7775da = argSort($sp126299); $spf28067 = createLinkString($sp7775da); switch (strtoupper(trim($this->alipay_config['sign_type']))) { case 'MD5': $sp4953fd = md5Verify($spf28067, $sp75e4cc, $this->alipay_config['key']); break; default: $sp4953fd = false; } return $sp4953fd; } function getResponse($spe34c98) { $spef6a96 = strtolower(trim($this->alipay_config['transport'])); $sp982396 = trim($this->alipay_config['partner']); if ($spef6a96 == 'https') { $sp0c2923 = $this->https_verify_url; } else { $sp0c2923 = $this->http_verify_url; } $sp0c2923 = $sp0c2923 . 'partner=' . $sp982396 . '&notify_id=' . $spe34c98; $sp4598be = getHttpResponseGET($sp0c2923, $this->alipay_config['cacert']); return $sp4598be; } }
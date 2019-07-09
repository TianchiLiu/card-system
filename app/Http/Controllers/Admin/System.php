<?php
namespace App\Http\Controllers\Admin; use App\Library\Helper; use App\Library\Response; use Illuminate\Http\Request; use App\Http\Controllers\Controller; use Illuminate\Support\Facades\Mail; class System extends Controller { private function set(Request $spd5cc4d, $spad38fb) { foreach ($spad38fb as $spcc609a) { if ($spd5cc4d->has($spcc609a)) { \App\System::_set($spcc609a, $spd5cc4d->post($spcc609a)); } } } private function setMoney(Request $spd5cc4d, $spad38fb) { foreach ($spad38fb as $spcc609a) { if ($spd5cc4d->has($spcc609a)) { \App\System::_set($spcc609a, (int) round($spd5cc4d->post($spcc609a) * 100)); } } } private function setInt(Request $spd5cc4d, $spad38fb) { foreach ($spad38fb as $spcc609a) { if ($spd5cc4d->has($spcc609a)) { \App\System::_set($spcc609a, (int) $spd5cc4d->post($spcc609a)); } } } function setItem(Request $spd5cc4d) { $spcc609a = $spd5cc4d->post('name'); $spc82d84 = $spd5cc4d->post('value'); if (!$spcc609a || !$spc82d84) { return Response::forbidden(); } \App\System::_set($spcc609a, $spc82d84); return Response::success(); } function info(Request $spd5cc4d) { $sp7ea948 = array('app_name', 'app_title', 'app_url', 'app_url_api', 'keywords', 'description', 'shop_ann', 'shop_ann_pop', 'shop_qq', 'company', 'js_tj', 'js_kf'); $spe7d73e = array('shop_inventory'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($sp7ea948 as $spcc609a) { $spc27de0[$spcc609a] = \App\System::_get($spcc609a); } foreach ($spe7d73e as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $sp3ae187 = array('app_url' => Helper::format_url($_POST['app_url']), 'app_url_api' => Helper::format_url($_POST['app_url_api'])); $spd5cc4d->merge($sp3ae187); $this->set($spd5cc4d, $sp7ea948); $this->setInt($spd5cc4d, $spe7d73e); return Response::success(); } function theme(Request $spd5cc4d) { if ($spd5cc4d->isMethod('GET')) { \App\ShopTheme::freshList(); return Response::success(array('themes' => \App\ShopTheme::get(), 'default' => \App\ShopTheme::defaultTheme()->name)); } $sp54ae95 = \App\ShopTheme::whereName($spd5cc4d->post('shop_theme'))->firstOrFail(); \App\System::_set('shop_theme_default', $sp54ae95->name); $sp54ae95->config = @json_decode($spd5cc4d->post('theme_config')) ?? array(); $sp54ae95->saveOrFail(); return Response::success(); } function order(Request $spd5cc4d) { $spad38fb = array('order_clean_unpay_open', 'order_clean_unpay_day'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($spad38fb as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $this->setInt($spd5cc4d, $spad38fb); return Response::success(); } function vcode(Request $spd5cc4d) { $sp7ea948 = array('vcode_driver', 'vcode_geetest_id', 'vcode_geetest_key'); $spe7d73e = array('vcode_login', 'vcode_shop_buy', 'vcode_shop_search'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($sp7ea948 as $spcc609a) { $spc27de0[$spcc609a] = \App\System::_get($spcc609a); } foreach ($spe7d73e as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $this->set($spd5cc4d, $sp7ea948); $this->setInt($spd5cc4d, $spe7d73e); return Response::success(); } function email(Request $spd5cc4d) { $sp7ea948 = array('mail_driver', 'mail_smtp_host', 'mail_smtp_port', 'mail_smtp_username', 'mail_smtp_password', 'mail_smtp_from_address', 'mail_smtp_from_name', 'mail_smtp_encryption', 'sendcloud_user', 'sendcloud_key'); $spe7d73e = array('mail_send_order'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($sp7ea948 as $spcc609a) { $spc27de0[$spcc609a] = \App\System::_get($spcc609a); } foreach ($spe7d73e as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $this->set($spd5cc4d, $sp7ea948); $this->setInt($spd5cc4d, $spe7d73e); return Response::success(); } function sms(Request $spd5cc4d) { $sp7ea948 = array('sms_api_id', 'sms_api_key'); $spe7d73e = array('sms_send_order', 'sms_price'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($sp7ea948 as $spcc609a) { $spc27de0[$spcc609a] = \App\System::_get($spcc609a); } foreach ($spe7d73e as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $this->set($spd5cc4d, $sp7ea948); $this->setInt($spd5cc4d, $spe7d73e); return Response::success(); } function storage(Request $spd5cc4d) { $sp7ea948 = array('storage_driver', 'storage_s3_access_key', 'storage_s3_secret_key', 'storage_s3_region', 'storage_s3_bucket', 'storage_oss_access_key', 'storage_oss_secret_key', 'storage_oss_bucket', 'storage_oss_endpoint', 'storage_oss_cdn_domain', 'storage_qiniu_domains_default', 'storage_qiniu_domains_https', 'storage_qiniu_access_key', 'storage_qiniu_secret_key', 'storage_qiniu_bucket', 'storage_qiniu_notify_url'); $spe7d73e = array('storage_oss_is_ssl', 'storage_oss_is_cname'); if ($spd5cc4d->isMethod('GET')) { $spc27de0 = array(); foreach ($sp7ea948 as $spcc609a) { $spc27de0[$spcc609a] = \App\System::_get($spcc609a); } foreach ($spe7d73e as $spcc609a) { $spc27de0[$spcc609a] = (int) \App\System::_get($spcc609a); } return Response::success($spc27de0); } $this->set($spd5cc4d, $sp7ea948); $this->set($spd5cc4d, $spe7d73e); return Response::success(); } function emailTest(Request $spd5cc4d) { $this->validate($spd5cc4d, array('to' => 'required')); $spa6aeab = $spd5cc4d->post('to'); try { $sp29a775 = Mail::to($spa6aeab)->send(new \App\Mail\Test()); return Response::success($sp29a775); } catch (\Throwable $spece20f) { \App\Library\LogHelper::setLogFile('mail'); \Log::error('Mail Test Exception:' . $spece20f->getMessage()); return Response::fail($spece20f->getMessage(), $spece20f); } } function orderClean(Request $spd5cc4d) { $this->validate($spd5cc4d, array('day' => 'required|integer|min:1')); $sp135b94 = (int) $spd5cc4d->post('day'); \App\Order::where('status', \App\Order::STATUS_UNPAY)->where('created_at', '<', (new \Carbon\Carbon())->addDays(-$sp135b94))->delete(); return Response::success(); } }
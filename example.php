<?php
require "zerossl.php";

$api = new ZeroSSLAPI("7b5a1f142db51537453685e0cc544f0c",true);


print_r($api->order_cert("example.com", file_get_contents("csr.txt")));
print_r($api->get_certs());
print_r($api->get_certs('example.com'));
print_r($api->get_cert('56f43f950c292a6f14440d5740'));
print_r($api->get_challenges('56f43f950c292a6f14440d5740'));
print_r($api->send_challenges('56f43f950c292a6f14440d5740'));
print_r($api->get_cert_content('56f43f950c292a6f14440d5740'));
print_r($api->cancel_cert('56f43f950c292a6f14440d5740'));
print_r($api->revoke_cert('56f43f950c292a6f14440d5740'));
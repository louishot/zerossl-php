<?php

class ZeroSSLAPI
{
    private $api_url = "https://api.zerossl.com";

    public function __construct($api_key, $debug = false)
    {
        $this->api_key = $api_key;
        $this->debug = $debug;
    }

    private $CA_USERTrust = "-----BEGIN CERTIFICATE-----
MIIFgTCCBGmgAwIBAgIQOXJEOvkit1HX02wQ3TE1lTANBgkqhkiG9w0BAQwFADB7
MQswCQYDVQQGEwJHQjEbMBkGA1UECAwSR3JlYXRlciBNYW5jaGVzdGVyMRAwDgYD
VQQHDAdTYWxmb3JkMRowGAYDVQQKDBFDb21vZG8gQ0EgTGltaXRlZDEhMB8GA1UE
AwwYQUFBIENlcnRpZmljYXRlIFNlcnZpY2VzMB4XDTE5MDMxMjAwMDAwMFoXDTI4
MTIzMTIzNTk1OVowgYgxCzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpOZXcgSmVyc2V5
MRQwEgYDVQQHEwtKZXJzZXkgQ2l0eTEeMBwGA1UEChMVVGhlIFVTRVJUUlVTVCBO
ZXR3b3JrMS4wLAYDVQQDEyVVU0VSVHJ1c3QgUlNBIENlcnRpZmljYXRpb24gQXV0
aG9yaXR5MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAgBJlFzYOw9sI
s9CsVw127c0n00ytUINh4qogTQktZAnczomfzD2p7PbPwdzx07HWezcoEStH2jnG
vDoZtF+mvX2do2NCtnbyqTsrkfjib9DsFiCQCT7i6HTJGLSR1GJk23+jBvGIGGqQ
Ijy8/hPwhxR79uQfjtTkUcYRZ0YIUcuGFFQ/vDP+fmyc/xadGL1RjjWmp2bIcmfb
IWax1Jt4A8BQOujM8Ny8nkz+rwWWNR9XWrf/zvk9tyy29lTdyOcSOk2uTIq3XJq0
tyA9yn8iNK5+O2hmAUTnAU5GU5szYPeUvlM3kHND8zLDU+/bqv50TmnHa4xgk97E
xwzf4TKuzJM7UXiVZ4vuPVb+DNBpDxsP8yUmazNt925H+nND5X4OpWaxKXwyhGNV
icQNwZNUMBkTrNN9N6frXTpsNVzbQdcS2qlJC9/YgIoJk2KOtWbPJYjNhLixP6Q5
D9kCnusSTJV882sFqV4Wg8y4Z+LoE53MW4LTTLPtW//e5XOsIzstAL81VXQJSdhJ
WBp/kjbmUZIO8yZ9HE0XvMnsQybQv0FfQKlERPSZ51eHnlAfV1SoPv10Yy+xUGUJ
5lhCLkMaTLTwJUdZ+gQek9QmRkpQgbLevni3/GcV4clXhB4PY9bpYrrWX1Uu6lzG
KAgEJTm4Diup8kyXHAc/DVL17e8vgg8CAwEAAaOB8jCB7zAfBgNVHSMEGDAWgBSg
EQojPpbxB+zirynvgqV/0DCktDAdBgNVHQ4EFgQUU3m/WqorSs9UgOHYm8Cd8rID
ZsswDgYDVR0PAQH/BAQDAgGGMA8GA1UdEwEB/wQFMAMBAf8wEQYDVR0gBAowCDAG
BgRVHSAAMEMGA1UdHwQ8MDowOKA2oDSGMmh0dHA6Ly9jcmwuY29tb2RvY2EuY29t
L0FBQUNlcnRpZmljYXRlU2VydmljZXMuY3JsMDQGCCsGAQUFBwEBBCgwJjAkBggr
BgEFBQcwAYYYaHR0cDovL29jc3AuY29tb2RvY2EuY29tMA0GCSqGSIb3DQEBDAUA
A4IBAQAYh1HcdCE9nIrgJ7cz0C7M7PDmy14R3iJvm3WOnnL+5Nb+qh+cli3vA0p+
rvSNb3I8QzvAP+u431yqqcau8vzY7qN7Q/aGNnwU4M309z/+3ri0ivCRlv79Q2R+
/czSAaF9ffgZGclCKxO/WIu6pKJmBHaIkU4MiRTOok3JMrO66BQavHHxW/BBC5gA
CiIDEOUMsfnNkjcZ7Tvx5Dq2+UUTJnWvu6rvP3t3O9LEApE9GQDTF1w52z97GA1F
zZOFli9d31kWTz9RvdVFGD/tSo7oBmF0Ixa1DVBzJ0RHfxBdiSprhTEUxOipakyA
vGp4z7h/jnZymQyd/teRCBaho1+V
-----END CERTIFICATE-----";

    public function Requests($url, $method = 'GET', $headers = [], $data = null)
    {
        $curl = curl_init();
        $response_headers = [];
        $headerCallback = function ($curl, $header) use (&$response_headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }
            $response_headers[strtolower(trim($header[0]))][] = trim($header[1]);
            return $len;
        };

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($curl, CURLOPT_HEADERFUNCTION, $headerCallback);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);


        if (in_array($method, ['POST', 'PUT', 'PATCH']) && !is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : $data);
        }

        if (!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($curl);
        $req_headers = curl_getinfo($curl, CURLINFO_HEADER_OUT);
        $req_headers = explode("\n", $req_headers);
        $error = curl_error($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $data = [
            'req_url' => $url,
            'req_method' => $method,
            'req_headers' => $req_headers,
            'response' => $response,
            'response_headers' => $response_headers,
            'error' => $error,
            'response_code' => $statusCode
        ];

        if ($this->debug) {
            print_r($data);
        }

        return $data;
    }

    public function get_certs($search = null, $page = 1, $limit = 1000)
    {

        $url_base = "{api_url}/certificates?access_key={api_key}&page={page}&limit={limit}";
        if ($search) {
            $url_base = "{api_url}/certificates?access_key={api_key}&search={search}&page={page}&limit={limit}";
        }
        $url = strtr(
            $url_base,
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{search}" => $search,
                "{page}" => $page,
                "{limit}" => $limit
            ]
        );
        $res = $this->Requests($url);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response'], true);
    }

    public function get_cert($id)
    {
        $url = strtr(
            "{api_url}/certificates/{id}?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id,
            ]
        );
        $res = $this->Requests($url);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response']);
    }

    public function get_cert_content($id, $compatibility = true)
    {
        $url = strtr(
            "{api_url}/certificates/{id}/download/return?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id,
                "{include_cross_signed}" => 1,
            ]
        );
        $res = $this->Requests($url);

        if ($res['response_code'] != 200) {
            return false;
        }

        $response = json_decode($res['response'], true);

        if ($compatibility) {

            $ca_bundle = $response['ca_bundle.crt'] . $this->CA_USERTrust;
            $response['ca_bundle.crt'] = $ca_bundle;
            return $response;
        }

        return $response;
    }

    public function order_cert($domains, $csr, $days = 90, $strict = 0)
    {
        $url = strtr(
            "{api_url}/certificates?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key
            ]
        );
        $data = [
            "certificate_domains" => $domains,
            "certificate_csr" => $csr,
            "certificate_validity_days" => $days,
            "strict_domains" => $strict,
        ];
        $res = $this->Requests($url, 'POST', null, $data);

        if ($res['response_code'] != 200) {
            return false;
        }
        $response = json_decode($res['response'], true);
        $domains = explode(',', $domains);
        $domain = $domains[0];
        $validation = $response['validation'];
        $validation_summary = [
            "eamil" => $validation['email_validation'][$domain],
            "file" => [
                "url" => $validation['other_methods'][$domain]['file_validation_url_http'],
                "surl" => $validation['other_methods'][$domain]['file_validation_url_https'],
                "content" => $validation['other_methods'][$domain]['file_validation_content']
            ],
            "dns" => [
                "type" => "CNAME",
                "fqdn" => $validation['other_methods'][$domain]['cname_validation_p1'],
                "value" => $validation['other_methods'][$domain]['cname_validation_p2'],
            ]

        ];
        $response['validation_summary'] = $validation_summary;

        return $response;
    }

    public function get_challenges($id)
    {
        $url = strtr(
            "{api_url}/certificates/{id}/status?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id
            ]
        );
        $res = $this->Requests($url);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response'], true);
    }

    public function send_challenges($id, $method = 'HTTP_CSR_HASH', $email = null)
    {
        $url = strtr(
            "{api_url}/certificates/{id}/challenges?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id,
            ]
        );
        $data = [
            "validation_method" => $method
        ];
        if ($email) {
            $data['validation_email'] = $email;
        }
        $res = $this->Requests($url, 'POST', null, $data);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response'], true);
    }

    public function cancel_cert($id)
    {
        $url = strtr(
            "{api_url}/certificates/{id}/cancel?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id,
            ]
        );

        $res = $this->Requests($url, 'POST', null);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response'], true);
    }

    public function revoke_cert($id, $reason = null)
    {
        $url = strtr(
            "{api_url}/certificates/{id}/revoke?access_key={api_key}",
            [
                "{api_url}" => $this->api_url,
                "{api_key}" => $this->api_key,
                "{id}" => $id,
            ]
        );

        $data = null;
        if ($reason) {
            $data = [
                'reason' => $reason
            ];
        }

        $res = $this->Requests($url, 'POST', null, $data);

        if ($res['response_code'] != 200) {
            return false;
        }

        return json_decode($res['response'], true);
    }
}
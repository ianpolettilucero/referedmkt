<?php
use Core\GoogleApi;

/**
 * El intercambio con Google necesita credenciales reales, pero el JWT que lo
 * inicia se puede probar entero: firma RS256 verificable y claims correctos.
 */
TestRunner::group('Google API', function () {

    TestRunner::run('el JWT de la cuenta de servicio se firma y se verifica', function () {
        $key = openssl_pkey_new(['private_key_bits' => 2048, 'private_key_type' => OPENSSL_KEYTYPE_RSA]);
        assert_true($key !== false, 'openssl no pudo generar una clave de prueba');
        openssl_pkey_export($key, $pem);
        $pub = openssl_pkey_get_details($key)['key'];

        $sa = ['client_email' => 'lector@proyecto.iam.gserviceaccount.com', 'private_key' => $pem,
               'private_key_id' => 'abc123', 'token_uri' => 'https://oauth2.googleapis.com/token'];
        $jwt = GoogleApi::jwt($sa, GoogleApi::SCOPES, 1_800_000_000);

        [$h, $c, $s] = explode('.', $jwt);
        $dec = fn($x) => base64_decode(strtr($x, '-_', '+/') . str_repeat('=', (4 - strlen($x) % 4) % 4));
        assert_eq(1, openssl_verify("$h.$c", $dec($s), $pub, OPENSSL_ALGO_SHA256));

        $header = json_decode($dec($h), true);
        assert_eq('RS256', $header['alg']);
        assert_eq('abc123', $header['kid']);

        $claims = json_decode($dec($c), true);
        assert_eq('lector@proyecto.iam.gserviceaccount.com', $claims['iss']);
        assert_eq('https://oauth2.googleapis.com/token', $claims['aud']);
        assert_eq(1_800_003_600, $claims['exp']);
        assert_contains('webmasters.readonly', $claims['scope']);
        assert_contains('analytics.readonly', $claims['scope']);
    });

    TestRunner::run('solo pide alcances de lectura', function () {
        foreach (GoogleApi::SCOPES as $s) {
            assert_contains('.readonly', $s);
        }
    });

    TestRunner::run('una clave sin client_email se rechaza', function () {
        $threw = false;
        try { new GoogleApi(['private_key' => 'x']); } catch (\InvalidArgumentException $e) { $threw = true; }
        assert_true($threw);
    });
});

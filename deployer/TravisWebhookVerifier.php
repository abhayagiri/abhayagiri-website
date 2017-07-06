<?php
/**
 *
 * Usage:
 *
 * $payload = $_POST['payload'];
 * $signature = $_SERVER['HTTP_SIGNATURE'];
 * if (TravisWebhookVerifier::verify($payload, $signature)) {
 *     // all is well...
 * } else {
 *     // invalid webhook
 * }
 *
 */

class TravisWebhookVerifier
{

    const DEFAULT_API_HOST = 'https://api.travis-ci.org';

    protected static $cachedPublicKey = null;

    public static function verify($payload, $signature)
    {
        $id = openssl_pkey_get_public(self::getCachedPublicKey());
        $result = openssl_verify($payload, base64_decode($signature), $id);
        openssl_free_key($id);
        if ($result === 1) {
            return true;
        } else {
            return false;
        }
    }

    public static function getPublicKey()
    {
        $response = file_get_contents(self::DEFAULT_API_HOST . '/config');
        return json_decode($response)->config->notifications->webhook->public_key;
    }

    public static function getCachedPublicKey()
    {
        if (!self::$cachedPublicKey) {
            self::$cachedPublicKey = self::getPublicKey();
        }
        return self::$cachedPublicKey;
    }

}

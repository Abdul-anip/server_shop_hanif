<?php
class SimpleJWT {
    // Secret key for signing the token - Change this to a secure random string!
    private static $secret_key = 'RahasiaDapurHanifShop2024!@#';
    
    // Algorithm used for signing
    private static $alg = 'HS256';

    /**
     * Encode data to JWT Token
     */
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$alg]);
        
        // Add expiration if not exists (default 24 hours)
        if (!isset($payload['exp'])) {
            $payload['exp'] = time() + (24 * 60 * 60);
        }
        
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    /**
     * Decode and Verify Token
     */
    public static function decode($jwt) {
        if (!$jwt) return null;
        
        // Remove 'Bearer ' prefix if exists
        $jwt = str_replace('Bearer ', '', $jwt);
        
        $tokenParts = explode('.', $jwt);
        
        if (count($tokenParts) != 3) {
            return null; // Invalid token format
        }
        
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];
        
        // Check signature
        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);
        
        if ($base64UrlSignature !== $signature_provided) {
            return null; // Invalid signature
        }
        
        // Check expiration
        $payloadObj = json_decode($payload);
        if (isset($payloadObj->exp) && $payloadObj->exp < time()) {
            return null; // Token expired
        }
        
        return (array)$payloadObj;
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
?>

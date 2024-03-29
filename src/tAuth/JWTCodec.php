<?php

namespace App;

use App\Shared\Exceptions\InvalidSignatureException;
use App\Shared\Exceptions\TokenExpiredException;
use InvalidArgumentException;

class JWTCodec
{
    private string $key;

    public function __construct()
    {
        $this->key = $_ENV["SECRET_KEY"];
    }

    public function encode(array $payload): string
    {
        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);

        $header = $this->base64UrlEncode($header);

        $payload = json_encode($payload);
        $payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac("sha256",
                                $header . "." . $payload,
                                $this->key,
                                true);
                                
        $signature = $this->base64UrlEncode($signature);

        return $header . "." . $payload . "." . $signature;
    }

    public function decode(string $token): array
    {
        if (preg_match("/^(?<header>.+)\.(?<payload>.+)\.(?<signature>.+)$/",
                    $token, 
                    $matches) !==1) {

            throw new InvalidArgumentException("invalid token format");
        }

        $signature = hash_hmac("sha256",
                                $matches["header"] . "." . $matches["payload"],
                                $this->key,
                                true);

        $signatureFromToken = $this->base64urlDecode($matches["signature"]);

        if (!hash_equals($signature, $signatureFromToken)) {

            throw new InvalidSignatureException();
        }
        

        $payload = json_decode($this->base64urlDecode($matches["payload"]), true);

        if ($payload["exp"] < time()) {
            throw new TokenExpiredException();
        }

        return $payload;
    }

    //php no tiene un método para codficar url en base64
    private function base64UrlEncode(string $text): string
    {
        $encodedText = base64_encode($text);

        return str_replace(
            ["+", "/", "="],
            ["-", "_", ""],
            $encodedText
        );
    }

    private function base64urlDecode(string $text): string
    {
        return base64_decode(str_replace(
            ["-", "_"],
            ["+", "/"],
            $text)
        );
    }
}

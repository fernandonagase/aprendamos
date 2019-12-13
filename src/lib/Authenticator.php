<?php
namespace aprendamos\lib;

class Authenticator
{
    // Definitely not the best way to store a session
    // Storing user's name here strongly couples Authenticator with the Model
    public static function createSession(int $userId, string $name)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['userId'] = $userId;
        $_SESSION['userName'] = $name;
    }

    public static function authorize(): bool
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (!isset($_SESSION['userId'])) {
            session_destroy();
            return false;
        }
        return true;
    }

    public static function currentUser(): int
    {
        if (self::authorize()) {
            return $_SESSION['userId'];
        }
        return -1;
    }

    public static function disconnect()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION['userId']);
    }
}
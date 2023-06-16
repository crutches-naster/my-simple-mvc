<?php

namespace App\Helpers;

class Session
{
    static public function check(): bool
    {
        return !empty($_SESSION['user_data']);
    }

    static public function id(): int|null
    {
        return $_SESSION['user_data']['id'] ?? null;
    }

    static public function setUserData($id, $options = []) : bool
    {
        $options = array_merge(
            compact('id'),
            $options
        );

        $_SESSION['user_data'] = array_merge(
            $_SESSION['user_data'] ?? [],
            $options
        );

        return true;
    }

    static public function destroy() : bool
    {
        if (session_id()) {
            return session_destroy();
        }

        return false;
    }
}

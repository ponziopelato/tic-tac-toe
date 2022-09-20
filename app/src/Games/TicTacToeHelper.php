<?php
declare(strict_types=1);

namespace App\Games;

class TicTacToeHelper
{
    public static function checkPayload ($body)
    {
        $errors = [];

        if (!isset($body['player']) || $body['player'] <= 0 || $body['player'] > 2) {
            $errors[] = 'Player not set/invalid';
        }

        if (!isset($body['move']) || !is_array($body['move']) ||
            count($body['move']) == 0 || count($body['move']) > 2 ||
            $body['move'][0] < 0 || $body['move'][0] > 2 ||
            $body['move'][1] < 0 || $body['move'][1] > 2
        ) {
            $errors[] = 'Move not set/invalid';
        }

        return $errors;
    }
}
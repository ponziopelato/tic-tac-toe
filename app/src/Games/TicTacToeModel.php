<?php
declare(strict_types=1);

namespace App\Games;

class TicTacToeModel
{
    public static function playGame ($board, $move, $player)
    {
        foreach ($move as $key => $value) {
            $count = 0;

            for ($i = 0; $i < 3; $i++) {
            	if ($key == 0) {
            	    $check = $board[$value][$i];
		} else {
            	    $check = $board[$i][$value];
		}
		
                if ($check == $player) {
                    $count++;
                }
            }

            if ($count == 3) {
                return 'win';
            }
        }

        if (($board[0][0] == $player && $board[1][1] == $player && $board[2][2] == $player) ||
            ($board[0][2] == $player && $board[1][1] == $player && $board[2][0] == $player))  {
            return 'win';
        } else {
            $count = 0;

            foreach ($board as $values) {
                foreach ($values as $value) {
                    if (empty($value)) {
                        $count++;
                    }
                }
            }

            if ($count == 0) {
                return 'draw';
            }
        }

        return '-';
    }
}

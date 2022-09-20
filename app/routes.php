<?php
// Routes
use App\Games\TicTacToeController;
use App\Generic;

$app->get('/', Generic::class . ':healthCheck');

$app->group('/games', function () use ($app): void {

    $app->post('/tic-tac-toe', TicTacToeController::class . ':newGame');

    $app->patch('/tic-tac-toe/{game_id}', TicTacToeController::class . ':playGame');

});
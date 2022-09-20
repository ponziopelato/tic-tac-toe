<?php
declare(strict_types=1);

namespace App\Games;

use App\Generic;

class TicTacToeController extends Generic
{
    protected $cache;

    public function __construct($container)
    {
        parent::__construct($container);
        $cache = $container->get('cache');
        $this->cache = $cache;
    }

    public function newGame ($request, $response, $args)
    {
        try {
            $uuid = uniqid('game');
            $this->cache->set($uuid, serialize(['last_player_turn' => null, 'board' => [['','',''],['','',''],['','','']], 'game_end' => false, 'result' => null]));
        } catch (\Exception $e) {
            return $this->responder($e->getCode(), $e->getMessage());
        }

        return $this->responder(200, ['game_id' => $uuid]);
    }

    public function playGame ($request, $response, $args)
    {
        $gameId = $args['game_id'] ?? null;
        $body = $request->getParsedBody();

        $checkPayload = TicTacToeHelper::checkPayload($body);

        if (count($checkPayload) > 0) {
            return $this->responder(400, ['errors' => $checkPayload]);
        }

        try {
            $cache = $this->cache->get($gameId);

            if (!is_string($cache)) {
                return $this->responder(400, ['error' => 'Not valid game_id']);
            }

            $data = unserialize($cache);

            if ($data['game_end']) {
                return $this->responder(400, ['error' => 'Game is already ended']);
            }

            if (!is_null($data['last_player_turn']) && $body['player'] == $data['last_player_turn']) {
                return $this->responder(400, ['error' => 'Player ' . $body['player'] . ' is not your turn']);
            }

            if (!empty($data['board'][$body['move'][0]][$body['move'][1]])) {
                return $this->responder(400, ['error' => 'Player ' . $body['player'] . ' not valid move']);
            }

            $data['last_player_turn'] = $body['player'];
            $data['board'][$body['move'][0]][$body['move'][1]] = $body['player'];

            $gameResult = TicTacToeModel::playGame($data['board'], $body['move'], $body['player']);

            $data['result'] = $gameResult;

            $result = [
                'board' => $data['board'],
                'winner' => false,
                'player_won' => null
            ];

            if ($gameResult == 'win') {
                $data['game_end'] = true;
                $result['winner'] = true;
                $result['player_won'] = $body['player'];
            } else if ($gameResult == 'draw') {
                $data['game_end'] = true;
            }

            $this->cache->set($gameId, serialize($data));
        } catch (\Exception $e) {
            return $this->responder($e->getCode(), $e->getMessage());
        }

        return $this->responder(200, $result);
    }
}
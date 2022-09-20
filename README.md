# Start project:

    $ docker-compose up

## Run it:

1. Open a new prompt tab
2. `$ docker exec -ti tic-tac-toe_php_1 bash`
3. `$ composer install`
4. Browse to `http://localhost`, server should be up and running

## List of cURL to test the project:

#### Create a new game 
    
Endpoint: POST `http://localhost/games/tic-tac-toe`. It will return the game id

#### Play the game

Endpoint: PATCH `http://localhost/games/tic-tac-toe/{game_id}`. 

Body: `{"player":{player_number}}, "move":{coordinates}}`

#### Example:

POST `http://localhost/games/tic-tac-toe`

It will return the game id (for example) `game6329c85ddb8dd`.

PATCH `http://localhost/games/tic-tac-toe/game6329c85ddb8dd`

##### List of moves:

1. `{"player":1, "move":[0,0]}`
   1. Return {"board": [[1,"",""],["","",""],["","",""]],"winner": false,"player_won": null}
2. `{"player":2, "move":[1,1]}`
   1. Return {"board": [[1,"",""],["","2",""],["","",""]],"winner": false,"player_won": null}
3. `{"player":1, "move":[1,2]}`
    1. Return {"board": [[1,"",""],["","2","1"],["","",""]],"winner": false,"player_won": null}
4. `{"player":2, "move":[2,0]}`
    1. Return {"board": [[1,"",""],["","2","1"],["2","",""]],"winner": false,"player_won": null}
5. `{"player":1, "move":[0,2]}`
    1. Return {"board": [[1,"","1"],["","2","1"],["2","",""]],"winner": false,"player_won": null}
6. `{"player":2, "move":[2,2]}`
    1. Return {"board": [[1,"","1"],["","2","1"],["2","","2"]],"winner": false,"player_won": null} 
7. `{"player":1, "move":[0,1]}`
    1. Return {"board": [[1,"1","1"],["","2","1"],["2","","2"]],"winner": true,"player_won": 1}

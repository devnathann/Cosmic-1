<?php
namespace App\Controllers\Games;

use App\Config;

use App\Models\Player;
use App\Models\Community;

use Core\Locale;
use Core\View;

class Ranking
{
    public function index()
    {
        $currencys = array();
        foreach(Config::currencys as $currency => $type) 
        {
            $highscores = Community::getCurrencyHighscores($type, 7);
            $type = $currency;
          
            foreach($highscores as $highscore) {
                $highscore->player = Player::getDataById($highscore->user_id, array('username', 'look'));
            }
          
            $currencys[$type] = $highscores;
        }
      
        $achievements = Player::getByAchievement();
        foreach ($achievements as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
        
        View::renderTemplate('Games/ranking.html', [
            'title' => Locale::get('core/title/games/ranking'),
            'page'  => 'games_ranking',
            'achievements' => $achievements,
            'currencys'  => $currencys
        ]);
    }
}
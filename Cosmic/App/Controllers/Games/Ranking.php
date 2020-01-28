<?php
namespace App\Controllers\Games;

use App\Config;

use App\Models\Player;
use App\Models\Core;
use App\Models\Community;

use Core\Locale;
use Core\View;

class Ranking
{
    public function index()
    {
        $currencys = array();
        foreach(Core::getCurrencys() as $type) 
        {
            $highscores = Community::getCurrencyHighscores($type->type, 6);
            $type = $type->currency;
            
            foreach($highscores as $highscore) {
                $highscore->player = Player::getDataById($highscore->user_id, ['username', 'look']);
            }
          
            $currencys[$type] = $highscores;
        }
      
        $credits = Community::getCredits(6);
        foreach ($credits as $item) 
        {
            $item->player = Player::getDataById($item->id, array('username', 'look'));
        }
      
        $achievements = Community::getAchievement(6);
        foreach ($achievements as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
     
        $respectreceived = Community::getRespectsReceived(6);
        foreach ($respectreceived as $item) 
        {
            $item->player = Player::getDataById($item->user_id, array('username', 'look'));
        }
        
        View::renderTemplate('Games/ranking.html', [
            'title' => Locale::get('core/title/games/ranking'),
            'page'  => 'games_ranking',
            'achievements' => $achievements,
            'credits' => $credits,
            'respects' => $respectreceived,
            'currencys'  => $currencys
        ]);
    }
}
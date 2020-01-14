<?php
namespace Library\Installation;

use App\Config;
use App\Auth;

use App\Models\Install;
use App\Models\Player;

use Core\Session;;
use Core\View;

use stdClass;
use PDO;

class Home
{
    public function dbconnection()
    {

        $validate = request()->validator->validate([
            'host' => 'required|min:1|max:30',
            'username' => 'required|min:1|max:100',
            'database' => 'required|min:1|max:100',
            'password' => 'required|min:1|max:100'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }

        $hostname = input()->post('host')->value;
        $database = input()->post('database')->value;
        $username = input()->post('username')->value;
        $password = input()->post('password')->value;

        if(!Install::checkConnection($hostname, $username, $database, $password)) {
            echo '{"status":"error","message":"Database connection failed!"}';
            exit;
        }

        $this->saveDatabase(input()->all());
    }

    public function saveDatabase($data) {

        unset($data['installation/api/home/install']);
        foreach($data as $key => $value) {
            $find    = $key . ' = \'\'';
            $replace = $key . ' = \''.$value.'\'';

            if(Install::editConfig($find, $replace) !== true) {
                Install::rollback();
            }
        }

        echo '{"status":"success"}';
    }

    public function createtables()
    {
        $dbSettings = input()->all();

        unset($dbSettings['installation/api/home/install']);
        foreach($dbSettings as $key => $value) {
            $find    = $key . ' = \'\'';
            $replace = $key . ' = \''.$value.'\'';

            if(Install::editConfig($find, $replace) !== true) {
                Install::rollback();
            }
        }

        if(Install::createTables()) {
            echo '{"status":"success","message":"Database imported"}';
        }
    }

    public function createuser()
    {
        $validate = request()->validator->validate([
            'username'              => 'required|min:2|max:15|pattern:[a-zA-Z0-9-=?!@:.]+',
            'password'              => 'required|min:6|max:32',
            'password_repeat'       => 'required|same:password',
            'email'                 => 'required'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }

        $username = input()->post('username')->value;
        $email = input()->post('email')->value;
        $password = input()->post('password')->value;

        Session::set('username', $username);
        if(Install::createUser($username, $email, $password)) {
            echo '{"status":"success","message":"User created!"}';
        }
    }

    public function array2string($data){
        $log_a = "";

        $i = 0;
        $len = count($data);
        foreach ($data as $key => $value) {
            if(is_array($value))  {
                $log_a .= "'".$key."' => (". array2string($value). ") \n";
            } else {
                if ($i == $len - 1) {
                    $log_a .= "'".$key."' => ".$value."\n";
                } else {
                    $log_a .= "'".$key."' => ".$value.",\n";
                }
            }
            $i++;
        }
        return $log_a;
    }

    public function array2stringa($data){
        $log_a = "";

        $i = 0;
        $len = count($data);
        foreach ($data as $key => $value) {
            if(is_array($value))  {
                $log_a .= "'".$key."' => (". array2string($value). ") \n";
            } else {
                if ($i == $len - 1) {
                    $log_a .= "'".$key."' => 1000\n";
                } else {
                    $log_a .= "'".$key."' => 1000,\n";
                }
            }
            $i++;
        }
        return $log_a;
    }

    public function complete()
    {
        $configuration = input()->all();

        $validate = request()->validator->validate([
            'SECRET_TOKEN'      => 'required|min:10',
            'domain'            => 'required',
            'path'              => 'required',
            'swfPath'           => 'required',
            'figurePath'        => 'required',
            'clientHost'        => 'required',
            'clientPort'        => 'required|numeric',
            'apiHost'           => 'required',
            'apiPort'           => 'required|numeric',
            'currencys'         => 'required',
            'payCurrency'       => 'required|numeric',
            'credits'           => 'required|numeric',
            'points'            => 'required|numeric',
            'pixels'            => 'required|numeric',
            'language'          => 'required'
        ]);

        if (!$validate->isSuccess()) {
            exit;
        }

        $currencys = $configuration['currencys'];
        $validJson = is_string($currencys) && is_array(json_decode($currencys, true));
        if ($validJson == false) {
             echo '{"status":"error","message":"Currency is not in a correct JSON format."}';
             exit;
        }

        /* TODO */
        unset($configuration['installation/api/home/complete']);
        unset($configuration['api_host']);
        unset($configuration['api_port']);
        unset($configuration['currencys']);

        foreach($configuration as $key => $value) {
            $find    = $key . ' = \'\'';
            $replace = $key . ' = \''.$value.'\'';

            Install::editConfig($find, $replace);

            $find    = $key . ' = 0';
            $replace = $key . ' = ' . $value;

            Install::editConfig($find, $replace);
        }


        $decode = json_decode($currencys, true);
        $currencys = 'array( ' . $this->array2string($decode) . ')';
        $freeCurrencys = 'array( ' . $this->array2stringa($decode) . ')';

        Install::editConfig('language = \'EN\'', 'language = \''. $configuration['language'] . '\'');
        Install::editConfig('currencys = null', 'currencys = ' . $currencys);
        Install::editConfig('freeCurrency = null', 'freeCurrency = ' . $freeCurrencys);
        Install::editConfig('view = \'Library/Installation/Views\'', 'view = \'App/View\'');
        Install::editConfig('installation = true', 'installation = false');
        Install::editConfig('debug = true', 'debug = false');

        $player = Player::getDataByUsername(Session::get('username'), array('id', 'password', 'rank'));
        Auth::login($player);

        echo '{"status":"success","message":"Install done! Please wait we redirect you!"}';
    }

    public function index()
    {
        $step = null;
        if(Install::checkConnection(Config::host, Config::username, Config::database, Config::password, 'install')) {
            $step = 'connectionSuccess';

            if(Install::checkTables()){
                $step = 'tablesExists';
            }
        }

        View::renderTemplate('home.html', ['step' => $step]);
    }

}

<?php
namespace App\Models;

mysqli_report(MYSQLI_REPORT_STRICT);

use App\Core;
use App\Hash;
use App\Config;

use mysqli;

class Install {
  
    public static $exception;
  
    public static $path = __DIR__ . '/../../App/Config.php';
    public static $tmp = __DIR__ . '/../../Library/Installation/Config.tmp';
  
    public static function checkConnection($host, $user, $db, $pass, $param = false) {
      
        $dsn = 'mysql:dbname=' . $db . ';host=' . $host;
      
        try {
          return new mysqli($host, $user, $pass, $db);
        } catch (\mysqli_sql_exception  $e) {
            if(empty($param))
            self::rollback('Can\'t connect to mysql database');
        }
    }
  
    public static function rollback($message = false) {

        if(self::dropTables()) {
            $copy = copy(self::$tmp, self::$path);
            if ($copy) {
                $message = (!$message) ? self::$exception : $message;
                echo '{"status":"error","message":"Rollback started: ' . $message . '"}';
                exit;
            }
        }
    }
  
    public static function editConfig($OldText, $NewText) {
        $FilePath = self::$path;
        if (!file_exists($FilePath)) {
            self::$exception = 'File ' . $FilePath . ' does not exist !';
        }
        if (is_writeable($FilePath)) {
            self::$exception = 'File ' . $FilePath . ' is not writable !';
        }
        try {
            $FileContent = file_get_contents($FilePath);
            $FileContent = str_replace($OldText, $NewText, $FileContent);
            if (file_put_contents($FilePath, $FileContent) > 0) {
                return true;
            } else {
                self::$exception = 'Error while writing file';
            }
        }
        catch(Exception $e) {
            self::$exception = 'Error : ' . $e;
        }
    }
  
    public static function checkTables() {
        $conn = self::checkConnection(Config::host, Config::username, Config::database, Config::password);
      
       if(mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'website_alert_messages'"))) {
            return true;
        }
    }
  
    public static function createUser($username, $mail, $password) {
          
          $conn = self::checkConnection(Config::host, Config::username, Config::database, Config::password);
          $query = "INSERT INTO users (username,password,mail,account_created,credits,pixels,points,look,account_day_of_birth,gender,rank,last_login,ip_register,ip_current) VALUES ('" . $username . "', '" . Hash::password($password) . "', '" . $mail . "', '" . time() . "', '100', '100', '100', 'hr-802-37.hd-185-1.ch-804-82.lg-280-73.sh-3068-1408-1408.wa-2001', '0', 'M', '7', '" . time() . "', '" . Core::getIpAddress() . "', '" . Core::getIpAddress() . "')";
          if ($conn->query($query) !== true) {
              echo $conn->error;
          }
      
          $inserted_id = $conn->insert_id;
          $query = "INSERT INTO users_settings (user_id) VALUES ('" . $inserted_id . "')";
          if ($conn->query($query) !== true) {
              echo $conn->error;
          }
      
          $query = "INSERT INTO users_currency (user_id, type, amount) VALUES ('" . $inserted_id . "', '0', '1000')";
          if ($conn->query($query) !== true) {
              echo $conn->error;
          }
      
          $query = "INSERT INTO users_currency (user_id, type, amount) VALUES ('" . $inserted_id . "', '5', '1000')";
          if ($conn->query($query) !== true) {
              echo $conn->error;
          }
      
          $query = "INSERT INTO users_currency (user_id, type, amount) VALUES ('" . $inserted_id . "', '103', '1000')";
          if ($conn->query($query) !== true) {
              echo $conn->error;
          }

          return true;
    }

    public static function dropTables() {
        $conn = self::checkConnection(Config::host, Config::username, Config::database, Config::password);
        if($result = $conn->query('SHOW TABLES')){
            while($row = $result->fetch_assoc()){
                if( strpos($row['Tables_in_' . Config::database],  'website_') !== false) {
                    $query = "DROP TABLE " . $row['Tables_in_' . Config::database];
                    if ($conn->query($query) !== true) {
                        echo $conn->error;
                    }
                }
            }
            $query = "ALTER TABLE users DROP pincode";
            if ($conn->query($query) !== true) {
                return false;
            }
            $query = "ALTER TABLE users DROP secret_key";
            if ($conn->query($query) !== true) {
                return false;
            }
            return true;
        }
    }
  
    public static function createTables() {
      
      
        $conn = self::checkConnection(Config::host, Config::username, Config::database, Config::password);
      
         if(mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'users'")) == 0) {
            echo '{"status":"error","message":"Please import the arcturus database first!"}';
            exit;
        }
      
         if(mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'website_alert_messages'"))) {
            self::rollback();
            exit;
        }
      
        $alertMessages = "
            CREATE TABLE `website_alert_messages` (
              `id` int(11) NOT NULL,
              `title` varchar(50) NOT NULL DEFAULT '',
              `message` varchar(150) NOT NULL DEFAULT 'Unacceptable for the Hotel Management'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
      
        if ($conn->query($alertMessages) !== true) {
            self::rollback($conn->error);
        }
      
        $alertMessagesInsert = "
            INSERT INTO `website_alert_messages` (`id`, `title`, `message`) VALUES
            (1, 'Use of language', 'Watch your language! You will be banned on repeated occasions.'),
            (2, 'Act as Staff', 'Acting as Staff is against the rules. You will be banned on repeated occasions.'),
            (3, 'Talking about retro hotels', 'Talking about retro hotels is against the rules! At repetition you will be banned.'),
            (4, 'Requesting/giving away personal information', 'Asking/giving away personal data is against the rules! You will be banned on repeated occasions.'),
            (5, 'Ask/give away Social Media', 'Ask/giving away snapchat, insta or other Social Media is against the rules! You will be banned on repeated occasions.'),
            (6, 'Unacceptable language/behavior', 'Unacceptable language/behavior is against the rules! You will be banned on repeated occasions.'),
            (7, 'Harassment', 'Don\'t bother other players! You will be banned on repeated occasions.'),
            (8, 'Sexual conversations/behaviors', 'Sexual conversations or behaviors are against the rules! You will be banned on repeated occasions.')
        ";
        if ($conn->query($alertMessagesInsert) !== true) {
            self::rollback($conn->error);
        }
        $banAsnMessages = "
            CREATE TABLE `website_bans_asn` (
              `id` int(11) NOT NULL,
              `asn` int(11) NOT NULL,
              `host` varchar(100) NOT NULL DEFAULT '',
              `added_by` varchar(75) NOT NULL DEFAULT '0',
              `timestamp` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($banAsnMessages) !== true) {
            self::rollback($conn->error);
        }
        $banAsnMessaages = "
            CREATE TABLE `website_ban_messages` (
              `id` int(11) NOT NULL,
              `message` varchar(75) NOT NULL DEFAULT 'Unacceptable for the Hotel Management'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($banAsnMessaages) !== true) {
            self::rollback($conn->error);
        }
        $banAsnMessagesInsert = "
            INSERT INTO `website_ban_messages` (`id`, `message`) VALUES
            (1, 'Advertising for Retro Hotels'),
            (2, 'Highlight one or more players'),
            (3, 'Illegal activities'),
            (4, 'Hate speech/discrimination'),
            (5, 'Pedophile activities'),
            (6, 'Requesting/giving away personal information'),
            (7, 'Ask/giving away snapchat, insta or other Social Media'),
            (8, 'Harassment/unacceptable language or behavior'),
            (9, 'Order disturbance'),
            (10, 'Embarrassing sexual behaviors'),
            (11, 'Requesting/offering webscam sex or sexual images'),
            (12, 'Threat of one or more players with ddos/hack/expose'),
            (13, 'Act as Staff'),
            (14, 'Username in violation of the rules')
        ";
        if ($conn->query($banAsnMessagesInsert) !== true) {
            self::rollback($conn->error);
        }
        $banTypes = "
            CREATE TABLE `website_ban_types` (
              `id` int(11) NOT NULL,
              `seconds` int(10) NOT NULL DEFAULT 7200,
              `message` varchar(50) NOT NULL DEFAULT '',
              `min_rank` int(10) NOT NULL DEFAULT 6
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($banTypes) !== true) {
            self::rollback($conn->error);
        }
        $banTypesInsert = "
            INSERT INTO `website_ban_types` (`id`, `seconds`, `message`, `min_rank`) VALUES
            (1, 7200, '2 hours', 4),
            (2, 14400, '4 hours', 4),
            (3, 28800, '8 hours', 4),
            (4, 43200, '12 hours', 4),
            (5, 86400, '1 day', 6),
            (6, 259200, '3 days', 6),
            (7, 604800, '1 week', 6),
            (8, 2629743, '1 month', 6),
            (9, 7889231, '3 months', 6),
            (10, 946707780, 'permanent', 6)
        ";
        if ($conn->query($banTypesInsert) !== true) {
            self::rollback($conn->error);
        }
        $webConfig = "
        CREATE TABLE `website_jobs_applys` (
          `id` int(11) NOT NULL,
          `job_id` int(11) DEFAULT NULL,
          `user_id` int(11) DEFAULT NULL,
          `firstname` varchar(50) DEFAULT NULL,
          `message` text DEFAULT NULL,
          `available_monday` varchar(50) DEFAULT NULL,
          `available_tuesday` varchar(50) DEFAULT NULL,
          `available_wednesday` varchar(50) DEFAULT NULL,
          `available_thursday` varchar(50) DEFAULT NULL,
          `available_friday` varchar(50) DEFAULT NULL,
          `available_saturday` varchar(50) DEFAULT NULL,
          `available_sunday` varchar(50) DEFAULT NULL,
          `status` enum('open','closed') DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        if ($conn->query($webConfig) !== true) {
            self::rollback($conn->error);
        }
        $webConfig = "
            CREATE TABLE `website_jobs` (
              `id` int(11) NOT NULL,
              `job` varchar(40) NOT NULL,
              `small_description` varchar(255) NOT NULL,
              `full_description` longtext NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        if ($conn->query($webConfig) !== true) {
            self::rollback($conn->error);
        }
        $webConfig = "
            CREATE TABLE `website_config` (
              `maintenance` varchar(1) NOT NULL DEFAULT 0,
              `revision` varchar(50) DEFAULT ''
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webConfig) !== true) {
            self::rollback($conn->error);
        }
        $webConfigInsert = "
            INSERT INTO `website_config` (`maintenance`, `revision`) VALUES
            ('0', '0')
        ";
        if ($conn->query($webConfigInsert) !== true) {
            self::rollback($conn->error);
        }
        $webFeeds = "
            CREATE TABLE `website_feeds` (
              `id` int(11) NOT NULL,
              `to_user_id` int(11) NOT NULL,
              `message` text NOT NULL,
              `timestamp` int(11) NOT NULL,
              `from_user_id` int(11) NOT NULL,
              `is_hidden` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webFeeds) !== true) {
            self::rollback($conn->error);
        }
        $webFeedsLikes = "
            CREATE TABLE `website_feeds_likes` (
              `id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `feed_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webFeedsLikes) !== true) {
            self::rollback($conn->error);
        }
        $webFeedsReactions = "
            CREATE TABLE `website_feeds_reactions` (
              `id` int(11) NOT NULL,
              `feed_id` int(11) NOT NULL,
              `message` text NOT NULL,
              `timestamp` int(11) NOT NULL DEFAULT 0,
              `user_id` int(11) NOT NULL DEFAULT 0,
              `is_hidden` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webFeedsReactions) !== true) {
            self::rollback($conn->error);
        }
        $webForumLikes = "
            CREATE TABLE `website_forum_likes` (
              `id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `post_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumLikes) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpCat = "
          CREATE TABLE `website_helptool_categories` (
            `id` int(11) NOT NULL,
            `category` varchar(50) NOT NULL DEFAULT ''
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumHelpCat) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpFaq = "
          CREATE TABLE `website_helptool_faq` (
            `id` int(11) NOT NULL,
            `slug` varchar(100) NOT NULL DEFAULT '',
            `title` varchar(100) NOT NULL DEFAULT '',
            `desc` text NOT NULL,
            `category` int(11) NOT NULL DEFAULT 0,
            `timestamp` int(11) NOT NULL DEFAULT 0,
            `author` int(11) NOT NULL DEFAULT 0
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumHelpFaq) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpFaq = "
          CREATE TABLE `website_rare_values` (
            `id` int(11) NOT NULL,
            `slug` varchar(255) DEFAULT NULL,
            `cat_name` varchar(255) DEFAULT NULL,
            `cat_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
            `is_hidden` enum('0','1') DEFAULT NULL,
            `discount` int(3) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
        if ($conn->query($webForumHelpFaq) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpLogs = "
            CREATE TABLE `website_helptool_logs` (
              `id` int(11) NOT NULL,
              `player_id` int(11) NOT NULL DEFAULT 0,
              `target` varchar(50) NOT NULL DEFAULT '',
              `value` varchar(255) DEFAULT '',
              `timestamp` int(11) NOT NULL DEFAULT 0,
              `type` enum('CHANGE','SEND') DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumHelpLogs) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpReactions = "
            CREATE TABLE `website_helptool_reactions` (
              `id` int(11) NOT NULL,
              `request_id` int(11) DEFAULT NULL,
              `practitioner_id` int(11) NOT NULL,
              `message` text DEFAULT NULL,
              `timestamp` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumHelpReactions) !== true) {
            self::rollback($conn->error);
        }
        $webForumHelpReq = "
            CREATE TABLE `website_helptool_requests` (
              `id` int(11) NOT NULL,
              `subject` varchar(255) NOT NULL DEFAULT '',
              `message` text NOT NULL,
              `email` varchar(72) NOT NULL DEFAULT '',
              `player_id` int(11) DEFAULT 0,
              `ip_address` varchar(75) NOT NULL,
              `timestamp` int(11) NOT NULL DEFAULT 0,
              `status` enum('closed','open','in_treatment','wait_reply') DEFAULT 'open'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumHelpReq) !== true) {
            self::rollback($conn->error);
        }
        $webForumNewsq = "
            CREATE TABLE `website_news` (
              `id` int(11) NOT NULL,
              `slug` varchar(255) NOT NULL DEFAULT '',
              `title` varchar(255) NOT NULL DEFAULT '0',
              `short_story` text NOT NULL,
              `full_story` text NOT NULL,
              `images` text NOT NULL,
              `author` int(11) NOT NULL DEFAULT 0,
              `header` varchar(255) NOT NULL DEFAULT '',
              `category` int(4) NOT NULL DEFAULT 0,
              `form` enum('none','photo','badge','look','word') NOT NULL DEFAULT 'none',
              `timestamp` int(11) NOT NULL DEFAULT 0,
              `hidden` enum('0','1') NOT NULL DEFAULT '0'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumNewsq) !== true) {
            self::rollback($conn->error);
        }
        $webForumNewsq = "
	    INSERT INTO `website_news` (`id`, `slug`, `title`, `short_story`, `full_story`, `images`, `author`, `header`, `category`, `form`, `timestamp`, `hidden`) VALUES
	      (1, 'welcome-to-cosmiccms', 'Welcome to CosmicCMS', 'Curious about CosmicCMS\'s functions? Then be sure to read this!', '<p>Hey you! Who the hell are you?! Are you using CosmicCMS?!<br /><br />You managed to get Cosmic working!<br /><br />Our team has worked very hard on Cosmic to keep the installation as easy as possible. Cosmic has been specially developed with a new proprietary written Framework. So it\'s normal that you won\'t know how everything works yet, hence the installation in the beginning!<br /><br />Cosmic offers many functions, all of which are easy to use. Just think of adding currency ids, adding and editing furniture, managing the FAQ, managing and answering help tickets, VPN control, remote control (RCON), and much more!<br /><br />Since Cosmic already has the basic functions, you won\'t have to add anything yourself, but of course you can! Though you will have to learn the Framework first. We have thought about this as well. Our team has provided a documentation that will allow you to write new features in our Framework within minutes/hours/days/weeks! This depends on the skills you already have!<br /><br />Unfortunately Cosmic only works on Arcturus Morningstar, this means that you cannot set Cosmic to Plus Emulator/Comet Server or R63 Emulators. Now the question is, can I also use Cosmic on the normal Arcturus or upcoming Sirius Emulator? We advise you not to do this as some functions may differ between these emulators. If you don\'t want to use Arcturus Morningstar anyway, you\'ll have to convert Cosmic to the emulator you love!<br /><br />Cosmic is composed of about 42% JavaScript, 25.5% PHP, 17% HTML and 15.5% CSS. Now you will think, why is Cosmic almost half made of JavaScript? Cosmic wouldn\'t work without this JavaScript, because we made sure that you only need to load Cosmic once and you can control all functions without refresh! This also ensures that we can use a hotel that can stay open. This makes it easier to switch between website and hotel, but also to control the functions, since we can use notifications without refreshing the page and because of this your data is gone!<br /><br />Cosmic also has a shop and forum! In the shop you can buy GOTW-Points via paypal and/or phone, and you can buy vip with GOTW-Points! This can all be set via housekeeping. Because of the RCON system built into Cosmic, the user who buys GOTW-Points or vip doesn\'t have to leave the hotel or log in. Everything happens automatically and you immediately receive your GOTW-Points and/or vip! After the purchase the user can view their purchase / purchases through the purchase history page. The forum also offers multiple functions. You can create topics, respond to topics, like topics, report topics, pin messages, ... Members can ask questions or offer information that is useful for other members. The categories and so on can all be created/adjusted in the housekeeping.<br /><br />Cosmic also has a photo page. Here you can see all the nice pictures of members taken in the hotel. If you think there is a picture you really like, you can also give it a like. Or maybe a photo that is inappropriate does not belong on the page, you can report it and the team will delete it.<br /><br />With Cosmic we try to offer our members a nice environment. Because of the many features that Cosmic offers, this has certainly succeeded! You no longer need to communicate only through the hotel, but you can also do so through the forum, or if you have a try through the help tool. Parents will also be able to reach the team easily and they will gain more confidence in the hotel.<br /><br />Cosmic also owns a number of info pages. Just think of the general terms and conditions, the rules, a privacy statement, cookie policy and a guide for parents. All this to create the best playing experience for the members.<br /><br />Our team has also thought about the security of your account. You can use Google Authenticator. But what is that now? Google Authenticator provides a 6-digit code with which you can login to your account. This code is asked when your username and password are entered correctly. The code you get is changed every X number of seconds to ensure the security of your account. If you can\'t get this code anymore, because your phone is broken or for any reason, you can restore it via Google itself or you can report this to the team and they will reopen your account so you can get back in (if this is your account of course).<br /><br />When you visit a user\'s profile, you can see different things: which badges he/she has, who his/her friends are, in which groups he/she sits, which rooms he/she has taken and which photos he/she has taken. You will also find a guestbook on each profile page. If you want to let a user know something small, you can write it in the guestbook. Or you can let them know on your own profile what you are doing at that moment.<br /><br />The settings of your account also offer some special features: Can other users see your profile? Can other users see when you were last online or when you are online? Can other users add you at the hotel or follow you to other rooms? It\'s all possible! As an employee you also have the possibility not to show yourself on the employee page.<br /><br />If all this is not enough, you also have a very large and extensive housekeeping! In the beginning of this message a number of functions are already explained. The housekeeping has been developed in such a way that you hardly need to search your database for data. Because of the RCON system we were able to add some extra functions, like sending alerts, banning users, ... You can create permissions, manage rooms, manage users, block VPN\'s using ASN, manage the word filter, view chatlogs/banlogs/stafflogs, manage help tickets, manage FAQ\'s, manage the feed, manage news, shop and catalogue! As an administrator you can easily assign all these permissions to different ranks. All pages have different permissions, which can be found under the permissions tab in the menu.<br /><br />Changing your password is also no problem due to the implemented e-mail system. Users can request their password and will receive an e-mail in the inbox. There is a link in the inbox that allows them to change their password. You should make sure you use a secure mail server, so that your email is not spamming, as this can cause confusion for some users and/or parents.<br /><br />This was it for the explanation of Cosmic. We hope you\'re sufficiently informed about Cosmic. If you have any questions, you can always visit our Discord. The support team is always ready to answer any questions you might have.<br /><br />We wish you a lot of fun using Cosmic!<br /><br />- Cosmic support team</p>', 'https://images.habbo.com/c_images/Security/safetytips1_n.png', 1, 'https://habboo-a.akamaihd.net/c_images/web_promo/lpromo_SweetHome1.png', 1, 'none', 1536203060, '0')";
        if ($conn->query($webForumNewsq) !== true) {
            self::rollback($conn->error);
        }
        $webForumNewsCat = "
            CREATE TABLE `website_news_categories` (
              `id` int(11) NOT NULL,
              `category` varchar(50) NOT NULL DEFAULT ''
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webForumNewsCat) !== true) {
            self::rollback($conn->error);
        }
        $webForumNewsCat = "
        INSERT INTO `website_news_categories` (`id`, `category`) VALUES
        (1, 'Updates')";
        if ($conn->query($webForumNewsCat) !== true) {
            self::rollback($conn->error);
        }
        $webForumNewsReact = "
            CREATE TABLE `website_news_reactions` (
              `id` int(11) NOT NULL,
              `date` int(11) NOT NULL DEFAULT 0,
              `news_id` int(11) DEFAULT NULL,
              `player_id` int(11) DEFAULT NULL,
              `message` varchar(250) DEFAULT NULL,
              `hidden` int(11) DEFAULT 0,
              `timestamp` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumNewsReact) !== true) {
            self::rollback($conn->error);
        }
        $webNoti = "
            CREATE TABLE `website_notifications` (
              `id` int(11) NOT NULL,
              `player_id` int(11) NOT NULL DEFAULT 0,
              `message` varchar(255) NOT NULL DEFAULT '',
              `type` enum('0','1') NOT NULL,
              `is_read` enum('0','1') NOT NULL DEFAULT '0',
              `timestamp` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webNoti) !== true) {
            self::rollback($conn->error);
        }
        $webNotiReset = "
            CREATE TABLE `website_password_reset` (
              `id` int(11) NOT NULL,
              `player_id` int(11) NOT NULL,
              `email` varchar(75) NOT NULL DEFAULT '',
              `ip_address` varchar(100) NOT NULL DEFAULT '0.0.0.0',
              `token` varchar(255) NOT NULL DEFAULT '',
              `token_expires_at` int(11) NOT NULL,
              `timestamp` int(11) NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webNotiReset) !== true) {
            self::rollback($conn->error);
        }
        $webNotiReset = "
            CREATE TABLE website_remembered_logins  (
              id int(11) NOT NULL AUTO_INCREMENT,
              token_hash varchar(128) NOT NULL DEFAULT NULL,
              user_id int(11) NULL DEFAULT NULL,
              expires_at datetime(0) NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($webNotiReset) !== true) {
            self::rollback($conn->error);
        }
        $webPermssions = "
            CREATE TABLE `website_permissions` (
              `id` int(11) NOT NULL,
              `permission` varchar(255) NOT NULL DEFAULT 'housekeeping_',
              `description` text DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webPermssions) !== true) {
            self::rollback($conn->error);
        }
        $webPermssionsInsert = "
            INSERT INTO `website_permissions` (`id`, `permission`, `description`) VALUES
            (1, 'housekeeping', 'Player has access to the housekeeping.'),
            (2, 'housekeeping_remote_control', 'Player is able to nd may adjust other players account information, except the ranks.'),
            (3, 'housekeeping_ban_user', 'Player is able to ban users from the control panel. A rank system is included in this permission.'),
            (4, 'housekeeping_ban_logs', 'Player is able to view which players have been denied access to the hotel'),
            (5, 'housekeeping_staff_logs', 'Player is able to view all loggings that staffs have committed in the cms'),
            (6, 'housekeeping_chat_logs', 'Player is able to read chat logs from other players'),
            (7, 'housekeeping_website', '\r\nPlayer has access to the website category'),
            (8, 'housekeeping_website_news', 'Player is able to manage news items'),
            (9, 'housekeeping_ranks', 'Player is able to change ranks of other players'),
            (10, 'housekeeping_permissions', 'Player can add/remove permissions for other players who have access to housekeeping'),
            (11, 'housekeeping_ip_display', 'Player is able to see IP addresses of other players'),
            (12, 'housekeeping_reset_user', 'Player is able to reset the player (motto, look, relationships)'),
            (13, 'housekeeping_alert_user', 'Player is able to send warn the player'),
            (14, 'housekeeping_room_control', 'Player is able to see rooms but not able to edit the room'),
            (15, 'housekeeping_moderation_tools', 'Player is able to use the moderation tools on the website'),
            (16, 'housekeeping_website_reports', 'Player is able to see reported items and also take actions'),
            (17, 'housekeeping_website_helptool', 'Player is able to handle the helptool'),
            (18, 'housekeeping_change_email', 'Player is able to change mail adresses'),
            (19, 'housekeeping_website_feeds', 'Player is able to remove feeds from the website'),
            (20, 'housekeeping_vpn_control', 'Player is able to ban of unban AS numbers'),
            (21, 'housekeeping_wordfilter_control', 'Player is able to manage the word filter'),
            (22, 'housekeeping_website_rarevalue', 'Player is able to change rare values'),
            (23, 'housekeeping_website_faq', 'Player is able to manage the FAQ'),
            (24, 'housekeeping_shop_control', 'Player is able to handle and see purchase logs'),
            (25, 'housekeeping_website_namechange', 'Player is able to change a players username which already exists'),
            (26, 'housekeeping_server', '\r\nPlayer has access to the server category'),
            (27, 'housekeeping_server_catalog', 'Player is able to manage the catalog'),
            (28, 'housekeeping_ranks_extra', 'Player is able to edit the extra rank'),
            (29, 'housekeeping_staff_logs_menu', 'Player is able to see logs in menu'),
            (30, 'housekeeping_website_forum', 'Player is able to manage forums')";
        if ($conn->query($webPermssionsInsert) !== true) {
            self::rollback($conn->error);
        }
        $webPermssionsInsertRank = "
            CREATE TABLE `website_permissions_ranks` (
              `id` int(11) NOT NULL,
              `permission_id` int(11) NOT NULL,
              `rank_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webPermssionsInsertRank) !== true) {
            self::rollback($conn->error);
        }
        $webPermssionsInsertRankI = "
            INSERT INTO `website_permissions_ranks` (`id`, `permission_id`, `rank_id`) VALUES
            (1, 1, 7),
            (2, 2, 7),
            (3, 3, 7),
            (4, 4, 7),
            (5, 5, 7),
            (6, 6, 7),
            (7, 7, 7),
            (8, 8, 7),
            (9, 9, 7),
            (10, 10, 7),
            (11, 11, 7),
            (12, 12, 7),
            (13, 13, 7),
            (14, 14, 7),
            (15, 15, 7),
            (16, 16, 7),
            (17, 17, 7),
            (18, 18, 7),
            (19, 19, 7),
            (20, 20, 7),
            (21, 21, 7),
            (22, 22, 7),
            (23, 23, 7),
            (24, 24, 7),
            (25, 25, 7),
            (26, 26, 7),
            (27, 27, 7),
            (28, 28, 7),
            (29, 29, 7),
            (30, 30, 7)";
        if ($conn->query($webPermssionsInsertRankI) !== true) {
            self::rollback($conn->error);
        }
        $photolikes = "
            CREATE TABLE `website_photos_likes` (
              `id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `photo_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC";
        if ($conn->query($photolikes) !== true) {
            self::rollback($conn->error);
        }
        $offers = "
            CREATE TABLE `website_shop_offers` (
              `id` int(11) NOT NULL,
              `currency` enum('belcredits','diamonds','credits','vip') NOT NULL DEFAULT 'belcredits',
              `amount` int(11) NOT NULL DEFAULT 20,
              `price` varchar(10) NOT NULL DEFAULT '1.50',
              `image` varchar(50) NOT NULL DEFAULT '',
              `offer_id` varchar(50) NOT NULL DEFAULT '',
              `private_key` varchar(50) NOT NULL DEFAULT '',
              `lang` enum('NL','BE','FR') NOT NULL DEFAULT 'NL'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($offers) !== true) {
            self::rollback($conn->error);
        }
        $purchases = "
            CREATE TABLE `website_shop_purchases` (
              `id` int(11) NOT NULL,
              `user_id` int(11) NOT NULL,
              `data` varchar(255) DEFAULT NULL,
              `lang` varchar(10) DEFAULT NULL,
              `timestamp` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
        if ($conn->query($purchases) !== true) {
            self::rollback($conn->error);
        }
        $staff = "
            CREATE TABLE `website_staff_logs` (
              `id` int(11) NOT NULL,
              `type` varchar(25) DEFAULT NULL,
              `value` varchar(255) DEFAULT NULL,
              `player_id` int(11) DEFAULT NULL,
              `target` int(11) DEFAULT NULL,
              `timestamp` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
        if ($conn->query($staff) !== true) {
            self::rollback($conn->error);
        }
        $mail = "
      CREATE TABLE `website_user_logs_email` (
        `id` int(11) NOT NULL,
        `user_id` int(11) DEFAULT NULL,
        `old_mail` varchar(50) DEFAULT NULL,
        `new_mail` varchar(50) DEFAULT NULL,
        `ip_address` varchar(50) DEFAULT NULL,
        `timestamp` int(11) DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
              if ($conn->query($mail) !== true) {
                  self::rollback($conn->error);
              }
              $aas = "
        ALTER TABLE `website_jobs`
          ADD PRIMARY KEY (`id`)";
              if ($conn->query($aas) !== true) {
                  self::rollback($conn->error);
              }
               $aas = "
      ALTER TABLE `website_jobs_applys`
        ADD PRIMARY KEY (`id`)";
              if ($conn->query($aas) !== true) {
                  self::rollback($conn->error);
              }
              $aas = "
      ALTER TABLE `website_alert_messages`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE";
              if ($conn->query($aas) !== true) {
                  self::rollback($conn->error);
              }
              $aaas = "
      ALTER TABLE `website_bans_asn`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD UNIQUE KEY `asn` (`asn`) USING BTREE";
              if ($conn->query($aaas) !== true) {
                  self::rollback($conn->error);
              }
              $aaas = "
      ALTER TABLE `website_rare_values`
        ADD PRIMARY KEY (`id`)";
              if ($conn->query($aaas) !== true) {
                  self::rollback($conn->error);
              }
              $aaasa = "
      ALTER TABLE `website_ban_messages`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE";
              if ($conn->query($aaasa) !== true) {
                  self::rollback($conn->error);
              }
              $aaaasa = "
      ALTER TABLE `website_ban_types`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE,
        ADD KEY `min_rank` (`min_rank`) USING BTREE";
              if ($conn->query($aaaasa) !== true) {
                  self::rollback($conn->error);
              }
              $aaaaasa = "
      ALTER TABLE `website_remembered_logins`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($aaaaasa) !== true) {
                  self::rollback($conn->error);
              }
              $aaaaasa = "
      ALTER TABLE `website_feeds`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($aaaaasa) !== true) {
                  self::rollback($conn->error);
              }
              $aaa = "
      ALTER TABLE `website_feeds_likes`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($aaa) !== true) {
                  self::rollback($conn->error);
              }
 
              $bbb = "
      ALTER TABLE `website_feeds_reactions`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($bbb) !== true) {
                  self::rollback($conn->error);
              }
              $babb = "
      ALTER TABLE `website_forum_likes`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($babb) !== true) {
                  self::rollback($conn->error);
              }
              $addasd = "
      ALTER TABLE `website_helptool_categories`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($addasd) !== true) {
                  self::rollback($conn->error);
              }
              $asaas = "
      ALTER TABLE `website_helptool_faq`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `category` (`category`) USING BTREE";
              if ($conn->query($asaas) !== true) {
                  self::rollback($conn->error);
              }
              $lokkk = "
      ALTER TABLE `website_helptool_logs`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `player_id` (`player_id`) USING BTREE,
        ADD KEY `target` (`target`) USING BTREE,
        ADD KEY `type` (`type`) USING BTREE";
              if ($conn->query($lokkk) !== true) {
                  self::rollback($conn->error);
              }
              $fdssdsfdf = "
      ALTER TABLE `website_helptool_reactions`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($fdssdsfdf) !== true) {
                  self::rollback($conn->error);
              }
              $wqweqew = "
      ALTER TABLE `website_helptool_requests`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($wqweqew) !== true) {
                  self::rollback($conn->error);
              }
              $adadaad = "
      ALTER TABLE `website_news`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE,
        ADD KEY `slug` (`slug`) USING BTREE,
        ADD KEY `timestamp` (`timestamp`) USING BTREE,
        ADD KEY `hidden` (`hidden`) USING BTREE,
        ADD KEY `category` (`category`) USING BTREE";
              if ($conn->query($adadaad) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_news_categories`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE,
        ADD KEY `category` (`category`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_news_reactions`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_notifications`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_password_reset`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_permissions`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `id` (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_permissions_ranks`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `rank_id` (`rank_id`) USING BTREE,
        ADD KEY `permission_id` (`permission_id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_photos_likes`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_shop_offers`
        ADD PRIMARY KEY (`id`) USING BTREE,
        ADD KEY `lang` (`lang`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_shop_purchases`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_staff_logs`
        ADD PRIMARY KEY (`id`)";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_user_logs_email`
        ADD PRIMARY KEY (`id`)";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_alert_messages`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_bans_asn`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_jobs_applys`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_jobs`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_ban_messages`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_ban_types`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_feeds`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_remembered_logins`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_feeds_likes`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_feeds_reactions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_forum_likes`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_helptool_categories`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_helptool_faq`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_helptool_logs`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_helptool_reactions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_helptool_requests`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_news`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_news_categories`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_news_reactions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_notifications`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_password_reset`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_permissions`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_permissions_ranks`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_photos_likes`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_rare_values`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_shop_offers`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_shop_purchases`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_staff_logs`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_user_logs_email`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
        $qeqewwe = "
      ALTER TABLE `catalog_items`
        ADD COLUMN `rate` enum('up','down','none') DEFAULT 'none'";
        if ($conn->query($qeqewwe) !== true) {
            self::rollback($conn->error);
        }
        $qeqewwe = "
      ALTER TABLE `users`
        ADD COLUMN `pincode` varchar(8) DEFAULT NULL,
        ADD COLUMN `secret_key` varchar(16) DEFAULT NULL";
        if ($conn->query($qeqewwe) !== true) {
            self::rollback($conn->error);
        }
        return true;
    }
}

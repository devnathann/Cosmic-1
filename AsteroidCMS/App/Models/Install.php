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
        $copy = copy(self::$tmp, self::$path);
        if ($copy) {
            $message = (!$message) ? self::$exception : $message;
            echo '{"status":"error","message":"Rollback started: ' . $message . '"}';
            exit;
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

          return true;
    }
  
    public static function createTables() {
      
      
        $conn = self::checkConnection(Config::host, Config::username, Config::database, Config::password);
      
         if(mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'users'")) == 0) {
            echo '{"status":"error","message":"Please import the arcturus database first!"}';
            exit;
        }
      
         if(mysqli_num_rows(mysqli_query($conn,"SHOW TABLES LIKE 'website_alert_messages'"))) {
            echo '{"status":"error","message":"Database already exists! Please truncate the database for a fresh install."}';
            exit;
        }
      
        $alertMessages = "
            CREATE TABLE `website_alert_messages` (
              `id` int(11) NOT NULL,
              `title` varchar(50) NOT NULL DEFAULT '',
              `message` varchar(150) NOT NULL DEFAULT 'Onacceptabel voor het Hotel Management'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
      
        if ($conn->query($alertMessages) !== true) {
            self::rollback($conn->error);
        }
      
        $alertMessagesInsert = "
            INSERT INTO `website_alert_messages` (`id`, `title`, `message`) VALUES
            (1, 'Taalgebruik', 'Let op je taalgebruik! Bij herhaling zul je worden verbannen.'),
            (2, 'Voordoen als Leet Staff', 'Het voordoen als Leet Staff is tegen de Leet Regels. Bij herhaling zul je worden verbannen.'),
            (3, 'Praten over Retro Hotels', 'Praten over Retro Hotels is tegen de Leet Regels! Bij Herhaling zul je worden verbannen.'),
            (4, 'Vragen/weggeven van persoonlijke gegevens', 'Vragen/weggeven van persoonlijke gegevens is tegen de Leet Regels! Bij herhaling zul je worden verbannen.'),
            (5, 'Vragen/weggeven van Social Media', 'Vragen/weggeven van snapchat, insta of andere Social Media is tegen de Leet Regels! Bij herhaling zul je worden verbannen.'),
            (6, 'Lastigvallen / onacceptabel taalgebruik / gedrag', 'Lastigvallen / onacceptabel taalgebruik of gedrag is tegen de Leet Regels! Bij herhaling zul je worden verbannen.'),
            (7, 'Lastigvallen', 'Val andere Leet\'s niet lastig! Bij herhaling zul je worden verbannen.'),
            (8, 'Seksuele gesprekken/gedragingen', 'Seksuele gesprekken of gedragingen is tegen de Leet Regels! Bij herhaling zul je worden verbannen.')
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
              `message` varchar(75) NOT NULL DEFAULT 'Onacceptabel voor het Hotel Management'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT";
        if ($conn->query($banAsnMessaages) !== true) {
            self::rollback($conn->error);
        }
        $banAsnMessagesInsert = "
            INSERT INTO `website_ban_messages` (`id`, `message`) VALUES
            (1, 'Adverteren voor Retro Hotels'),
            (2, 'Oplichten van een of meerdere Leet\'s'),
            (3, 'Illegale activiteiten'),
            (4, 'Haatzaaien/discriminatie'),
            (5, 'Pedofiele activiteiten'),
            (6, 'Vragen/weggeven van persoonlijke gegevens'),
            (7, 'Vragen/weggeven van snapchat, insta of andere Social Media'),
            (8, 'Lastigvallen / onacceptabel taalgebruik of gedrag'),
            (9, 'Ordeverstoring'),
            (10, 'Nadrukkelijk seksuele gedragingen'),
            (11, 'Vragen/aanbieden van webscam seks of seksuele afbeeldingen'),
            (12, 'Oplichten van een of meerdere Leet\'s'),
            (13, 'Bedreigen van een of meerdere Leet\'s met ddos/hack/expose'),
            (14, 'Voordoen als Leet Staff'),
            (15, 'Leetnaam in strijd met de Leet Regels')
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
            (1, 7200, '2 uur', 4),
            (2, 14400, '4 uur', 4),
            (3, 28800, '8 uur', 4),
            (4, 43200, '12 uur', 4),
            (5, 86400, '1 dag', 6),
            (6, 259200, '3 dagen', 6),
            (7, 604800, '1 week', 6),
            (8, 2629743, '1 maand', 6),
            (9, 7889231, '3 maanden', 6),
            (10, 946707780, 'permanent', 6)
        ";
        if ($conn->query($banTypesInsert) !== true) {
            self::rollback($conn->error);
        }
        $webConfig = "
            CREATE TABLE `website_config` (
              `short_name` varchar(50) DEFAULT 'Asteroid',
              `hotel_name` varchar(50) DEFAULT 'Asteroid Hotel',
              `maintenance` enum('0','1') DEFAULT '0',
              `revision` varchar(50) DEFAULT ''
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT";
        if ($conn->query($webConfig) !== true) {
            self::rollback($conn->error);
        }
        $webConfigInsert = "
            INSERT INTO `website_config` (`short_name`, `hotel_name`, `maintenance`, `revision`) VALUES
            ('Leet', 'Leet Hotel', '0', '150')
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
        $webForumCat = "
            CREATE TABLE `website_forum_categories` (
              `id` int(11) NOT NULL,
              `name` varchar(40) NOT NULL,
              `description` text DEFAULT NULL,
              `position` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumCat) !== true) {
            self::rollback($conn->error);
        }
        $webForumIndex = "
            CREATE TABLE `website_forum_index` (
              `id` int(11) UNSIGNED NOT NULL,
              `title` varchar(255) NOT NULL DEFAULT '',
              `description` varchar(255) DEFAULT NULL,
              `created_at` int(11) NOT NULL,
              `updated_at` int(11) DEFAULT NULL,
              `image` varchar(50) DEFAULT NULL,
              `cat_id` int(11) DEFAULT NULL,
              `slug` varchar(60) DEFAULT NULL,
              `position` int(11) NOT NULL,
              `max_rank` int(11) DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumIndex) !== true) {
            self::rollback($conn->error);
        }
        $webForumIndexInsert = "
            INSERT INTO `website_forum_index` (`id`, `title`, `description`, `created_at`, `updated_at`, `image`, `cat_id`, `slug`, `position`, `max_rank`) VALUES
            (1, 'Algemene Regels', 'De regels van Asteroid zijn speciaal ontworpen om een gezellige sfeer te maken en te behouden, ieder lid dient deze regels te volgen.', 1523703600, NULL, 'notice.gif', 1, 'algemene-regels', 0, NULL),
            (2, 'Klachten, feedback en vragen', 'Heb je een klacht, of wil je ons een tip geven? Dat kan hier, in de klachten en feedback sectie!', 1523703600, NULL, 'lightbulb.gif', 1, 'klachten-feedback-en-vragen', 1, NULL),
            (4, 'Stel jezelf voor', 'Ben je nieuw op Leet of heb je je nog niet voorgesteld? Dan kan je dat nu doen.', 1523703600, NULL, 'hand.gif', 1, 'stel-jezelf-voor', 2, NULL),
            (5, 'De kletshoek', 'Hier kun je over vanalles en nog wat praten.', 1523703600, NULL, 'newspapertext.gif', 2, NULL, 4, NULL),
            (6, 'Actualiteiten & Serieuze discussies', 'Hier kan je praten over alles dat actueel is', 1523703600, NULL, 'event.gif', 2, NULL, 0, NULL),
            (7, 'Webdevelopment', 'Alles m.b.t. webdevelopment vind je hier.', 1523703600, NULL, 'event.gif', 3, NULL, 0, NULL),
            (8, 'PHP Forum', 'Alles mbt PHP kan je hier plaatsen en vinden.', 1523703600, NULL, 'phone.gif', 3, NULL, 0, NULL),
            (9, 'Project Showcase & Releases', 'Heb jij een fantastisch project gemaakt en wil je die aan iedereen laten zien? Dan kan je dat hier doen!', 1523703600, NULL, 'camera.gif', 3, NULL, 0, NULL),
            (10, 'Development Overige', 'Mist er iets binnen de Development sectie, waar jij toevallig iets wilt over plaatsen? Geen vrees, dat kan hier!', 1523703600, NULL, 'sunglass.gif', 3, NULL, 0, NULL),
            (11, 'School en studies', 'Ben jij ook de docent wiskunde beu? Of wil je eerder praten over je lievelingsvak?', 1523703600, NULL, 'ufoeffect.gif', 4, NULL, 0, NULL),
            (12, 'Muziek, films, series...', 'Wat is jouw favoriete serie/film? Wat voor genre muziek beluister jij graag?', 1523703600, NULL, 'event.gif', 4, NULL, 0, NULL),
            (13, 'Forum spelletjes', 'Hier vind je de algemene forumspelletjes, zoals het Teltopic e.d.', 1523703600, NULL, 'forum-games.gif', 4, NULL, 0, NULL),
            (14, 'Overige', 'Alles wat niet in een ander forum past kan hier worden geplaatst', 1523703600, NULL, 'newspapertext.gif', 4, NULL, 0, NULL)
        ";
        if ($conn->query($webForumIndexInsert) !== true) {
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
        $webForumPosts = "
            CREATE TABLE `website_forum_posts` (
            `id` int(11) UNSIGNED NOT NULL,
            `content` longtext NOT NULL,
            `user_id` int(11) UNSIGNED NOT NULL,
            `topic_id` int(11) UNSIGNED NOT NULL,
            `created_at` int(11) NOT NULL,
            `updated_at` int(11) DEFAULT NULL
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumPosts) !== true) {
            self::rollback($conn->error);
        }
        $webForumTopics = "
          CREATE TABLE `website_forum_topics` (
            `id` int(11) UNSIGNED NOT NULL,
            `title` varchar(70) NOT NULL DEFAULT '',
            `slug` varchar(90) DEFAULT NULL,
            `created_at` int(11) NOT NULL,
            `updated_at` int(11) DEFAULT NULL,
            `user_id` int(11) UNSIGNED NOT NULL,
            `forum_id` int(11) UNSIGNED NOT NULL,
            `is_sticky` tinyint(1) NOT NULL DEFAULT 0,
            `is_closed` tinyint(1) NOT NULL DEFAULT 0,
            `is_visible` tinyint(1) NOT NULL DEFAULT 1
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC";
        if ($conn->query($webForumTopics) !== true) {
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
(1, 'welcome-to-asteroid-cms', 'Welkom op AsteroidCMS', 'Ben jij een echte gokker? Lees dit dan zeker!', '<p><strong>Hey Leet\'s!</strong><br />De afgelopen dagen komen er ontzettend veel oproepen binnen van mensen waarvan de meubi zijn \'gescamd\' door een andere Leet.<br /><br /><strong>Wat is nu precies \'scammen\' en wat kun je er tegen doen?<br /></strong>In Leet zijn er een aantal casino\'s ook wel \'gamble\' genoemd. In dit soort kamers vergokken Leet\'s hun meubi met andere Leet\'s door middel van een klein spelletje. Maar er zijn helaas Leet\'s die hier misbruik van maken en de meubi van de tegenstander stelen of gewoon snel weg gaan als ze verloren hebben.<br /><br /><strong>Wat kan ik tegen scammen doen?<br /></strong>Er is een simpele manier om scammen te voorkomen. Zoek samen naar een tussen persoon, ook wel \'paler\' genoemd, die jullie allebei vertrouwen of een offici&euml;le paler. Je geeft allebei de meubi die je gaat vergokken aan de \'paler\' en hij/zij zal dan de winnaar de meubi\'s overhandigen. Denk hierbij aan een \'Leet Staff\'-lid of een officieel \'Paler\'-lid. Deze personen kun je altijd vertrouwen! :-)<br /><br />Dit is de meest gebruikte manier van gokken in Leet.<br /><br /><strong>Hoe herken ik een Paler?</strong><br />Een paler kun je herkennen aan zijn/haar Paler badge.<br /><br /><img src=\"https://images.leet.ws/c_images/album1584/PAAL.gif\" alt=\"\" width=\"36\" height=\"34\" />&nbsp;</p>', 'https://images.habbo.com/c_images/Security/safetytips1_n.png', 1, 'https://habboo-a.akamaihd.net/c_images/web_promo/lpromo_SweetHome1.png', 1, 'none', 1536203060, '0')";
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
              `timestamp` datetime DEFAULT NULL
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
            (29, 'housekeeping_staff_logs_menu', 'Player is able to see logs in menu')";
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
            (30, 29, 7)";
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
              $avv = "
      ALTER TABLE `website_forum_posts`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($avv) !== true) {
                  self::rollback($conn->error);
              }
              $eeas = "
      ALTER TABLE `website_forum_topics`
        ADD PRIMARY KEY (`id`) USING BTREE";
              if ($conn->query($eeas) !== true) {
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
      ALTER TABLE `website_forum_posts`
        MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
              if ($conn->query($qeqewwe) !== true) {
                  self::rollback($conn->error);
              }
              $qeqewwe = "
      ALTER TABLE `website_forum_topics`
        MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0";
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
      ALTER TABLE `users`
        `pincode` varchar(6) DEFAULT NULL,
        `secret_key` varchar(16) DEFAULT NULL";
        if ($conn->query($qeqewwe) !== true) {
            self::rollback($conn->error);
        }
        return true;
    }
}

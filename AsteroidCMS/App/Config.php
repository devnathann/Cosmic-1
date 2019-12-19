<?php
namespace App;

class Config
{
    /* DB config */
    const driver = 'mysql';
    const host = 'localhost';
    const username = 'root';
    const password = 'HDSUFUDSYF&*hukwj3rY';
    const database = 'asteroidcms_arcturus';
    const charset = 'utf8';
    const collation = 'collation';
    const prefix = '';

    const installation = false;
  
    /* Web config */
    const debug = true;
    const vpnBlock = true;
    const vpnLocation = '/../../../ASN.mmdb';

    const shortName = 'Asteroid';
    const siteName = 'AsteroidCMS';
    const language = 'NL';
    
    const clientHost = '46.105.247.163';
    const clientPort = 3000;

    const domain = 'asteroidcms.online';
    const path = 'http://asteroidcms.online';
    const imgPath = 'http://images.asteroidcms.online';
    const figurePath = 'https://cdn.leet.ws/leet-imaging';

    const view = 'App/View';

    const SECRET_TOKEN = 'ASTEROID-nm3jivnnjktekocxn1z4c';

    /* Admin settings */
    const minRank = 5;
    const maxRank = 7;
    const vipRank = 2;
    const vipPrice = 1000;

    /* Cache config */
    const cacheEnabled = true;
    const cacheTime = 3600;

    /* Captcha */
    const publicKey = '6LepDKsUAAAAAEnPxCPVJ7KxazzQ7TIvZkjF2ssb';
    const secretKey = '6LepDKsUAAAAAJMgACYrxoTpranj9bzMk7rRAtIJ';

    /* Google Auth config */
    const authEnabled = false;

    /* Mail settings */
    const mailHost      = null;
    const mailFrom      = null;
    const mailUser      = null;
    const mailPass      = null;
    const mailPort      = null;

    /* Register settings */
    const credits = 1000;
    const pixels = 1000;
    const points = 1000;
  
    /* Currency settings */
    const currencys = array( 'duckets' => 0,
'diamonds' => 5,
'belcredits' => 103
);
    const payCurrency = 103;
  
    const homeRoom = 0;

    const look = array(
        /* Males */
        'hr-802-37.hd-185-1.ch-804-82.lg-280-73.sh-3068-1408-1408.wa-2001',
        'hr-893-36.hd-208-8.ch-225-73.lg-270-64.sh-300-64.ea-1406.wa-2001',
        'hr-165-39.hd-180-8.ch-255-82.lg-275-1408',
        'hr-170-35.hd-190-10.ch-267-72.lg-3290-64.sh-3068-1408-72.cp-3125-64',
        'hr-125-31.hd-209-14.ch-3030-64.lg-275-64.sh-295-64.ha-1020.fa-1201',

        /* Females */
        'hr-890-35.hd-629-8.ch-665-76.lg-696-76.sh-730-64.ha-1003-64',
        'hr-890-37.hd-605-8.ch-650-76.lg-715-76.sh-907-71.he-3274-71.fa-3276-1408.ca-1812.wa-2008',
        'hd-629-8.ch-630-1408.lg-695-1408.sh-730-1408.ca-1812',
        'hr-545-45.hd-600-14.ch-650-76.lg-696-64.sh-907-76.he-1602-1408.wa-3210-1408-1408',
        'hr-890-42.hd-625-14.ch-3113-75-64.lg-720-64.sh-3115-75-64.he-1605-74'
    );
    

    /* Asteroid Api */
    const apiHost = '46.105.247.163';
    const apiPort = 3001;
    const apiEnabled = true;
}

<?php
use App\Config;
$GLOBALS['language'] = array (
    'website' => array (
	    /* Deutsche Übersetzung von Shanks (Discord: Shanks#9284) /*
        /*     App/View/base.html     */
        'base' => array(
            'nav_home'              => 'Meine Seite',

            'nav_community'         => 'Community',
            'nav_news'              => 'Neuigkeiten',
            'nav_jobs'              => 'Jobs',
            'nav_photos'            => 'Fotos',
            'nav_staff'             => 'Unser Team',
            'nav_exchange'          => 'Preisliste',

            'nav_shop'              => 'Shop',
            'nav_buy_points'        => 'Punkte Kaufen',
            'nav_buy_club'          => 'Habbo Club',
            'nav_purchasehistory'   => 'Meine Käufe',
            'nav_changename'        => 'Username ändern',

            'nav_highscores'        => 'Highscores',

            'nav_forum'             => 'Meine Gruppen',

            'nav_helptool'          => 'Help Tool',
            'nav_helptickets'       => 'Help Tickets',

            'nav_housekeeping'      => 'Housekeeping',

            'close'                 => 'Schließen',
            'cookies'               => 'benutzt eigene sowie Drittanbieter Cookies um einen besseren Service anzubieten und um Werbung anzuzeigen, die zu deinen Vorlieben gehören. Durch die Nutzung unserer Webseite stimmst du unseren Cookie Richtlinien zu.',
            'read_more'             => 'Weiter lesen.',
            'thanks_for_playing'    => 'Vielen dank für deinen Besuch im',
            'made_with_love'        => 'wurde mit viel Liebe entwickelt.',
            'credits'               => 'Ein besonderes Danke geht an Raizer und Metus',
            'and_all'               => 'und natürlich auch an alle',

            'login_name'            => 'Username',
            'login_password'        => 'Passwort',
            'login_save_data'       => 'Logindaten merken',
            'login_lost_password'   => 'Passwort vergessen?',

            'report_message'        => 'Diese Nachricht Melden',
            'report_certainty'      => 'Du bist dabei diese Nachricht zu Melden. Willst du dies wirklich tun oder bist du nur ausversehen auf dieses Zeichen gekommen?',
            'report_inappropriate'  => 'Ja, ich möchte dies Melden',

            'user_to'               => 'Ins',
            'user_profile'          => 'Mein Profil',
            'user_settings'         => 'Einstellungen',
            'user_logout'           => 'Abmelden',

            'header_slogan'         => 'Slogen1',
            'header_slogan2'        => 'Slogen2',
            'header_login'          => 'Anmelden',
            'header_register'       => 'Kostenlos Registrieren',
            'header_to'             => 'Ins',

            'footer_helptool'       => 'Help Tool',
            'footer_rules'          => Config::shortName .' Regelwerk',
            'footer_terms'          => 'Nutzungsbedingungen',
            'footer_privacy'        => 'Datenschutzerklärung',
            'footer_cookies'        => 'Cookie Richtlinien',
            'footer_guide'          => 'Für Eltern'
        ),

        /*     public/assets/js/web     */
        'javascript' => array(
            'web_customforms_markedfields'                  => 'Alle Felder die mit einem * Markiert sind müssen aufgefüllt werden.',
            'web_customforms_loadingform'                   => 'wird geladen...',
            'web_customforms_next'                          => 'Weiter',
            'web_customforms_close'                         => 'Schließen',
            'web_customforms_participation'                 => 'Vielen dank fürs Mitmachen :)',
            'web_customforms_sent'                          => 'Deine Antwort wurde abgeschickt und wird von einem Mitarbeiter ausgewertet und anschließend freigegeben.',
            'web_customforms_answer'                        => 'Deine Antwort',

            'web_dialog_cancel'                             => 'Abbrechen',
            'web_dialog_validate'                           => 'Verifizieren',
            'web_dialog_confirm'                            => 'Deine Auswahl wiederholen',

            'web_hotel_backto'                              => 'Zurück ins Hotel',

            'web_fill_pincode'                              => 'Geben Sie den PIN-Code ein, den Sie in der Google Authentication APP angezeigt bekommen. Solltest du hier Probleme haben, melde dich über unser Help Tool.',
            'web_twostep'                                   => 'Zwei-Schritt Verifizierung',
            'web_login'                                     => 'Du musst eingeloggt sein um diese Nachricht zu Melden.',
            'web_loggedout'                                 => 'Abmelden',

            'web_notifications_success'                     => 'Geschafft!',
            'web_notifications_error'                       => 'Error!',
            'web_notifications_info'                        => 'Information!',

            'web_page_article_login'                        => 'Du musst eingeloggt sein um hier zu Kommentieren!',

            'web_page_community_photos_login'               => 'Du musst eingeloggt sein um dieses Bild zu liken!',
            'web_page_community_photos_loggedout'           => 'Abmelden',

            'web_page_forum_change'                         => 'Ändern',
            'web_page_forum_cancel'                         => 'Abbrechen',
            'web_page_forum_oops'                           => 'Oops...',
            'web_page_forum_topic_closed'                   => 'Dieser Beitrag wurde geschlossen daher kannst du keine Nachricht mehr hinterlassne',
            'web_page_forum_login_toreact'                  => 'Um zu antworten musst du angemeldet sein.',
            'web_page_forum_login_tolike'                   => 'Du musst Angemeldet sein um diesen Beitrag zu liken',
            'web_page_forum_loggedout'                      => 'Abmelden',

            'web_page_profile_login'                        => 'Du musst eingeloggt sein um dieses Bild zu liken!',
            'web_page_profile_loggedout'                    => 'Abmelden',

            'web_page_settings_namechange_request'          => 'Anfragen',
            'web_page_settings_namechange_not_available'    => 'Nicht Verfügbar!',
            'web_page_settings_namechange_choose_name'      => 'Wähle deinen neuen Usernamen',

            'web_page_settings_verification_oops'           => 'Oops...',
            'web_page_settings_verification_fill_password'  => 'Bitte dein Passwort eingeben!',
            'web_page_settings_verification_2fa_on'         => 'Die Google-Authentifizierung ist derzeit in Ihrem Konto festgelegt. Um eine andere Bestätigungsmethode zu verwenden, musst du zuerst deine alte Bestätigung entfernen!',
            'web_page_settings_verification_2fa_secretkey'  => 'Hast du den QR-Code auf Ihrem Handy gescannt? Dann gib nun den geheimen Schlüssel ein, um dein Konto zu bestätigen!',
            'web_page_settings_verification_2fa_authcode'   => 'Authentication Code',
            'web_page_settings_verification_pincode_on'     => 'Du hast derzeit einen PIN-Code für Ihr Konto festgelegt. Um eine andere Bestätigungsmethode zu verwenden, musst du zuerst deine alte Bestätigung entfernen!',
            'web_page_settings_verification_2fa_off'        => 'Um die Google-Authentifizierung zu deaktivieren, bitten wir dich, den Geheimcode des Generators einzugeben.',
            'web_page_settings_verification_pincode_off'    => 'Um die PIN-Code-Authentifizierung zu deaktivieren, bitten wir dich, deinen PIN-Code einzugeben.',
            'web_page_settings_verification_pincode'        => 'Pin-Code',
            'web_page_settings_verification_switch'         => 'Wähle den Button, um eine Authentifizierungsmethode zu aktivieren!',

            'web_page_shop_offers_neosurf_name'             => 'Neosurf',
            'web_page_shop_offers_neosurf_description'      => 'Zahle einfach mit Neosurf und deine GOTW-Punkte werden sofort aufgeladen',
            'web_page_shop_offers_neosurf_dialog'           => 'Gib unten deine Neosurf-Guthaben-Code ein, um fortzufahren.',
            'web_page_shop_offers_paypal_name'              => 'PayPal',
            'web_page_shop_offers_paypal_description'       => 'Zahle einfach mit PayPal und deine GOTW-Punkte werden sofort aufgeladen',
            'web_page_shop_offers_paypal_dialog'            => 'Gib unten deine PayPal-E-Mail-Adresse ein, um fortzufahren.',
            'web_page_shop_offers_sms_name'                 => 'SMS',
            'web_page_shop_offers_sms_description'          => 'Zahle einfach mit PayPal und deine GOTW-Punkte werden sofort aufgeladen.',
            'web_page_shop_offers_sms_dialog'               => 'Gib den Code aus der Empfangenen SMS ein, um fortzufahren.',
            'web_page_shop_offers_audiotel_name'            => 'Audiotel',
            'web_page_shop_offers_audiotel_description'     => 'Ruf eine Nummer einmal oder mehrmals an, um einen GOTW-Punkte-Code zu erhalten.',
            'web_page_shop_offers_audiotel_dialog'          => 'Ruf die folgende Nummer an, um einen GOTW-Punkte-Code zu erhalten.',
            'web_page_shop_offers_pay_with'                 => 'Zahlen mit',
            'web_page_shop_offers_points_for'               => 'GOTW-Points für',
            'web_page_shop_offers_get_code'                 => 'Hol dir einen GOTW-Punkte-Code',
            'web_page_shop_offers_fill_code'                => 'Gib hier deinen GOTW-Punkte-Code ein',
            'web_page_shop_offers_fill_code_desc'           => 'Gib unten deinen GOTW-Punkte-Code ein, um deine GOTW-Punkte zu erhalten.',
            'web_page_shop_offers_submit'                   => 'Senden',
            'web_page_shop_offers_success'                  => 'Zahlung Erfolgreich!',
            'web_page_shop_offers_received'                 => 'Danke für deinen Einkauf, du hast',
            'web_page_shop_offers_received2'                => 'GOTW-Punkte erhalten.',
            'web_page_shop_offers_close'                    => 'Schließen',
            'web_page_shop_offers_failed'                   => 'Zahlung Fehlgeschlagen!',
            'web_page_shop_offers_failed_desc'              => 'Dein Kauf ist Fehlgeschlagen, versuche es erneut oder kontaktiere uns über das Help Tool.',
            'web_page_shop_offers_back'                     => 'Zurück',
            'web_page_shop_offers_no_card'                  => 'Wenn du keine Neosurf Prepaid-Karte hast, kannst du hier die',
            'web_page_shop_offers_no_card2'                 => 'Verkaufsstellen sehen',
            'web_page_shop_offers_to'                       => 'zu',
            'web_page_shop_offers_buy_code'                 => 'Zugangscode kaufen',
            'web_page_hotel_loading'                        => 'wird geladen...',
            'web_page_hotel_sometinhg_wrong_1'              => 'Oops, da ist etwas schiefgelaufen.',
            'web_page_hotel_sometinhg_wrong_2'              => 'Seite neuladen',
            'web_page_hotel_sometinhg_wrong_3'              => 'Oder erstell ein Ticket im Help Tool erstellen',
            'web_page_hotel_welcome_at'                     => 'Willkommen im',
            'web_page_hotel_soon'                           => Config::shortName . 'Habboversum wird geladen...',
            'web_hotel_active_flash_1'                      => 'Willkommen im ' . Config::shortName . '!',
            'web_hotel_active_flash_2'                      => 'Klick hier',
            'web_hotel_active_flash_3'                      => 'und "erlaube" deinen Browser Flash zu Aktivieren um das Habboversum zu betreten.'
        ),

        /*     App/View/Community     */
        'article' => array (
            'reactions'         => 'Kommentare',
            'reactions_empty'   => 'Es gibt noch keine Reaktionen.',
            'reactions_fill'    => 'Bitte hier schreiben...',
            'reactions_post'    => 'Senden',
            'latest_news'       => 'Aktuelle Neuigkeiten'
        ),
        'forum' => array (
          /*  Forum/index.html  */
            'index_subject'             => 'Thema',
            'index_topics'              => 'Beiträge',
            'index_latest_topic'        => 'Letzter Beitrag',
            'index_empty'               => 'Keine Beiträge vorhanden',
            'index_latest_activities'   => 'Letzte Antwort',
            'index_by'                  => 'von',

          /*  Forum/category.html  */
            'category_new_topic'        => 'neuen Beitrag',
            'category_back'             => 'Zurück',
            'category_topics'           => 'Beiträge',
            'category_posts'            => 'Antworten',
            'category_latest_reacts'    => 'Letzte Antwort',
            'category_topic_by'         => 'von',
            'category_no_reacts'        => 'Keine Antwort',
            'category_latest_react_by'  => 'Letzte Antwort von',
            'category_create_topic'     => 'Erstelle einen neuen Beitrag',
            'category_subject'          => 'Thema',
            'category_description'      => 'Beschreibung',
            'category_create_button'    => 'Veröffentlichen',
            'category_or'               => 'oder',
            'category_cancel'           => 'Abbrechen',

          /*  Forum/topic.html  */
            'topic_react'               => 'Antworten',
            'topic_close'               => 'Schließen',
            'topic_reopen'              => 'Reaktivieren',
            'topic_since'               => 'Mitglied seid:',
            'topic_posts'               => 'Beitrag:',
            'topic_topic'               => 'Thema:',
            'topic_reaction'            => 'Antwort auf:',
            'topic_closed'              => 'ACHTUNG! Dieser Beitrag wurde geschlossen. Bist du der Meinung das sollte sich ändern? Dann Kontaktiere uns darüber!',
            'topic_helptool'            => 'Help Tool',
            'topic_and'                 => 'und',
            'topic_likes_1'             => 'anderen gefällt das!',
            'topic_likes_2'             => 'gefällt das!',
            'topic_likes_3'             => 'gefällt das!'
        ),

        /*     App/View/Community     */
        'community_photos' => array (
            'by'          => 'von',
            'photos_by'   => 'Fotos von',
            'photos_desc' => 'Die neusten Fotos von',
            'load_more'   => 'Mehr Fotos laden'
        ),
        'community_staff' => array (
            'title'       => 'Wie werde ich ein ' . Config::shortName . ' Mitarbeiter',
            'desc'        => 'Unsere Mitarbeiter sind hier um dir zu helfen!',
            'content_1'   => 'Natürlich träumt jeder von einer Stelle als' . Config::shortName . '-Mitarbeiter, aber leider ist dies nicht jedermanns Sache. Um ein Mitarbeiter zu werden, musst du dich bewerben.',
            'content_2'   => 'Dies ist nur möglich, wenn wir freie Stellen haben, wenn wir dies haben, wird euch dies in den Neuigkeiten mitgeteilt.'
        ),
        'community_value' => array (
            'title_header'    =>  Config::shortName . 'Marktplatz',
            'decs_header'     => 'Hier kannst du als User deine Raritäten zum Kauf anbieten.',
            'furni_name'      => 'Rarename',
            'furni_type'      => 'Art',
            'furni_costs'     => 'Kostet',
            'furni_amount'    => 'Im Spiel',
            'furni_value'     => 'Alter Preis',
            'furni_rate'      => 'Anzahl',
            'looking_for'     => 'Ich suche nach..'
        ),
        /*     App/View/Games     */
        'games_ranking' => array (
            'username' => 'name'
        ),

        /*     App/View/Help     */
        'help' => array (
          /*  Help/help.html  */
            'help_title'                => 'FAQ',
            'help_label'                => 'Finde Antworten zu deinen Fragen.',
            'help_other_questions'      => 'Deine Frage ist nicht dabei?',
            'help_content_1'            => 'Kein Problem! Dann frage einen unserer Mitarbeiter. Hier hast du die Möglichkeit ein Help Ticket zu erstellen welches von einem Mitarbeiter schnellstmöglich bearbeitet wird.',
            'help_contact'              => 'Help Ticket erstellen',
            'title'                     => 'Help Tool',
            'desc'                      => 'Such nach einer Antwort. Wenn du keine findest, erstell ein neues Help Ticket.',

          /*  Help/request.html  */
            'request_closed'            => 'Geschlossen',
            'request_on'                => 'On:',
            'request_ticket_amount'     => 'Anzahl deiner Tickets:',
            'request_react_on'          => 'Bearbeitet von:',
            'request_react'             => 'Antwort',
            'request_description'       => 'Beschreibung',
            'request_react_on_ticket'   => 'Bearbeitung des Tickets',
            'request_contact'           => 'Ein Help Ticket erstellen',
            'request_contact_help'      => 'Hier hast du die Möglichkeit ein Help Ticket zu erstellen welches von einem Mitarbeiter schnellstmöglich bearbeitet wird.',
            'request_new_ticket'        => 'Ticket Erstellen',
            'request_subject'           => 'Problemkategorie',
            'request_type'              => 'Status',
            'request_status'            => 'Ticket Erstellt',
            'request_in_treatment'      => 'in bearbeitung',
            'request_open'              => 'Noch offen',
            'request_closed'            => 'Geschlossen'
        ),
        'help_new' => array (
            'title'         => 'Ein Ticket Erstellen',
            'subject'       => 'Um was für ein Problem handelt es sich? z.B. Handelsproblem, Logindaten Verlust... ',
            'description'   => 'Worum geht es genau? Bitte möglichst ausführlich.',
            'open_ticket'   => 'Ticket Eröffnen'
        ),

        /*     App/View/Home     */
        'home' => array (
            'to'                     => 'ins',
            'friends_online'         => 'Online Freunde',
            'now_in'                 => 'Jetzt ins',
            'latest_news'            => 'Aktuelle Neuigkeiten',
            'latest_facts'           => 'Die aktuellsten Neuigkeiten.',
            'popular_rooms'          => 'Beliebte Räume',
            'popular_rooms_label'    => 'Kenne immer die beliebtesten Treffpunkte im Habboversum.',
            'popular_no_rooms'       => 'Derzeit ist kein User Online!',
            'goto_room'              => 'Raum betreten',
            'popular_groups'         => 'Beliebte Gruppen',
            'popular_groups_label'   => 'Möchtest du vielleicht auch beitreten?',
            'popular_no_groups'      => 'Derzeit Existieren noch keine Gruppen.',
            'load_news'              => 'Mehr Neuigkeiten laden'
        ),
        'lost' => array (
            'page_not_found'          => 'Seite nicht gefunden!',
            'page_content_1'          => 'Sorry, diese Seite existiert nicht.',
            'page_content_2'          => 'Überprüfe erneut, ob du die richtige URL hast. Wenn du wieder hierher kommen (Willkommen zurück!). Kehre dann mit dem Button "Zurück" zu Ihrem Ursprungsort zurück.',
            'sidebar_title'           => 'Vielleicht suchst du ja',
            'sidebar_stats'           => 'Die Profilseite einer deiner Freunde?',
            'sidebar_stats_label_1'   => 'Vielleicht ist Er oder Sie ja in den',
            'sidebar_stats_label_2'   => 'Highscores',
            'sidebar_rooms'           => 'Beliebte Räume?',
            'sidebar_rooms_label_1'   => 'Öffne den',
            'sidebar_rooms_label_2'   => 'Navigator',
            'sidebar_else'            => 'Ich habe meine Nikes verloren :(',
            'sidebar_else_label'      => 'Dann musst du wirklich besser suchen los!!! :O'
        ),
        'profile' => array (
            'overlay_search'        => 'Nach wem suchst du?',
            'since'                 => 'Mitglied seid',
            'currently'             => 'Ich bin',
            'never_online'          => 'War noch nie Online',
            'last_visit'            => 'Zuletzt Online',
            'guestbook_title'       => 'Gästebuch',
            'guestbook_label'       => 'Möchtest du etwas hinterlassen?',
            'guestbook_input'       => 'Was machst du,',
            'guestbook_input_1'     => 'Was möchtest du ',
            'guestbook_input_2'     => 'hinerlassen?',
            'guestbook_load_more'   => 'Mehr Einträge laden',
            'badges_title'          => 'Badges',
            'badges_label'          => 'Dies sind einige Badges die ich besitze.',
            'badges_empty'          => 'Ich besitze noch keine Badges.',
            'friends_title'         => 'Freunde',
            'friends_label'         => 'Dies sind einige meiner Freunde',
            'friends_empty'         => 'Hat noch keine Freunde :(',
            'groups_title'          => 'Gruppen',
            'groups_label'          => 'Tritt doch auch bei ;)',
            'groups_empty'          => 'Ich bin noch keiner Gruppe beigetreten.',
            'rooms_title'           => 'Räume',
            'rooms_label'           => 'Das sind meine neusten Räume!',
            'rooms_empty'           => 'Ich habe noch keine Räume erstellt.',
            'photos_title'          => 'Fotos',
            'photos_label'          => 'Wir können ja mal ein Foto zusammen machen :)',
            'photos_empty'          => 'Ich nutze die Kamera nicht.'
        ),
        'registration' => array (
            'title'                 => 'Werde ein teil dieser Community!',
            'email'                 => 'E-Mail Adresse',
            'email_fill'            => 'Hier eingeben...',
            'email_help'            => 'Wir speichern diese Information damit wir, solltest du mal deine Zugangsdaten vergessen wir sichergehen können das du es wirklich bist.',
            'password'              => 'Dein Passwort',
            'password_fill'         => 'Hier eingeben...',
            'password_repeat'       => 'Passwort wiederholen',
            'password_repeat_fill'  => 'Hier eingeben...',
            'password_help_1'       => 'Dein Passwort muss mindestens 6 Zeichen lang sein und muss Buchstaben und Zahlen enthalten.',
            'password_help_2'       => 'Nutze am besten ein Passwort welches du auf keiner anderen Seite verwendest.',
            'birthdate'             => 'Dein Geburtsdatum',
            'day'                   => 'Tag',
            'month'                 => 'Monat',
            'year'                  => 'Jahr',
            'birthdate_help'        => 'Wir speichern diese Information damit wir, solltest du mal deine Zugangsdaten vergessen wir sichergehen können das du es wirklich bist.',
            'found'                 => 'Wie hast du das Habboversum gefunden?',
            'found_choose'          => 'Auswahlmöglichkeiten',
            'found_choose_1'        => 'Google',
            'found_choose_2'        => 'Durch einen Freund',
            'found_choose_3'        => 'Durch ein anderes Hotel',
            'found_choose_4'        => 'durch Soziale Medien',
            'found_choose_5'        => 'Anderweitig',
            'create_user'           => 'Erstelle deinen Avatar!',
            'username'              => 'Username',
            'username_fill'         => 'Tippe hier deinen Usernamen ein...',
            'username_help'         => 'Im Habboversum sehen andere Spieler dich nur unter diesem Usernamen.',
            'sex'                   => 'Geschlecht',
            'male'                  => 'Junge',
            'female'                => 'Mädchen',
            'register'              => 'Registrieren'
        ),

        /*     App/View/Jobs     */
        'apply' => array (
            'title'               => 'Reagiere auf ein Stellenangebot',
            'content_1'           => 'Vielen Dank für dein Interesse am' . Config::shortName . ' und deine Antwort auf das Stellenausschreiben.',
            'content_2'           => 'Versuche den Fragebogen möglichst genau zu beantworten.',
            'description'         => 'Stellenbeschreibung',
            'question_name'       => 'Wie heißt du?',
            'question_age'        => 'Wie alt bist du?',
            'question_why'        => 'Warum denkst du, du bist der richtige für diese Stelle?',
            'question_time'       => 'Wie viele Stunden bist du in der Woche Online?',
            'question_time_help'  => 'Teile uns mit an welchen Tagen du wie viele Stunden Online sein kannst. (Ungefähr)',
            'monday'              => 'Montag',
            'tuesday'             => 'Dienstag',
            'wednesday'           => 'Mittwoch',
            'thursday'            => 'Donnerstag',
            'friday'              => 'Freitag',
            'saturday'            => 'Samstag',
            'sunday'              => 'Sonntag',
            'time_to_time'        => 'von XX:XX bis XX:XX Uhr',
            'send'                => 'Absenden'
        ),
        'jobs' => array (
            'title'                   => 'Aktuelle Stellenangebote',
            'applications'            => 'Meine Bewerbungen',
            'available_applications'  => 'Freie Stellen',
            'buildteam'               => 'Eventteam',
            'buildteam_desc'          => 'Du bist dafür Verantwortlich Offizielle Räume und Events zu bauen und zu veranstallten.',
            'react'                   => 'Bewerben'
        ),

        /*     App/View/Password     */
        'password_claim' => array (
            'title'                 => 'Hast du dein Passwort vergessen?',
            'content_1'             => 'Gib hier deinen Usernamen und deine E-Mail ein und wir senden dir einen Link per E-Mail mit dem du dein Passwort ändern kannst.',
            'content_2'             => 'Tu das nicht, wenn dich jemand darum bittet!',
            'username'              => 'Username',
            'email'                 => 'E-Mail',
            'send'                  => 'Senden',
            'wrong_page'            => 'Falscher Alarm!',
            'wrong_page_content_1'  => 'Wenn du dich nun doch an dein Passwort erinnerst oder nur ausversehen hierher gekommen bist, kannst du über den folgenden Link zurück zur Startseite.',
            'back_to_home'          => 'Zurück zur Startseite'
        ),
        'password_reset' => array (
            'title'                     => 'Passwort ändern',
            'new_password'              => 'Neues Passwort',
            'new_password_fill'         => 'Hier das neue Passwort eingeben...',
            'new_password_repeat_fill'  => 'Bitte das Neue Passwort wiederholen...',
            'change_password'           => 'Passwort Ändern'
        ),

        /*     App/View/Settings     */
        'settings_panel' => array (
            'preferences'    => 'Meine Einstellungen',
            'password'       => 'Passwort ändern',
            'verification'   => 'Bestätigung festlegen',
            'email'          => 'E-Mail ändern',
            'namechange'     => 'Usernamen ändern',
            'shop_history'   => 'Meine Käufe'
        ),
        'settings_email' => array (
            'title'           => 'E-Mail ändern',
            'email_title'     => 'E-Mail',
            'email_label'     => 'Deine E-Mail Adresse wird benötigt, solltest du mal dein Passwort vergessen es zurück zu setzen.',
            'password_title'  => 'Passwort',
            'fill_password'   => 'Hier das Passwort eingeben...',
            'save'            => 'Speichern'
        ),
        'settings_namechange' => array (
            'title'           => 'Usernamen ändern',
            'help_1'          => 'Möchtest du deinen Usernamen ändern? Das geht! Aber Das kostet etwas',
            'help_2'          => 'der Betrag wird sofort nach deiner Anfrage abgebucht, die änderung kann nicht rückgängig gemacht werden! Denke also sorgfältig darüber nach und Entscheide gut!',
            'fill_username'   => 'Username...',
            'request'         => 'Anfragen'
        ),
        'settings_password' => array (
            'title'                     => 'Passwort ändern',
            'password_title'            => 'Aktuelles Passwort',
            'fill_password'             => 'Hier das aktuelle Passwort eingeben...',
            'newpassword_title'         => 'Neues Passwort',
            'fill_newpassword'          => 'Hier das neue Passwort eingeben...',
            'fill_newpassword_repeat'   => 'Hier erneut das neue Passwort eingeben...',
            'help'                      => 'Dein Neues Passwort muss aus mindestens 6 Zeichen bestehen. Diese müssen mindestens ein Buchstabe und eine Zahl sein',
            'save'                      => 'Speichern'
        ),
        'settings_preferences' => array (
            'title'               => 'Meine Einstellungen',
            'follow_title'        => 'Folgen - Wer kann dir folgen?' ,
            'follow_label'        => 'Ich möchte das mir keiner folgen kann',
            'friends_title'       => 'Freundschaftsanfragen',
            'friends_label'       => 'Dürfen dir andere Freundschaftsanfragen schicken?',
            'room_title'          => 'Raumeinladungen',
            'room_label'          => 'Ich möchte nicht in Räume eingeladen werden',
            'hotelalerts_title'   => 'Mitarbeiter Rundmeldungen',
            'hotelalerts_label'   => 'Ich möchte keine ALERTS von Mitarbeiter sehen.',
            'chat_title'          => 'Chat Einstellungen',
            'chat_label'          => 'Ich möchte den Alten Chat nutzen'
        ),
        'settings_verification' => array (
            'title'                 => 'Sichere deinen Account',
            'help'                  => 'Diese Überprüfung erhöht die Sicherheit Ihres Accounts. Wenn du dich anmeldest, musst du je nach Auswahl deiner Einstellungen einen von dir gewählten Sicherheitscode eingeben oder von der Google Authentification APP einen extra Generierten Code eingeben.',
            'password_title'        => 'Dein Aktuelles Passwort',
            'auth_title'            => 'Zwei-Schritt Verifizierung',
            'auth_label'            => 'Sichere dein Account mit der Zwei-Schritt Verifizierung',
            'method_title'          => 'Verifizierungsmethoden',
            'method_choose'         => 'Wähle eine Methode aus',
            'method_pincode'        => 'Ich möchte einen Sicherheits-Code vorgeben',
            'method_auth_app'       => 'Ich möchte die Google Authentification APP Nutzen.',
            'pincode_title'         => 'Sicherheits-Code',
            'pincode_label'         => 'Tippe hier deinen Sicherheits-Code ein um für mehr Sicherheit in deinem Account zu sorgen.',
            'fill_pincode'          => 'Hier Sicherheits-Code eingeben...',
            'generate_auth'         => 'Codes mit Google-Authentifizierungs APP Erstellen',
            'generate_auth_label'   => 'Diese Methode ist die zuverlässigste. Es wird eine Verbindung zwischen deinem Account und deiner APP (Google-Authentifizierungs APP) auf deinem Smartphone hergestellt. Jedesmal wenn du dich anmelden willst musst du die APP Öffnen und den generierten Code eingeben. Dieser wird jedesmal neu Generiert für mehr Sicherheit!',
            'link_account'          => 'Account Verlinken',
            'link_account_label'    => 'Um dein Konto zu Verknüpfen, scanne einfach deinen QR-Code mit deiner APP ein und Speichere diesen ab.',
            'save'                  => 'Speichern'
        ),

        /*     App/View/Shop     */
        'shop_club' => array (
            'club_benefits'       => 'Clubvorteile',
            'club_buy'            => config::shortName . ' Club Kaufen',
            'unlimited'           => 'Unbegrenzt',
            'more_information'    => 'Mehr Informationen',
            'content_1'           => 'Hast du Fragen oder ein Problem mit einer Kaufabwicklung?',
            'content_2'           => 'Dann zögere nicht ein Help Ticket zu erstellen.',
            'help_tool'           => config::shortName . ' Help Ticket',
            'random_club_users'   => 'Zufällige ' . config::shortName . ' Club Mitglieder',
            'desc'                => 'Kaufe eine Club Mitgliedschaft mit echtem Geld. Dadurch erhälst du Zugriff auf exklusive Möbel!'
        ),
        'shop_history' => array (
            'buy_history'         => 'Meine Käufe',
            'product'             => 'Was',
            'date'                => 'Wann',
            'buy_history_empty'   => 'Du hast noch nichts im' . Config::shortName . ' gekauft.',
            'buy_club'            => config::shortName . 'Club kaufen',
            'content_1'           => 'Hast du Fragen oder ein Problem mit einer Kaufabwicklung?',
            'content_2'           => 'Dann zögere nicht ein Help Ticket zu erstellen.',
            'help_tool'           => Config::shortName . ' Help Ticket',
            'title'               => 'Meine Käufe',
            'desc'                => 'Hier siehst du alle deine Käufe.'
        ),
        'shop_offers' => array (
            'back'              => 'Zurück',
            'buymethods'        => 'Zahlungsmöglichkeiten',
            'for'               => 'für',
            'or_lower'          => 'oder weniger',
            'loading_methods'   => 'Die Zahlungsmöglichkeiten werden geladen...',
            'store'             => 'Store'
        ),
        'shop' => array (
            'title'             => 'Wähle ein Produkt',
            'country'           => 'Land:',
            'netherlands'       => 'Niederlande',
            'belgium'           => 'Belgien',
			      'germany'			=> 'Deutschland',
            'super_rare'        => 'Super Rarität',
            'more_information'  => 'Mehr Informationen',
            'content_1'           => 'Hast du Fragen oder ein Problem mit einer Kaufabwicklung?',
            'content_2'           => 'Dann zögere nicht ein Help Ticket zu erstellen.',
            'help_tool'           => Config::shortName . ' Help Ticket',
            'not_logged'        => 'Oops! Du bist nicht angemeldet.',
            'have_to_login'     => 'Du musst angemeldet sein um den Shop abrufen zu können.',
            'click_here'        => 'Klick hier',
            'to_login'          => 'zum anmelden.',
            'desc'              => 'Kaufe Taler mit echtem Geld. Dadurch kannst du dir Möbel in unserem Katalog kaufen!'
        ),
        'games_ranking' => array(
            'title'             => 'Highscores',
            'desc'              => 'Hier findest du die Highscores von unseren Mitglieder.'
        )
    ),
    'core' => array (
        'belcredits' => 'GOTW-Punkte',
        'hotelapi' => array (
            'disabled' => 'Anfrage kann nicht bearbeitet werden, da die Hotel-API Deaktiviert wurde.'
        ),
        'dialog' => array (
            'logged_in'             => 'Oops, du musst eingeloggt sein um diese Seite zu sehen!',
            'not_logged_in'         => 'Du musst nicht eingeloggt sein um diese Seite zu sehen!'
        ),
        'notification' => array (
            'message_placed'        => 'Deine Nachricht wurde erfolgreich abgeschickt!',
            'message_deleted'       => 'Deine Nachricht wurde gelöscht.',
            'invisible'             => 'Das wird unsichtbar gemacht!',
            'profile_invisible'     => 'Dieses Profil ist Unsichtbar.',
            'profile_notfound'      => 'Leider konnten wir den User nicht finden',
            'no_permissions'        => 'Du besitzt keine Berechtigung dies zu tun.',
            'already_liked'         => 'Dir gefällt dies bereits!',
            'liked'                 => 'Es gefällt mir!',
            'banned_1'              => 'Dein Account wurdest wegen folgenden Verstoße des Habboversum Regelwerks gesperrt:',
            'banned_2'              => 'Dein Account ist bis zu folgenden Zeitpunkt gesperrt:',
            'something_wrong'       => 'Etwas ist schiefgelaufen, versuche es erneut.',
            'room_not_exists'       => 'Dieser Raum existiert nicht.',
            'staff_received'        => 'Vielen Dank! Unsere Mitarbeiter haben deine Nachricht erhalten.',
            'not_enough_belcredits' => 'Du besitzt nicht genug Punkte.',
            'topic_closed'          => 'Du kannst nicht auf einen geschlossenen Beitrag antworten',
            'post_not_allowed'      => 'Sie haben keinen Zugang, um einen Beitrag in diesem Forum zu erstellen!'
        ),
        'pattern' => array (
            'can_be'                => 'darf maximal',
            'must_be'               => 'muss mindestens',
            'characters_long'       => 'Zeichen lang sein.',
            'invalid'               => 'Entspricht nicht den Anforderungen',
            'invalid_characters'    => 'Erhält ungültige Zeichen!',
            'is_required'           => 'Fülle alle Felder aus!',
            'not_same'              => 'Stimmt nicht überein',
            'captcha'               => 'Recaptcha-Code stimmt nicht',
            'numeric'               => 'Muss aus Nummern bestehen.',
            'email'                 => 'ist ungültig!',
        ),
        'title' => array (
            'home'              => 'Meine Seite',
            'lost'              => 'Seite nicht gefunden!',
            'registration'      => 'Kostenlos Registrieren!',
            'hotel'             => 'Hotel',

            'password' => array (
                'claim'    => 'Passwort vergessen?',
                'reset'    => 'Passwort zurücksetzen',
            ),
            'settings' => array (
                'index'         => 'Einstellungen',
                'password'      => 'Passwort ändern',
                'email'         => 'E-Mail ändern',
                'namechange'    => 'Usernamen ändern',
            ),
            'community' => array (
                'index'     => 'Community',
                'photos'    => 'Fotos',
                'staff'     => 'Unser Management',
                'team'      => 'Unser Team',
                'fansites'  => 'Fanseiten',
                'value'     => 'Preisliste',
                'forum'     => 'Forum',
            ),
            'games' => array (
                'ranking'   => 'Highscores',
            ),
            'shop' => array (
                'index'     => Config::shortName .'Shop',
                'history'   => 'Meine Käufe',
                'club'      => Config::shortName .'Club',
            ),
            'help' => array (
                'index'     => 'Help Tool',
                'requests'  => 'Help Tickets',
                'new'       => 'Ticket Erstellen',
            ),
            'jobs' => array (
                'index'     => 'Stellenangebote',
                'apply'     => 'Bewerben',
            )
        )
    ),
    'login' => array (
        'invalid_password'          => 'Dein eingegebenes Passwort stimmt nicht.',
        'invalid_pincode'           => 'Dieser PIN-Code stimmt nicht mit dem dieses Users überein!',
        'fill_in_pincode'           => 'Geben Sie jetzt Ihren PIN-Code ein, um Zugriff auf Ihr Konto zu erhalten!',
    ),
    'register' => array (
        'username_invalid'          => 'Dieser Username verstößt gegen das Regelwerk!',
        'username_exists'           => 'Dieser Username wird bereits verwendet.',
    ),
    'claim' => array (
        'invalid_email'             => 'Diese E-Mail-Adresse stimmt nicht mit der dieses Users überein.',
        'invalid_link'              => 'Dieser Link ist abgelaufen. Fordern Sie Ihr Passwort erneut an, um Ihr Passwort zu ändern.',
        'send_link'                 => 'Wir haben dir eine E-Mail gesendet. Vergiss nicht im Spam-Ordner nachzusehen!',
        'password_changed'          => 'Dein Passwort wurde geändert. Du kannst dich jetzt einloggen!',

        'email'  => array (
            'title'                 => 'Passwort ändern.',
        )
    ),
    'settings' => array (
        'email_saved'               => 'Deine E-Mail Adresse wurde geändert',
        'pincode_saved'             => 'Ihr Sicherheits-Code wurde gespeichert! Du musst dich nun erneut anmelden, bis gleich!',
        'password_saved'            => 'Dein Passwort wurde geändert. Du musst dich nun erneut anmelden, bis gleich!',
        'preferences_saved'         => 'Deine Einstellungen wurden gespeichert!',
        'current_password_invalid'  => 'Das von dir eingegebene Aktuelle Passwort stimmt nicht.',
        'choose_new_username'       => 'Gib einen neuen Usernamen ein',
        'choose_new_pincode'        => 'Gib einen neuen Sicherheits-Code ein',
        'user_is_active'            => 'Dieser Username ist noch Verfügbar!',
        'user_not_exists'           => 'Dieser Username ist nicht mehr verfügbar!',
        'name_change_saved'         => 'Deine änderung des Usernamens wird bearbeitet. 50 GOTW-Punkte wurden abgebucht.',
        'invalid_secretcode'        => 'Google-Authentifizierungs Code ist Ungültig!',
        'enabled_secretcode'        => 'Authentifizierungsmethode eingestellt! Du musst dich nun erneut anmelden, bis gleich!',
        'disabled_secretcode'       => 'Authentifizierungsmethode deaktiviert!'
    ),
    'shop' => array (
        'offers' => array (
            'invalid_transaction'   => 'Transaktion konnte nicht bearbeitet werden!',
            'invalid_code'          => 'Der eingegebene Code ist falsch.',
            'success_1'             => 'Danke für Ihren Einkauf! Du hast',
            'success_2'             => 'GOTW-Punkte erhalten.',
        ),
        'club' => array (
            'already_vip'           => 'Du besitzt bereits die' . Config::shortName . ' Club Mitgliedschaft (Ungebrenzt) ',
            'purchase_success'      => 'YEAH! Du bist nun' . Config::shortName . ' Club Mitglied!',

        )
    ),
    'help' => array (
        'ticket_created'            => 'Ihr Help Ticket wurde erstellt. Um den aktuellen stand zu sehen, rufe deine Ticket liste auf.',
        'ticket_received'           => 'Ein Mitarbeiter hat auf dein Help Ticket geantwortet. Besuch das Help Tool um die Antwort zu sehen.',
        'already_open'              => 'Du hast noch ein Offenes Ticket. Sollte dieses bereits bearbeitet werden, kannst du ein neues erstellen.',
        'no_answer_yet'             => 'Du kannst erst antworten, wenn ein Mitarbeiter dein Ticket bearbeitet.',
    ),
    'forum' => array (
        'is_sticky'                 => 'Sticky aktuallisiert!',
        'is_closed'                 => 'Beitragsstatus geändert!'
    )
);

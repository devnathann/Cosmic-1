<?php
$GLOBALS['language'] = array(
    'core' => array(
        'belcredits' => 'Bel-Credits',
        'hotelapi' => array(
            'disabled'              => 'Kan vezoek niet verwerken omdat de hotelapi staat uitgeschakeld!'
        ),
        'notification' => array(
            'message_placed'        => 'Je bericht is geplaatst!',
            'message_deleted'       => 'Je bericht is verwijderd!',
            'invisible'             => 'Dit is onzichtbaar gemaakt!',
            'profile_invisible'     => 'Deze Leet heeft zijn/haar profiel onzichtbaar gemaakt.',
            'profile_notfound'      => 'Helaas.. we hebben de Leet niet kunnen vinden!',
            'no_permissions'        => 'Je hebt geen toestemming.',
            'already_liked'         => 'Je vindt dit al leuk!',
            'liked'                 => 'Je vindt dit leuk!',
            'banned_1'              => 'Je bent verbannen voor het overtreden van de Leet Regels:',
            'banned_2'              => 'Je ban verloopt over:',
            'something_wrong'       => 'Er is iets misgegaan, probeer het nogmaals.',
            'room_not_exists'       => 'Deze kamer bestaat niet!',
            'staff_received'        => 'Bedankt! De Leet Staff heeft dit ontvangen!',
            'not_enough_belcredits' => 'Je hebt niet genoeg belcredits.',
            'topic_closed'          => 'Je kunt niet reageren op een topic dat is gesloten!'
        ),
        'pattern' => array(
            'can_be'                => 'mag maximaal',
            'must_be'               => 'moet minimaal',
            'characters_long'       => 'karakters lang zijn.',
            'invalid'               => 'voldoet niet aan de eisen!',
            'invalid_characters'    => 'bevat ongeldige karakters!',
            'is_required'           => 'Vul alle velden in!',
            'not_same'              => 'komt niet overeen',
            'captcha'               => 'Recaptcha is foutief ingevoerd!',
            'numeric'               => 'moet numeriek zijn!',
            'email'                 => 'is niet geldig!'
        ),
        'title' => array(
            'home'              => 'Maak vrienden, speel games, maak kamers en val op!',
            'lost'              => 'Pagina niet gevonden!',
            'registration'      => 'Meld je gratis aan!',
            'hotel'             => 'Hotel',

            'password' => array(
                'claim'    => 'Wachtwoord kwijt?',
                'reset'    => 'Wachtwoord veranderen',
            ),
            'settings' => array(
                'index'         => 'Mijn voorkeuren',
                'password'      => 'Wachtwoord veranderen',
                'email'         => 'E-mail veranderen',
                'namechange'    => 'Leetnaam veranderen'
            ),
            'community' => array(
                'index'     => 'Community',
                'photos'    => 'Foto\'s',
                'staff'     => 'Leet Staff',
                'team'      => 'Leet Team',
                'fansites'  => 'Fansites',
                'value'     => 'Ruilwaarde',
                'forum'     => 'Ons Forum'
            ),
            'games' => array(
                'ranking'   => 'Highscores'
            ),
            'shop' => array(
                'index'     => 'Leet Winkel',
                'history'   => 'Aankoopgeschiedenis',
                'club'      => 'Leet Club'
            ),
            'help' => array(
                'index'     => 'Help Tool',
                'requests'  => 'Help Tickets',
                'new'       => 'Help Ticket openen'
            ),
            'jobs' => array(
                'index'     => 'Leet Vacatures',
                'apply'     => 'Reageren op vacature'
            )
        )
    ),
    'login' => array(
        'invalid_password'          => 'Onjuist wachtwoord.',
        'invalid_pincode'           => 'Deze pincode komt niet overeen met die van deze Leet!',
        'fill_in_pincode'           => 'Vul nu je pincode in om toegang te krijgen tot jouw account!'
    ),
    'register' => array(
        'username_invalid'          => 'Leetnaam is in strijd met de Leet Regels.',
        'username_exists'           => 'Leetnaam is al in gebruik :-('
    ),
    'claim' => array(
        'invalid_email'             => 'Dit e-mailadres komt niet overeen met die van deze Leet ID.',
        'invalid_link'              => 'Deze link is verlopen. Vraag opnieuw je wachtwoord aan om je wachtwoord te veranderen.',
        'send_link'                 => 'We hebben zojuist een e-mail naar je gestuurd! Niks ontvangen? Controleer dan de map met ongewenste e-mail.',
        'password_changed'          => 'Je wachtwoord is veranderd. Je kunt nu weer inloggen!',

        'email'  => array(
            'title'                 => 'Verander je wachtwoord.'
        )
    ),
    'settings' => array(
        'email_saved'               => 'Je e-mailadres is veranderd.',
        'pincode_saved'             => 'Je pincode is opgeslagen, je zult opnieuw moeten inloggen. Tot zo! :)',
        'password_saved'            => 'Je wachtwoord is veranderd. Je zult nu opnieuw moeten inloggen. Tot zo! :)',
        'preferences_saved'         => 'Je voorkeuren zijn opgeslagen!',
        'current_password_invalid'  => 'Huidig wachtwoord komt niet overeen met die van je Leet ID.',
        'choose_new_username'       => 'Vul een nieuwe Leetnaam in.',
        'choose_new_pincode'        => 'Vul een nieuwe pincode in.',
        'user_is_active'            => 'Deze Leet is mogelijk nog actief!',
        'user_not_exists'           => 'Deze Leetnaam is beschikbaar en bestaat nog niet!',
        'name_change_saved'         => 'Je naam is gewijzigd! En er zijn 50 Bel-Credits afgeschreven.',
        'invalid_secretcode'        => 'Google Authenticatie secretcode is onjuist.',
        'enabled_secretcode'        => 'Authenticatie methode ingesteld! Je zult opnieuw moeten inloggen.. tot zo!',
        'disabled_secretcode'       => 'Authenticatie methode uitgeschakeld!'
    ),
    'shop' => array(
        'offers' => array(
            'invalid_transaction'   => 'Transactie kon niet verwerkt worden!',
            'invalid_code'          => 'De door jouw ingevulde code is niet correct.',
            'success_1'             => 'Bedankt voor je aankoop! Je hebt',
            'success_2'             => 'Bel-Credits ontvangen.'
        ),
        'club' => array(
            'already_vip'           => 'Je bent al onbeperkt lid van de VIP Club.',
            'purchase_success'      => 'Jeuj! Je hebt een levenslange VIP-Club gekocht!'

        )
    ),
    'help' => array(
        'ticket_created'            => 'Jouw Help Ticket is aangemaakt. Bekijk je Help Tickets om het hulpverzoek te bekijken.',
        'ticket_received'           => 'Een Leet Staff heeft gereageerd op je Help Tool ticket. Bezoek de Help Tool om de reactie te bekijken.',
        'already_open'              => 'Je hebt nog een openstaande ticket! Wanneer deze behandeld is kun je weer een ticket aanmaken.',
        'no_answer_yet'             => 'Je kunt pas reageren als een Leet Staff je ticket heeft beantwoord.',
    ),
    'forum' => array(
        'is_sticky'                 => 'Sticky geüpdate!',
        'is_closed'                 => 'Topic status aangepast!'
    )
);
<?php
$GLOBALS['language'] = array(
    'core' => array(
        'belcredits' => 'GOTW-Points',
        'notification' => array(
            'message_placed'        => 'Your message has been posted!',
            'message_deleted'       => 'Your message has been deleted!',
            'invisible'             => 'This is made invisible!',
            'profile_invisible'     => 'This Asteroid has made his/her profile invisible.',
            'profile_notfound'      => 'Unfortunately.. we could not find the Asteroid!',
            'no_permissions'        => 'You do not have permission.',
            'already_liked'         => 'You already like this!',
            'liked'                 => 'You like this!',
            'banned_1'              => 'You have been banned for breaking the Asteroid Rules:',
            'banned_2'              => 'Your ban expires:',
            'something_wrong'       => 'Something went wrong, please try again.',
            'room_not_exists'       => 'This room does not exist!',
            'staff_received'        => 'Thanks! The Asteroid Staff has received this!',
            'not_enough_belcredits' => 'You do not have enough gotwpoints.',
            'topic_closed'          => 'You cannot respond to a topic that has been closed!'
        ),
        'pattern' => array(
            'can_be'                => 'may maximum',
            'must_be'               => 'must be minimal',
            'characters_long'       => 'characters long.',
            'invalid'               => 'does not meet the requirements!',
            'invalid_characters'    => 'contains invalid characters!',
            'is_required'           => 'Fill out all fields!',
            'not_same'              => 'does not match',
            'captcha'               => 'Recaptcha was entered incorrectly!',
            'numeric'               => 'must be numeric!',
            'email'                 => 'is not valid!'
        ),
        'title' => array(
            'home'              => 'Make friends, play games, make rooms and stand out!',
            'lost'              => 'Page not found!',
            'registration'      => 'Register for free!',
            'hotel'             => 'Hotel',

            'password' => array(
                'claim'    => 'Forgot password?',
                'reset'    => 'Change password',
            ),
            'settings' => array(
                'index'         => 'My preferences',
                'password'      => 'Change password',
                'email'         => 'Change e-mail',
                'namechange'    => 'Change Asteroidname'
            ),
            'community' => array(
                'index'     => 'Community',
                'photos'    => 'Photo\'s',
                'staff'     => 'Asteroid Staff',
                'team'      => 'Asteroid Team',
                'fansites'  => 'Fansites',
                'value'     => 'Exchange value',
                'forum'     => 'Our forum'
            ),
            'games' => array(
                'ranking'   => 'Highscores'
            ),
            'shop' => array(
                'index'     => 'Asteroid Store',
                'history'   => 'Purchase history',
                'club'      => 'Asteroid Club'
            ),
            'help' => array(
                'index'     => 'Help Tool',
                'requests'  => 'Help Tickets',
                'new'       => 'Open Help Ticket'
            ),
            'jobs' => array(
                'index'     => 'Asteroid Vacancies',
                'apply'     => 'Respond to vacancy'
            )
        )
    ),
    'login' => array(
        'invalid_password'          => 'Invalid password.',
        'invalid_pincode'           => 'This pin code does not match that of this Asteroid!',
        'fill_in_pincode'           => 'Enter your pin code now to gain access to your account!'
    ),
    'register' => array(
        'username_invalid'          => 'Asteroidname is contrary to the Asteroid Rules.',
        'username_exists'           => 'Asteroidname is already in use :-('
    ),
    'claim' => array(
        'invalid_email'             => 'This e-mail address does not match that of this Asteroid ID.',
        'invalid_link'              => 'This link has expired. Request your password again to change your password.',
        'send_link'                 => 'We have just sent you an e-mail! Received nothing? Then check the junk e-mail folder.',
        'password_changed'          => 'Your password has been changed. You can now log in again!',

        'email'  => array(
            'title'                 => 'Change your password.'
        )
    ),
    'settings' => array(
        'email_saved'               => 'Your e-mail address has been changed.',
        'pincode_saved'             => 'Your pin code has been saved, you will have to log in again. See you soon! :)',
        'password_saved'            => 'Your password has been changed. You will now have to log in again. See you soon! :)',
        'preferences_saved'         => 'Your preferences have been saved!',
        'current_password_invalid'  => 'Current password does not match that of your Asteroid ID.',
        'choose_new_username'       => 'Enter a new Asteroidname.',
        'choose_new_pincode'        => 'Enter a new pin code.',
        'user_is_active'            => 'This Asteroid may still be active!',
        'user_not_exists'           => 'This Asteroidname is available and does not exist yet!',
        'name_change_saved'         => 'Your application will be processed, 50 gotw-points have been debited.',
        'invalid_secretcode'        => 'Google Authentication secret code is incorrect.',
        'enabled_secretcode'        => 'Authentication method set! You will have to log in again... see you soon!',
        'disabled_secretcode'       => 'Authentication method disabled!'
    ),
    'shop' => array(
        'offers' => array(
            'invalid_transaction'   => 'Transaction could not be processed!',
            'invalid_code'          => 'The code you entered is incorrect.',
            'success_1'             => 'Thank you for your purchase! You have received',
            'success_2'             => 'gotw-points.'
        ),
        'club' => array(
            'already_vip'           => 'You are an unlimited member of the Asteroid Club.',
            'purchase_success'      => 'Yeah! You are now a member of the Asteroid Club for 31 days.'

        )
    ),
    'help' => array(
        'ticket_created'            => 'Your Help Ticket has been created. View your Help Tickets to view the help request.',
        'ticket_received'           => 'A Asteroid Staff has responded to your Help Tool ticket. Visit the Help Tool to view the response.',
        'already_open'              => 'You still have an outstanding ticket! When this has been treated you can create a ticket again.',
        'no_answer_yet'             => 'You can only respond once a Asteroid Staff has answered your ticket.',
    ),
    'forum' => array(
        'is_sticky'                 => 'Sticky updated!',
        'is_closed'                 => 'Topic status changed!'
    )
);
<?php
use App\Config;

$GLOBALS['language'] = array (
    'website' => array (
        /*     App/View/base.html     */
        'base' => array(
            'nav_home'              => 'Inicio',

            'nav_community'         => 'Comunidade',
            'nav_news'              => 'Noticias',
            'nav_jobs'              => 'Jobs',
            'nav_photos'            => 'Fotos',
            'nav_staff'             => 'Equipe',

            'nav_shop'              => 'Loja',
            'nav_buy_points'        => 'Comprar GOTW-Points',
            'nav_buy_club'          => 'Comprar ' . Config::shortName . ' Clube',
            'nav_purchasehistory'   => 'Histórico de Compras',
            'nav_changename'        => 'Mudar Nome',

            'nav_highscores'        => 'Rank',

            'nav_forum'             => 'My Groups',

            'nav_helptool'          => 'Ajuda',
            'nav_helptickets'       => 'Ticket de Ajuda',

            'nav_housekeeping'      => 'Painel',

            'close'                 => 'Fechar',
            'cookies'               => 'uses its own and third-party cookies to provide a better service and also ensures that the advertisements better match your preferences. If you use our website you agree with our cookie policy.',
            'read_more'             => 'Ler mais',
            'thanks_for_playing'    => 'Muito obrigada por jogar',
            'made_with_love'        => 'foi feito com muito amor',
            'credits'               => 'Agradecimentos a Raizer e Metus',
            'and_all'               => 'e todos',

            'login_name'            => 'Usuário',
            'login_password'        => 'Senha',
            'login_save_data'       => 'Mantenha-me logado',
            'login_lost_password'   => 'Esqueci minha senha',

            'report_message'        => 'Reporte essa mensagem',
            'report_certainty'      => 'Você está prestes a reportar essa mensagem. Tem certeza que deseja reportar?',
            'report_inappropriate'  => 'Sim, isso é inapropriado!',

            'user_to'               => 'Vá para o',
            'user_profile'          => 'Meu perfil',
            'user_settings'         => 'Configurações',
            'user_logout'           => 'Deslogar',

            'header_slogan'         => 'Virtual world for young people!',
            'header_slogan2'        => 'Join our community and make new friends',
            'header_login'          => 'Entrar',
            'header_register'       => 'Registre-se de graça!',
            'header_to'             => 'Vá para o',

            'footer_helptool'       => 'Help Tool',
            'footer_rules'          => 'The Leet Rules',
            'footer_terms'          => 'Terms and Conditions',
            'footer_privacy'        => 'Privacy declaration',
            'footer_cookies'        => 'Cookie policy',
            'footer_guide'          => 'Parents guide'
        ),

        /*     public/assets/js/web     */
        'javascript' => array(
            'web_customforms_markedfields'                  => 'All fields marked with an * are mandatory.',
            'web_customforms_loadingform'                   => 'Loading form...',
            'web_customforms_next'                          => 'Next',
            'web_customforms_close'                         => 'Close',
            'web_customforms_participation'                 => 'Thanks for your participation!',
            'web_customforms_sent'                          => 'Your answers have been sent and will be analyzed by the person who starts this form.',
            'web_customforms_answer'                        => 'Your answer',

            'web_dialog_cancel'                             => 'Cancel',
            'web_dialog_validate'                           => 'Validate',
            'web_dialog_confirm'                            => 'Confirm your choice',

            'web_hotel_backto'                              => 'Voltar para o ' . Config::shortName . ' Hotel',

            'web_fill_pincode'                              => 'Enter the pin code that you specified when creating the extra security on your account. Well, I forgot this one? Then contact us via the Leet Help Tool',
            'web_twostep'                                   => 'Two-step authorization!',
            'web_login'                                     => 'You must be logged in to report this message!',
            'web_loggedout'                                 => 'Logged out :(',

            'web_notifications_success'                     => 'Success!',
            'web_notifications_error'                       => 'Erro!',
            'web_notifications_info'                        => 'Information!',

            'web_page_article_login'                        => 'You must be logged in to post a comment!',

            'web_page_community_photos_login'               => 'You must be logged in to like photos!',
            'web_page_community_photos_loggedout'           => 'Logged out :(',

            'web_page_forum_change'                         => 'Change',
            'web_page_forum_cancel'                         => 'Cancel',
            'web_page_forum_oops'                           => 'Oops...',
            'web_page_forum_topic_closed'                   => 'This topic is closed and you can no longer respond.',
            'web_page_forum_login_toreact'                  => 'In order to respond, you need to be logged in!',
            'web_page_forum_login_tolike'                   => 'You must be logged in to like this post!',
            'web_page_forum_loggedout'                      => 'Logged out :(',

            'web_page_profile_login'                        => 'You must be logged in to like photos!',
            'web_page_profile_loggedout'                    => 'Logged out :(',

            'web_page_settings_namechange_request'          => 'Request',
            'web_page_settings_namechange_not_available'    => 'Not available',
            'web_page_settings_namechange_choose_name'      => 'Choose Leetname',

            'web_page_settings_verification_oops'           => 'Oops...',
            'web_page_settings_verification_fill_password'  => 'Enter your password!',
            'web_page_settings_verification_2fa_on'         => 'Google Authentication is currently set on your account. To use another verification method, you must first remove your old verification!',
            'web_page_settings_verification_2fa_secretkey'  => 'Have you scanned the QR code on your mobile? Then enter the secret key to confirm your account!',
            'web_page_settings_verification_2fa_authcode'   => 'Authentication code',
            'web_page_settings_verification_pincode_on'     => 'You currently have a pin code set on your account. To use another verification method you first have to remove your old verification!',
            'web_page_settings_verification_2fa_off'        => 'To disable Google Authentication we ask you to enter the secret code from the generator.',
            'web_page_settings_verification_pincode_off'    => 'To disable the pincode authentication we ask you to enter your pincode.',
            'web_page_settings_verification_pincode'        => 'Pincode code',
            'web_page_settings_verification_switch'         => 'Select the switch button to enable an authentication method!',

            'web_page_shop_offers_neosurf_name'             => 'Neosurf',
            'web_page_shop_offers_neosurf_description'      => 'Pay easily with Paypal and your GOTW-Points will be topped up immediately.',
            'web_page_shop_offers_neosurf_dialog'           => 'Enter your Paypal e-mailaddress below to continue.',
            'web_page_shop_offers_paypal_name'              => 'Paypal',
            'web_page_shop_offers_paypal_description'       => 'Pay easily with Paypal and your GOTW-Points will be topped up immediately.',
            'web_page_shop_offers_paypal_dialog'            => 'Enter your Paypal e-mailaddress below to continue.',
            'web_page_shop_offers_sms_name'                 => 'SMS',
            'web_page_shop_offers_sms_description'          => 'Send a code by SMS and receive a GOTW-Points code.',
            'web_page_shop_offers_sms_dialog'               => 'Send the code below in an SMS to get a GOTW-Points code.',
            'web_page_shop_offers_audiotel_name'            => 'Audiotel',
            'web_page_shop_offers_audiotel_description'     => 'Call a number one or more times to get a GOTW-Points code.',
            'web_page_shop_offers_audiotel_dialog'          => 'Call the number below to get a GOTW-Points code.',
            'web_page_shop_offers_pay_with'                 => 'Pay with',
            'web_page_shop_offers_points_for'               => 'GOTW-Points for',
            'web_page_shop_offers_get_code'                 => 'Get a GOTW-Points code',
            'web_page_shop_offers_fill_code'                => 'Enter your GOTW-Points code',
            'web_page_shop_offers_fill_code_desc'           => 'Enter your GOTW-Points code below to receive your GOTW-Points.',
            'web_page_shop_offers_submit'                   => 'Submit',
            'web_page_shop_offers_success'                  => 'Purchase successful!',
            'web_page_shop_offers_received'                 => 'Thank you for your purchase. You got',
            'web_page_shop_offers_received2'                => 'GOTW-Points.',
            'web_page_shop_offers_close'                    => 'Close',
            'web_page_shop_offers_failed'                   => 'Purchase failed!',
            'web_page_shop_offers_failed_desc'              => 'The purchase failed. Try again or contact us via the Help Tool.',
            'web_page_shop_offers_back'                     => 'Back',
            'web_page_shop_offers_no_card'                  => 'If you do not have a Neosurf prepaid card, you can see the',
            'web_page_shop_offers_no_card2'                 => 'points of sale',
            'web_page_shop_offers_to'                       => 'to',
            'web_page_shop_offers_buy_code'                 => 'Purchase access code'
        ),

        /*     App/View/Community     */
        'article' => array (
            'reactions'         => 'Reactions',
            'reactions_empty'   => 'There are no reactions yet.',
            'reactions_fill'    => 'Digite sua mensagem aqui...',
            'reactions_post'    => 'Publicar',
            'latest_news'       => 'Últimas notícias'
        ),
        'forum' => array (
          /*  Forum/index.html  */
            'index_subject'             => 'Assuntos',
            'index_topics'              => 'Tópicos',
            'index_latest_topic'        => 'Últimos tópicos',
            'index_empty'               => 'Não há tópicos',
            'index_latest_activities'   => 'Últimas atividades',
            'index_by'                  => 'por',

          /*  Forum/category.html  */
            'category_new_topic'        => 'Novo tópico',
            'category_back'             => 'Voltar',
            'category_topics'           => 'Tópicos',
            'category_posts'            => 'Posts',
            'category_latest_reacts'    => 'Latest reactions',
            'category_topic_by'         => 'Por',
            'category_no_reacts'        => 'No reactions',
            'category_latest_react_by'  => 'Última resposta por',
            'category_create_topic'     => 'Crie um tópico',
            'category_subject'          => 'Assunto',
            'category_description'      => 'Descrição',
            'category_create_button'    => 'Criar tópico',
            'category_or'               => 'ou',
            'category_cancel'           => 'cancelar',

          /*  Forum/topic.html  */
            'topic_react'               => 'Responder',
            'topic_close'               => 'Fechar',
            'topic_reopen'              => 'Re-abrir',
            'topic_since'               => 'Desde:',
            'topic_posts'               => 'Posts:',
            'topic_topic'               => 'Tópico:',
            'topic_reaction'            => 'Reaction:',
            'topic_closed'              => 'Atenção! Esse tópico está fechado, algum problema? Contate-nos via',
            'topic_helptool'            =>  Config::shortName . ' Ajuda',
            'topic_and'                 => 'e',
            'topic_likes_1'             => 'outros como este!',
            'topic_likes_2'             => 'likes this!',
            'topic_likes_3'             => 'like this!'
        ),
        'community_photos' => array (
            'by'          => 'por',
            'photos_by'   => 'Photos\'s by',
            'photos_desc' => 'See all the latest pictures taken by',
            'load_more'   => 'Carregar mais fotos'
        ),
        'community_staff' => array (
            'title'       => 'Como fazer parte da equipe?',
            'desc'        => 'Our staff is to help and guide you within this hotel!',
            'content_1'   => 'Todos sonham com um lugar entre os membros da equipe ' . Config::shortName . ', mas infelizmente não é possível abrir vaga a todos.',
            'content_2'   => 'Quando abrirem novas vagas para equipe, não se preocupe que você irá ficar sabendo.'
        ),

        /*     App/View/Games     */
        'games_ranking' => array (
            'username' => 'name'
        ),

        /*     App/View/Help     */
        'help' => array (
          /*  Help/help.html  */
            'help_title'                => 'FAQ',
            'help_label'                => 'Find all the answers about your questions here!',
            'help_other_questions'      => 'Other questions',
            'help_content_1'            => 'Didn\'t find the answer to your question? Do not hesitate to contact our customer service so that we can provide more information.',
            'help_contact'              => 'Contact',
            'title'                     => 'Help Tool',
            'desc'                      => 'You can search here for answers to your questions. If you cannot find the answer to your question, submit a request.',

          /*  Help/request.html  */
            'request_closed'            => 'FECHADO',
            'request_on'                => 'On:',
            'request_ticket_amount'     => 'Quantidade de tickets:',
            'request_react_on'          => 'React on:',
            'request_react'             => 'React',
            'request_description'       => 'Descrição',
            'request_react_on_ticket'   => 'React on ticket',
            'request_contact'           => 'Contate-nos',
            'request_contact_help'      => 'Você pode entrar em contato conosco abrindo um novo ticket.',
            'request_new_ticket'        => 'Novo ticket',
            'request_subject'           => 'Resumo',
            'request_type'              => 'Status',
            'request_status'            => 'Ticket aberto',
            'request_in_treatment'      => 'Em processo',
            'request_open'              => 'Aberto',
            'request_closed'            => 'Fechado'
        ),
        'help_new' => array (
            'title'         => 'Meu ticket',
            'subject'       => 'Resumo',
            'description'   => 'Descrição',
            'open_ticket'   => 'Abrir um ticket'
        ),

        /*     App/View/Home     */
        'home' => array (
            'to'                     => 'Vá para o',
            'friends_online'         => 'Amigos Online',
            'now_in'                 => 'Now in',
            'latest_news'            => 'Últimas notícias',
            'latest_facts'           => 'Últimas notícias no ' . Config::shortName . '!',
            'popular_rooms'          => 'Quartos populares',
            'popular_rooms_label'    => 'Saiba quais quartos estão em alta no ' . Config::shortName . '!',
            'popular_no_rooms'       => 'Nenhum quarto foi criado ainda!',
            'goto_room'              => 'Juntar-se',
            'popular_groups'         => 'Grupos Populares',
            'popular_groups_label'   => 'Fique à vontade e junte-se ao que preferir',
            'popular_no_groups'      => 'Nenhum grupo foi criado ainda!',
            'load_news'              => 'Carregue mais notícias'
        ),
        'lost' => array (
            'page_not_found'          => 'Página não encontrada!',
            'page_content_1'          => 'Desculpe, a página que você está procurando não existe ou está em manutenção.',
            'page_content_2'          => 'Dá uma conferida se o link da página está correto.',
            'sidebar_title'           => 'Talvez você esteja procurando por...',
            'sidebar_stats'           => 'Onde estão seus amigos?',
            'sidebar_stats_label_1'   => 'Talvez você possa encontrá-los no',
            'sidebar_stats_label_2'   => 'Rank',
            'sidebar_rooms'           => 'Procurando por grupos populares?',
            'sidebar_rooms_label_1'   => 'Dá uma procurada dentro do',
            'sidebar_rooms_label_2'   => 'jogo',
            'sidebar_else'            => 'Eu perdi meus óculos!',
            'sidebar_else_label'      => 'Sério? Procura eles por aí que você vai achar! :-)'
        ),
        'profile' => array (
            'overlay_search'        => 'Por quem você está procurando?',
            'since'                 => 'desde',
            'currently'             => 'Neste momento',
            'never_online'          => 'Ainda não está online',
            'last_visit'            => 'Última visita',
            'guestbook_title'       => 'Livro de visitas',
            'guestbook_label'       => 'Deixe algo aqui :D',
            'guestbook_input'       => 'O que você está fazendo,',
            'guestbook_input_1'     => 'O que você quer',
            'guestbook_input_2'     => 'saber?',
            'guestbook_load_more'   => 'Carregar mais mensagens',
            'badges_title'          => 'Emblemas',
            'badges_label'          => 'Alguns dos seus emblemas',
            'badges_empty'          => 'Não tem emblemas ainda',
            'friends_title'         => 'Amigos',
            'friends_label'         => 'Alguns amigos em sua lista',
            'friends_empty'         => 'Não tem amigos ainda',
            'groups_title'          => 'Grupos',
            'groups_label'          => 'Se divirta!',
            'groups_empty'          => 'Não está em nenhum grupo',
            'rooms_title'           => 'Quartos',
            'rooms_label'           => 'Últimos quartos criados',
            'rooms_empty'           => 'Não criou nenhum quarto ainda',
            'photos_title'          => 'Fotos',
            'photos_label'          => 'Quer tirar uma foto comigo?',
            'photos_empty'          => 'Não tirou nenhuma foto ainda'
        ),
        'registration' => array (
            'title'                 => 'Coloque suas informações abaixo!',
            'email'                 => 'E-mail',
            'email_fill'            => 'Coloque seu endereço de e-mail aqui...',
            'email_help'            => 'We will need this information to restore your account in case you lose access.',
            'password'              => 'Senha',
            'password_fill'         => 'Senha...',
            'password_repeat'       => 'Repita sua senha',
            'password_repeat_fill'  => 'Repita sua senha...',
            'password_help_1'       => 'Sua senha deve ter no mínimo 6 caracteres e conter letras e números.',
            'password_help_2'       => 'Faça uma senha diferente de outros sites!',
            'birthdate'             => 'Data de nascimento',
            'day'                   => 'Dia',
            'month'                 => 'Mês',
            'year'                  => 'Ano',
            'birthdate_help'        => 'Precisaremos dessas informações para restaurar sua conta caso você perca o acesso a ela.',
            'found'                 => 'Por onde conheceu o ' . Config::shortName . ' Hotel?',
            'found_choose'          => 'Escolha...',
            'found_choose_1'        => 'Google',
            'found_choose_2'        => 'Por um amigo',
            'found_choose_3'        => 'Por outro jogo',
            'found_choose_4'        => 'Pelo Facebook',
            'found_choose_5'        => 'Outro',
            'create_user'           => 'Crie seu ' . Config::shortName . '!',
            'username'              => 'Nome',
            'username_fill'         => 'Nome...',
            'username_help'         => 'Seu nome será único.',
            'sex'                   => 'Gênero',
            'male'                  => 'Menino',
            'female'                => 'Menina',
            'register'              => 'Registre-se'
        ),

        /*     App/View/Jobs     */
        'apply' => array (
            'title'               => 'React on the invoice',
            'content_1'           => 'Thank you for your interest in Leet Hotel and for responding to the vacancy.',
            'content_2'           => 'Try to answer the questionnaire as accurately as possible.',
            'description'         => 'Job description',
            'question_name'       => 'Qual o seu nome?',
            'question_age'        => 'Quantos anos você tem?',
            'question_why'        => 'Why do you think you might be suitable?',
            'question_time'       => 'How many hours are you online?',
            'question_time_help'  => 'Tell us how many hours you spend online a day at Leet Hotel.',
            'monday'              => 'Segunda-feira',
            'tuesday'             => 'Terça-feira',
            'wednesday'           => 'Quarta-feira',
            'thursday'            => 'Quinta-feira',
            'friday'              => 'Sexta-feira',
            'saturday'            => 'Sábado',
            'sunday'              => 'Domingo',
            'time_to_time'        => 'from X to Y hours',
            'send'                => 'Send my application'
        ),
        'jobs' => array (
            'title'                   => 'List of vacancies',
            'applications'            => 'My applications',
            'available_applications'  => 'Available vacancies',
            'buildteam'               => 'Buildteam',
            'buildteam_desc'          => 'They are responsible for building (event/official) rooms.',
            'react'                   => 'React'
        ),

        /*     App/View/Password     */
        'password_claim' => array (
            'title'                 => 'Esqueceu sua senha?',
            'content_1'             => 'Insira seu nome ' . Config::shortName . ' e seu endereço de e-mail abaixo para enviarmos um link por e-mail para você criar uma nova senha.',
            'content_2'             => 'Don\'t do this if someone asks you to do this!',
            'username'              => 'Nome ' . Config::shortName . '',
            'email'                 => 'Endereço de e-mail',
            'send'                  => 'Enviar e-mail',
            'wrong_page'            => 'Alarme falso!',
            'wrong_page_content_1'  => 'Se você lembrou da sua senha - ou entrou aqui sem querer - você pode clicar no link abaixo para voltar para a página inicial.',
            'back_to_home'          => 'Voltar a página inicial'
        ),
        'password_reset' => array (
            'title'                     => 'Mudar senha',
            'new_password'              => 'Nova senha',
            'new_password_fill'         => 'Insira sua nova senha aqui...',
            'new_password_repeat_fill'  => 'Repita sua nova senha...',
            'change_password'           => 'Mudar senha'
        ),

        /*     App/View/Settings     */
        'settings_panel' => array (
            'preferences'    => 'Minhas preferências',
            'password'       => 'Mudar senha',
            'verification'   => 'Colocar verificação',
            'email'          => 'Mudar e-mail',
            'namechange'     => 'Mudar nome ' . Config::shortName . '',
            'shop_history'   => 'Histórico de compras'
        ),
        'settings_email' => array (
            'title'           => 'Mudar e-mail',
            'email_title'     => 'E-mail',
            'email_label'     => 'Seu endereço de e-mail é necessário para restaurar sua conta caso esqueça sua senha.',
            'password_title'  => 'Senha atual',
            'fill_password'   => 'Insira sua senha atual aqui...',
            'save'            => 'Salvar'
        ),
        'settings_namechange' => array (
            'title'           => 'Mudar nome ' . Config::shortName . '',
            'help_1'          => 'Você deseja mudar seu nome ' . Config::shortName . '? Isso custa pontos',
            'help_2'          => 'e será debitado imediatament após sua solicitação. Depois que seu nome for trocado, não terá como desfazer! Então, tenha certeza sobre sua decisão!',
            'fill_username'   => 'Nome ' . Config::shortName . '...',
            'request'         => 'Solicitar'
        ),
        'settings_password' => array (
            'title'                     => 'Mudar senha',
            'password_title'            => 'Senha atual',
            'fill_password'             => 'Insira sua senha atual aqui...',
            'newpassword_title'         => 'Nova senha',
            'fill_newpassword'          => 'Insira sua nova senha aqui...',
            'fill_newpassword_repeat'   => 'Repita a nova senha...',
            'help'                      => 'Sua senha deve ter no mínimo 6 caracteres e conter letras e números..',
            'save'                      => 'Salvar'
        ),
        'settings_preferences' => array (
            'title'               => 'Minhas preferências',
            'follow_title'        => 'Função de seguir - quem pode te seguir?' ,
            'follow_label'        => 'Eu não quero que nenhum ' . Config::shortName . ' me siga',
            'friends_title'       => 'Pedidos de amizade',
            'friends_label'       => 'Bloquear pedidos de amizade?',
            'room_title'          => 'Convites para quartos',
            'room_label'          => 'Eu não quero ser convidado para quartos',
            'hotelalerts_title'   => 'Alertas do Hotel',
            'hotelalerts_label'   => 'Eu não quero receber notificações do Hotel',
            'chat_title'          => 'Configurações do chat',
            'chat_label'          => 'Eu quero usar o chat no modo antigo'
        ),
        'settings_verification' => array (
            'title'                 => 'Proteja sua conta',
            'help'                  => 'Esse processo vai aumentar a segurança da sua conta. Quando você logar, você deve, de acordo com suas preferências, colocar o PIN que você definiu ou pegar o código gerado pelo aplicativo de autenticação do Google.',
            'password_title'        => 'Insira sua senha',
            'auth_title'            => 'Verificação de dois fatores',
            'auth_label'            => 'Proteja sua conta com a verificação de dois fatores',
            'method_title'          => 'Método de verificação',
            'method_choose'         => 'Selecione o método de verificação...',
            'method_pincode'        => 'Eu quero escolher um código PIN',
            'method_auth_app'       => 'Eu quero usar o Google 2FA',
            'pincode_title'         => 'Seguraça com código PIN',
            'pincode_label'         => 'Escolha um código PIN para garantir maior segurança em sua conta, tendo uma maior proteção contra hackers.',
            'fill_pincode'          => 'Insira seu código PIN',
            'generate_auth'         => 'Geração de código pelo Google 2FA',
            'generate_auth_label'   => 'Esse método é o mais confiável. Ele irá conectar sua conta ' . Config::shortName . ' ao aplicativo de autenticação do Google (Google Authenticator) no seu dispositivo. Quando você logar no ' . Config::shortName . ', você terá apenas que colocar o código gerado no app e estará pronto para jogar.',
            'link_account'          => 'Conecte sua conta',
            'link_account_label'    => 'Para conectar sua conta, você pode scannear o QR code com um aplicativo e clicar para salvar no Google Authenticator.',
            'save'                  => 'Salvar'
        ),

        /*     App/View/Shop     */
        'shop_club' => array (
            'club_benefits'       => 'Clube: Benefícios',
            'club_buy'            => 'Comprar o ' . Config::shortName . ' Clube',
            'unlimited'           => 'Ilimitado',
            'more_information'    => 'Mais informações',
            'content_1'           => 'Você tem alguma dúvida ou problema com a compra?',
            'content_2'           => 'Caso necessite de ajuda, entre em contato conosco pelo',
            'help_tool'           =>  Config::shortName . ' Ajuda',
            'random_club_users'   => 'Alguns membros do ' . Config::shortName . ' Clube',
            'desc'                => 'Here you can buy a club for real money. With club you can buy exclusive items.'
        ),
        'shop_history' => array (
            'buy_history'         => 'Histórico de Compras',
            'product'             => 'Produto',
            'date'                => 'Data',
            'buy_history_empty'   => 'Você não fez compras ainda.',
            'buy_club'            => 'Compre ' . Config::shortName . ' Clube',
            'content_1'           => 'Você tem alguma dúvida ou problema com a compra?',
            'content_2'           => 'Caso necessite de ajuda, entre em contato conosco pelo',
            'help_tool'           =>  Config::shortName . ' Ajuda',
            'title'               => 'My Histórico de Compra',
            'desc'                => 'You see here all the purchases you have made'
        ),
        'shop_offers' => array (
            'back'              => 'Voltar',
            'buymethods'        => 'Métodos de pagamento',
            'for'               => 'for',
            'or_lower'          => 'or lower',
            'loading_methods'   => 'Os métodos de pagamento estão sendo carregados...',
            'store'             => 'Store'
        ),
        'shop' => array (
            'title'             => 'Selecione um produto',
            'country'           => 'País:',
            'netherlands'       => 'Países Baixos',
            'belgium'           => 'Bélgica',
            'super_rare'        => 'Super raros',
            'more_information'  => 'Mais informações',
            'content_1'         => 'Você tem alguma dúvida ou problema com a compra?',
            'content_2'         => 'Caso necessite de ajuda, entre em contato conosco pelo',
            'help_tool'         =>  Config::shortName . ' Ajuda',
            'not_logged'        => 'Oops! Você não está logado.',
            'have_to_login'     => 'Você precisa estar logado para visitar a Loja ' . Config::shortName . '.',
            'click_here'        => 'Clique aqui',
            'to_login'          => 'para logar.',
            'desc'              => 'Here you can buy credits for real money for real money, with this you can buy exclusive items in our catalogue'
        ),
        'games_ranking' => array(
            'title'             => 'Highscores',
            'desc'              => 'On this you find all the high scores of our players!'
        )
    ),
    'core' => array (
        'belcredits' => 'GOTW-Points',
        'hotelapi' => array (
            'disabled' => 'Cannot process request because the hotelapi is turned off!'
        ),
        'dialog' => array (
            'logged_in'             => 'Oops to visit this page you must be logged in!',
            'not_logged_in'         => 'You do not have to be logged in to visit this page!'
        ),
        'notification' => array (
            'message_placed'        => 'Sua mensagem foi publicada!',
            'message_deleted'       => 'Sua mensagem foi deletada!',
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
            'topic_closed'          => 'You cannot respond to a topic that has been closed!',
            'post_not_allowed'      => 'Você não tem acesso para criar uma postagem neste fórum!'
        ),
        'pattern' => array (
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
        'title' => array (
            'home'              => 'Make friends, play games, make rooms and stand out!',
            'lost'              => 'Página não encontrada!',
            'registration'      => 'Registre-se de graça!',
            'hotel'             => 'Hotel',

            'password' => array (
                'claim'    => 'Esqueceu sua senha?',
                'reset'    => 'Mudar senha',
            ),
            'settings' => array (
                'index'         => 'Minhas preferências',
                'password'      => 'Mudar senha',
                'email'         => 'Mudar e-mail',
                'namechange'    => 'Mudar nome ' . Config::shortName . ''
            ),
            'community' => array (
                'index'     => 'Community',
                'photos'    => 'Photo\'s',
                'staff'     => 'Asteroid Staff',
                'team'      => 'Asteroid Team',
                'fansites'  => 'Fansites',
                'value'     => 'Exchange value',
                'forum'     => 'Our forum'
            ),
            'games' => array (
                'ranking'   => 'Rank'
            ),
            'shop' => array (
                'index'     => 'Asteroid Store',
                'history'   => 'Purchase history',
                'club'      => 'Asteroid Club'
            ),
            'help' => array (
                'index'     => 'Help Tool',
                'requests'  => 'Help Tickets',
                'new'       => 'Open Help Ticket'
            ),
            'jobs' => array (
                'index'     => 'Asteroid Vacancies',
                'apply'     => 'Respond to vacancy'
            )
        )
    ),
    'login' => array (
        'invalid_password'          => 'Invalid password.',
        'invalid_pincode'           => 'This pin code does not match that of this Asteroid!',
        'fill_in_pincode'           => 'Enter your pin code now to gain access to your account!'
    ),
    'register' => array (
        'username_invalid'          => 'Asteroidname is contrary to the Asteroid Rules.',
        'username_exists'           => 'Asteroidname is already in use :-('
    ),
    'claim' => array (
        'invalid_email'             => 'This e-mail address does not match that of this Asteroid ID.',
        'invalid_link'              => 'This link has expired. Request your password again to change your password.',
        'send_link'                 => 'We have just sent you an e-mail! Received nothing? Then check the junk e-mail folder.',
        'password_changed'          => 'Your password has been changed. You can now log in again!',

        'email'  => array (
            'title'                 => 'Change your password.'
        )
    ),
    'settings' => array (
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
    'shop' => array (
        'offers' => array (
            'invalid_transaction'   => 'Transaction could not be processed!',
            'invalid_code'          => 'The code you entered is incorrect.',
            'success_1'             => 'Thank you for your purchase! You have received',
            'success_2'             => 'gotw-points.'
        ),
        'club' => array (
            'already_vip'           => 'You are an unlimited member of the Asteroid Club.',
            'purchase_success'      => 'Yeah! You are now a member of the Asteroid Club for 31 days.'

        )
    ),
    'help' => array (
        'ticket_created'            => 'Your Help Ticket has been created. View your Help Tickets to view the help request.',
        'ticket_received'           => 'A Asteroid Staff has responded to your Help Tool ticket. Visit the Help Tool to view the response.',
        'already_open'              => 'You still have an outstanding ticket! When this has been treated you can create a ticket again.',
        'no_answer_yet'             => 'You can only respond once a Asteroid Staff has answered your ticket.',
    ),
    'forum' => array (
        'is_sticky'                 => 'Sticky updated!',
        'is_closed'                 => 'Topic status changed!'
    )
);

<?php

include_once('../locale/tools/php-gettext/gettext.inc');

T_setlocale(LC_MESSAGES, LOCALE);
T_bindtextdomain('messages', '../locale');
T_bind_textdomain_codeset('messages', 'UTF-8');
T_textdomain('messages');

?>

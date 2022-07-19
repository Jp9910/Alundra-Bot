<?php

namespace Jp;

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

$ini_file = parse_ini_file(".env");

$discord = new Discord([
    'token' => $ini_file['bot-token'],
]);

$discord->on('ready', function (Discord $discord) {
    echo "Bot is ready!", PHP_EOL;

    // Listen for messages.
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord) {
        if ($message->author->bot) {
            return;
        }
        // var_dump($message);
        switch ($message->content) {
            case 'pog':
                $message->reply("PogChamp");
                break;
            case 'oi':
                $message->reply("Oi {$message->author->username}!!");
                break;
        }
        echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });
});

$discord->run();

// Events: https://discord-php.github.io/DiscordPHP/reference/classes/Discord-WebSockets-Event.html
// Message class: https://discord-php.github.io/DiscordPHP/reference/classes/Discord-Parts-Channel-Message.html#property_channel_id

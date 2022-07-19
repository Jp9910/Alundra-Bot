<?php

namespace Jp;

include __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Websockets\MessageReaction;
use Discord\Builders\MessageBuilder;
use Discord\WebSockets\Event;
use Discord\Parts\Channel;

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
        $firstChar = $message->content[0];
        switch ($firstChar) {
            case '!':
                switch (substr($message->content, 1)) {
                    case 'alundra':
                        replyWithMyImage($message, $discord);
                        break;
                    case 'tnc':
                        replyVaVoce($message);
                        break;
                }
                break;
            default:
                switch ($message->content) {
                    case 'pog':
                        $message->reply("PogChamp");
                        break;
                    case 'oi':
                        $message->reply("Hi {$message->author->username}!!");
                        break;
                }
        }
        echo "{$message->author->username}: {$message->content}", PHP_EOL;
    });

    $discord->on(Event::MESSAGE_UPDATE, function (Message $newMessage, Discord $discord, ?Message $oldMessage) {
        /**
         * Called with two Message objects when a message is updated in a guild or private
         * channel. The old message may be null if storeMessages is not enabled or the message
         * was sent before the bot was started. Discord does not provide a way to get message
         * update history. Requires the Intents::GUILD_MESSAGES intent.
         */

        // var_dump($oldMessage); // NULL
        $newMessage->reply("{$newMessage->author->username} Você alterou a mensagem!");
    });

    $discord->on(Event::MESSAGE_DELETE, function (mixed $message, Discord $discord) {
        if ($message instanceof Message) {
            // Message is present in cache
        }
        // If the message is not present in the cache:
        else {
            // {
            //     "id": "", // deleted message ID,
            //     "channel_id": "", // message channel ID,
            //     "guild_id": "" // channel guild ID
            // }
        }
    });

    $discord->on(Event::MESSAGE_REACTION_ADD, function (MessageReaction $reaction, Discord $discord) {
        echo "ok";
        //var_dump($reaction);
        var_dump($reaction->message);
        var_dump($reaction->message->channel);
        $builder = MessageBuilder::new()->setContent('Alguém reagiu a uma mensagem!');
        $reaction->channel->sendMessage($builder);
    });
});

$discord->run();

// Documentation: https://discord-php.github.io/DiscordPHP/
// List of events: https://github.com/discord-php/DiscordPHP/blob/master/src/Discord/WebSockets/Event.php#L30-L75
// Event names: https://discord-php.github.io/DiscordPHP/reference/classes/Discord-WebSockets-Event.html
// Message class: https://discord-php.github.io/DiscordPHP/reference/classes/Discord-Parts-Channel-Message.html#property_channel_id

function replyWithMyImage(Message $message, Discord $discord)
{
    $builder = MessageBuilder::new();
    $builder->setContent('Sou eu!');
    $builder->addFile('./emote.png', 'ola.png');
    // what does this means??
    // $discord->on(Event::MESSAGE_CREATE, function (Message $message) use ($builder) {
    //     $builder->setReplyTo($message);
    // });
    $message->channel->sendMessage($builder);
    // How to get a channel from its id?
    $channel_id = $message->channel_id; //string 998764665364566038
}

function replyVaVoce(Message $message)
{
    $builder = MessageBuilder::new();
    $builder->setContent('Vá você');
    $message->channel->sendMessage($builder);
}
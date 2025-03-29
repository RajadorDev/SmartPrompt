# Smart Prompt Lib
SmartPrompt is a **PocketMine-MP 2.0.0** lib that makes text requests to players more autonomously

## Installation
You can install the `phar` [here](https://github.com/RajadorDev/SmartPrompt/releases)

It is recommended that you add it as a dependency of your plugin:

in `plugin.yml`:

```yml
depend: SmartPrompt
```

## Usage

### Creating a text request
```php
use pocketmine\Player;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptRules;
use SmartPrompt\prompt\response\AnyPromptResponse;
use SmartPrompt\PromptAPI;

/**
 * Create a simple prompt
 *
 * @param Player $player The player that needs to send the text response
 * @param string $message The message sended when the request is created
 * @param PromptRules $rules @see \SmartPrompt\prompt\PromptRules
 * @param PromptResponse[] $responses An array of accepted response with a callback called when the response is sended (if the response is accepted from the PromptResponse object)
 * @param boolean $executeOnlyOneResponse If true will execute only the first PromptResponse callback that accept the response 
 * @return Prompt Returns the promt created
 */
PromptAPI::prompt(
    $player, 
    'Send the player name for i kick him', 
    PromptRules::default(),
    [
        AnyPromptResponse::create(
            /**
             * @param Player $sender The player that send the request response
             * @param $text Sender response
             * @param Prompt $prompt The prompt created instance
             */
            function (Player $sender, string $text, Prompt $prompt) {
                /** Here you can destroy the prompt (if this method is not called, the prompt still getting the player chat message as response) */
                $prompt->destroy();
                if ($target = \pocketmine\Server::getInstance()->getPlayerExact($text))
                {
                    $target->close('', '');
                    $sender->sendMessage("Player $text was kicked suceffully");
                } else {
                    $sender->sendMessage("Theres no player with name $text online now!");
                }
            }
        )
    ],
    true
);
```

### Setting a timeout

You can set a timeout using the method `Prompt::setTimeout` example:

```php
use pocketmine\Player;
use SmartPrompt\prompt\Prompt;

/** @var Prompt $prompt */

/**
 * @param int $seconds The timeout in seconds
 * @param callable $callback Called when the time ends
 */
$prompt->setTimeout(
    10, 
    static function (Player $player) {
        $player->sendMessage('You can\'t to respond now, timout reached!');
    }
);
```

### Confirmation prompt

You can create a confirmation prompt in a very easy way using [SmartPrompt\PromptAPI](https://github.com/RajadorDev/SmartPrompt/tree/main/src/SmartPrompt/PromptAPI.php) example:

```php
use pocketmine\Player;
use pocketmine\item\Item;
use SmartPrompt\PromptAPI;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptRules;
use SmartPrompt\prompt\response\AnyPromptResponse;
use SmartPrompt\prompt\response\WordPromptResponse;

/** @var Player $player */

PromptAPI::confirmationPrompt(
    $player,
    'Say yes to get an apple or not to cancel',
    WordPromptResponse::create(
        ['yes', 'yeah'],
        static function (Player $player, string $response, Prompt $prompt) {
            $prompt->destroy();
            $player->getInventory()->addItem(Item::get(Item::APPLE));
        },
        false, // Optional Regular, if true will check in case sensitive
        true // If true, will check only the first word sended
    ),
    WordPromptResponse::create(
        ['no', 'not'],
        static function (Player $player, string $response, Prompt $prompt) {
            $prompt->destroy();
            $player->sendMessage('Cancelled');
        }
    ),
    AnyPromptResponse::create(
        static function (Player $player, string $response, Prompt $prompt) {
            $player->sendMessage("$response is invalid response! Say just yes or not.");
        }
    ),
    PromptRules::default() // optional, if not passed th rules is default too
);
```

### Prompt object

You can create your own prompt object implementing [SmartPrompt\prompt\Prompt](https://github.com/RajadorDev/SmartPrompt/tree/main/src/SmartPrompt/prompt/Prompt.php) interface, then you can register the request using:

```php
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptManager;

/** @var Prompt $myPrompt Your custom prompt object */
PromptManager::getInstance()->addPrompt($myPrompt);
```

You can create your own PromptResponse handler extending to [SmartPrompt\prompt\response\PromptResponse](https://github.com/RajadorDev/SmartPrompt/tree/main/src/SmartPrompt/prompt/response/PromptResponse.php)


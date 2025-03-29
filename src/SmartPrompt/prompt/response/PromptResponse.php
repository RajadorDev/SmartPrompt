<?php

declare (strict_types=1);

/***
 *   
 * Rajador Developer
 * 
 * ▒█▀▀█ ░█▀▀█ ░░░▒█ ░█▀▀█ ▒█▀▀▄ ▒█▀▀▀█ ▒█▀▀█ 
 * ▒█▄▄▀ ▒█▄▄█ ░▄░▒█ ▒█▄▄█ ▒█░▒█ ▒█░░▒█ ▒█▄▄▀ 
 * ▒█░▒█ ▒█░▒█ ▒█▄▄█ ▒█░▒█ ▒█▄▄▀ ▒█▄▄▄█ ▒█░▒█
 * 
 * GitHub: https://github.com/RajadorDev
 * 
 * Discord: rajadortv
 * 
 * 
**/

namespace SmartPrompt\prompt\response;

use pocketmine\Player;
use SmartPrompt\prompt\Prompt;

abstract class PromptResponse 
{

    /** @var callable */
    protected $whenRespond;

    /**
     * @param callable $whenRespond (Player, string, Prompt) : void
     */
    public function __construct(callable $whenRespond)
    {
        $this->whenRespond = $whenRespond;
    }

    /**
     * Returns if the message is the response expected
     *
     * @param string $text
     * @return boolean
     */
    abstract public function isResponse(string $text) : bool;

    public function parse(Player $player, string $text, Prompt $prompt) : bool 
    {
        if ($this->isResponse($text))
        {
            ($this->whenRespond)($player, $text, $prompt);
            return true;
        }
        return false;
    }
    
}
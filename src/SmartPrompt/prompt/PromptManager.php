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

namespace SmartPrompt\prompt;

use pocketmine\Player;
use pocketmine\plugin\Plugin;
use RuntimeException;
use SmartPrompt\listener\PromptListener;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\utils\SingletonTrait;

final class PromptManager
{

    use SingletonTrait;

    /** @var array<string,Prompt> */
    private $prompts = [];

    public static function init(Plugin $plugin) : PromptManager
    {
        if (!self::$instance)
        {
            return new PromptManager($plugin);
        } else {
            throw new RuntimeException('Manager is already created');
        }
    }

    public function __construct(Plugin $plugin)
    {
        PromptListener::register($plugin);
        self::setInstance($this);
    }

    public function addPrompt(Prompt $prompt) 
    {
        $this->prompts[strtolower($prompt->getPlayer()->getName())] = $prompt;
    }

    public function getPlayerPrompt(Player $player)
    {
        return $this->prompts[strtolower($player->getName())] ?? null;
    }

    public function removePrompt(Prompt $prompt) 
    {
        if (in_array($prompt, $this->prompts))
        {
            unset($this->prompts[strtolower($prompt->getPlayer()->getName())]);
        }
    }

    public function destroyAll() 
    {
        foreach ($this->prompts as $prompt)
        {
            $prompt->destroy();
        }
    }

    public function isActive(Prompt $prompt) : bool 
    {
        return in_array($prompt, $this->prompts);
    }
    
}
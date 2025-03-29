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

namespace SmartPrompt;

use pocketmine\Player;
use pocketmine\plugin\Plugin as PocketMinePlugin;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptRules;
use SmartPrompt\prompt\PromptManager;
use SmartPrompt\prompt\defaults\BasePrompt;
use SmartPrompt\prompt\response\AnyPromptResponse;
use SmartPrompt\prompt\response\PromptResponse;
use SmartPrompt\prompt\response\WordPromptResponse;

final class PromptAPI
{

    /** @var PocketMinePlugin */
    private static $plugin;

    /**
     * This static method only can be called one time
     *
     * @param PocketMinePlugin $plugin
     * @return void
     */
    public static function init(Plugin $plugin)
    {
        new PromptManager($plugin);
        self::$plugin = $plugin;
    }

    /**
     * Get the plugin that registered this library
     *
     * @return PocketMinePlugin
     */
    public static function getPlugin() : PocketMinePlugin
    {
        return self::$plugin;
    }

    /**
     * Create a simple prompt
     *
     * @param Player $player
     * @param string $message
     * @param PromptRules $rules
     * @param PromptResponse[] $responses
     * @param boolean $executeOnlyOneResponse
     * @return Prompt
     */
    public static function prompt(Player $player, string $message, PromptRules $rules, array $responses, bool $executeOnlyOneResponse = true) : Prompt
    {
        $prompt = new BasePrompt(
            $player,
            $message,
            $rules,
            $responses,
            $executeOnlyOneResponse
        );
        PromptManager::getInstance()->addPrompt($prompt);
        return $prompt;
    }

    /**
     * Create a confirmation prompt (like yes or not)
     *
     * @param Player $player
     * @param string $message
     * @param PromptResponse $acceptResponse
     * @param PromptResponse $rejectResponse
     * @param AnyPromptResponse $onInvalid
     * @param PromptRules|null $rules
     * @return Prompt
     */
    public static function confirmationPrompt(Player $player, string $message, PromptResponse $acceptResponse, PromptResponse $rejectResponse, PromptResponse $onInvalid, PromptRules $rules = null) : Prompt
    {
        return self::prompt(
            $player,
            $message,
            $rules ?? PromptRules::default(),
            [
                $acceptResponse,
                $rejectResponse,
                $onInvalid
            ],
            true
        );
    }

    public static function textPrompt(Player $player, string $message, AnyPromptResponse $callback, WordPromptResponse $cancelPrompt = null, PromptRules $rules = null)
    {
        $responses = [
            $callback
        ];
        $responses[] = $cancelPrompt;
        return self::prompt(
            $player,
            $message,
            $rules ?? PromptRules::default(),
            $responses,
            true
        );
    }

}
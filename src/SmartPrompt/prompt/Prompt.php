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

interface Prompt 
{

    /**
     * Player who needs to answer the prompt
     *
     * @return Player
     */
    public function getPlayer() : Player;

    /**
     * The prompt rules
     *
     * @return PromptRules
     */
    public function getRules() : PromptRules;

    /**
     * Called when the player send a message with the prompt open
     *
     * @param string $text
     * @return boolean true when the PlayerChatEvent needs to be cancelled
     */
    public function onRespond(string $text) : bool;

    /**
     * Called for close the prompt
     *
     * @return void
     */
    public function destroy();

    /**
     * Check if the prompt is active
     *
     * @return boolean
     */
    public function isActive() : bool;

    /**
     * Set timeout in seconds and the callback called when the time finish
     * 
     * @param int $seconds
     * @param callable $callback (Player) : void 
     */
    public function setTimeout(int $seconds, callable $callback) : Prompt;

    /**
     * Called when the timeout is finished
     * 
     * @return void
     */
    public function onTimeout();

}
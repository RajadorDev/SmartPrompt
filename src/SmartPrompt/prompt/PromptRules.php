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

class PromptRules 
{

    /** @var bool */
    public $showResponseWhenExecuted, $showMessageWhenNotExecuted, $canMove, $canExecuteCommands, $canPlaceBlocks, $canBreakBlocks, $canInteract, $canUseItems, $canAttack, $canBeAttacked;

    public function __construct(
        bool $showResponseWhenExecuted,
        bool $showMessageWhenNotExecuted,
        bool $canMove,
        bool $canExecuteCommands,
        bool $canPlaceBlocks,
        bool $canBreakBlocks,
        bool $canInteract,
        bool $canUseItems,
        bool $canAttack,
        bool $canBeAttacked
    )
    {
        $this->showResponseWhenExecuted = $showResponseWhenExecuted;
        $this->showMessageWhenNotExecuted = $showMessageWhenNotExecuted;
        $this->canMove = $canMove;
        $this->canExecuteCommands = $canExecuteCommands;
        $this->canPlaceBlocks = $canPlaceBlocks;
        $this->canBreakBlocks = $canBreakBlocks;
        $this->canInteract = $canInteract;
        $this->canUseItems = $canUseItems;
        $this->canAttack = $canAttack;
        $this->canBeAttacked = $canBeAttacked;
    }

    public static function default() : PromptRules
    {
        return new PromptRules(
            false,
            false,
            true,
            true,
            true,
            true,
            true,
            true,
            true,
            true
        );
    }

}
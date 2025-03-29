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

namespace SmartPrompt\prompt\defaults;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptRules;
use SmartPrompt\prompt\response\PromptResponse;
use SmartPrompt\prompt\PromptManager;
use SmartPrompt\PromptAPI;
use SmartPrompt\utils\PromptUtils;
use Throwable;

class BasePrompt implements Prompt
{

    /** @var Player */
    protected $player;

    /** @var PromptRules */
    protected $rules;

    /** @var PromptResponse[] */
    protected $responses;

    /** @var bool */
    protected $executeOnlyOneResponse;

    /** @var callable|null */
    protected $whenTimeout = null;

    /**
     * A base prompt class
     *
     * @param Player $player
     * @param string $message Message sended when the prompt is created
     * @param PromptRules $rules
     * @param array $responses
     * @param boolean $executeOnlyOneResponse
     */
    public function __construct(Player $player, string $message, PromptRules $rules, array $responses, bool $executeOnlyOneResponse = true)
    {
        $this->player = $player;
        $this->rules = $rules;
        $this->responses = $responses;
        $this->executeOnlyOneResponse = $executeOnlyOneResponse;
        if (!$rules->canMove)
        {
            PromptUtils::immobilize($player, true);
        }
        $player->sendMessage($message);
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getRules(): PromptRules
    {
        return $this->rules;
    }

    public function onRespond(string $text): bool
    {
        $player = $this->getPlayer();
        $sucess = false;
        foreach ($this->responses as $responseId => $response)
        {
            try {
                if ($response->parse($player, $text, $this))
                {
                    $sucess = true;
                    if ($this->executeOnlyOneResponse)
                    {
                        break;
                    }
                }
            } catch (Throwable $error) {
                PromptAPI::getPlugin()->getLogger()->error((string) 'Response ' . $responseId . ' error: ' . $error);
            }
        }
        if ($sucess)
        {
            return !$this->getRules()->showResponseWhenExecuted;
        }
        return !$this->getRules()->showMessageWhenNotExecuted;
    }

    public function destroy()
    {
        if (!$this->rules->canMove)
        {
            PromptUtils::immobilize($this->getPlayer(), false);
        }
        PromptManager::getInstance()->removePrompt($this);
    }

    public function isActive() : bool 
    {
        return PromptManager::getInstance()->isActive($this);
    }

    public function onTimeout() 
    {
        ($this->whenTimeout)($this->getPlayer());
        $this->destroy();
    }

    public function setTimeout(int $seconds, callable $callback): Prompt
    {
        $this->whenTimeout = $callback;
        Server::getInstance()->getScheduler()->scheduleDelayedTask(
            new class($this) extends Task 
            {
                /** @var Prompt */
                private $prompt;

                public function __construct(Prompt $prompt)
                {
                    $this->prompt = $prompt;
                }

                public function onRun($currentTick)
                {
                    if ($this->prompt->isActive())
                    {
                        $this->prompt->onTimeout();
                    }
                }
            },
            20 * $seconds
        );
        return $this;
    }

}
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

namespace SmartPrompt\listener;

use Exception;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\Plugin;
use pocketmine\event\Listener;
use SmartPrompt\prompt\Prompt;
use SmartPrompt\prompt\PromptManager;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerQuitEvent;

final class PromptListener implements Listener
{

    /** @var Plugin|null */
    private static $registeredBy = null;

    public static function register(Plugin $plugin) 
    {
        if (is_null(self::$registeredBy))
        {
            Server::getInstance()->getPluginManager()->registerEvents(
                new PromptListener,
                $plugin
            );
        } else {
            throw new Exception('Listener already registered by ' . $plugin->getName());
        }
    }

    /**
     * @priority LOW
     * @ignoreCancelled true
     */
    public function parsePrompt(PlayerChatEvent $event)
    {
        if ($prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer()))
        {
            if ($prompt->onRespond($event->getMessage()))
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function moveOnPrompt(PlayerMoveEvent $event)
    {
        if ($event->getTo()->distance($event->getFrom()) > 0.1)
        {
            $prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer());
            if ($prompt instanceof Prompt && !$prompt->getRules()->canMove)
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     */
    public function executeCommands(PlayerCommandPreprocessEvent $event)
    {
        $message = $event->getMessage();
        if (trim($message) != '' && $message[0] == '/')
        {
            $prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer());
            if ($prompt instanceof Prompt && !$prompt->getRules()->canExecuteCommands)
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function place(BlockPlaceEvent $event)
    {
        if ($prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer()))
        {
            if (!$prompt->getRules()->canPlaceBlocks)
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function break(BlockBreakEvent $event) 
    {
        if ($prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer()))
        {
            if (!$prompt->getRules()->canBreakBlocks)
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function interact(PlayerInteractEvent $event)
    {
        if ($prompt = PromptManager::getInstance()->getPlayerPrompt($event->getPlayer()))
        {
            if (!$prompt->getRules()->canInteract)
            {
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @priority LOWEST
     * @ignoreCancelled true
     */
    public function attack(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if ($player instanceof Player)
        {
            if ($prompt = PromptManager::getInstance()->getPlayerPrompt($player))
            {
                if (!$prompt->getRules()->canBeAttacked)
                {
                    $event->setCancelled(true);
                }
            }
        }
        if (!$event->isCancelled() && $event instanceof EntityDamageByEntityEvent)
        {
            $killer = $event->getDamager();
            if ($killer instanceof Player)
            {
                if ($prompt = PromptManager::getInstance()->getPlayerPrompt($killer))
                {
                    if (!$prompt->getRules()->canAttack)
                    {
                        $event->setCancelled(true);
                    }
                }
            }
        }
    }

    /**
     * @priority MONITOR
     */
    public function quit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        if ($prompt = PromptManager::getInstance()->getPlayerPrompt($player))
        {
            $prompt->destroy();
        }
    }

}
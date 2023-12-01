<?php

declare(strict_types=1);

namespace Terpz710\AutoInvPE\Command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use Terpz710\AutoInvPE\Main;

class AutoInvCommand extends Command {

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin) {
        parent::__construct("autoinv", "Toggle auto inventory for the current world", "/autoinv <enable|disable>");
        $this->plugin = $plugin;
        $this->setPermission("autoinvpe.autoinv");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if ($sender instanceof Player) {
            if (count($args) === 1) {
                $action = strtolower($args[0]);
                if ($action === "enable" || $action === "disable") {
                    $this->handleEnableDisableCommand($sender, $action);
                    return true;
                }
            }

            $sender->sendMessage("Usage: §e/autoinv <enable|disable>");
            return false;
        }

        return false;
    }

    private function handleEnableDisableCommand(Player $player, string $action): void {
        $worldName = $player->getWorld()->getFolderName();
        $autoInventoryWorlds = $this->plugin->getAutoInventoryWorlds();

        if ($action === "enable") {
            if (!isset($autoInventoryWorlds[$worldName]) || !$autoInventoryWorlds[$worldName]) {
                $autoInventoryWorlds[$worldName] = true;
                $player->sendMessage("§l§f(§b!§f)§r §eAuto inventory§f for this world has been §benabled§f! You can §cdisable§f it using §e/autoinv disable§f!");
            } else {
                $player->sendMessage("§l§f(§e!§f) §r§fAuto inventory is already §aenabled§f for this world!");
            }
        } elseif ($action === "disable") {
            if (isset($autoInventoryWorlds[$worldName]) && $autoInventoryWorlds[$worldName]) {
                $autoInventoryWorlds[$worldName] = false;
                $player->sendMessage("§l§f(§e!§f)§r§e Auto inventory §ffor this world has been §cdisabled§f! You can §benable §fit using §e/autoinv enable§f!");
            } else {
                $player->sendMessage("§l§f(§e!§f) §r§fAuto inventory is already §cdisabled§f for this world!");
            }
        }

        $this->plugin->setAutoInventoryWorlds($autoInventoryWorlds);
        $this->plugin->saveAutoInventoryWorlds();
    }
}
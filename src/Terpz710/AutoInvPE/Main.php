<?php

declare(strict_types=1);

namespace Terpz710\AutoInvPE;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Terpz710\AutoInvPE\Command\AutoInvCommand;
use Terpz710\AutoInvPE\EventListener;

class Main extends PluginBase {

    /** @var array */
    private $autoInventoryWorlds = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->loadAutoInventoryWorlds();
        $this->getServer()->getCommandMap()->register("autoinv", new AutoInvCommand($this));
    }

    public function onDisable(): void {
        $this->saveAutoInventoryWorlds();
    }

    public function getAutoInventoryWorlds(): array {
        return $this->autoInventoryWorlds;
    }

    public function setAutoInventoryWorlds(array $autoInventoryWorlds): void {
        $this->autoInventoryWorlds = $autoInventoryWorlds;
    }

    public function loadAutoInventoryWorlds() {
        $config = new Config($this->getDataFolder() . "auto_inventory_worlds.json", Config::JSON);

        $this->autoInventoryWorlds = $config->get("auto_inventory_worlds", []);
    }

    public function saveAutoInventoryWorlds() {
        $config = new Config($this->getDataFolder() . "auto_inventory_worlds.json", Config::JSON);

        $config->set("auto_inventory_worlds", $this->autoInventoryWorlds);
        $config->save();
    }
}
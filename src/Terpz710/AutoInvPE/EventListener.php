<?php

declare(strict_types=1);

namespace Terpz710\AutoInvPE;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\player\Player;
use pocketmine\item\Item;

class EventListener implements Listener {

    /** @var Main */
    private $plugin;

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBlockBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $worldName = $player->getWorld()->getFolderName();

        $autoInventoryWorlds = $this->plugin->getAutoInventoryWorlds();

        if (isset($autoInventoryWorlds[$worldName]) && $autoInventoryWorlds[$worldName]) {
            $this->autoAddToInventory($player, $event->getDrops());

            $event->setDrops([]);
        }
    }

    private function autoAddToInventory(Player $player, array $items): void {
        foreach ($items as $item) {
            if ($item instanceof Item) {
                $player->getInventory()->addItem($item);
            }
        }
    }
}
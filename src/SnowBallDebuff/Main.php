<?php

namespace SnowBallDebuff;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\ItemTypeIds;
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("SnowballDebuff Plugin Enabled!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Este evento se activa cuando un jugador lanza una bola de nieve.
     */
    public function onPlayerThrowSnowball(EntityDamageByEntityEvent $event): void {
        if ($event->getDamager() instanceof Player && 
            $event->getEntity() instanceof Player) {
            
            $item = $event->getDamager()->getInventory()->getItemInHand();
            if ($item->getTypeId() === ItemTypeIds::SNOWBALL) { 
                $targetPlayer = $event->getEntity();

                $effect = new EffectInstance(VanillaEffects::SLOWNESS(), 100, 1);
                $targetPlayer->getEffects()->add($effect);
                $targetPlayer->sendMessage("¡Has sido golpeado por una bola de nieve y ahora estás lento!");
                $event->getDamager()->sendMessage("¡Has golpeado a " . $targetPlayer->getName() . " con una bola de nieve!");
            }
        }
    }

    /**
     * /snowball
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "snowball") {
 
            if ($sender instanceof Player) {
                $snowball = VanillaItems::SNOWBALL();
               
                $sender->getInventory()->addItem($snowball);
                
                $sender->sendMessage("¡Has recibido una bola de nieve!");
                return true; // Comando ejecutado con éxito
            } else {
                
                $sender->sendMessage("Este comando solo se puede usar en el juego.");
                return false;
            }
        }
        return false; // Comando no reconocido
    }
}

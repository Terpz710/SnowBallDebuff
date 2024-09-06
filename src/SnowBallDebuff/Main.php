<?php

namespace SnowBallDebuff;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory; // Asegúrate de importar ItemFactory
use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Main extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("SnowballDebuff Plugin Enabled!");
        // Registramos el evento
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Este evento se activa cuando un jugador lanza una bola de nieve.
     */
    public function onPlayerThrowSnowball(EntityDamageByEntityEvent $event): void {
        // Verificamos si el evento es un jugador golpeando a otro
        if ($event->getDamager() instanceof Player && 
            $event->getEntity() instanceof Player) {
            
            // Verificamos si el jugador lanzó una bola de nieve
            $item = $event->getDamager()->getInventory()->getItemInHand();
            if ($item->getTypeId() === Item::SNOWBALL) { // Usa Item::SNOWBALL
                // Obtener el jugador golpeado
                $targetPlayer = $event->getEntity();

                // Aplicar el efecto de lentitud
                $effect = new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 100, 1); // 100 ticks, nivel 1
                $targetPlayer->getEffects()->add($effect); // Añadir el efecto al jugador

                // Enviar un mensaje al jugador golpeado
                $targetPlayer->sendMessage("¡Has sido golpeado por una bola de nieve y ahora estás lento!");

                // También enviar un mensaje al jugador que lanzó la bola
                $event->getDamager()->sendMessage("¡Has golpeado a " . $targetPlayer->getName() . " con una bola de nieve!");
            }
        }
    }

    /**
     * Este método se llama cuando se ejecuta el comando /snowball.
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "snowball") {
            // Verificamos que el jugador sea un jugador (no un comando de consola)
            if ($sender instanceof Player) {
                // Crear una bola de nieve
                $snowball = ItemFactory::get(Item::SNOWBALL, 0, 1); // Obtener la bola de nieve usando ItemFactory
                
                // Agregar la bola de nieve al inventario del jugador
                $sender->getInventory()->addItem($snowball);
                
                // Enviar un mensaje al jugador
                $sender->sendMessage("¡Has recibido una bola de nieve!");
                return true; // Comando ejecutado con éxito
            } else {
                // Mensaje de error si se ejecuta desde la consola
                $sender->sendMessage("Este comando solo se puede usar en el juego.");
                return false; // Comando no ejecutado con éxito
            }
        }
        return false; // Comando no reconocido
    }
}

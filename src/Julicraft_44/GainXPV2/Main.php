<?php
//update with config coming soon
declare(strict_types=1);

namespace Julicraft_44\GainXPV2;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use pocketmine\Player;

class Main extends PluginBase {
	
	private static $config;

	public function onEnable(): void {
		$this->getLogger()->info(TextFormat::AQUA . "You can now execute /xp");

        $this->saveDefaultConfig();
		self::$config = $this->getConfig()->getAll();	
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args): bool {
		
		$target = $this->getServer()->getPlayer($args[1]);
		
		switch($cmd->getName()) {
			case "xp":
			if($sender instanceof Player) {
			if($sender->hasPermission("cmd.xp")) {
			
			if(!isset($args[0])) {								//add:remove
				$sender->sendMessage("Usage: /xp <add:remove> <Player> <Amount>");
				return false;
			}
			if(!isset($args[1])) {								//Player
				$sender->sendMessage("Usage: /xp <add:remove> <Player> <Amount>");
				return false;
			}
			if(!isset($args[2])) {								//Amount
				$sender->sendMessage("Usage: /xp <add:remove> <Player> <Amount>");
				return false;
			}
			
			
			if($target === null || !$target->isOnline()) {
				$sender->sendMessage("Player not found or isn't online");
				return false;
			}

			if($args[0]) {
				switch(strtolower($args[0])) {
					case "add":
						
						$amount = $args[2];
						$target->addXpLevels((int)$amount, true);
						$sender->sendMessage(str_replace(["{amount}", "{target}"], [$amount, $target->getName()], $sender->sendMessage("You added " .  $amount . "xp to " . $target->getName())));
						$target->sendMessage(str_replace(["{amount}", "{sender}"], [$amount, $sender->getName()], $target->sendMessage("You gain " . $amount . "xp from " . $sender->getName())));
						break;
					case "remove":
						
						$current = $target->getXpLevel();
						$amount = $args[2];
						
						if($current >= $amount) {
						
						$amount = $args[2];
						$target->subtractXpLevels((int)$amount, true);
						$sender->sendMessage(str_replace(["{amount}", "{target}"], [$amount, $target->getName()], $sender->sendMessage("You removed " . $amount . "xp from " .  $target->getName())));
						$target->sendMessage(str_replace(["{amount}", "{sender}"], [$amount, $sender->getName()], $target->sendMessage("You lose ". $amount . "xp from " . $sender->getName())));
						break;
						} else {
							$sender->sendMessage("The amount is too high. Player " . $target->getName() . " has an amount of " . $current . "xp");
						}
				}
			}
			} else {
				$sender->sendMessage("You do not have the permission to run this command");
			}
			} else {
				$sender->sendMessage("Please run this command In-Game");
			}
		}
		return true;

	}
}

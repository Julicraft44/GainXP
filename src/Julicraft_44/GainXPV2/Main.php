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
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
	
	public $config;

	public function onEnable() {
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		$this->getLogger()->info("Delete this befor release");
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args): bool {		
		switch($cmd->getName()) {
			case "xp":
			if($sender instanceof Player) {
			if($sender->hasPermission("cmd.xp")) {
			
			if(!isset($args[0])) {								//add:remove
				$sender->sendMessage($this->getConfig()->get("command-usage"));
				return false;
			}
			if(!isset($args[1])) {								//Player
				$sender->sendMessage($this->getConfig()->get("command-usage"));
				return false;
			}
			if(!isset($args[2])) {								//Amount
				$sender->sendMessage($this->getConfig()->get("command-usage"));
				return false;
			}
			
			$target = $this->getServer()->getPlayer($args[1]);
			if($target === null || !$target->isOnline()) {
				$sender->sendMessage($this->getConfig()->get("not-found"));
				return false;
			}

			if($args[0]) {
				switch(strtolower($args[0])) {
					case "add":
						$target = $this->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						$target->addXpLevels((int)$amount, true);
						$sender->sendMessage(str_replace(["{amount}", "{target}"], [$amount, $target->getName()], $sender->sendMessage($this->getConfig()->get("succsess-sender-add"))));
						$target->sendMessage(str_replace(["{amount}", "{sender}"], [$amount, $sender->getName()], $target->sendMessage($this->getConfig()->get("succsess-target-add"))));
						break;
					case "remove":
					
						$target = $this->getServer()->getPlayer($args[1]);
						$current = $target->getXpLevel();
						$amount = $args[2];
						
						if($current >= $amount) {
						$target = $this->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						$target->subtractXpLevels((int)$amount, true);
						$sender->sendMessage(str_replace(["{amount}", "{target}"], [$amount, $target->getName()], $sender->sendMessage($this->getConfig()->get("succsess-sender-rem"))));
						$target->sendMessage(str_replace(["{amount}", "{sender}"], [$amount, $sender->getName()], $target->sendMessage($this->getConfig()->get("succsess-target-rem"))));
						break;
						} else {
							$sender->sendMessage(str_replace(["{target}", "{current}", "{amount}"], [$target->getName(), $current, $amount], $sender->sendMessage($this->getConfig()->get("to-high-amount"))));
						}
				}
			}
			} else {
				$sender->sendMessage($this->getConfig()->get("no-perm"));
			}
			} else {
				$sender->sendMessage("Please run this command In-Game");
			}
		}
		return true;

	}
}
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
					
						$msg = $this->getConfig()->get("succsess-sender-add");
						$msg = str_replace("%amount", "$amount", $msg);
						$sender->sendMessage($msg);
						
						$msg = $this->getConfig()->get("succsess-target-add");
						$msg = str_replace("%amount", "$amount", $msg);
						$target->sendMessage($msg);
						break;
					case "remove":
					
						$target = $this->getServer()->getPlayer($args[1]);
						$current = $target->getXpLevel();
						$amount = $args[2];
						
						if($current >= $amount) {
						$target = $this->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						
						$target->subtractXpLevels((int)$amount, true);
						
						$msg = $this->getConfig()->get("succsess-sender-rem");
						$msg = str_replace("%amount", "$amount", $msg);
						$sender->sendMessage($msg);
						
						$msg = $this->getConfig()->get("succsess-target-rem");
						$msg = str_replace("%amount", "$amount", $msg);
						$target->sendMessage($msg);
						break;
						} else {
							$msg = $this->getConfig()->get("to-high-amount");
							$msg = str_replace("%amount", "$amount", $msg);
							$msg = str_replace("%current", "$current", $msg);
							$sender->sendMessage($msg);
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
<?php

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
		
		if($this->getConfig()->get("version") !== 1.2) {
			$this->getLogger()->critical("The version of the config is outdated. Download the newest version of the plugin or reload the server to check if the plugin fix it's self");
			rename("plugin_data/GainXPV2/config.yml", "plugin_data/GainXPV2/config.yml.old");
		}
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $lable, array $args): bool {		
		switch($cmd->getName()) {
			case "xp":
			if($sender instanceof Player) {
			if($sender->hasPermission("gainxp.use")) {
			
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
						$current = $target->getXpLevel();
						$amount = $args[2];
						$tar_name = $target->getName();
						$send_name = $sender->getName();
						
						if($amount + $current <= 24791) {
						$target = $this->getServer()->getPlayer($args[1]);
						$current = $target->getXpLevel();
						$amount = $args[2];
						
						$target->addXpLevels((int)$amount, true);
					
						$msg = $this->getConfig()->get("success-sender-add");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$sender->sendMessage($msg);
						
						$msg = $this->getConfig()->get("success-target-add");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$target->sendMessage($msg);
						} else {
							$msg = $this->getConfig()->get("to-high-end");
							$msg = str_replace("%amount", "$amount", $msg);
							$msg = str_replace("%current", "$current", $msg);
							$sender->sendMessage($msg);
						}
						break;

					case "remove":

						$target = $this->getServer()->getPlayer($args[1]);
						$current = $target->getXpLevel();
						$amount = $args[2];
						$tar_name = $target->getName();
						$send_name = $sender->getName();
						
						if($current >= $amount) {
						$target = $this->getServer()->getPlayer($args[1]);
						$amount = $args[2];
						
						$target->subtractXpLevels((int)$amount, true);
						
						$msg = $this->getConfig()->get("success-sender-rem");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$sender->sendMessage($msg);
						
						$msg = $this->getConfig()->get("success-target-rem");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$target->sendMessage($msg);
						
						} else {
							$msg = $this->getConfig()->get("to-high-amount");
							$msg = str_replace("%amount", "$amount", $msg);
							$msg = str_replace("%current", "$current", $msg);
							$sender->sendMessage($msg);
						}
						break;
						
					case "drop":
					
						$target = $this->getServer()->getPlayer($args[1]);
						$current = $target->getXpLevel();
						$amount = $args[2];
						$tar_name = $target->getName();
						$send_name = $sender->getName();
						
						if($current >= $amount) {
						if($amount + $current <= 24791) {
						$target = $this->getServer()->getPlayer($args[1]);
						$amount = $args[2];
							
						$target->addXpLevels((int)$amount, true);
						$sender->subtractXpLevels((int)$amount, true);
						
						$msg = $this->getConfig()->get("success-target-drop");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$target->sendMessage($msg);
						
						$msg = $this->getConfig()->get("success-sender-drop");
						$msg = str_replace("%amount", "$amount", $msg);
						$msg = str_replace("%send_name", "$send_name", $msg);
						$msg = str_replace("%tar_name", "$tar_name", $msg);
						$sender->sendMessage($msg);
						} else {
							$msg = $this->getConfig()->get("to-high-end");
							$msg = str_replace("%amount", "$amount", $msg);
							$msg = str_replace("%current", "$current", $msg);
							$sender->sendMessage($msg);
						}
						} else {
							$msg = $this->getConfig()->get("to-high-amount");
							$msg = str_replace("%amount", "$amount", $msg);
							$msg = str_replace("%current", "$current", $msg);
							$sender->sendMessage($msg);
						}
						break;
						
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
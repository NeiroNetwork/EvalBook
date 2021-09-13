<?php

declare(strict_types=1);

namespace NeiroNetwork\EvalBook;

use NeiroNetwork\EvalBook\permission\EvalBookPermissions;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use Webmozart\PathUtil\Path;

class Main extends PluginBase{

	private static self $instance;
	private Config $operators;

	public static function getInstance() : self{
		return self::$instance;
	}

	public function getOperators() : Config{
		return $this->operators;
	}

	protected function onEnable() : void{
		self::$instance = $this;

		EvalBookPermissions::registerCorePermissions();

		$this->operators = new Config(Path::join($this->getDataFolder(), "whitelist.txt"), Config::ENUM);

		/**
		 * not work
		 * @see https://github.com/pmmp/PocketMine-MP/issues/4456
		 */
		foreach($this->getServer()->getBroadcastChannelSubscribers(Server::BROADCAST_CHANNEL_USERS) as $subscriber){
			echo $subscriber->getName() . "\n";
			if($subscriber instanceof ConsoleCommandSender){
				$subscriber->setBasePermission(EvalBookPermissions::ROOT_OPERATOR, true);
				$subscriber->setBasePermission(EvalBookPermissions::ROOT_USER, true);
				var_dump($subscriber);
			}
		}

		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}
}
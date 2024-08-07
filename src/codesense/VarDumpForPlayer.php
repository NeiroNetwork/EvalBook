<?php

declare(strict_types=1);

namespace {
	use pocketmine\network\mcpe\convert\TypeConverter;
	use pocketmine\network\mcpe\protocol\NpcDialoguePacket;
	use pocketmine\network\mcpe\protocol\SetPlayerGameTypePacket;
	use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
	use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
	use pocketmine\player\Player;

	if(!function_exists("var_dump_p")){
		function var_dump_p(Player $player, mixed ...$value) : void{
			ob_start();
			var_dump(...$value);
			$output = ob_get_clean();

			$cleanOutput = "";
			foreach(mb_str_split($output) as $string){
				$cleanOutput .= $string === "\x00" || mb_ord($string) === false ? "?" : $string;
			}

			$player->sendData([$player], [EntityMetadataProperties::HAS_NPC_COMPONENT => new ByteMetadataProperty(1)]);
			$networkSession = $player->getNetworkSession();
			if($revertGameMode = $player->isCreative()){
				$networkSession->sendDataPacket(SetPlayerGameTypePacket::create(0));
			}
			$networkSession->sendDataPacket(NpcDialoguePacket::create(
				$player->getId(),
				NpcDialoguePacket::ACTION_OPEN,
				$cleanOutput,
				uniqid(),
				(substr_count($output, "\n") >= 1639 ? "§c出力が1639行を超えました：正常に表示されません！" : "var_dump() の出力結果"),
				""
			));
			if($revertGameMode){
				$id = TypeConverter::getInstance()->coreGameModeToProtocol($player->getGamemode());
				$networkSession->sendDataPacket(SetPlayerGameTypePacket::create($id));
			}
		}
	}
}

namespace NeiroNetwork\EvalBook\codesense{

	final readonly class VarDumpForPlayer{

		public function __construct(){
			// NOOP
		}
	}
}

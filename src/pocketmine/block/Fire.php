<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\entity\Entity;
use pocketmine\entity\projectile\Arrow;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Server;

class Fire extends Flowable{

	protected $id = self::FIRE;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function hasEntityCollision() : bool{
		return true;
	}

	public function getName() : string{
		return "Fire Block";
	}

	public function getLightLevel() : int{
		return 15;
	}

	public function isBreakable(Item $item) : bool{
		return false;
	}

	public function canBeReplaced() : bool{
		return true;
	}

	public function onEntityCollide(Entity $entity) : void{
		$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_FIRE, 1);
		$entity->attack($ev);

		$ev = new EntityCombustByBlockEvent($this, $entity, 8);
		if($entity instanceof Arrow){
			$ev->setCancelled();
		}
		Server::getInstance()->getPluginManager()->callEvent($ev);
		if(!$ev->isCancelled()){
			$entity->setOnFire($ev->getDuration());
		}
	}

	public function getDropsForCompatibleTool(Item $item) : array{
		return [];
	}

	public function onNearbyBlockChange() : void{
		$down = $this->getSide(Vector3::SIDE_DOWN);
		if($down->getId() !== self::AIR and !($down instanceof Liquid)){
			$replace = false;
			for($s = 1; $s <= 5; ++$s){
				$side = $this->getSide($s);
				if($side instanceof Liquid){
					$replace = true;
					break;
				}elseif($side->getId() !== self::AIR){
					$replace = true;
				}else{
					$replace = false;
				}
			}
			if(!$replace) return;
		}

		$this->getLevel()->setBlock($this, BlockFactory::get(Block::AIR), true);
	}

	public function ticksRandomly() : bool{
		return true;
	}

	public function onRandomTick() : void{
		if($this->getSide(Vector3::SIDE_DOWN)->getId() !== self::NETHERRACK){
			if(mt_rand(0, 2) === 0){
				if($this->meta === 0x0F){
					$this->level->setBlock($this, BlockFactory::get(Block::AIR));
				}else{
					$this->meta++;
					$this->level->setBlock($this, $this);
				}
			}
		}

		//TODO: fire spread
	}
}
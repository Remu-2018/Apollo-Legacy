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

namespace pocketmine\event\entity;

use pocketmine\entity\Entity;

class EntityCombustByEntityEvent extends EntityCombustEvent{

	/** @var Entity */
	protected $combuster;

	/**
	 * @param Entity $combuster
	 * @param Entity $combustee
	 * @param int    $duration
	 */
	public function __construct(Entity $combuster, Entity $combustee, int $duration){
		parent::__construct($combustee, $duration);
		$this->combuster = $combuster;
	}

	/**
	 * @return Entity
	 */
	public function getCombuster() : Entity{
		return $this->combuster;
	}

}
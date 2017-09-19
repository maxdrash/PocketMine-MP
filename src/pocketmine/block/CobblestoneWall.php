<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\block;

use pocketmine\item\Tool;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

class CobblestoneWall extends Transparent{
	const NONE_MOSSY_WALL = 0;
	const MOSSY_WALL = 1;

	protected $id = self::COBBLESTONE_WALL;

	public function __construct(int $meta = 0){
		$this->meta = $meta;
	}

	public function isSolid() : bool{
		return false;
	}

	public function getToolType() : int{
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() : float{
		return 2;
	}

	public function getName() : string{
		if($this->meta === 0x01){
			return "Mossy Cobblestone Wall";
		}

		return "Cobblestone Wall";
	}

	protected function recalculateBoundingBox(){

		$north = $this->canConnect($this->getSide(Vector3::SIDE_NORTH));
		$south = $this->canConnect($this->getSide(Vector3::SIDE_SOUTH));
		$west = $this->canConnect($this->getSide(Vector3::SIDE_WEST));
		$east = $this->canConnect($this->getSide(Vector3::SIDE_EAST));

		$n = $north ? 0 : 0.25;
		$s = $south ? 1 : 0.75;
		$w = $west ? 0 : 0.25;
		$e = $east ? 1 : 0.75;

		if($north and $south and !$west and !$east){
			$w = 0.3125;
			$e = 0.6875;
		}elseif(!$north and !$south and $west and $east){
			$n = 0.3125;
			$s = 0.6875;
		}

		return new AxisAlignedBB(
			(float)$this->x + $w,
			(float)$this->y + 0.0,
			(float)$this->z + $n,
			(float)$this->x + $e,
			(float)$this->y + 1.5,
			(float)$this->z + $s
		);
	}

	public function canConnect(Block $block){
		return ($block->getId() !== self::COBBLESTONE_WALL and $block->getId() !== self::FENCE_GATE) ? $block->isSolid() and !$block->isTransparent() : true;
	}

}

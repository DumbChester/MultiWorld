<?php

/**
 * MultiWorld - PocketMine plugin that manages worlds.
 * Copyright (C) 2018 - 2021  CzechPMDevs
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace czechpmdevs\multiworld\command\subcommand;

use czechpmdevs\multiworld\util\LanguageManager;
use czechpmdevs\multiworld\util\WorldUtils;
use pocketmine\command\CommandSender;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;
use function count;

class InfoSubcommand implements SubCommand {

    public function executeSub(CommandSender $sender, array $args, string $name): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§cThis command can be used only in-game!");
            return;
        }

        if (isset($args[0])) {
            if (!Server::getInstance()->isLevelGenerated($args[0])) {
                $sender->sendMessage(LanguageManager::getMsg($sender, "info.levelnotexists", [$args[0]]));
                return;
            }

            WorldUtils::lazyLoadLevel($args[0]);

            $sender->sendMessage($this->getInfoMessage($sender, WorldUtils::getLevelByNameNonNull($args[0])));
            return;
        }
        $sender->sendMessage($this->getInfoMessage($sender, $sender->getLevel()));
    }

    private function getInfoMessage(CommandSender $sender, Level $level): string {
        return LanguageManager::getMsg($sender, "info", [$level->getName()]) . "\n" .
            LanguageManager::getMsg($sender, "info-name", [$level->getName()]) . "\n" .
            LanguageManager::getMsg($sender, "info-folderName", [$level->getFolderName()]) . "\n" .
            LanguageManager::getMsg($sender, "info-players", [(string)count($level->getPlayers())]) . "\n" .
            LanguageManager::getMsg($sender, "info-generator", [$level->getProvider()->getGenerator()]) . "\n" .
            LanguageManager::getMsg($sender, "info-seed", [$level->getSeed()]) . "\n" .
            LanguageManager::getMsg($sender, "info-time", [(string)$level->getTime()]);
    }
}

<?php

declare(strict_types=0);

/* vim:set softtabstop=4 shiftwidth=4 expandtab: */
/**
 *
 * LICENSE: GNU Affero General Public License, version 3 (AGPL-3.0-or-later)
 * Copyright 2001 - 2020 Ampache.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

/**
 * This is the wrapper for opening music streams from this server.  This script
 * will play the local version or redirect to the remote server if that be
 * the case.  Also this will update local statistics for songs as well.
 * This is also where it decides if you need to be downsampled.
 */

use Ampache\Application\Playback\ChannelApplication;
use Psr\Container\ContainerInterface;

define('NO_SESSION', '1');

/** @var ContainerInterface $dic */
$dic = require __DIR__ . '/../../src/Config/init.php';

$dic->get(ChannelApplication::class)->run();

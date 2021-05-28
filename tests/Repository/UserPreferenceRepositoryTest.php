<?php
/*
 * vim:set softtabstop=4 shiftwidth=4 expandtab:
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

declare(strict_types=1);

namespace Ampache\Repository;

use Ampache\MockeryTestCase;
use Doctrine\DBAL\Connection;
use Mockery\MockInterface;

class UserPreferenceRepositoryTest extends MockeryTestCase
{
    private MockInterface $database;

    private UserPreferenceRepository $subject;

    public function setUp(): void
    {
        $this->database = $this->mock(Connection::class);

        $this->subject = new UserPreferenceRepository(
            $this->database
        );
    }

    public function testGetByUserAndPreferenceReturnsSettingsForDefaultUser(): void
    {
        $userId       = 666;
        $preferenceId = 42;
        $value        = 'some-value';

        $this->database->shouldReceive('fetchOne')
            ->with(
                'SELECT `value` FROM `user_preference` WHERE `preference`= ? AND `user` = ?',
                [$preferenceId, $userId]
            )
            ->andReturnFalse();
        $this->database->shouldReceive('fetchOne')
            ->with(
                'SELECT `value` FROM `user_preference` WHERE `preference` = ? AND `user`=\'-1\'',
                [$preferenceId]
            )
            ->andReturn($value);

        $this->assertSame(
            $value,
            $this->subject->getByUserAndPreference($userId, $preferenceId)
        );
    }

    public function testGetByUserAndPreferenceReturnsSettingsForGivenUser(): void
    {
        $userId       = 666;
        $preferenceId = 42;
        $value        = 'some-value';

        $this->database->shouldReceive('fetchOne')
            ->with(
                'SELECT `value` FROM `user_preference` WHERE `preference`= ? AND `user` = ?',
                [$preferenceId, $userId]
            )
            ->andReturn($value);

        $this->assertSame(
            $value,
            $this->subject->getByUserAndPreference($userId, $preferenceId)
        );
    }
}

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

namespace Ampache\Module\Playback\MediaUrlListGenerator;

use Ampache\Module\Api\Xml_Data;
use Ampache\Module\Playback\Stream_Playlist;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class XspfMediaUrlListGeneratorType extends AbstractMediaUrlListGeneratorType
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(
        StreamFactoryInterface $streamFactory
    ) {
        $this->streamFactory = $streamFactory;
    }

    public function generate(
        Stream_Playlist $playlist,
        ResponseInterface $response
    ): ResponseInterface {
        $result = '';
        foreach ($playlist->urls as $url) {
            $xml = [];

            $xml['track'] = [
                'title' => $url->title,
                'creator' => $url->author,
                'duration' => $url->time * 1000,
                'location' => $url->url,
                'identifier' => $url->url
            ];
            if ($url->type == 'video') {
                $xml['track']['meta'] = [
                    'attribute' => 'rel="provider"',
                    'value' => 'video'
                ];
            }
            if ($url->info_url) {
                $xml['track']['info'] = $url->info_url;
            }
            if ($url->image_url) {
                $xml['track']['image'] = $url->image_url;
            }
            if ($url->album) {
                $xml['track']['album'] = $url->album;
            }
            if ($url->track_num) {
                $xml['track']['trackNum'] = $url->track_num;
            }

            $result .= Xml_Data::keyed_array($xml, true);
        } // end foreach

        Xml_Data::set_type('xspf');
        $ret = Xml_Data::header($playlist->title);
        $ret .= $result;
        $ret .= Xml_Data::footer();

        return $this
            ->setHeader($response, 'xspf', 'application/xspf+xml')
            ->withBody($this->streamFactory->createStream($ret));
    }
}

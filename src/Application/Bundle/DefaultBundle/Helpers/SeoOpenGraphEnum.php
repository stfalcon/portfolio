<?php

namespace Application\Bundle\DefaultBundle\Helpers;

/**
 * SeoOpenGraphEnum class
 *
 * @author Oleg Kachinsky <logansoleg@gmail.com>
 */
class SeoOpenGraphEnum
{
    // region Music

    const MUSIC_SONG          = 'music.song';
    const MUSIC_ALBUM         = 'music.album';
    const MUSIC_PLAYLIST      = 'music.playlist';
    const MUSIC_RADIO_STATION = 'music.radio_station';

    // endregion Music

    // region Video

    const VIDEO_MOVIE   = 'video.movie';
    const VIDEO_EPISODE = 'video.episode';
    const VIDEO_TV_SHOW = 'video.tv_show';
    const VIDEO_OTHER   = 'video.other';

    // endregion Video

    // region Other

    const ARTICLE = 'article';
    const BOOK    = 'book';
    const PROFILE = 'profile';
    const WEBSITE = 'website';

    // endregion Other
}

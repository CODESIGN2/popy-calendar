<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregMatchPattern;

/**
 * Implementation of the native DateTime formats using preg patterns &
 * PregMatchPattern lexer
 */
class NativeFormatPregMatch implements SymbolParserInterface
{
    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $formater)
    {
        if ($token->is('y')) {
            // y   A two digit representation of a year
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->isOne('Y', 'o')) {
            // Y   A full numeric representation of a year, 4 digits
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            
            return new PregMatchPattern($token, '\d\d\d\d');
        }

        if ($token->is('L')) {
            // L   Whether it's a leap year
            return new PregMatchPattern($token, '[01]');
        }

        if ($token->is('F')) {
            // F   A full textual representation of a month, such as January or March
            return new PregMatchPattern($token, '\S.*?');
        }

        if ($token->is('M')) {
            // M   A short textual representation of a month, three letters    Jan through Dec
            return new PregMatchPattern($token, '.{1,3}');
        }

        if ($token->is('m')) {
            // m   Numeric representation of a month, with leading zeros   01 through 12
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('n')) {
            // n   Numeric representation of a month, without leading zeros
            return new PregMatchPattern($token, '\d\d?');
        }

        if ($token->is('t')) {
            // t   Number of days in the given month
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('d')) {
            // d   Day of the month, 2 digits with leading zeros   01 to 31
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('j')) {
            // j   Day of the month without leading zeros  1 to 31
            return new PregMatchPattern($token, '\d\d?');
        }

        if ($token->is('l')) {
            // l (lowercase 'L')   A full textual representation of the day of the week
            return new PregMatchPattern($token, '\S.*?');
        }

        if ($token->is('D')) {
            // D   A textual representation of a day, three letters
            return new PregMatchPattern($token, '.{1,3}');
        }

        if ($token->is('S')) {
            // S   English ordinal suffix for the day of the month, 2 characters
            return new PregMatchPattern($token, '\S{1,2}');
        }

        if ($token->is('w')) {
            // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
            return new PregMatchPattern($token, '\d');
        }

        if ($token->is('z')) {
            // z   The day of the year (starting from 0)
            return new PregMatchPattern($token, '\d{1,3}');
        }

        if ($token->is('N')) {
            // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
            return new PregMatchPattern($token, '\d');
        }

        if ($token->is('W')) {
            // W   ISO-8601 week number of year, weeks starting on Monday
            return new PregMatchPattern($token, '\d\d?');
        }

        if ($token->isOne('a', 'A')) {
            // a   Lowercase Ante meridiem and Post meridiem   am or pm
            // A   Uppercase Ante meridiem and Post meridiem   AM or PM
            return new PregMatchPattern($token, '[apAP][mM]');
        }

        if ($token->is('B')) {
            // B   Swatch Internet time    000 through 999
            return new PregMatchPattern($token, '\d\d\d');
        }

        if ($token->isOne('g', 'G')) {
            // g   12-hour format of an hour without leading zeros 1 through 12
            // G   24-hour format of an hour without leading zeros 0 through 23
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->isOne('h', 'H')) {
            // h   12-hour format of an hour with leading zeros    01 through 12
            // H   24-hour format of an hour with leading zeros    00 through 23
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('i')) {
            // i   Minutes with leading zeros  00 to 59
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('s')) {
            // s   Seconds, with leading zeros 00 through 59
            return new PregMatchPattern($token, '\d\d');
        }

        if ($token->is('u')) {
            // u   Microseconds
            return new PregMatchPattern($token, '\d{6}');
        }

        if ($token->is('v')) {
            // u   Milliseconds
            return new PregMatchPattern($token, '\d\d\d');
        }

        if ($token->is('e')) {
            // e   Timezone identifier (added in PHP 5.1.0)    Examples: UTC, GMT, Atlantic/Azores
            return new PregMatchPattern($token, '\S.*?');
        }

        if ($token->is('I')) {
            // I (capital i)   Whether or not the date is in daylight saving time  1 if Daylight Saving Time, 0 otherwise.
            return new PregMatchPattern($token, '\d');
        }

        if ($token->is('O')) {
            // O   Difference to Greenwich time (GMT) in hours Example: +0200
            return new PregMatchPattern($token, '[+\-]\d{4}');
        }

        if ($token->is('P')) {
            // P   Difference to Greenwich time (GMT) with colon between hours and minutes (added in PHP 5.1.3)    Example: +02:00
            return new PregMatchPattern($token, '[+\-]\d\d:\d\d');
        }

        if ($token->is('T')) {
            // T   Timezone abbreviation   Examples: EST, MDT ...
            return new PregMatchPattern($token, '[A-Z]{1,3}');
        }

        if ($token->is('Z')) {
            // Z   Timezone offset in seconds. The offset for timezones west of UTC is always negative, and for those east of UTC is always positive.  -43200 through 50400
            return new PregMatchPattern($token, '-?\d{1,5}');
        }

        if ($token->is('c')) {
            // c   ISO 8601 date (added in PHP 5)  2004-02-12T15:19:21+00:00
            return $parser->parse('Y-m-d\TH:i:sP');
        }

        if ($token->is('r')) {
            // r   » RFC 2822 formatted date   Example: Thu, 21 Dec 2000 16:01:07 +0200
            return $parser->parse('D, d M Y H:i:s P');
        }

        if ($token->is('U')) {
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)  See also time()
            return new PregMatchPattern($token, '-?\d+?');
        }
    }
}
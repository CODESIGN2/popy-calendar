<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\LocalisationInterface;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Standard format, handling DateFragmentedRepresentationInterface.
 *
 * Weeks and day names assume a gregorian calendar structure.
 */
class StandardDateFragmented implements SymbolFormaterInterface
{
    /**
     * Locale (used for day & month names)
     *
     * @var LocalisationInterface
     */
    protected $locale;

    /**
     * Class constructor.
     *
     * @param LocalisationInterface $locale
     */
    public function __construct(LocalisationInterface $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
        if (!$input instanceof DateFragmentedRepresentationInterface) {
            return;
        }

        if ($token->is('F')) {
            // F   A full textual representation of a month
            return $this->locale->getMonthName($input->getDateParts()->get(0));
        }

        if ($token->is('M')) {
            // M   A short textual representation of a month, three letters
            return $this->locale->getMonthShortName($input->getDateParts()->get(0));
        }

        if ($token->is('m')) {
            // m   Numeric representation of a month, with leading zeros
            return sprintf('%02d', $input->getDateParts()->get(0) + 1);
        }

        if ($token->is('n')) {
            // n   Numeric representation of a month, without leading zeros
            return $input->getDateParts()->get(0) + 1;
        }

        if ($token->is('t')) {
            // NIY
            return '0';
        }

        if ($token->is('d')) {
            // d   Day of the month, 2 digits with leading zeros
            return sprintf('%02d', $input->getDateParts()->get(1) + 1);
        }

        if ($token->is('j')) {
            // j   Day of the month without leading zeros
            return $input->getDateParts()->get(1) + 1;
        }

        if ($token->is('l')) {
            // l (lowercase 'L')   A full textual representation of the day of the week
            return $this->locale->getDayName($this->getIsoDayOfWeek($input) - 1);
        }

        if ($token->is('D')) {
            // D   A textual representation of a day, three letters
            return $this->locale->getDayShortName($this->getIsoDayOfWeek($input) - 1);
        }

        if ($token->is('S')) {
            // S   English ordinal suffix for the day of the month, 2 characters
            return $this->locale->getNumberOrdinalSuffix($input->getDateParts()->get(1));
        }

        if ($token->is('w')) {
            // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
            return $this->getIsoDayOfWeek($input) % 7;
        }

        if ($token->is('N')) {
            // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
            return $this->getIsoDayOfWeek($input);
        }

        if ($token->is('W')) {
            // W   ISO-8601 week number of year, weeks starting on Monday
            return $this->getIsoYearNumber($input);
        }

        if ($token->is('o')) {
            // Y   A full numeric representation of a year, 4 digits
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            return sprintf('%04d', $this->getIsoYearNumber($input));
        }
    }

    protected function getIsoDayOfWeek(DateFragmentedRepresentationInterface $input)
    {
        return $this->getIsoDayOfWeekFromIndex($input->getEraDayIndex());
    }

    protected function getIsoWeekNumber(DateFragmentedRepresentationInterface $input)
    {
        $fixedDayIndex = $this->getFixedIsoDayIndex($input);

        if ($fixedDayIndex < 0) {
            return 52;
        }

        return intval($fixedDayIndex / 7) + 1;
    }

    protected function getIsoYearNumber(DateFragmentedRepresentationInterface $input)
    {
        $fixedDayIndex = $this->getFixedIsoDayIndex($input);

        if ($fixedDayIndex < 0) {
            return $input->getYear() - 1;
        }

        return $input->getYear();
    }

    protected function getFixedIsoDayIndex(DateFragmentedRepresentationInterface $input)
    {
        $firstWeekStart = $this->getIsoDayOfWeekFromIndex($input->getEraDayIndex() - $input->getDayIndex()) - 1;

        if ($firstWeekStart > 3) {
            $firstWeekStart -= 7;
        }

        return $input->getDayIndex() + $firstWeekStart;
    }


    protected function getIsoDayOfWeekFromIndex($index)
    {
        // Assuming the era starting year is 1970, it starts a Thursday.
        $index += 3;

        return $index % 7 + 1;
    }
}
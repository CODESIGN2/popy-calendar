<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Minimal implementatuon.
 */
class SolarTime extends Date implements DateTimeRepresentationInterface, DateSolarRepresentationInterface
{
    use DateTimeTrait;
    use DateSolarTrait;
}

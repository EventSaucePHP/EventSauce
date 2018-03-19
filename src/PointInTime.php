<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing;

use DateTimeImmutable;

final class PointInTime
{
    /**
     * @private
     */
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s.uO';

    /**
     * @var DateTimeImmutable
     */
    private $pointInTime;

    /**
     * @param DateTimeImmutable $pointInTime
     */
    private function __construct(DateTimeImmutable $pointInTime)
    {
        $this->pointInTime = $pointInTime;
    }

    /**
     * @return DateTimeImmutable
     */
    public function dateTime(): DateTimeImmutable
    {
        return $this->pointInTime;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->pointInTime->format(self::DATE_TIME_FORMAT);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->pointInTime->format(self::DATE_TIME_FORMAT);
    }

    /**
     * @param string $pointInTime
     *
     * @return PointInTime
     */
    public static function fromString(string $pointInTime): PointInTime
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $pointInTime);

        if ( ! $dateTime instanceof DateTimeImmutable) {
            throw new \InvalidArgumentException(sprintf('Invalid point in time input "%s"', $pointInTime));
        }

        return new PointInTime($dateTime);
    }

    /**
     * @param DateTimeImmutable $dateTime
     *
     * @return PointInTime
     */
    public static function fromDateTime(DateTimeImmutable $dateTime): PointInTime
    {
        return new PointInTime($dateTime);
    }
}

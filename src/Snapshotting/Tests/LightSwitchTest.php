<?php

declare(strict_types=1);

namespace EventSauce\EventSourcing\Snapshotting\Tests;

use EventSauce\EventSourcing\AggregateRootId;
use EventSauce\EventSourcing\TestUtilities\AggregateRootTestCase;

class LightSwitchTest extends AggregateRootTestCase
{
    protected function newAggregateRootId(): AggregateRootId
    {
        return LightSwitchId::fromString('living-room');
    }

    protected function aggregateRootClassName(): string
    {
        return LightSwitch::class;
    }

    /**
     * @test
     */
    public function turning_the_light_on(): void
    {
        $this->when(new TurnLightOn($this->aggregateRootId()))
            ->then(
                LightSwitchWasFlipped::on()
            );
    }

    /**
     * @test
     */
    public function turning_the_light_on_again(): void
    {
        $this->given(LightSwitchWasFlipped::on())
            ->when(new TurnLightOn($this->aggregateRootId()))
            ->thenNothingShouldHaveHappened();
    }

    /**
     * @test
     */
    public function turning_the_light_off_after_it_was_turned_on(): void
    {
        $this->given(LightSwitchWasFlipped::on())
            ->when(new TurnLightOff($this->aggregateRootId()))
            ->then(LightSwitchWasFlipped::off());
    }

    /**
     * @test
     */
    public function turning_the_light_off_after_installing(): void
    {
        $this->when(new TurnLightOff($this->aggregateRootId()))
            ->thenNothingShouldHaveHappened();
    }

    protected function handle(object $command): void
    {
        if ( ! $command instanceof TurnLightOn && ! $command instanceof TurnLightOff) {
            return;
        }

        /** @var LightSwitch $lightSwitch */
        $lightSwitch = $this->retrieveAggregateRoot($command->id());

        if ($command instanceof TurnLightOn) {
            $lightSwitch->turnOn();
        } elseif ($command instanceof TurnLightOff) {
            $lightSwitch->turnOff();
        }

        $this->persistAggregateRoot($lightSwitch);
    }
}

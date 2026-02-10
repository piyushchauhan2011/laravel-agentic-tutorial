<?php

declare(strict_types=1);

namespace Tests\Behat\Context;

use Behat\Behat\Context\Context;
use RuntimeException;
use Symfony\Component\Process\Process;

class PlaywrightContext implements Context
{
    /**
     * @var array<int, array<string, string>>
     */
    private array $commands = [];

    /**
     * @Given I open :path in Playwright
     */
    public function iOpenInPlaywright(string $path): void
    {
        $this->commands[] = [
            'type' => 'open',
            'path' => $path,
        ];

        $this->runCommandSequence();
    }

    /**
     * @When I fill :field with :value in Playwright
     */
    public function iFillWithInPlaywright(string $field, string $value): void
    {
        $this->commands[] = [
            'type' => 'fill',
            'field' => $field,
            'value' => $value,
        ];

        $this->runCommandSequence();
    }

    /**
     * @When I click :text in Playwright
     */
    public function iClickInPlaywright(string $text): void
    {
        $this->commands[] = [
            'type' => 'click',
            'text' => $text,
        ];

        $this->runCommandSequence();
    }

    /**
     * @Then I should be on :path in Playwright
     */
    public function iShouldBeOnInPlaywright(string $path): void
    {
        $this->commands[] = [
            'type' => 'assert_path',
            'path' => $path,
        ];

        $this->runCommandSequence();
    }

    /**
     * @Then I should see :text in Playwright
     */
    public function iShouldSeeInPlaywright(string $text): void
    {
        $this->commands[] = [
            'type' => 'assert_text',
            'text' => $text,
        ];

        $this->runCommandSequence();
    }

    /**
     * @Then the :field field should have value :value in Playwright
     */
    public function theFieldShouldHaveValueInPlaywright(string $field, string $value): void
    {
        $this->commands[] = [
            'type' => 'assert_field_value',
            'field' => $field,
            'value' => $value,
        ];

        $this->runCommandSequence();
    }

    private function runCommandSequence(): void
    {
        $process = new Process([
            'node',
            'tests/Behat/playwright/run_scenario.mjs',
            json_encode($this->commands, JSON_THROW_ON_ERROR),
        ], dirname(__DIR__, 3));

        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(trim($process->getErrorOutput()."\n".$process->getOutput()));
        }
    }
}

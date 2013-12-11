<?php
namespace Context;

use Behat\MinkExtension\Context\MinkContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Exception\PendingException;

class FeatureContext extends MinkContext
{
    /**
     * @Given /^There is file with lines:$/
     */
    public function thereIsFileWithLines(TableNode $table)
    {
        // all givens should set app environment
        // it should be always the same for each scenario
        $hash = $table->getHash();
        // it should be file taken from test envirinment
        // but for this presentation purposes it will be file
        // loaded by "bin/console parser" script without parameters
        $file = __DIR__ . '/../../attachments.txt';

        // resetting file content
        `echo -n > {$file}`;
        foreach ($hash as $row) {
            `echo "{$row['line']}" >> {$file}`;
        }
    }

    /**
     * @When /^I run console script$/
     */
    public function iRunConsoleSctipt()
    {
        $basePath = $file = __DIR__ . '/../../';
        $this->output = `cd {$basePath}; bin/console parse`;
    }

    /**
     * @Then /^I should see following output$/
     */
    public function iShouldSeeFollowingOutput(TableNode $table)
    {
        $lines = explode("\n", $this->output);
        foreach($table->getHash() as $i => $row) {
            if ($row['output'] != $lines[$i]) {
                throw new \Exception("Output doesn't match, {$lines[$i]} expected, got {$row['output']}");
            }
        }
    }
}

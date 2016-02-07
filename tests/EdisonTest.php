<?php
/**
 * A PHP script file.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2016 Aaron Bieber - All rights reserved
 */
use AaronBieber\Edison\Experiment;
use AaronBieber\Edison\Noop_Journal;

class Edison_Test extends PHPUnit_Framework_TestCase {
  /**
   * Test that experimental data is collected.
   *
   * @return void
   */
  public function test_experiment_runs() {
    $journal = new Noop_Journal('unit_test_experiment');
    $experiment = new Experiment($journal);
    $experiment->use_control(
        function () {
          return 'control';
        }
    );
    $experiment->use_variant(
        function () {
          return 'variant';
        }
    );
    $result = $experiment->run();
    $observation = $experiment->get_journal()->observation;

    // Verify that all experimental data was collected.
    $this->assertEquals('control', $result);
    $this->assertEquals('control', $observation->control_result);
    $this->assertEquals('variant', $observation->variant_result);
    $this->assertEquals('unit_test_experiment', $experiment->get_journal()->experiment_name);
    $this->assertTrue($observation->discrepancy);
  }

  /**
   * Verify that experiments pass and fail correctly.
   *
   * @return void
   */
  public function test_discrepancy() {
    $journal = new Noop_Journal('unit_test_experiment');
    $experiment = new Experiment($journal);
    $experiment->use_control(
        function () {
          return 'expected_result';
        }
    );
    $experiment->use_variant(
        function () {
          return 'expected_result';
        }
    );
    $result = $experiment->run();
    $this->assertFalse($experiment->get_journal()->observation->discrepancy);

    $experiment->use_variant(
        function () {
          return 'unexpected_result';
        }
    );
    $result = $experiment->run();
    $this->assertTrue($experiment->get_journal()->observation->discrepancy);
  }

  /**
   * Test that experiments run when they should.
   *
   * @return void
   */
  public function test_variant_percent() {
    $journal = new Noop_Journal('unit_test_experiment');
    $experiment = new Experiment($journal);
    $experiment->use_control(
        function () {
          return 'control';
        }
    );
    $experiment->use_variant(
        function () {
          return 'variant';
        }
    );

    // At zero percent, no experiment is run; the observation will be null.
    $experiment->variant_percent(0);
    $result = $experiment->run();
    $observation = $experiment->get_journal()->observation;
    $this->assertNull($observation);

    // At 100 percent, the experiment is always run; observation will be an object.
    $experiment->variant_percent(100);
    $result = $experiment->run();
    $observation = $experiment->get_journal()->observation;
    $this->assertInstanceOf('AaronBieber\\Edison\\Observation', $observation);
  }
}
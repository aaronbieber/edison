<?php
/**
 * This file is part of Edison. See README.md for more information.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2015 Aaron Bieber - All rights reserved
 */
namespace AaronBieber\Edison;

class Experiment {
  /**
   * @var \AaronBieber\Edison\Interfaces\Comparator A comparator.
   */
  private $comparator;

  /**
   * @var \AaronBieber\Edison\Interfaces\Journal A journal.
   */
  private $journal;

  /**
   * @var callable The control case
   */
  private $control;

  /**
   * @var callable The variant to test
   */
  private $variant;

  /**
   * @var int Percentage of requests that should test the variant.
   */
  private $variant_percent = 100;

  /**
   * Construct this object.
   *
   * @param string $experiment_name Experiment name used in statistics.
   *
   * @return void
   */
  public function __construct(
      Interfaces\Journal $journal,
      Interfaces\Comparator $comparator = null
  ) {
    $this->journal = $journal;
    $this->comparator = $comparator ?: new Generic_Comparator();
  }

  /**
   * Comparator setter
   *
   * @param \AaronBieber\Edison\Comparator $comparator A comparator that implements the Comparator interface.
   *
   * @return \AaronBieber\Edison\Experiment Self, for fluent chaining.
   */
  public function use_comparator(Comparator $comparator) {
    $this->comparator = $comparator;

    return $this;
  }

  /**
   * Control case setter
   *
   * @param callable $control A function to call as the control case.
   *
   * @return \AaronBieber\Edison\Experiment Self, for fluent chaining.
   */
  public function use_control(callable $control) {
    $this->control = $control;

    return $this;
  }

  /**
   * Variant setter
   *
   * @param callable $variant A function to call as the variant to test.
   *
   * @return \AaronBieber\Edison\Experiment Self, for fluent chaining.
   */
  public function use_variant(callable $variant) {
    $this->variant = $variant;

    return $this;
  }

  /**
   * Variant percent setter
   *
   * @param int $variant_percent How often to call the variant, as an integer percentage.
   *
   * @return \AaronBieber\Edison\Experiment Self, for fluent chaining.
   */
  public function variant_percent($variant_percent) {
    $this->variant_percent = $variant_percent;

    return $this;
  }

  /**
   * Journal getter, primarily for unit testing purposes. You shouldn't need to access the journal directly.
   *
   * @return \AaronBieber\Edison\Journal The journal.
   */
  public function get_journal() {
    return $this->journal;
  }

  /**
   * Run the experiment.
   *
   * @return mixed The result of calling the control case
   */
  public function run() {
    $observation = new Observation();

    $start = $this->time_ms();
    $control = $this->control;
    $observation->control_result = $control();
    $observation->control_duration = $this->time_ms() - $start;

    if ($this->experiment_should_run()) {
      $start = $this->time_ms();
      $variant = $this->variant;
      $observation->variant_result = $variant();
      $observation->variant_duration = $this->time_ms() - $start;

      $observation->discrepancy = !$this->compare(
          $observation->control_result,
          $observation->variant_result
      );

      $this->journal->save($observation);
    }

    // Always return control
    return $observation->control_result;
  }

  /**
   * Get the current time in rounded milliseconds.
   *
   * @return int
   */
  private function time_ms() {
    return round((1000 * microtime(true)), 2);
  }

  /**
   * Should the experiment run?
   *
   * @return bool
   */
  private function experiment_should_run() {
    $prob = rand(1, 100) <= $this->variant_percent;
    return $prob;
  }

  /**
   * Compare the results and tell me if they're equivalent
   *
   * @param mixed $control_result Result from the control
   * @param mixed $variant_result Result from the variant
   *
   * @return bool Experiment results
   */
  private function compare($control_result, $variant_result) {
    return $this->comparator->compare(
        $control_result,
        $variant_result
    );
  }
}
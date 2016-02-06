<?php
/**
 * This file is part of Edison. See README.md for more information.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2015 Aaron Bieber - All rights reserved
 */
namespace Edison;

class Echo_Journal implements Interfaces\Journal {
  /**
   * @var string The experiment name.
   */
  public $experiment_name;

  /**
   * @var int How long the control took to execute.
   */
  public $control_duration;

  /**
   * @var int How long the variant took to execute.
   */
  public $variant_duration;

  /**
   * @var mixed Actual result from the control.
   */
  public $control_result;

  /**
   * @var mixed Actual result from the variant.
   */
  public $variant_result;

  /**
   * @var bool Was there a discrepancy?
   */
  public $discrepancy;

  /**
   * Documentation
   *
   * @return void
   */
  public function set_control_duration($duration) {
    $this->control_duration = $duration;
  }

  /**
   * Documentation
   *
   * @return void
   */
  public function set_variant_duration($duration) {
    $this->variant_duration = $duration;
  }

  /**
   * Documentation
   *
   * @return void
   */
  public function set_control_result($result) {
    $this->control_result = $result;
  }

  /**
   * Documentation
   *
   * @return void
   */
  public function set_variant_result($result) {
    $this->variant_result = $result;
  }

  /**
   * Documentation
   *
   * @return void
   */
  public function set_discrepancy($discrepancy) {
    $this->discrepancy = $discrepancy;
  }

  /**
   * Documentation
   *
   * @return void
   */
  public function save() {
    echo json_encode(
        [
            'experiment_name'  => $this->experiment_name,
            'control_duration' => $this->control_duration,
            'control_result'   => $this->control_result,
            'variant_duration' => $this->variant_duration,
            'variant_result'   => $this->variant_result,
            'discrepancy'      => $this->discrepancy
        ]
    ) . "\n";
  }

  /**
   * Constructor
   *
   * @param string $experiment_name The name of the experiment.
   *
   * @return void
   */
  public function __construct($experiment_name) {
    $this->experiment_name = $experiment_name;
  }
}
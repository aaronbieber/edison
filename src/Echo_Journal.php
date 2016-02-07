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

class Echo_Journal implements Interfaces\Journal {
  /**
   * @var string The experiment name.
   */
  public $experiment_name;

  /**
   * Documentation
   *
   * @param \Edison\Observation $observation The observation to record.
   *
   * @return void
   */
  public function save(Observation $observation) {
    echo json_encode(
        [
            'experiment_name'  => $this->experiment_name,
            'control_duration' => $observation->control_duration,
            'control_result'   => $observation->control_result,
            'variant_duration' => $observation->variant_duration,
            'variant_result'   => $observation->variant_result,
            'discrepancy'      => $observation->discrepancy
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
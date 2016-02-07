<?php
/**
 * This file is part of Edison. See README.md for more information.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2016 Aaron Bieber - All rights reserved
 */
namespace AaronBieber\Edison;

class Noop_Journal implements Interfaces\Journal {
  /**
   * @var string The experiment name.
   */
  public $experiment_name;

  /**
   * @var \AaronBieber\Edison\Observation The observed data.
   */
  public $observation;


  /**
   * Documentation
   *
   * @param \Edison\Observation $observation The observation to record.
   *
   * @return void
   */
  public function save(Observation $observation) {
    $this->observation = $observation;
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
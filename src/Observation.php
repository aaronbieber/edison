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

class Observation {
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
}
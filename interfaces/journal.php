<?php
/**
 * This file is part of Edison. See README.md for more information.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2015 Aaron Bieber - All rights reserved
 */
namespace Edison\Interfaces;

interface Journal {
  public function __construct($experiment_name);
  public function set_control_duration($duration);
  public function set_variant_duration($duration);
  public function set_control_result($result);
  public function set_variant_result($result);
  public function set_discrepancy($discrepancy);
  public function save();
}
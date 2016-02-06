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
  public function save(\Edison\Observation $observation);
  public function __construct($experiment_name);
}
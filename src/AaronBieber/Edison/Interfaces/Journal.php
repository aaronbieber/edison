<?php
/**
 * This file is part of Edison. See README.md for more information.
 *
 * PHP Version 5
 *
 * @author    Aaron Bieber <aaron@aaronbieber.com>
 * @copyright 2015 Aaron Bieber - All rights reserved
 */
namespace AaronBieber\Edison\Interfaces;

use AaronBieber\Edison\Observation;

interface Journal {
  public function save(Observation $observation);
  public function __construct($experiment_name);
}
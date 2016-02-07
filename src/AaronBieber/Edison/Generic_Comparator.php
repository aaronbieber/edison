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

class Generic_Comparator implements Interfaces\Comparator {
  /**
   * @param mixed $result_one Result one
   * @param mixed $result_two Result two
   *
   * @return bool
   */
  public function compare($result_one, $result_two) {
    return $result_one === $result_two;
  }
}
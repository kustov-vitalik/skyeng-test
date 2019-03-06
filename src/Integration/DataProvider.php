<?php
/**
 * Created by IntelliJ IDEA.
 * User: vitaly
 * Date: 2019-03-06
 * Time: 06:19
 */

namespace src\Integration;

/**
 * Interface DataProvider
 * @package src\Integration
 */
interface DataProvider
{
    /**
     * Returns some data from some web service
     * @param array $request
     * @return array
     */
    public function get(array $request): array;
}
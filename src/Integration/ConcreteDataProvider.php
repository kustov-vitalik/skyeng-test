<?php
/**
 * Created by IntelliJ IDEA.
 * User: vitaly
 * Date: 2019-03-05
 * Time: 10:01
 */

namespace src\Integration;

/**
 * Class DataProvider
 * @package Solution\Integration
 */
class ConcreteDataProvider implements DataProvider
{

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @param $host
     * @param $user
     * @param $password
     */
    public function __construct(string $host, string $user, string $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function get(array $request): array
    {
        // returns some data
        return [];
    }
}
<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Pcabreus\Banggood\Exceptions;

/**
 * Trait BanggoodResponseExceptionTrait
 * @package Pcabreus\Banggood\Exceptions
 *
 * @internal
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
trait BanggoodResponseExceptionTrait
{
    private $apiResult;

    public function __construct($result, $message = '', $code = 0)
    {
        $this->apiResult = $result;
        parent::__construct($message, $code);
    }

    /**
     * @return mixed
     */
    public function getApiResult()
    {
        return $this->apiResult;
    }

    /**
     * @param mixed $apiResult
     */
    public function setApiResult($apiResult)
    {
        $this->apiResult = $apiResult;
    }
}
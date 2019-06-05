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
 * Class BanggoodResponseExceptionInterface
 * @package Pcabreus\Banggood\Exceptions
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
interface BanggoodResponseExceptionInterface
{
    /**
     * @return mixed
     */
    public function getApiResult();

    /**
     * @param mixed $apiResult
     */
    public function setApiResult($apiResult);
}
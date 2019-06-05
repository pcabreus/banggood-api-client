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
 * Class IllegalRequestException
 * @package Pcabreus\Banggood\Exception
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class IllegalRequestException extends \InvalidArgumentException implements BanggoodResponseExceptionInterface
{
    protected $message = 'Illegal request. Please use the registered IP address';
    use BanggoodResponseExceptionTrait;
}
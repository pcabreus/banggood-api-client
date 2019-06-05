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
 * Class ExpiredTokenException
 * @package Pcabreus\Banggood\Exceptions
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class ExpiredTokenException extends \RuntimeException implements BanggoodResponseExceptionInterface
{
    protected $message = 'Expired TOKEN. Please use a new active access-token';
    use BanggoodResponseExceptionTrait;
}
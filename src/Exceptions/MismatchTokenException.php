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
 * Class MismatchTokenException
 * @package Pcabreus\Banggood\Exceptions
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class MismatchTokenException extends \RuntimeException implements BanggoodResponseExceptionInterface
{
    protected $message = 'Mismatch in TOKEN. Input correct access-token';
    use BanggoodResponseExceptionTrait;
}
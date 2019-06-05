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
 * Class AccessRestrictionException
 * @package Pcabreus\Banggood\Exceptions
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class AccessRestrictionException extends \RuntimeException implements BanggoodResponseExceptionInterface
{
    protected $message = 'Access restrictions. You have exceeded the maximum number of calls, please visit again in second days';
    use BanggoodResponseExceptionTrait;
}
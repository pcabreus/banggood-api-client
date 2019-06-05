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


namespace Pcabreus\Banggood;

use Pcabreus\Banggood\Exceptions\AccessRestrictionException;
use Pcabreus\Banggood\Exceptions\EmptyArgumentException;
use Pcabreus\Banggood\Exceptions\ErrorAccountException;
use Pcabreus\Banggood\Exceptions\ExpiredTokenException;
use Pcabreus\Banggood\Exceptions\IllegalRequestException;
use Pcabreus\Banggood\Exceptions\InvalidArgumentException;
use Pcabreus\Banggood\Exceptions\MismatchTokenException;
use Pcabreus\Banggood\Exceptions\SystemErrorException;
use Pcabreus\Banggood\Exceptions\UnknownCodeException;
use Pcabreus\Banggood\Exceptions\BanggoodResponseExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;


/**
 * Class ResponseBanggood
 * @package Pcabreus\Banggood\Response
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class Response
{
    /**
     * Create a Banggound Response
     *
     * @param ResponseInterface $response
     * @param bool $assoc
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public static function create(ResponseInterface $response, $assoc = true)
    {
        $content = json_decode($response->getContent(), $assoc);

        $apiCode = $content['code'];
        unset($content['code']);
        switch ($apiCode) {
            case '0': {
                return $content;
            }
            case '21020': {
                throw new ExpiredTokenException($content);
            }
            case '21010': {
                throw new SystemErrorException($content);
            }
            case '21030': {
                throw new MismatchTokenException($content);
            }
            case '41010': {
                throw new AccessRestrictionException($content);
            }
            case '31010': {
                throw new IllegalRequestException($content);
            }
            case '31020': {
                throw new ErrorAccountException($content);
            }
            case '11020': {
                throw new EmptyArgumentException($content, '`access_token` is empty');
            }
            case '11010': {
                throw new EmptyArgumentException($content, '`app_id` is empty');
            }
            case '11011' : {
                throw new EmptyArgumentException($content, '`app_secret` is empty');
            }
            case '12022' : {
                throw new InvalidArgumentException($content, 'Cannot query by this catid. Please use the sub-category');
            }
            default: {
                throw new UnknownCodeException($content, sprintf('Unknown code `%s` response', $apiCode));
            }
        }
    }
}
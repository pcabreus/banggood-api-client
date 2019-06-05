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

use Pcabreus\Banggood\Exceptions\ConnectionException;
use Pcabreus\Banggood\Exceptions\ExpiredTokenException;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


/**
 * Class BanggoodClient
 * @package Pcabreus\Banggood
 *
 * @TODO Completar los métodos que faltan.
 * @TODO Ponerle los datos esperados a los parametros de los métodos.
 * @TODO Documentar cada método, con las entradas y salidas según la doc oficial.
 *
 * @TODO Esta Api pudiera retornar el Response en vez del string content, e implementar el método getContent() para devolverlo como array.
 * @TODO Lo anterior permite que después se puedan crear clases extensiones de la clase Response para cada método.
 *
 * @TODO revisar la implementación vieja por si hay alguna funcionalidad util que se pueda agregar.
 *
 * @TODO Limpiar el composer.json y crear bien el readme.md
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class BanggoodApiClient
{
    const CACHE_EXPIRE_AFTER = 7200;
    const CACHE_ACCESS_TOKEN_KEY = 'banggond.access_token';

    private $httpClient;
    private $cache;
    private $domain;
    private $id;
    private $secret;

    private $lang;
    private $currency;

    public function __construct(
        HttpClientInterface $httpClient,
        CacheItemPoolInterface $cache,
        $domainBanggood,
        $idBanggood,
        $secretBanggood,
        $lang = 'en',
        $currency = 'USD'
    ) {
        $this->httpClient = $httpClient;
        $this->cache = $cache;

        $this->domain = $domainBanggood;
        $this->id = $idBanggood;
        $this->secret = $secretBanggood;
        $this->lang = $lang;
        $this->currency = $currency;
    }

    /**
     * @param $path
     * @param array $params
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function get($path, $params = [])
    {
        $response = $this->httpClient->request('GET', $this->domain.$path, ['query' => $params]);

        if (200 !== $code = $response->getStatusCode()) {
            throw new ConnectionException(
                sprintf('Error on api connection `%s` with %s status', $response->getInfo('url'), $code)
            );
        }
        try {
            $content = Response::create($response);
        } catch (ExpiredTokenException $e) {
            $params['access_token'] = $this->getAccessToken(true);

            return $this->get($path, $params);
        }

        return $content;
    }

    /**
     * @param bool $clearCache
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getAccessToken($clearCache = false): string
    {
        if ($clearCache) {
            $this->cache->deleteItem(self::CACHE_ACCESS_TOKEN_KEY);
        }
        $item = $this->cache->getItem(self::CACHE_ACCESS_TOKEN_KEY);
        if (!$item->isHit()) {
            $item->expiresAfter(self::CACHE_EXPIRE_AFTER);

            $accessToken = $this->get('/getAccessToken', ['app_id' => $this->id, 'app_secret' => $this->secret], false);
            $item->expiresAfter($accessToken['expires_in']);
            $item->set($accessToken['access_token']);

            $this->cache->save($item);
        }

        return $item->get();

    }

    /**
     * @desc category/getCategoryList
     * @access public
     *
     * @param int $page
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getCategoryList($page = 1)
    {
        $result = $this->get(
            '/category/getCategoryList',
            [
                'access_token' => $this->getAccessToken(),
                'page' => $page,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc product/getProductList
     * @access public
     *
     * @param $catId
     * @param int $page
     * @param null $addDateStart
     * @param null $addDateEnd
     * @param null $modifyDateStart
     * @param null $modifyDateEnd
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getProductList(
        $catId,
        $page = 1,
        $addDateStart = null,
        $addDateEnd = null,
        $modifyDateStart = null,
        $modifyDateEnd = null
    ) {
        $result = $this->get(
            '/product/getProductList',
            [
                'access_token' => $this->getAccessToken(),
                'cat_id' => $catId,
                'add_date_start' => $addDateStart,
                'add_date_end' => $addDateEnd,
                'modify_date_start' => $modifyDateStart,
                'modify_date_end' => $modifyDateEnd,
                'page' => $page,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc product/getProductInfo
     * @access public
     *
     * @param $productId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getProductInfo($productId)
    {
        $result = $this->get(
            '/product/getProductInfo',
            [
                'access_token' => $this->getAccessToken(),
                'product_id' => $productId,
                'currency' => $this->currency,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc product/getProductStock
     * @access public
     *
     * @param $productId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getProductStock($productId)
    {
        $result = $this->get(
            '/product/getProductStock',
            [
                'access_token' => $this->getAccessToken(),
                'product_id' => $productId,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc product/getShipments
     * @access public
     *
     * @param string $productId
     * @param string $warehouse
     * @param string $country
     * @param string|null $poaId
     * @param int|null $quantity
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getShipments(
        string $productId,
        string $warehouse,
        string $country,
        string $poaId = null,
        int $quantity = null
    ) {
        $result = $this->get(
            '/product/getShipments',
            [
                'access_token' => $this->getAccessToken(),
                'product_id' => $productId,
                'warehouse' => $warehouse,
                'country' => $country,
                'pao_id' => $poaId,
                'quantity' => $quantity,
                'lang' => $this->lang,
                'currency' => $this->currency,
            ]
        );

        return $result;
    }

    /**
     * @desc order/importOrder
     * @access public
     *
     * @todo Implementar
     */
    public function importOrder()
    {
    }

    /**
     * @desc order/getOrderInfo
     * @access public
     *
     * @param $saleRecordId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getOrderInfo($saleRecordId)
    {
        $result = $this->get(
            '/order/getOrderInfo',
            [
                'access_token' => $this->getAccessToken(),
                'sale_record_id' => $saleRecordId,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc order/getOrderHistory
     * @access public
     *
     * @param $saleRecordId
     * @param $orderId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getOrderHistory($saleRecordId, $orderId)
    {
        $result = $this->get(
            '/order/getOrderHistory',
            [
                'access_token' => $this->getAccessToken(),
                'sale_record_id' => $saleRecordId,
                'order_id' => $orderId,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }

    /**
     * @desc order/getTrackInfo
     * @access public
     *
     * @param $orderId
     * @return mixed
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getTrackInfo($orderId)
    {
        $result = $this->get(
            '/order/getOrderHistory',
            [
                'access_token' => $this->getAccessToken(),
                'order_id' => $orderId,
                'lang' => $this->lang,
            ]
        );

        return $result;
    }
}
<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">Plugin Skeleton</h1>

<p align="center">Skeleton for starting Sylius plugins.</p>

## Features

* Static Price: Regular and Sale prices, define de forma fija cuando cuesta el paquete completo.
* Individual price: enables discount %, makes it visible (Add visibility disable in advance panel)
* Shipping (Read more): Assembled and Unassembled, Shipping Individual
* Setting: 
    - Basic: Min and Max Qty, Optional, Filter Variation, Override the Default Selection, Price individually (discount added), Shipping Individual,
    - Advanced: Visibility, Price visibility (on Price individual), Override Title and Short Description, Hide thumbnails
* Layout: List, Table, Grid
* Item Grouping: Type: Grouped, Flat, None: Permite definir si es se agrupan en el elemento padre o individual
* Inventory checks (REad more)

## TODOs

* Managing not simple product on cart.

## Installation

1. Run `composer require positibe/bundled-products-plugin`.

2...

. Add BundledProduct collection to your Products by implementing the ``Positibe\Sylius\ProductBundlePlugin\Entity\ProductBundleInterface`` interface.

Note: You may use of the ``Positibe\Sylius\ProductBundlePlugin\Entity\ProductBundleTrait`` for simplify.

    <?php
    
    declare(strict_types=1);
    
    namespace App\Entity;
    
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping\MappedSuperclass;
    use Doctrine\ORM\Mapping\Table;
    use Positibe\Sylius\ProductBundlesPlugin\Entity\ProductBundleInterface;
    use Positibe\Sylius\ProductBundlesPlugin\Entity\ProductBundleTrait;
    use Sylius\Component\Core\Model\Product as BaseProduct;
    use Sylius\Component\Product\Model\ProductTranslationInterface;
    
    /**
     * @Entity
     * @Table(name="sylius_product")
     */
    class Product extends BaseProduct implements ProductBundleInterface
    {
        use ProductBundleTrait;
    
        public function __construct()
        {
            parent::__construct();
            $this->bundledProducts = new ArrayCollection();
        }
    
    
        protected function createTranslation(): ProductTranslationInterface
        {
            return new ProductTranslation();
        }
    }



## Usage

### Running plugin tests

### Opening Sylius with your plugin


<?php

namespace App\Entity;

use App\Repository\MarketProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarketProductRepository::class)
 */
class MarketProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $createTime;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $editTime;

    /**
     * @ORM\Column(type="string", length=65)
     */
    private $sku;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rangeIdentifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coreIdentifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_retailHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_batchHashArray;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_batchHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_coreHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_productRangeHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_productGroupHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $_productCoreHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name_search;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_stock;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rtl_size_code;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $batchIdOriginal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_stock_total;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_container_type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_unit_weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_height_from;

    /**
     * @ORM\Column(type="string", length=101, nullable=true)
     */
    private $btch_height_to;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_container_size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_container_shape;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_container_contents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_container_diameter;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $chn_price_retail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $baseHash;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $btch_stem_height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shape_diameter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plantenbak_vorm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $plantenbak_diameter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maximale_hoogte_in_cm;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $order_minimum;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $qty_increments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $root;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $rootType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateTime(): string
    {
        return $this->createTime;
    }

    public function setCreateTime(string $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getEditTime(): string
    {
        return $this->editTime;
    }

    public function setEditTime(string $editTime): self
    {
        $this->editTime = $editTime;

        return $this;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getRangeIdentifier(): ?string
    {
        return $this->rangeIdentifier;
    }

    public function setRangeIdentifier(string $rangeIdentifier): self
    {
        $this->rangeIdentifier = $rangeIdentifier;

        return $this;
    }

    public function getCoreIdentifier(): ?string
    {
        return $this->coreIdentifier;
    }

    public function setCoreIdentifier(string $coreIdentifier): self
    {
        $this->coreIdentifier = $coreIdentifier;

        return $this;
    }

    public function getRetailHash(): ?string
    {
        return $this->_retailHash;
    }

    public function setRetailHash(string $_retailHash): self
    {
        $this->_retailHash = $_retailHash;

        return $this;
    }

    public function getBatchHashArray(): ?string
    {
        return $this->_batchHashArray;
    }

    public function setBatchHashArray(string $_batchHashArray): self
    {
        $this->_batchHashArray = $_batchHashArray;

        return $this;
    }

    public function getBatchHash(): ?string
    {
        return $this->_batchHash;
    }

    public function setBatchHash(string $_batchHash): self
    {
        $this->_batchHash = $_batchHash;

        return $this;
    }

    public function getCoreHash(): ?string
    {
        return $this->_coreHash;
    }

    public function setCoreHash(string $_coreHash): self
    {
        $this->_coreHash = $_coreHash;

        return $this;
    }

    public function getProductRangeHash(): ?string
    {
        return $this->_productRangeHash;
    }

    public function setProductRangeHash(string $_productRangeHash): self
    {
        $this->_productRangeHash = $_productRangeHash;

        return $this;
    }

    public function getProductGroupHash(): ?string
    {
        return $this->_productGroupHash;
    }

    public function setProductGroupHash(string $_productGroupHash): self
    {
        $this->_productGroupHash = $_productGroupHash;

        return $this;
    }

    public function getProductCoreHash(): ?string
    {
        return $this->_productCoreHash;
    }

    public function setProductCoreHash(string $_productCoreHash): self
    {
        $this->_productCoreHash = $_productCoreHash;

        return $this;
    }

    public function getNameSearch(): ?string
    {
        return $this->name_search;
    }

    public function setNameSearch(string $name_search): self
    {
        $this->name_search = $name_search;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBtchStock(): ?string
    {
        return $this->btch_stock;
    }

    public function setBtchStock(string $btch_stock): self
    {
        $this->btch_stock = $btch_stock;

        return $this;
    }

    public function getRtlSizeCode(): ?string
    {
        return $this->rtl_size_code;
    }

    public function setRtlSizeCode(string $rtl_size_code): self
    {
        $this->rtl_size_code = $rtl_size_code;

        return $this;
    }

    public function getBatchIdOriginal(): ?int
    {
        return $this->batchIdOriginal;
    }

    public function setBatchIdOriginal(int $batchIdOriginal): self
    {
        $this->batchIdOriginal = $batchIdOriginal;

        return $this;
    }

    public function getBtchStockTotal(): ?string
    {
        return $this->btch_stock_total;
    }

    public function setBtchStockTotal(string $btch_stock_total): self
    {
        $this->btch_stock_total = $btch_stock_total;

        return $this;
    }

    public function getBtchContainerType(): ?string
    {
        return $this->btch_container_type;
    }

    public function setBtchContainerType(string $btch_container_type): self
    {
        $this->btch_container_type = $btch_container_type;

        return $this;
    }

    public function getBtchUnitWeight(): ?string
    {
        return $this->btch_unit_weight;
    }

    public function setBtchUnitWeight(string $btch_unit_weight): self
    {
        $this->btch_unit_weight = $btch_unit_weight;

        return $this;
    }

    public function getBtchHeightFrom(): ?string
    {
        return $this->btch_height_from;
    }

    public function setBtchHeightFrom(string $btch_height_from): self
    {
        $this->btch_height_from = $btch_height_from;

        return $this;
    }

    public function getBtchHeightTo(): ?string
    {
        return $this->btch_height_to;
    }

    public function setBtchHeightTo(string $btch_height_to): self
    {
        $this->btch_height_to = $btch_height_to;

        return $this;
    }

    public function getBtchContainerSize(): ?string
    {
        return $this->btch_container_size;
    }

    public function setBtchContainerSize(string $btch_container_size): self
    {
        $this->btch_container_size = $btch_container_size;

        return $this;
    }

    public function getBtchContainerShape(): ?string
    {
        return $this->btch_container_shape;
    }

    public function setBtchContainerShape(string $btch_container_shape): self
    {
        $this->btch_container_shape = $btch_container_shape;

        return $this;
    }

    public function getBtchContainerContents(): ?string
    {
        return $this->btch_container_contents;
    }

    public function setBtchContainerContents(string $btch_container_contents): self
    {
        $this->btch_container_contents = $btch_container_contents;

        return $this;
    }

    public function getBtchContainerDiameter(): ?string
    {
        return $this->btch_container_diameter;
    }

    public function setBtchContainerDiameter(string $btch_container_diameter): self
    {
        $this->btch_container_diameter = $btch_container_diameter;

        return $this;
    }

    public function getChnPriceRetail(): ?float
    {
        return $this->chn_price_retail;
    }

    public function setChnPriceRetail(float $chn_price_retail): self
    {
        $this->chn_price_retail = $chn_price_retail;

        return $this;
    }

    public function getBaseHash(): ?string
    {
        return $this->baseHash;
    }

    public function setBaseHash(?string $baseHash): self
    {
        $this->baseHash = $baseHash;

        return $this;
    }

    public function getBtchStemHeight(): ?string
    {
        return $this->btch_stem_height;
    }

    public function setBtchStemHeight(?string $btch_stem_height): self
    {
        $this->btch_stem_height = $btch_stem_height;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getShapeDiameter(): ?string
    {
        return $this->shape_diameter;
    }

    public function setShapeDiameter(string $shape_diameter): self
    {
        $this->shape_diameter = $shape_diameter;

        return $this;
    }

    public function getPlantenbakVorm(): ?string
    {
        return $this->plantenbak_vorm;
    }

    public function setPlantenbakVorm(?string $plantenbak_vorm): self
    {
        $this->plantenbak_vorm = $plantenbak_vorm;

        return $this;
    }

    public function getPlantenbakDiameter(): ?string
    {
        return $this->plantenbak_diameter;
    }

    public function setPlantenbakDiameter(?string $plantenbak_diameter): self
    {
        $this->plantenbak_diameter = $plantenbak_diameter;

        return $this;
    }

    public function getMaximaleHoogteInCm(): ?string
    {
        return $this->maximale_hoogte_in_cm;
    }

    public function setMaximaleHoogteInCm(?string $maximale_hoogte_in_cm): self
    {
        $this->maximale_hoogte_in_cm = $maximale_hoogte_in_cm;

        return $this;
    }

    public function getOrderMinimum(): ?int
    {
        return $this->order_minimum;
    }

    public function setOrderMinimum(?int $order_minimum): self
    {
        $this->order_minimum = $order_minimum;

        return $this;
    }

    public function getQtyIncrements(): ?int
    {
        return $this->qty_increments;
    }

    public function setQtyIncrements(?int $qty_increments): self
    {
        $this->qty_increments = $qty_increments;

        return $this;
    }

    public function getRoot(): ?string
    {
        return $this->root;
    }

    public function setRoot(?string $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function getRootType(): ?string
    {
        return $this->rootType;
    }

    public function setRootType(?string $rootType): self
    {
        $this->rootType = $rootType;

        return $this;
    }
}

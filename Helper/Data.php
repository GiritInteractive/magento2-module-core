<?php
/**
 * Girit-Interactive
 * http://www.girit-tech.com
 * +972-3-9177788
 * info@girit.biz
 *
 * @package     Girit_Core
 * @author      Pniel Cohen <pini@girit.biz>
 * @license     https://opensource.org/licenses/OSL-3.0
 * @copyright   © 2018 Girit-Interactive (https://www.girit-tech.com/). All rights reserved.
 */

namespace Girit\Core\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected static $_storesLocales = null;
    protected static $_localesAndStores = null;
    protected static $_attributeOptionsCache = [];

    protected $_origStoreId;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    protected $_resolver;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory
     */
    protected $_configCollectionFactory;

    /**
     * @var \Magento\Framework\App\Config\ConfigResource\ConfigInterface
     */
    protected $_resourceConfig;

    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Catalog\Model\Category
     */
    protected $_categoryModel;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $_categoryResourceModel;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $_categoryRepository;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * @var \Girit\Core\Model\EavConfig extends \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory
     */
    protected $_attributeFactory;

    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    protected $_eavAttributeRepository;

    /**
     * @var \Magento\Swatches\Helper\Data
     */
    protected $_swatchesHelper;

    /**
     * @var \Magento\Swatches\Helper\Media
     */
    protected $_swatchesMediaHelper;

    /**
     * @var \Magento\Catalog\Helper\Product
     */
    protected $_productHelper;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var \Magento\Framework\Filter\Translit
     */
    protected $_translit;

    /**
     * @var \Magento\Indexer\Model\IndexerFactory
     */
    protected $_indexerFactory;

    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry
     */
    protected $_indexerRegistry;

    /**
     * @var \Magento\Indexer\Model\Indexer\CollectionFactory
     */
    protected $_indexerCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\Locale\Resolver $resolver,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory $configCollectionFactory,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $resourceConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Catalog\Model\Category $categoryModel,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResourceModel,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Girit\Core\Model\EavConfig $eavConfig,
        \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $attributeFactory,
        \Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository,
        \Magento\Swatches\Helper\Data $swatchesHelper,
        \Magento\Swatches\Helper\Media $swatchesMediaHelper,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Filter\Translit $translit,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory
    ) {
        $this->_resolver = $resolver;
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->_registry = $registry;
        $this->_eventManager = $eventManager;
        $this->_directoryList = $directoryList;
        $this->_scopeConfig = $scopeConfig;
        $this->_configCollectionFactory = $configCollectionFactory;
        $this->_resourceConfig = $resourceConfig;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_categoryModel = $categoryModel;
        $this->_categoryResourceModel = $categoryResourceModel;
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_eavConfig = $eavConfig;
        $this->_attributeFactory = $attributeFactory;
        $this->_eavAttributeRepository = $eavAttributeRepository;
        $this->_swatchesHelper = $swatchesHelper;
        $this->_swatchesMediaHelper = $swatchesMediaHelper;
        $this->_productHelper = $productHelper;
        $this->_productFactory = $productFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_translit = $translit;
        $this->_indexerFactory = $indexerFactory;
        $this->_indexerRegistry = $indexerRegistry;
        $this->_indexerCollectionFactory = $indexerCollectionFactory;

        //$appState->setAreaCode('frontend');
        //$appState->setAreaCode('adminhtml');
        //$this->_registry->register('isSecureArea', true);
        $this->_origStoreId = $this->getCurrentStoreId();
        parent::__construct($context);
        $this->_initGlobalFunctions();
    }

    protected function _initGlobalFunctions()
    {
        if(!function_exists("giritTest")){
            /**
            * print_r Variable & Die.
            * @param mixed $var
            * @param mixed $var2 OPTIONAL [Any number of vars to print]
            */
            function giritTest(){
                $params = func_get_args();
                if($params && is_array($params)){
                    echo "<pre style='position:relative !important;z-index:99999999999 !important;direction:ltr !important;text-align:left !important;background:#d8d8d8 !important;padding:8px !important;overflow:auto !important;font-family: monospace, arial, sans-serif !important;font-size: 1em !important;'>";
                        foreach ($params as $k => $param){
                            echo "<pre style='position:relative !important;z-index:99999999999 !important;direction:ltr !important;text-align:left !important;background:#efefef !important;padding:5px !important;overflow:auto !important;font-family: monospace, arial, sans-serif !important;font-size: 1em !important;'>";
                                print_r($param);
                            echo "</pre>";
                        }
                    echo "</pre>";
                }
                die;
            }
        }
        if(!function_exists("giritTest2")){
            /**
            * print_r Variable (& Continue).
            * @param mixed $var
            * @param mixed $var2 OPTIONAL [Any number of vars to print]
            */
            function giritTest2(){
                $params = func_get_args();
                if($params && is_array($params)){
                    echo "<pre style='position:relative !important;z-index:99999999999 !important;direction:ltr !important;text-align:left !important;background:#d8d8d8 !important;padding:8px !important;overflow:auto !important;font-family: monospace, arial, sans-serif !important;font-size: 1em !important;'>";
                        foreach ($params as $k => $param){
                            echo "<pre style='position:relative !important;z-index:99999999999 !important;direction:ltr !important;text-align:left !important;background:#efefef !important;padding:5px !important;overflow:auto !important;font-family: monospace, arial, sans-serif !important;font-size: 1em !important;'>";
                                print_r($param);
                            echo "</pre>";
                        }
                    echo "</pre>";
                }
            }
        }
    }

    public function getConfig($configPath, $skipCahce = false, $scope = null, $scopeId = null)
    {
        $scope = (is_null($scope))? \Magento\Store\Model\ScopeInterface::SCOPE_STORE : $scope;
        $scopeId = (is_null($scopeId))? $this->_storeManager->getStore()->getId() : $scopeId;
        if($skipCahce){
            if ($scope === \Magento\Store\Model\ScopeInterface::SCOPE_STORE) {
                $scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
            } elseif ($scope === \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE) {
                $scope = \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES;
            }
            $collection = $this->_configCollectionFactory->create()
                ->addFieldToFilter('scope', $scope)
                ->addFieldToFilter('scope_id', $scopeId)
                ->addFieldToFilter('path', ['like' => $configPath . '%']);
            if($collection->count()){
                return $collection->getFirstItem()->getValue();
            }
        }else{
            return $this->_scopeConfig->getValue($configPath,$scope);
        }
    }

    public function getLocale()
    {
        return $this->_resolver->getLocale();
    }

    public function getStoreManager()
    {
        return $this->_storeManager;
    }

    public function getObjectManager()
    {
        return $this->_objectManager;
    }

    public function getResourceConfig()
    {
        return $this->_resourceConfig;
    }

    public function getTranslit(){
        return $this->_translit;
    }

    public function getCategoryModel(){
        return $this->_categoryModel;
    }

    public function getCategoryResourceModel(){
        return $this->_categoryResourceModel;
    }

    public function getCategoryFactory(){
        return $this->_categoryFactory;
    }

    public function getCategoryRepository(){
        return $this->_categoryRepository;
    }

    public function getCategoryCollection(){
        return $this->_categoryCollectionFactory->create();
    }

    public function cleanConfigCache(){
        $this->_cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        $this->_cacheTypeList->cleanType(\Magento\PageCache\Model\Cache\Type::TYPE_IDENTIFIER);
        return $this;
    }

    public function getProductCollectionFactory(){
        return $this->_productCollectionFactory;
    }

    public function getIndexer(){
        return $this->_indexerFactory;
    }

    //=====================================================================================================//

    public function reindex($indexerCollectionIds = []){
        $indexerCollectionIds = ($indexerCollectionIds)? (array)$ids : $this->_indexerCollectionFactory->create()->getAllIds();
        foreach ($indexerCollectionIds as $indexerId) {
            $indexer = $this->_indexerFactory->create()->load($indexerId)->reindexAll();
        }
    }

    public function reindexCategoryProducts($categoryId){
        $this->_indexerRegistry->get(\Magento\Catalog\Model\Indexer\Category\Product::INDEXER_ID)->reindexRow($categoryId);
    }

    public function reindexProductCategories($productId){
        $this->_indexerRegistry->get(\Magento\Catalog\Model\Indexer\Product\Category::INDEXER_ID)->reindexRow($productId);
    }

    //=====================================================================================================//

    public function createCategory($data){
        return $this->_categoryFactory->create()->setData($data)->save();
    }

    /**
     * Save category products relation
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param array $products Array of [PRODUCT_ID => POSITION, ...]
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function assignProductsToCategory($category, $products = [])
    {
        $category->setIsChangedProductList(false);
        $id = $category->getId();
        /**
         * new category-product relationships
         */
        $category->setPostedProducts($products);

        /**
         * Example re-save category
         */
        if ($products === null) {
            return $this;
        }

        /**
         * old category-product relationships
         */
        $oldProducts = $category->getProductsPosition();

        $insert = array_diff_key($products, $oldProducts);
        $delete = array_diff_key($oldProducts, $products);

        /**
         * Find product ids which are presented in both arrays
         * and saved before (check $oldProducts array)
         */
        $update = array_intersect_key($products, $oldProducts);
        $update = array_diff_assoc($update, $oldProducts);

        $connection = $this->_categoryResourceModel->getConnection();

        /**
         * Delete products from category
         */
        if (!empty($delete)) {
            $cond = ['product_id IN(?)' => array_keys($delete), 'category_id=?' => $id];
            $connection->delete($this->_categoryResourceModel->getCategoryProductTable(), $cond);
        }

        /**
         * Add products to category
         */
        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $productId => $position) {
                $data[] = [
                    'category_id' => (int)$id,
                    'product_id' => (int)$productId,
                    'position' => (int)$position,
                ];
            }
            $connection->insertMultiple($this->_categoryResourceModel->getCategoryProductTable(), $data);
        }

        /**
         * Update product positions in category
         */
        if (!empty($update)) {
            foreach ($update as $productId => $position) {
                $where = ['category_id = ?' => (int)$id, 'product_id = ?' => (int)$productId];
                $bind = ['position' => (int)$position];
                $connection->update($this->_categoryResourceModel->getCategoryProductTable(), $bind, $where);
            }
        }

        if (!empty($insert) || !empty($delete)) {
            $productIds = array_unique(array_merge(array_keys($insert), array_keys($delete)));
            $this->_eventManager->dispatch(
                'catalog_category_change_products',
                ['category' => $category, 'product_ids' => $productIds]
            );
        }

        if (!empty($insert) || !empty($update) || !empty($delete)) {
            $category->setIsChangedProductList(true);

            /**
             * Setting affected products to category for third party engine index refresh
             */
            $productIds = array_keys($insert + $delete + $update);
            $category->setAffectedProductIds($productIds);
        }
        return $this;
    }

    /**
     */
    public function getAttribute($entityTypeCode, $attributeCode, $clearObjectBefore = false)
    {
        if($clearObjectBefore){
            $this->_eavConfig->softClear();
        }
        /** @var \Magento\Eav\Api\Data\AttributeInterface $attribute */
        $attribute = $this->_eavConfig->getAttribute($entityTypeCode, $attributeCode);
        if (!$attribute || !$attribute->getAttributeId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __('Attribute with attributeCode "%1" does not exist.', $attributeCode)
            );
        }
        return $attribute;
    }

    public function getAttributeOptionsByCode($attributeCode, $reload = false){
        if($attributeCode){
            if(isset(self::$_attributeOptionsCache[$attributeCode][$this->_storeManager->getStore()->getId()]) && !$reload){
                return self::$_attributeOptionsCache[$attributeCode][$this->_storeManager->getStore()->getId()];
            }
            $attribute = $this->getAttribute(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE, $attributeCode, true);
            return self::$_attributeOptionsCache[$attributeCode][$this->_storeManager->getStore()->getId()] = $attribute->getSource()->getAllOptions(false);
        }
    }

    public function getSwatchesByOptionsId($swatchId){
        return $this->_swatchesHelper->getSwatchesByOptionsId($swatchId);
    }

    public function getAttributeOptionSwatchImage($swatchId){
        try {
            $swatches = $this->_swatchesHelper->getSwatchesByOptionsId($swatchId);
            $imagePath = $swatches[$swatchId['value']]['value'];
        } catch (\Exception $e) {
            $imagePath = null;
        }
        return $imagePath;
    }

    public function getAttributeOptionSwatchImagePath($swatchId){
        try {
            $swatches = $this->_swatchesHelper->getSwatchesByOptionsId($swatchId);
            $imagePath = $this->_swatchesMediaHelper->getSwatchMediaUrl() . $swatches[$swatchId['value']]['value'];
        } catch (\Exception $e) {
            $imagePath = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/placeholder/' . ltrim($this->_storeManager->getStore()->getConfig('catalog/placeholder/swatch_image_placeholder'),"/");
        }
        return (string)$imagePath;
    }

    public function getLocalesAndStores(){
        if(!is_null(self::$_localesAndStores)){return self::$_localesAndStores;}
        $stores = $this->_storeManager->getStores(false);
        $return = [];
        foreach ($stores as $store) {
            $locale = $this->getConfig('general/locale/code', true, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());
            if(!$locale){
                $locale = $this->getConfig('general/locale/code', true, \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES, $store->getWebsiteId());
            }
            if(!$locale){
                $locale = $this->getConfig('general/locale/code', true, $this->getDefaultScope(), $this->getDefaultStoreId());
            }
            if($locale){
                $return[$locale][] = $store->getStoreId();
            }
        }
        return self::$_localesAndStores = $return;
    }

    public function getStoresLocales($includeDefaultStore = true){
        if(($includeDefaultStore && !isset(self::$_storesLocales[$this->getDefaultStoreId()])) || (!$includeDefaultStore && isset(self::$_storesLocales[$this->getDefaultStoreId()]))){
            self::$_storesLocales = null;
        }
        if(!is_null(self::$_storesLocales)){return self::$_storesLocales;}
        $stores = $this->_storeManager->getStores($includeDefaultStore);
        $return = [];
        foreach ($stores as $store) {
            $locale = $this->getConfig('general/locale/code', true, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $store->getId());
            if(!$locale){
                $locale = $this->getConfig('general/locale/code', true, \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITES, $store->getWebsiteId());
            }
            if(!$locale){
                $locale = $this->getConfig('general/locale/code', true, $this->getDefaultScope(), $this->getDefaultStoreId());
            }
            if($locale){
                $return[$store->getStoreId()] = $locale;
            }
        }
        ksort($return);
        return self::$_storesLocales = $return;
    }

    public function setCurrentStore($storeId){
        return $this->_storeManager->setCurrentStore($storeId);
    }

    public function getCurrentStore(){
        return $this->getStoreManager()->getStore();
    }

    public function getCurrentStoreId(){
        return $this->getCurrentStore()->getId();
    }

    public function getCurrentRootCategory(){
        return $this->getCategoryModel()->load($this->getCurrentRootCategoryId());
    }

    public function getCurrentRootCategoryId(){
        return $this->getCurrentStore()->getRootCategoryId();
    }

    public function switchToDefaultStore(){
        return $this->setCurrentStore($this->getDefaultStoreId());
    }

    public function switchToAdminStore(){
        return $this->setCurrentStore($this->getDefaultAdminCode());
    }

    public function switchToOrigStore(){
        return $this->setCurrentStore($this->_origStoreId);
    }

    public function getOrigStoreId(){
        return $this->_origStoreId;
    }

    public function getDefaultStoreId(){
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }

    public function getDefaultAdminCode(){
        return \Magento\Store\Model\Store::ADMIN_CODE;
    }

    public function getDefaultScope(){
        return \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
    }

    public function getPlaceholderImage($path = 'small_image_placeholder'){
        $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/placeholder/' . ltrim($this->_storeManager->getStore()->getConfig('catalog/placeholder/'.$path),"/");
    }

    public function getCategoryById($categoryId, $storeId = null){
        return $this->_categoryRepository->get($categoryId, (is_null($storeId))?$this->_storeManager->getStore()->getId():$storeId);
    }

    public function getProductById($productId, $storeId = null){
        return $this->_productFactory->create()
            ->setStoreId((is_null($storeId))?$this->_storeManager->getStore()->getId():$storeId)
            ->load($productId);
    }

}

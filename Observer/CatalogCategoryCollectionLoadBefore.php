<?php
namespace VoolaTech\HideEmptyCategories\Observer;

class CatalogCategoryCollectionLoadBefore implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $observer->getCategoryCollection();
        $categoryCollection->addAttributeToSelect('is_anchor');
    }
}
<?php
namespace VoolaTech\HideEmptyCategories\Observer;

class CatalogCategoryCollectionLoadAfter implements \Magento\Framework\Event\ObserverInterface
{
    private $eventManager;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager
    ) {
        $this->eventManager = $eventManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $filteredCategoryCollection = $observer->getCategoryCollection();
        $categoryCollection = clone $filteredCategoryCollection;
        $filteredCategoryCollection->removeAllItems();

        /** @var \Magento\Catalog\Model\Category $category */
        foreach ($categoryCollection as $category) {

            $showCategory = $this->getTotalProductCount($category) > 0;

            $transport = new \Magento\Framework\DataObject(
                [
                    'show_category' => $showCategory
                ]
            );

            $this->eventManager->dispatch(
                'voolatech_hide_empty_categories_before',
                [
                    'collection' => $categoryCollection,
                    'category' => $category,
                    'transport' => $transport
                ]
            );

            if ($transport->getShowCategory()) {
                $filteredCategoryCollection->addItem($category);
            }
        }
    }

    protected function getTotalProductCount(\Magento\Catalog\Model\Category $category)
    {
        return $category->getProductCollection()->getSize();
    }
}
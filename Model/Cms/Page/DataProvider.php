<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Matritix\AdvancedCmsFields\Model\Cms\Page;

use Magento\Cms\Model\ResourceModel\Page\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Cms\Model\Page\DataProvider
{
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        /*
         * @var $page \Magento\Cms\Model\Page
         */
        foreach ($items as $page) {
            $this->loadedData[$page->getId()] = $page->getData();
        }

        $data = $this->dataPersistor->get('cms_page');

        // added
        if (isset($page)) {
            if ($this->loadedData[$page->getId()]['matritix_advancedform']) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$jsonHelper = $objectManager->get('Magento\Framework\Json\Helper\Data');
				$this->loadedData[$page->getId()]['matritix_advancedform'] = $jsonHelper->jsonDecode($this->loadedData[$page->getId()]['matritix_advancedform']);
            }
        }
		// end added
		
		if (!empty($data)) {
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $page->getData();
            $this->dataPersistor->clear('cms_page');
        }
        
        return $this->loadedData;

    }//end getData()


}//end class

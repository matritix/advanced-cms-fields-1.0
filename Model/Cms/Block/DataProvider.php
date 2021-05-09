<?php

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Matritix\AdvancedCmsFields\Model\Cms\Block;

use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Cms\Model\Block\DataProvider
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
         * @var $block \Magento\Cms\Model\block
         */
        foreach ($items as $block) {
            $this->loadedData[$block->getId()] = $block->getData();
        }

        $data = $this->dataPersistor->get('cms_block');

        // added
        if (isset($block)) {
            if ($this->loadedData[$block->getId()]['matritix_advancedform']) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $jsonHelper = $objectManager->get('Magento\Framework\Json\Helper\Data');
                $this->loadedData[$block->getId()]['matritix_advancedform'] = $jsonHelper->jsonDecode($this->loadedData[$block->getId()]['matritix_advancedform']);
            }
        }
        // end added

        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
            $this->dataPersistor->clear('cms_block');
        }

        return $this->loadedData;
    } //end getData()


}//end class

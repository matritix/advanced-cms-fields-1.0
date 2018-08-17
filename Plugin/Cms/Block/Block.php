<?php

namespace Matritix\AdvancedCmsFields\Plugin\Cms\Block;

class Block
{
    /*
        public function beforeGetProduct(\Magento\Cms\Block\Block $subject)
        {

        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug(__METHOD__ . ' - ' . __LINE__);
        }

        public function afterGetProduct(\Magento\Cms\Block\Block $subject, $result)
        {

        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug(__METHOD__ . ' - ' . __LINE__);

        return $result;
        }


        protected function aroundToHtml(\Magento\Cms\Block\Block $subject, \Closure $proceed)
        {
        $logger = \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface');
        $logger->debug(__METHOD__ . ' - ' . __LINE__);


        $returnValue = $proceed();


        $logger->debug(__METHOD__ . ' - ' . __LINE__);
        exit();
        }

        public function getTitle()
        {
         $blockId = $this->getBlockId();
         $title = "";
         if ($blockId) {
             $block = $this->_blockRepository->getById($blockId);
             $title = $block->getTitle();
         }
         return $title;
    } */
}//end class

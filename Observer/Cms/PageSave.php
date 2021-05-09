<?php

namespace Matritix\AdvancedCmsFields\Observer\Cms;

class PageSave implements \Magento\Framework\Event\ObserverInterface
{  

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
         $model = $observer->getEvent()->getPage(); 
		 
            // added 
            if ($advancedform_array = $model->getData('matritix_advancedform')) { 
					$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
					
					$helpermatritix = $objectManager->get('Matritix\AdvancedCmsFields\Helper\Data');  
                    $jsonHelper = $objectManager->get('Magento\Framework\Json\Helper\Data');
                    $advancedform_array_filter = $helpermatritix->array_remove_null($advancedform_array);
					
                    $advancedform_array_filter = $helpermatritix->aasort($advancedform_array_filter, "position");
                    $advancedform_array_filter = array_values($advancedform_array_filter);

                    $advancedform_array_filter = $jsonHelper->jsonEncode($advancedform_array_filter);

                    if ($advancedform_array_filter) {
                        $model->setMatritixAdvancedform($advancedform_array_filter);
                    } 
            } //end added 
    }
 
}//end class

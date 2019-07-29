<?php

namespace Matritix\AdvancedCmsFields\Controller\Adminhtml\Cms\Page;

use Magento\Backend\App\Action;
use Magento\Cms\Model\Page;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Cms\Controller\Adminhtml\Page\Save
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Cms::save';


    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /*
         * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $data = $this->dataProcessor->filter($data);
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Page::STATUS_ENABLED;
            }

            if (empty($data['page_id'])) {
                $data['page_id'] = null;
            }

            /*
             * @var \Magento\Cms\Model\Page $model
             */
            $model = $this->_objectManager->create('Magento\Cms\Model\Page');

            $id = $this->getRequest()->getParam('page_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            // added		 
            if (isset($data['matritix_advancedform'])) {
				if($data['matritix_advancedform']){
					$advancedform_array = $data['matritix_advancedform'];

					$advancedform_array_filter = $this->array_remove_null($advancedform_array);
				 
					$jsonHelper = $this->_objectManager->get('Magento\Framework\Json\Helper\Data');
					
					$advancedform_array_filter = $this->aasort($advancedform_array_filter, "matritix_position");
					$advancedform_array_filter = array_values($advancedform_array_filter);
				 
					$advancedform_array_filter = $jsonHelper->jsonEncode($advancedform_array_filter);
				 

					if ($advancedform_array_filter) {
						$model->setMatritixAdvancedform($advancedform_array_filter);
					}
				}
            }// end added
			
            $this->_eventManager->dispatch(
                'cms_page_prepare_save',
                [
                    'page'    => $model,
                    'request' => $this->getRequest(),
                ]
            );

            if (!$this->dataProcessor->validate($data)) {
                return $resultRedirect->setPath('*/*/edit', ['page_id' => $model->getId(), '_current' => true]);
            }

            try {
                $model->save();

                $this->messageManager->addSuccess(__('You saved the page.'));
                $this->dataPersistor->clear('cms_page');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['page_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the page.'));
            }

            $this->dataPersistor->set('cms_page', $data);
            return $resultRedirect->setPath('*/*/edit', ['page_id' => $this->getRequest()->getParam('page_id')]);
        }//end if

        return $resultRedirect->setPath('*/*/');

    }//end execute()


    public function array_remove_null($array)
    {
        foreach ($array as $key => $value) {
            if ($key == "matritix_position" && $value == '') {
                 $array[$key] = '0';
            } else {
                if (is_string($value) && $value == '') {
                    unset($array[$key]);
                }

                if (is_array($value)) {
                    $array[$key] = $this->array_remove_null($value);
                }

                if (isset($array[$key]) && count($array[$key]) == 0) {
                    unset($array[$key]);
                }
            }
        }

        return $array;

    }//end array_remove_null()
	 

	public function aasort(&$array, $key)
    {
        $sorter = [];
        $ret    = [];
        reset($array);
        foreach ($array as $ii => $va) {
            $sorter[$ii] = $va[$key];
        }

        asort($sorter);
        foreach ($sorter as $ii => $va) {
            $ret[$ii] = $array[$ii];
        }

        $array = $ret;
        return $array;

    }//end aasort()


}//end class

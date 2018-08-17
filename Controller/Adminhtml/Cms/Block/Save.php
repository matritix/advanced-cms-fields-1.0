<?php

namespace Matritix\AdvancedCmsFields\Controller\Adminhtml\Cms\Block;

use Magento\Backend\App\Action\Context;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Model\Block;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

class Save extends \Magento\Cms\Controller\Adminhtml\Block\Save
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
     * @return                                       \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /*
         * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
         */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Block::STATUS_ENABLED;
            }

            if (empty($data['block_id'])) {
                $data['block_id'] = null;
            }

            /*
             * @var \Magento\Cms\Model\Block $model
             */
            $model = $this->_objectManager->create('Magento\Cms\Model\Block');

            $id = $this->getRequest()->getParam('block_id');
            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            // added
            /*
                $matritix_block_order = $this->getRequest()->getParam('matritix_block_order');
                if($matritix_block_order){
                $model->setSortOrder($matritix_block_order);
            } */
            if (isset($data['matritix_advancedform'])) {
                $advancedform_array = $data['matritix_advancedform'];

                $advancedform_array_filter = $this->array_remove_null($advancedform_array);

                if ($serializer = $this->_objectManager->create(\Magento\Framework\Serialize\SerializerInterface::class)) {
                    $advancedform_array_filter = $serializer->serialize($advancedform_array_filter);
                } else {
                    $jsonHelper = $this->_objectManager->get('Magento\Framework\Json\Helper\Data');
                    $advancedform_array_filter = $jsonHelper->jsonEncode($advancedform_array_filter);
                }

                if ($advancedform_array_filter) {
                    $model->setMatritixAdvancedform($advancedform_array_filter);
                }
            }

            // fin added
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the block.'));
                $this->dataPersistor->clear('cms_block');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['block_id' => $model->getId()]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the block.'));
            }

            $this->dataPersistor->set('cms_block', $data);
            return $resultRedirect->setPath('*/*/edit', ['block_id' => $this->getRequest()->getParam('block_id')]);
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


}//end class

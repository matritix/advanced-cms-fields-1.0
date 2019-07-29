<?php

namespace Matritix\AdvancedCmsFields\Controller\Adminhtml\Cms;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    /**
     * Image uploader
     *
     * @var \Matritix\AdvancedCmsFields\Model\ImagesUploader
     */
    protected $ImageUploader;

    protected $request;


    /**
     * @param \Magento\Backend\App\Action\Context             $context
     * @param \Matritix\AdvancedCmsFields\Model\ImagesUploader $imageUploader
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Matritix\AdvancedCmsFields\Model\ImagesUploader $ImageUploader,
        \Magento\Framework\App\Request\Http $request
    ) {
        parent::__construct($context);
        $this->ImageUploader = $ImageUploader;
        $this->request       = $request;

    }//end __construct()


    /**
     * Upload file controller action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $docattchmentse = $this->getRequest()->getFiles('matritix_advancedform');

            foreach ($docattchmentse as $docattchment) {
                $docs = $docattchment['matritix_image'];
            }

            if (isset($docs)) {
                $result = $this->ImageUploader->saveFileToTmpDir($docs);
            }

            $result['cookie'] = [
                'name'     => $this->_getSession()->getName(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = [
                'error'     => $e->getMessage(),
                'errorcode' => $e->getCode(),
            ];
        }//end try

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);

    }//end execute()


}//end class

<?php

namespace Matritix\AdvancedCmsFields\Block\Cms;

class Page extends \Magento\Cms\Block\Page
{

    protected $_pageFactory;


    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Cms\Model\Page $page,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\PageFactory $pageFactory,
        \Magento\Framework\View\Page\Config $pageConfig,
        array $data=[]
    ) {
        parent::__construct($context, $page, $filterProvider, $storeManager, $pageFactory, $pageConfig, $data);
        $this->_page           = $page;
        $this->_filterProvider = $filterProvider;
        $this->_storeManager   = $storeManager;
        $this->_pageFactory    = $pageFactory;
        $this->pageConfig      = $pageConfig;

    }//end __construct()


    protected function _toHtml()
    {
        if ($this->getPage()->getMatritixAdvancedform()) {
            $html            = '';
            $orderpage       = 0;
            $htmlContentPass = false;
            $objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
            if ($serializer = $objectManager->create(\Magento\Framework\Serialize\SerializerInterface::class)) {
                $allContent = $serializer->unserialize($this->getPage()->getMatritixAdvancedform());
            } else {
                $jsonHelper = $objectManager->get('Magento\Framework\Json\Helper\Data');
                $allContent = $jsonHelper->jsonDecode($this->getPage()->getMatritixAdvancedform());
            }

                $allContent = $this->aasort($allContent, "matritix_position");

            if ($htmlContentPass == false && !$this->getPage()->getSortOrder()) {
                $html           .= $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());
                $htmlContentPass = true;
            } else {
                $orderpage = $this->getPage()->getSortOrder();
            }

            /*
                var_dump($allContent);
                var_dump($orderpage);
            exit(); */

            foreach ($allContent as $singleContent) {
                $classesContent      = 'class="matritix_page_field"';
                $matritix_text       = '';
                $matritix_textarea   = '';
                $matritix_image      = '';
                $matritix_image_text = '';
                $matritix_file       = '';
                $matritix_file_text  = '';
                $matritix_link_href  = '';
                $matritix_link_text  = '';
                $matritix_position   = 0;

                if (isset($singleContent['matritix_class'])) {
                    $classesContent = 'class="matritix_page_field '.$singleContent['matritix_class'].'"';
                }

                if (isset($singleContent['matritix_text'])) {
                    $matritix_text = $singleContent['matritix_text'];
                }

                if (isset($singleContent['matritix_textarea'])) {
                    $matritix_textarea = $singleContent['matritix_textarea'];
                }

                if (isset($singleContent['matritix_image'])) {
                    $matritix_image = $singleContent['matritix_image'][0]['url'];
                }

                if (isset($singleContent['matritix_image_text'])) {
                    $matritix_image_text = $singleContent['matritix_image_text'];
                }

                if (isset($singleContent['matritix_file'])) {
                    $matritix_file = $singleContent['matritix_file'][0]['url'];
                }

                if (isset($singleContent['matritix_file_text'])) {
                    $matritix_file_text = $singleContent['matritix_file_text'];
                }

                if (isset($singleContent['matritix_link_href'])) {
                    $matritix_link_href = $singleContent['matritix_link_href'];
                }

                if (isset($singleContent['matritix_link_text'])) {
                    $matritix_link_text = $singleContent['matritix_link_text'];
                }

                if (isset($singleContent['matritix_position'])) {
                    $matritix_position = $singleContent['matritix_position'];
                }

                if ($htmlContentPass == false && $orderpage <= $matritix_position) {
                    $html           .= $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());
                    $htmlContentPass = true;
                }

                switch ($singleContent['matritix_selecttype']) {
                case 0:
                    $html .= '<div '.$classesContent.' >'.$matritix_text.'</div>';
                    break;
                case 1:
                    $html .= '<div '.$classesContent.' >'.$matritix_textarea.'</div>';
                    break;
                case 2:
                    $html .= '<a '.$classesContent.' href="'.$matritix_link_href.'" target="_blank" title="'.$matritix_link_text.'">'.$matritix_link_text.'</a>';
                    break;
                case 3:
                    $html .= '<img '.$classesContent.' alt="'.$matritix_image_text.'" src="'.$matritix_image.'" />';
                    break;
                case 4:
                    $html .= '<a '.$classesContent.' href="'.$matritix_file.'" target="_blank" title="'.$matritix_file_text.'">'.$matritix_file_text.'</a>';
                    break;
                }
            }//end foreach

            if ($htmlContentPass == false) {
                $html           .= $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());
                $htmlContentPass = true;
            }
        } else {
            $html = $this->_filterProvider->getPageFilter()->filter($this->getPage()->getContent());
        }//end if

		$html = '<div class="matritix_content matritix_page_content">'.$html.'</div>';
		
        return $html;

    }//end _toHtml()


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

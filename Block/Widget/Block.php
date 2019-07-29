<?php

namespace Matritix\AdvancedCmsFields\Block\Widget;


class Block extends \Magento\Cms\Block\Widget\Block
{


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider       $filterProvider
     * @param \Magento\Cms\Model\BlockFactory                  $blockFactory
     * @param array                                            $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        array $data=[]
    ) {
        parent::__construct($context, $filterProvider, $blockFactory, $data);
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory   = $blockFactory;

    }//end __construct()


    /**
     * Prepare block text and determine whether block output enabled or not
     * Prevent blocks recursion if needed
     *
     * @return $this
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $blockId   = $this->getData('block_id');
        $blockHash = get_class($this).$blockId;

        if (isset(self::$_widgetUsageMap[$blockHash])) {
            return $this;
        }

        self::$_widgetUsageMap[$blockHash] = true;

        if ($blockId) {
            $storeId = $this->_storeManager->getStore()->getId();
            /*
             * @var \Magento\Cms\Model\Block $block
             */
            $block = $this->_blockFactory->create();
            $block->setStoreId($storeId)->load($blockId);
            if ($block->isActive()) {
                // added
                if ($block->getMatritixAdvancedform()) {
                    $html            = '';
                    $orderpage       = 0;
                    $htmlContentPass = false;
                    $objectManager   = \Magento\Framework\App\ObjectManager::getInstance();
					$jsonHelper = $objectManager->get('Magento\Framework\Json\Helper\Data');
					$allContent = $jsonHelper->jsonDecode($block->getMatritixAdvancedform());
					$allContent = $this->aasort($allContent, "matritix_position");

                    if ($htmlContentPass == false && !$block->getSortOrder()) {
                        $html           .= $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
                        $htmlContentPass = true;
                    } else {
                        $orderpage = $block->getSortOrder();
                    }

                  
					$matritix_level_sorter = 0;
                        // var_dump($allContent);
                        // var_dump($orderpage);
                    // exit();  

                    foreach ($allContent as $singleContent) {
                        $classesContent      = '';
                        $matritix_text       = '';
                        $matritix_textarea   = '';
                        $matritix_image      = '';
                        $matritix_image_text = '';
                        $matritix_file       = '';
                        $matritix_file_text  = '';
                        $matritix_link_href  = '';
                        $matritix_link_text  = '';
                        $matritix_position   = 0;
						$matritix_tag   = '';
						$classesContentTag = '';
						$matritix_level = 0;
						
						if (isset($singleContent['matritix_level'])) {
                            $matritix_level = $singleContent['matritix_level'];
                        }
						
                        if (isset($singleContent['matritix_class'])) {
							$classesSimple = $singleContent['matritix_class'];
                            $classesContent = 'class="'.$singleContent['matritix_class'].'"';
							$classesContentTag = 'class="tag '.$singleContent['matritix_class'].'"';
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
                            $html           .= $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
                            $htmlContentPass = true;
                        }
						
						for ($i = $matritix_level_sorter; $i < $matritix_level; $i++) {
								$levelnumber = $i+1;
								$html .= '<div class="level'.$levelnumber.' '.$classesSimple.'">';
						}
						for ($i = $matritix_level; $i < $matritix_level_sorter; $i++) {
								$html .= '</div>';
						}

						if (isset($singleContent['matritix_tag'])) {
                            $html .= '<'.$singleContent['matritix_tag'].' '.$classesContentTag.' >';
                        }
						else{
							if($singleContent['matritix_selecttype'] == 1 || $singleContent['matritix_selecttype'] ==2){
								$html .= '<div '.$classesContent.' >';
							}
						}
						 


						
                        switch ($singleContent['matritix_selecttype']) {
							case 0:
								$html .= $matritix_text;
								break;
							case 1:
								$html .= $matritix_textarea;
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
						if (isset($singleContent['matritix_tag'])) {
                            $html .= '</'.$singleContent['matritix_tag'].'>';
                        }
						else{
							if($singleContent['matritix_selecttype'] == 1 || $singleContent['matritix_selecttype'] ==2){
								$html .= '</div>';
							}
						}
						

						$matritix_level_sorter = $matritix_level;
                    }//end foreach
					
					for ($i = 0; $i < $matritix_level_sorter; $i++) {
						$html .= '</div>';
					}				

                    if ($htmlContentPass == false) {
                        $html           .= $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
                        $htmlContentPass = true;
                    }
                } else {
                    $html = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());
                }//end if

                $this->setText(
                    $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($html)
                );
				
				$this->setDivClass($block->getData('identifier'));
            }//end if
        }//end if

        unset(self::$_widgetUsageMap[$blockHash]);
        return $this;

    }//end _beforeToHtml()
 

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

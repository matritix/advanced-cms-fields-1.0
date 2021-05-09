<?php

namespace Matritix\AdvancedCmsFields\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Options implements ArrayInterface
{


    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            0 => [
                'label' => 'Text',
                'value' => 0,
            ],
            1 => [
                'label' => 'Textarea',
                'value' => 1,
            ],
            2 => [
                'label' => 'Link',
                'value' => 2,
            ],
            3 => [
                'label' => 'Image',
                'value' => 3,
            ],
            4 => [
                'label' => 'File',
                'value' => 4,
            ],
        ];

        return $options;
    } //end toOptionArray()


}//end class
<?php
 
namespace Matritix\AdvancedCmsFields\Helper;
 
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    public function array_remove_null($array)
    {  
        foreach ($array as $key => $value) { 
		    if(is_array($value)){ 
			 if (array_key_exists('matritix_text', $value)) { 
 		 
				 if(
				 isset($value['matritix_text']) && $value['matritix_text'] == '' && 
				 isset($value['matritix_textarea']) && $value['matritix_textarea'] == '' && 
				 isset($value['matritix_link_href']) && $value['matritix_link_href'] == '' &&
				 !isset($value['matritix_image']) &&
				 !isset($value['matritix_file'])   
				 ){ 
					 unset($array[$key]);
				 }
			 }
            } 
		}
 
		foreach ($array as $key => $value) {  
            if ($key == "position" && $value == '') {
                $array[$key] = '0';
            } else {
                if (is_string($value) && $value == '') {
                    unset($array[$key]);
                }

                if (is_array($value)) {
                    $array[$key] = $this->array_remove_null($value);
                }
				
                // if (isset($array[$key]) && count($array) == 0) {
                    // unset($array[$key]);
                // }
            }
        }  
        return $array;
    } //end array_remove_null()


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
    } //end aasort()
	
	
}
define(
    [
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
    ],
    function ($,_, uiRegistry, select, modal) {
        'use strict';

        return select.extend(
            {
 
                myChange : function (elements) {
                    $(elements).change(
                        $.proxy(
                            function () {

                                var valueSelect = $(elements).val();
                                /* var parentSelect = $(elements).closest("tr").data("repeat-index"); */
                                var parentSelect = $(elements).attr('name');
                                var parentSelect = parentSelect.replace("matritix_advancedform[", "");
                                var parentSelect = parentSelect.replace("][matritix_selecttype]", "");

                                var arr = [ "matritix_text", "matritix_textarea", "matritix_link_href", "matritix_link_href", "matritix_link_text", "matritix_image", "matritix_file","matritix_file_text","matritix_image_text" ];

                                $.each(
                                    arr,
                                    function ( i, val ) {
                                        var field = uiRegistry.get("dataScope = data.matritix_advancedform." + parentSelect + "." + val + "");
										if(field){
											if (field.visibleValue == valueSelect) {
												   field.show();
											} else {
												  field.hide();
											}
										}
                                    }
                                );
                            },
                            this
                        )
                    );
					setTimeout(function(){ $(elements).trigger('change'); }, 1000);
					
                },
                /**
                 * On value change handler.
                 *
                 * @param {String} value
                 */
                onUpdate: function (value) {
					
                    return this._super();
                },
            }
        );
    }
);
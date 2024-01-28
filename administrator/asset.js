/**
 * 
 * @version             See field version manifest file
 * @package             See field name manifest file
 * @author				Gregorio Nuti
 * @copyright			See field copyright manifest file
 * @license             GNU General Public License version 2 or later
 * 
 */

// jquery no conflict declaration
var $j = jQuery.noConflict();

$j(document).ready(function() {
	
	// variables
	var colorError = '#e9322d';
	var boxShadowError = 'inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(233, 50, 45, 0.8)';
    
    // hide empty label container and remove margin
    $j('label.hidden').add('label[id*=hidden]').parent('.control-label').hide();
    $j('label.hidden').add('label[id*=hidden]').parent('.control-label').parent('.control-group').children('.controls').css('margin-left','0');
    
    // check values on input for padding and margin with 4 values
    $j('.input-padding').add('.input-margin').each(function() {
    	
    	var item_id = '#' + $j(this).attr('id');
    	
    	$j(item_id).keyup(function() {
			var content = $j(item_id).val();
			if (content) {
				var spaces = (content.split(' ').length - 1);
				if (spaces <= 2) {
					$j(item_id).css('border-color',colorError).css('box-shadow',boxShadowError);
				} else if (spaces >= 4) {
					$j(item_id).css('border-color',colorError).css('box-shadow',boxShadowError);
				} else if (spaces == 3) {
					$j(item_id).css('border-color','').css('box-shadow','');
				}
			} else {
				$j(item_id).css('border-color','').css('box-shadow','');
			}
		});
		
	});
	
	// check values on input for bootstrap columns
    $j('.input-bootstrap-columns').each(function() {
    	
    	var item_id = '#' + $j(this).attr('id');
    	
    	// allowed numbers for bootstrap columns
    	var allowedNumbers = ['1','2','3','4','6','12'];
    	
    	$j(item_id).keyup(function() {
			var content = $j(item_id).val();
			if (content) {
				if ($j.inArray(content, allowedNumbers) != '-1') {
					$j(item_id).css('border-color','').css('box-shadow','');
				} else {
					$j(item_id).css('border-color',colorError).css('box-shadow',boxShadowError);
				}
			} else {
				$j(item_id).css('border-color','').css('box-shadow','');
			}
		});
		
	});
    
});
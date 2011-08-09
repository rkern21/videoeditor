<?php
/**
* @version $Id: cb.validator.php 1437 2011-02-12 13:05:54Z beat $
* @package Community Builder
* @subpackage cb.validator.php
* @author Beat and various
* @copyright (C) 2004-2011 Beat, www.joomlapolis.com
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU/GPL version 2
*/

// no direct access
if ( ! ( defined( '_VALID_CB' ) || defined( '_JEXEC' ) || defined( '_VALID_MOS' ) ) ) { die( 'Direct Access to this location is not allowed.' ); }

/**
* Form validation support class
* @since 1.4 RC BUT experimentally only: this will become dynamic instead of static
*/
class cbValidator {
	static $methods = array();
	static $rules = null;
	static function addMethod( $name, $jsFunction ) {
		self::$methods[$name]	=	$jsFunction;
	}
	static function addRule( $rule ) {
		self::$rules	.=	$rule;
	}
	static function renderGenericJs( ) {
?>
$.extend(jQuery.validator.messages, {
		required: "<?php echo addslashes( CBTxt::T("This field is required.") ); ?>",
		remote: "<?php echo addslashes( CBTxt::T("Please fix this field.") ); ?>",
		email: "<?php echo addslashes( CBTxt::T("Please enter a valid email address.") ); ?>",
		url: "<?php echo addslashes( CBTxt::T("Please enter a valid URL.") ); ?>",
		date: "<?php echo addslashes( CBTxt::T("Please enter a valid date.") ); ?>",
		dateISO: "<?php echo addslashes( CBTxt::T("Please enter a valid date (ISO).") ); ?>",
		number: "<?php echo addslashes( CBTxt::T("Please enter a valid number.") ); ?>",
		digits: "<?php echo addslashes( CBTxt::T("Please enter only digits.") ); ?>",
		creditcard: "<?php echo addslashes( CBTxt::T("Please enter a valid credit card number.") ); ?>",
		equalTo: "<?php echo addslashes( CBTxt::T("Please enter the same value again.") ); ?>",
		accept: "<?php echo addslashes( CBTxt::T("Please enter a value with a valid extension.") ); ?>",
		maxlength: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter no more than {0} characters.") ); ?>"),
		minlength: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter at least {0} characters.") ); ?>"),
		rangelength: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter a value between {0} and {1} characters long.") ); ?>"),
		range: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter a value between {0} and {1}.") ); ?>"),
		max: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter a value less than or equal to {0}.") ); ?>"),
		min: $.validator.format("<?php echo addslashes( CBTxt::T("Please enter a value greater than or equal to {0}.") ); ?>")
});

{
	var firstInvalidFieldFound	=	0;

	$('#cbcheckedadminForm').validate( {
		ignoreTitle : true,
		errorClass: 'cb_result_warning',
		// debug: true,
		cbIsOnKeyUp: false,
		highlight: function( element, errorClass ) {
			$( element ).parents('.fieldCell').parent().addClass( 'cbValidationError' );		// tables
			$( element ).parents('.cb_field,.cb_form_line').addClass( 'cbValidationError' );	// divs
			$( element ).addClass( 'cbValidationError' + $(element).attr('type') );
			$( element ).parents('.tab-page').addClass('cbValidationErrorTab')
			.each( function() {
				$(this).siblings('.tab-row')
				.find('h2:nth-child(' + $(this).index() + ')')
				.addClass('cbValidationErrorTabTip');
			})
			.filter(':not(:visible)').each( function() {
				if ( ! firstInvalidFieldFound++ ) {
					showCBTab( $(this).attr('id').substr(5) );
				}
			});;
		},
		unhighlight: function( element, errorClass ) {
			if ( this.errorList.length == 0 ) {
				firstInvalidFieldFound = 0;
			}
			$( element ).parents('.fieldCell').parent().removeClass( 'cbValidationError' );		// tables
			$( element ).parents('.cb_field,.cb_form_line').removeClass( 'cbValidationError' );	// divs
			$( element ).removeClass( 'cbValidationError' + $(element).attr('type') );
			$( element ).parents('.tab-page')
			.each( function() {
				if ( $(this).find('.cbValidationError').size() == 0 ) {
					$(this).removeClass('cbValidationErrorTab')
					.siblings('.tab-row')
					.find('h2:nth-child(' + $(this).index() + ')')
					.removeClass('cbValidationErrorTabTip');
				}
			});
		},
		errorElement: 'div',
		errorPlacement: function(error, element) {
			element.closest('.fieldCell, .cb_field').append( error[0] );		// .fieldCell : tables, .cb_field : div
		},
		onkeyup: function(element) {
			if ( element.name in this.submitted || element == this.lastElement ) {
				// avoid remotejhtml rule onkeyup
				this.cbIsOnKeyUp = true;
				this.element(element);
				this.cbIsOnKeyUp = false;
			}
<?php
/*
		},
		showErrors: function(errorMap, errorList) {
			var messages;
			for ( var i = 0; errorList[i]; i++ ) {
				messages += errorList[i].message + "\n";
			}
			this.defaultShowErrors();
			alert( messages );
		},
        rules: { 
            username: { 
                required: true, 
                minlength: 3 //, 
                // remote: "users.php" 
            },
            password: { 
                required: true, 
                minlength: 6 
            }, 
            password_confirm: { 
                required: true, 
                minlength: 6, 
                equalTo: "#password" 
            }, 
            email: { 
                required: true, 
                email: true //, 
     			//remote: "emails.php" 
            }
        },
*/
/*
        messages: { 
        	username: { 
                required: "Please enter a username", 
                minlength: jQuery.format("Enter at least {0} characters"), 
                remote: jQuery.format("{0} is already in use") 
            },
            password: { 
                required: "Please provide a password", 
                rangelength: jQuery.format("Enter at least {0} characters") 
            }, 
            password_confirm: { 
                required: "Please repeat your password", 
                minlength: jQuery.format("Enter at least {0} characters"), 
                equalTo: "Enter the same password as above" 
            },
            email: { 
                required: "Please enter a valid email address", 
                minlength: "Please enter a valid email address" //,
                // remote: jQuery.format("{0} is already in use") 
            }
*/
?>
        }
	} );
	$('#cbcheckedadminForm input:checkbox,#cbcheckedadminForm input:radio').click( function() {
		$('#cbcheckedadminForm').validate().element( $(this) );
	} );
}
<?php
		echo implode( "\n", self::$methods ) . "\n";
		echo self::$rules;
	}
}
?>
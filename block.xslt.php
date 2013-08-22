<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {xslt}{/xslt} block plugin
 *
 * Type:     block function<br>
 * Name:     xslt<br>
 * Purpose:  Apply recursive transformations to complex data structures like
 *           nested array, classes, trees etc. Usage for simple data structures
 *           will end with great pain in the ass!<br>
 * Requires: PHP-5 with MBString, DOM, XSL extensions<br>
 *
 * @author Keeper <hd_keeper at mail dot ru>
 * @param array $params
 * <pre>
 * Params:  from: mixed - array or class containing source data to display,
 *                        ignored if 'from_xml' was provided
 *          from_xml: string - XML containing source data to display
 *          assign_xml: string - template variable the intermediate XML will be
 *                               assigned to (useful for debugging)
 *          root_tag: string - name for the root element (default "root")
 *          default_tag: string - default name for tags (default "data")
 *          key_attribute: string - attribute name for array keys (default none)
 *          encoding: string - character set to use (default "UTF-8")
 *
 * You should provide either 'from' or 'from_xml' parameter!
 * </pre>
 * @param string $content - XSL-template to apply
 * @param Smarty $smarty - clever simulation of a method
 * @return string - generated HTML/XML data
 */

function smarty_block_xslt( $params, $content, &$smarty)
{
	if (is_null( $content)) {
		return;
	}

	// Setup default parameters
	$from = null;
	$from_xml = null;
	$assign_xml = null;
	$opts = array(
		'root_tag' => "root",
		'default_tag' => "data",
		'key_attribute' => null,
		'encoding' => "UTF-8"
	);

	// Obtain provided parameters
	foreach ($params as $_key => $_val) {
		switch ($_key) {
			case 'from':
			case 'from_xml':
			case 'assign_xml':
				$$_key = $_val;
				break;

			case 'root_tag':
			case 'default_tag':
			case 'key_attribute':
			case 'encoding':
				$opts[ $_key] = $_val;
				break;

			default:
				$smarty->trigger_error( "xslt: unknown attribute '$_key'");
		}
	}
	
	// Create DOM ducument containing provided data
	$dom_data = new DOMDocument( '1.0', $opts['encoding']);
	if (!is_null( $from_xml)) {
		// Load provided raw XML-data into DOM document
		$dom_data->loadXML( $from_xml);
	} elseif (!is_null( $from)) {
		// Build DOM document from provided source data
		mb_regex_encoding( $opts['encoding']);
		smarty_block_xslt_append( $dom_data, $opts['root_tag'], $from, $opts);
	} else {
		$smarty->trigger_error( "xslt: either 'from' or 'fromxml' attribute should be provided", E_USER_ERROR);
	}
	
	// Assign intermediate XML-data to the template variable
	if (!is_null( $assign_xml)) {
		$from_xml = $dom_data->saveXML();
		$smarty->assign( $assign_xml, $from_xml);
	}
	
	// Create DOM document containing provided XSL-template
	$dom_template = new DOMDocument( '1.0', $opts['encoding']);
	$dom_template->loadXML( $content);

	// Transform XML-data to make an output XML/HTML
	$_output = "";
	$processor = new XSLTProcessor();
	$processor->importStyleSheet( $dom_template);
	$_output = $processor->transformToXML( $dom_data);

	return $_output;
}

/**
 * Recursively walk through the provided variable,
 * append all found data to the DOM document
 *
 * @access private
 * @param DOMNode $dom_node - XML-element the childs are to be appended to
 * @param string  $use_key - suggested key to be used as tag name
 * @param mixed   $data - variable to be stored in the DOM document
 * @param array   $opts - set of immutable options
 * @return void
 */
function smarty_block_xslt_append( DOMNode &$dom_node, $use_key, &$data, &$opts)
{
	// For objects, use class name as tags
	$use_tag = is_object($data) ? get_class( $data) : $use_key;
	// Use default tag name instead of invalid tag names
	if (!mb_ereg_match( '^[[:alpha:]_][[:alnum:]:_\.\-]*$', $use_tag))
		$use_tag = $opts['default_tag'];
	if  (is_array($data) || is_object($data)) {
		// Add a node for entire array or object
		$dom_child = $dom_node->appendChild( new DOMElement(
			smarty_block_xslt_conv( $use_tag, $opts)
		));
		// Enumerate array elements or object properties
		foreach ($data as $_key => $_val) {
			// Recurrent call for array element or object property
			smarty_block_xslt_append( $dom_child, $_key, $_val, $opts);
		}
	} else {
		// Plain data is added as leaf node
		$dom_child = $dom_node->appendChild( new DOMElement(
			smarty_block_xslt_conv( $use_tag, $opts),
			htmlspecialchars( smarty_block_xslt_conv( $data, $opts), ENT_COMPAT, "UTF-8")
		));
	}
	// Store key as attribute if required
	if (!empty( $opts['key_attribute'])) {
		$dom_child->setAttribute(
			smarty_block_xslt_conv( $opts['key_attribute'], $opts),
			smarty_block_xslt_conv( $use_key, $opts)
		);
	}
}

/**
 * Convert data string to internal encoding of DOM document (UTF-8)
 *
 * @access private
 * @param string $str - data to be converted
 * @param array  $opts - set of immutable options
 * @return string - data converted to internal encoding
 */
function smarty_block_xslt_conv( $str, &$opts)
{
	if (!mb_check_encoding( (string) $str, $opts['encoding']))
		trigger_error( "xslt: data string '$str' has invalid encoding", E_USER_WARNING);
	return mb_convert_encoding( (string) $str, "UTF-8", $opts['encoding']);
}
?>
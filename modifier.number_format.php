<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty number_format modifier plugin
 *
 * Type:     modifier
 * Name:     number_format
 * Purpose:  format numbers via number_format
 *
 * @author Keeper <hd_keeper at mail dot ru>
 * @param  float   $number
 * @param  integer $decimals - number of decimal points
 * @param  string  $dec_point - separator for the decimal point
 * @param  string  $thousands_sep - separator for thousands
 * @return string
 */
function smarty_modifier_number_format( $number, $decimals = 0, $dec_point = '.', $thousands_sep = ',')
{
    return number_format( $number, $decimals, $dec_point, $thousands_sep);
}

?>
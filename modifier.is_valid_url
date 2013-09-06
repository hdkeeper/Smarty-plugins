<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     modifier.is_valid_url.php
 * Type:     modifier
 * Name:     is_valid_url
 * Purpose:  Validates string, return false if string is not valid url PHP 5.2+
 * -------------------------------------------------------------
 */
function smarty_modifier_is_valid_url($string)
{
    return filter_var($string, FILTER_VALIDATE_URL);
}

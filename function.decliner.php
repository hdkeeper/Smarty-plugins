<?php
/**
 * Smarty {decliner} function plugin
 *
 * Type:     function<br>
 * Name:     decliner<br>
 * Purpose:  handle word declines based on quantity number<br>
 * Usage;    {decliner qty=$number word='товара,товаров,товаров'}
 * @author   Dmitriy Soloduhin <darkomen86 at gmail dot com> (credits to http://mcaizer.habrahabr.ru/blog/11555/) 
 * @param array $params
 * <pre>
 * Params:  qty: number of items to choose correct declining form
 *          word: declining forms of word. Ex: день, дня, дней.
 * </pre>
 * @param Smarty
 * @return string
 */
  function smarty_function_decliner($params, &$smarty)
  {
      // be sure equation parameter is present
      if (empty($params['qty']))
            $params['qty']=0;
      if (empty ($params['word'])) {
            $smarty->trigger_error("decliner: missing required parameter");
            return;
      }
      $forms=explode(',',$params['word']);
      $params['qty'] = abs($params['qty']) % 100;
      $n1 = $params['qty'] % 10;
      if ( $params['qty'] > 10 &&  $params['qty'] < 20) return $forms[2];
      else if ($n1 > 1 && $n1 < 5) return $forms[1];
      else if ($n1 == 1) return $forms[0];
      return $forms[2];
  }

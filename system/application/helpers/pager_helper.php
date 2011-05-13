<?php

function initPagination($rows, $class, &$view)
{
  // Initializing pagination
  $class->load->helper('url');
  $class->load->library('pagination');
  $itemsCount = count($rows);
  $base_url = site_url(array($class->router->fetch_class(), $class->router->fetch_method()));
  $config['base_url'] = $base_url;
  $config['total_rows'] = $itemsCount;
  $class->pagination->initialize($config);
  $pagerViewData = array(
    'pagination' => $class->pagination,
    'baseUrl' => $base_url
  );
  $pagerView = $class->load->view('main/elements/pager', $pagerViewData, true);
  $view['pager'] = $pagerView;

  // Cur page
  $itemN = (int)$class->uri->segment($class->pagination->uri_segment);
  $curPage = getPageByItemN($itemN, $class->pagination->per_page);

  // Creating paginated results
  $sliceStart = ($curPage - 1) * $class->pagination->per_page;
  $sliceLength = $class->pagination->per_page;

  return array_slice($rows, $sliceStart, $sliceLength);
}

?>

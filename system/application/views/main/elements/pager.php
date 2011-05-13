<?php
function getPageByItemN($itemN, $perPage)
{
  return (int)($itemN / $perPage) + 1;
}
function getItemNByPage($page, $perPage)
{
  $res = ($page - 1) * $perPage;
  if ($res == 0)
  {
    return null;
  }
  else
  {
    return $res;
  }
}
$baseUrl = rtrim($baseUrl, "/");
$itemN = (int)$this->uri->segment($pagination->uri_segment);
$perPage = $pagination->per_page;
$curPage = getPageByItemN($itemN, $perPage); //$pagination->cur_page;
$itemsCount = $pagination->total_rows;
// Generating array of page numbers
$pageNumbers = array();
$j = 1;
for($i = 0; $i < $itemsCount; $i++)
{
  if ($i % $perPage == 0)
  {
    $pageNumbers[] = $j;
    $j++;
  }
}

?>

<div class='pages'><div class='block'><div class='inner-block'>

<?php
$c = 0;
foreach($pageNumbers as $n)
{
  $class = '';
  if (($c == 0) || ($c == count($pageNumbers) - 1)) $class = 'endpoints';
  if ($n == $curPage)
  {
    echo '<span>'.$n.'</span>';
  }
  else if (($n == $curPage - 1) ||
           ($n == $curPage + 1))
  {
    echo '<a class="'.$class.'" href="'.$baseUrl.'/'.getItemNByPage($n, $perPage).'">'.$n.'</a>';
  }
  else if ((($n == $curPage - 2) && ($n != 1)) ||
           (($n == $curPage + 2) && ($n != count($pageNumbers))) )
  {
    echo '<span>...</span>';
  }
  else if (($n <= 3) ||
           ($n > count($pageNumbers) - 3))
  {
    echo '<a class="'.$class.'" href="'.$baseUrl.'/'.getItemNByPage($n, $perPage).'">'.$n.'</a>';
  }
  $c++;
}
?>

<?php if (false):?>
<a href='#' class='endpoints'><?=$pageNumbers[0]?></a>
<a href='#'><?=$pageNumbers[1]?></a>
<a href='#'><?=$pageNumbers[2]?></a>

<span>...</span>

<a href='#'>17</a>

<span><?=$pageNumbers[$curPage]?></span>

<a href='#'>19</a>

<span>...</span>

<a href='#'><?=$pageNumbers[count($pageNumbers)-3]?></a>
<a href='#'><?=$pageNumbers[count($pageNumbers)-2]?></a>
<a href='#' class='endpoints'><?=$pageNumbers[count($pageNumbers)-1]?></a>
<?php endif ?>

</div></div></div>
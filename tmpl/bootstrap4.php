<?php
/*
* This template uses Bootstrap 4 styles.
* @version		1.0
* @package		JoomGallery Category With Description Content Plugin 'JoomGalCat'
* @copyright	Copyright (C) 2019 Sergey Tolkachyov
* @license		GNU/GPL http://www.gnu.org/licenses/gpl-2.0.html
*
*
*/

?>
<div class="product-ustanovki border-1 row mb-5 mt-5 ml-1 p-1" >
<div class="col-12 col-sm-12 col-md-3 col-lg-3 text-center ">
<img class="img-fluid img-thumbnail" src="<?php echo $imglink;?>" alt="<?php echo $imgAltTag;?>">
</div>
<div class="col-12 col-sm-12 col-md-9 col-lg-9">
<h5 class="text-center"><?php echo $catname;?></h5>
<?php echo $catdesc;?>
<div class="text-center">
<a class="btn btn-sm btn-sin-blue" href="<?php echo $catlink;?>"><?php echo JText::_("PLG_JGCAT_TMPL_VIEW_MORE_TEXT");?></a>
</div>
</div>
</div>

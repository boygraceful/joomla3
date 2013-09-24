<?php
/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      Duc Nguyen <ducntv@gmail.com>
 * @author      Hiepvu <vqhiep2010@gmail.com>
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */
defined('_JEXEC') or die;

?>
<?php foreach($this->articles as $a) : ?>
<?php
$link = ContentHelperRoute::getArticleRoute($a['id']);
?>
<h3><a href="<?php echo $link?>"><?php echo $a['title']?></a></h3>
<p class="lead"><?php echo $a['introtext']?></p>
<a class="btn btn-small btn-success" href="<?php echo $link?>">Read more</a>
<hr />
<?php endforeach; ?>
<?php
defined ('_JEXEC') or die ('resticted aceess');
//access zo2 framework
/** @var Zo2Framework $zo2 */
$zo2 = $this->zo2;
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <jdoc:include type="head" />
        <?php echo $this->zo2->addHead(); ?>
    </head>
    <body>
        <div class="container">
            <jdoc:include type="modules" name="top" style="xhtml"/>
            <?php echo $this->zo2->displayMegaMenu('mainmenu', $this->template); ?>
            <jdoc:include type="component" />
            <jdoc:include type="modules" name="bottom" />
        </div>
    <jdoc:include type="modules" name="debug" />
    </body>
</html>
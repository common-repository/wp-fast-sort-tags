
<div class="wrap">
    <h2><?php _e('Display', 'wpfastsorttags') ?></h2>

    <?php if(isset($page_created)): ?>
    <p><?php _e('The page was successfully created.', 'wpfastsorttags') ?> </p>
    <?php endif; ?>
    <?php if(isset($page_deleted) && $page_deleted): ?>
    <p><?php _e('The page was successfully deleted.', 'wpfastsorttags') ?> </p>
    <?php endif; ?>
    <?php if(isset($page_deleted) && !$page_deleted): ?>
    <p><?php _e('Error in moving to trash...', 'wpfastsorttags') ?> </p>
    <?php endif; ?>

    <p><?php _e('This tool is to create a page with the different posts sorted by tags.', 'wpfastsorttags') ?></p>

    <?php
    if(!$this->displayPageExists()) : ?>

	<p>Title of your page :  <br /></p>
	<form action="" method="post" accept-charset="utf-8">
				
        <input type="text" name="page_title" value="<?= get_option('wpfst_pagetitle')?>" size="55" />
        <input type="hidden" name="create_display_page" value="true" />
        <input type="submit" value="<? _e('Create Page', 'wpfastsorttags') ?>" />
    </form>
	
	
    <?php else: ?>

    <p>
            <?= _e('Do you want to delete the display page ?', 'wpfastsorttags') ?>
    </p>

    <form action="" method="post" accept-charset="utf-8">
        <input type="hidden" name="delete_display_page" value="true" />
        <input type="submit" value="<? _e('Delete Page', 'wpfastsorttags') ?>" />
    </form>

    <?php endif; ?>

	
</div>

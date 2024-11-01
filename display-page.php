<?php


/* Returns the first letter in UPPERCASE */
function first_letter_upper($string) {
    return strtoupper($string[0]);
}


	$tags=array();

	/* Get the list of all tags
	 *
	 */

	$myterms = get_terms('post_tag');
	foreach($myterms as $term){
	if($term->term_id != 1) //The first tag is "uncategorized" by default
	$tags[]=$term->name;
	}
	
	
	/* Get the list of the Initials of the tags.
     * 
     */

    $letter_used = array();
	
	
	foreach($tags as $tag) {
        $first_letter = first_letter_upper($tag);
        $letter_used[$first_letter] = true;
    };
	
	
    /*
     * Now we create an anchor for each letter
     */
	 ?>

<p><b> <?php _e('Tag List','wpfastsorttags'); ?> </b></p>

    <?php
    for($c=ord('A'); $c <= ord('Z'); $c++) {
        $letter = chr($c);
        if (isset($letter_used[$letter]))
            echo " <a href='#$letter'>$letter</a> ";
        else
            echo " $letter ";
    };
	
	
	 // Sort it by lexical order, so that they appear that way
    natcasesort($tags);
    $current_letter = null;

    foreach($tags as $tag) {
        if ($current_letter != first_letter_upper($tag)) {
            $letter = first_letter_upper($tag);
            echo "<h3><a name='$letter'>$letter</a></h3>";
            $current_letter = $letter;
        }
        
		
	// The current url + a get statement with the tag's name
		$url = get_bloginfo('url').'/?tag='.sanitize_title($tag);
        echo "<p><a href='$url'>$tag</p>";
    }
	
?>

<?php 
$recipeId = $recipe['Recipe']['id'];
$scale = 1; // default no scaling
$numberOfReviews = 0;
$averageRating = 0;
if (isset($servings)) {
    $scale = $servings / $recipe['Recipe']['serving_size'];
} else {
    $servings = $recipe['Recipe']['serving_size'];
}


if (isset($recipe['Review'])) {
    $numberOfReviews = count($recipe['Review']);
    if ($numberOfReviews > 0) {
        foreach ($recipe['Review'] as $review) {
            $averageRating += $review['rating'];
        }
        $averageRating = $averageRating / $numberOfReviews;
    }
}
?>
<script type="text/javascript">
    $(function() {
        $('#qtipSource').qtip({ // Grab some elements to apply the tooltip to
            content: {
                text: $('#qtipSourceData').html()
            },
            style: { classes: 'qtip-dark' }
        });
        
        $('#viewRefresh').click(function() {
            var newServings = $('#viewServings').val();
            ajaxNavigate("recipes/view/<?php echo $recipeId;?>/" + newServings);
            return false;
        })
        
        $('#doubleRefresh').click(function() {
            var newServings = $('#viewServings').val() * 2;
            ajaxNavigate("recipes/view/<?php echo $recipeId;?>/" + newServings);
            return false;
        })
        
        $('.rateit').rateit();
    });
    
    function loadImage(imageUrl, caption) {
        $('#selectedRecipeImage img').attr('src', imageUrl).attr('title', caption);
        return false;
    }
</script>






<?php
//Check for recipecard format
$UseBakers=0;
$UseRecipeCardFormat=0;
$directions_array=explode(PHP_EOL, $recipe['Recipe']['directions']);
$directions_array[0]=trim($directions_array[0]);
if (substr($directions_array[0],0,3)=="|x|")
{
	$UseRecipeCardFormat=1;
	$UseBakers=0;
}

if (!$UseRecipeCardFormat):

?>
<?php echo "dump here:<BR>:".$this->element('sql_dump'); ?>
<div class="recipes view">
    <h2><?php echo h($recipe['Recipe']['name']); ?> (orig theme)</h2>
        <div class="rateit" 
             data-rateit-value="<?php echo $averageRating;?>" 
             title="<?php echo __("$averageRating out of 5 stars");?>"
             data-rateit-ispreset="true" 
             data-rateit-readonly="true">
        </div> 
        <?php echo $this->Html->link($numberOfReviews . ' ' . __('Review(s)'), array('controller'=>'reviews', 'action' => 'index', $recipeId)); ?>
        <div class="actions">
            <ul>
                <?php if ($loggedIn):?>
                <li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $recipeId)); ?></li>
                <li><?php echo $this->Html->link(__('Add Review'), array('controller'=>'reviews', 'action' => 'edit', $recipeId)); ?></li>
                <li><?php echo $this->Html->link(__('Add to shopping list'), array('controller' => 'shoppingLists', 'action' => 'addRecipe', 0, $recipeId, $servings)); ?></li>
                <?php endif;?>
                <li><a href="#" onclick="window.print();"><?php echo __('Print');?></a></li>
                <?php if ($loggedIn) :?>
                <li><button id="moreActionLinks">More Actions...</button></li>
                <?php endif;?>
            </ul>
            <div style="display: none;">
                <ul id="moreActionLinksContent">
                    <li><?php echo $this->Html->link(__('Import'), array('controller' => 'import', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
                    <li><?php echo $this->Html->link(__('Export'), array('controller' => 'export', 'action' => 'edit'), array('class' => 'ajaxNavigation')); ?> </li>
                </ul>
            </div> 
        </div>
	<dl class="float50Section">
		<dt><?php echo __('Ethnicity'); ?></dt>
		<dd>
                        <?php echo h($recipe['Ethnicity']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Base Type'); ?></dt>
		<dd>
                        <?php echo h($recipe['BaseType']['name']); ?>
                        &nbsp;
		</dd>
		<dt><?php echo __('Course'); ?></dt>
		<dd>
			<?php echo h($recipe['Course']['name']); ?>
                        &nbsp;
		</dd>
		<dt><?php echo __('Preparation Time'); ?></dt>
		<dd>
			<?php echo h($recipe['PreparationTime']['name']); ?>
                        &nbsp;
		</dd>
		<dt><?php echo __('Difficulty'); ?></dt>
		<dd>
			<?php echo h($recipe['Difficulty']['name']); ?>
                        &nbsp;
		</dd>
		<dt><?php echo __('Serving Size'); ?></dt>

        </dl>

        <dl class="float50Section">
		<dt><?php echo __('Comments'); ?></dt>
		<dd>
			<?php echo h($recipe['Recipe']['comments']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Source'); ?></dt>
		<dd>
                    <a href="#" onclick="return false;" id="qtipSource"><?php echo $recipe['Source']['name'];?></a>
                    <div id="qtipSourceData" class="hide">
                        <?php echo $recipe['Source']['description'];?>
                    </div>
                    &nbsp;
		</dd>
		<dt><?php echo __('Source Description'); ?></dt>
		<dd>
			<?php echo h($recipe['Recipe']['source_description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Last Modified'); ?></dt>
		<dd>
			<?php echo h($recipe['Recipe']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
                    <?php echo h($recipe['User']['name']); ?>
		</dd>
	</dl>
        
        <div class="clear"/><br/>
        <hr/>
        <br/>
            <?php 
                $imageCount = (isset($recipe) && $recipe['Image'])? count($recipe['Image']) : 0;
                if ($imageCount > 0) {
                    echo '<div class="float50Section">';

                } else {
                    echo  '<div style="width: 100%;">';
                }
            ?>
            <b><?php echo __('Ingredients'); ?></b>
            <table>
                <?php for ($i = 0; $i < count($recipe['IngredientMapping']); $i++) :
                            $quantity = $recipe['IngredientMapping'][$i]['quantity'];
                            if ($quantity) {
                                if (isset($scale)) $quantity *= $scale;
                                $quantity = $this->Fraction->toFraction($quantity);
                                $unit = $recipe['IngredientMapping'][$i]['Unit']['abbreviation'];
                            } else {
                                $quantity = '';
                                $unit = '';
                            }
                            $ingredientName = $recipe['IngredientMapping'][$i]['Ingredient']['name'];
                            $qualifier = $recipe['IngredientMapping'][$i]['qualifier'];
                            $note = $recipe['IngredientMapping'][$i]['note'];
                            $optional = $recipe['IngredientMapping'][$i]['optional'] ? __('(optional)') : "";
                ?>
                <tr>
                    <td class="ingredientViewUnit"><?php echo "$quantity $unit";?></td>
                    <td><?php echo "$qualifier <b>$ingredientName</b> <i>$optional</i> ";?></td>
                    <td>
                        <?php if ($note) :?>
                        <div class="alert alert-info" role="alert"><?php echo "$note"; ?></div>
                        <?php endif;?>
                    </td>
                    
                </tr>
                <?php endfor; ?>
            </table>
        </div>
        <div class="float50Section" id="imagePreview">
            <?php 
            $baseUrl = Router::url('/');
            if ($imageCount > 0) {
                $imageName = $recipe['Image'][0]['attachment'];
                $imageDir = $recipe['Image'][0]['dir'];
                $imagePreview =  preg_replace('/(.*)\.(.*)/i', 'preview_${1}.$2', $imageName);
                $imageCaption = $recipe['Image'][0]['name'];
                
                echo "<div id='selectedRecipeImage'>";
                echo '<a href="#"><img src="' . $baseUrl . 'files/image/attachment/' .  $imageDir . '/' . 
                            $imagePreview . '" title="' . $imageCaption . '"/></a><br/>';
                echo "</div>";
                echo "<div id='previewImageOptions'>";
                if ($imageCount > 1) {
                    
                    for ($imageIndex = 0; $imageIndex < $imageCount; $imageIndex++) {
                        $imageName = $recipe['Image'][$imageIndex]['attachment'];
                        $imageDir = $recipe['Image'][$imageIndex]['dir'];
                        $imageThumb =  preg_replace('/(.*)\.(.*)/i', 'thumb_${1}.$2', $imageName);
                        $imagePreview =  preg_replace('/(.*)\.(.*)/i', 'preview_${1}.$2', $imageName);
                        $imageCaption = $recipe['Image'][$imageIndex]['name'];
                        
                        $previewUrl = $baseUrl . 'files/image/attachment/' .  $imageDir . '/' . $imagePreview;
                        echo '<a href="#" onclick=\'loadImage("' . $previewUrl. '", "'. $imageCaption . '");\'><img src="' . $baseUrl . 'files/image/attachment/' .  $imageDir . '/' . 
                                $imageThumb . '" title="' . $imageCaption . '"/></a>';
                    }
                    
                }
                echo "</div>";
            }?>
        </div> 
        <div class="clear"/><br/>    
        <div style="width: 100%;">
            <b><?php echo __('Directions'); ?></b>

            <pre><?php echo h($recipe['Recipe']['directions']); ?></pre>
        </div>
        
        <?php foreach ($recipe['RelatedRecipe'] as $related) :?>
            <div class="clear"/><br/> 
            <div class="relatedRecipe">
                <span>
                <?php echo $this->Html->link($related['Related']['name'], array('controller' => 'recipes', 'action' => 'view', $related['recipe_id']), 
                                array('class' => 'ajaxNavigationLink')); ?>
                        (<?php echo $related['required'] == "1" ? "required" : __('optional');?>)
                </span>   
                <div class="float50Section">
                    <b><?php echo __('Ingredients'); ?></b>
                    
                    <table>
                    <?php for ($i = 0; $i < count($related['Related']['IngredientMapping']); $i++) :
                            $quantity = $related['Related']['IngredientMapping'][$i]['quantity'];
                            if (isset($scale)) $quantity *= $scale;
                            $quantity = $this->Fraction->toFraction($quantity);
                            $unit = $related['Related']['IngredientMapping'][$i]['Unit']['abbreviation'];
                            $ingredientName = $related['Related']['IngredientMapping'][$i]['Ingredient']['name']; 
                            $qualifier = $related['Related']['IngredientMapping'][$i]['qualifier'];
                            $optional = $related['Related']['IngredientMapping'][$i]['optional'] ? __('(optional)') : "";
                        ?>
                        <tr>
                            <td class="ingredientViewUnit"><?php echo "$quantity $unit";?></td>
                            <td><?php echo "$qualifier <b>$ingredientName</b> <i>$optional</i>";?></td>
                            <td>
                                <?php if ($note) :?>
                                <div class="alert alert-info" role="alert"><?php echo "$note"; ?></div>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endfor;?>
                    </table>
                </div>
                <div class="float50Section">
                    <!-- placeholder for related recipe image -->
                </div>
                <div class="clear"/><br/>    
                <div style="width: 100%;">
                    <b><?php echo __('Directions'); ?></b>
                    <pre><?php echo $related['Related']['directions'];?></pre>
                </div>
            </div>
        <?php endforeach; ?>
        </pre>
</div>
<?php 


else:
//UseRecipeCardFormat=1
	//Remove the |x| at the beginning
	$directions_array[0]=substr($directions_array[0],3);
	//Remove the last | at the end
	if (substr($directions_array[0],-1)=="|")
	{
		$directions_array[0]=substr($directions_array[0], 0, -1);
	}
	/*
	First array element of directions should look like |x|0,1-0|2,3,4-1|
	Copy that element and explode it into map_array
	*/
	$map_array=explode("|",$directions_array[0]);
	//Remove initial element and then reindex, rename as directions_array2
	unset($directions_array[0]); // remove item at index 0
	$directions_array = array_values($directions_array); // 'reindex' array
	$directions_array2=array();
	foreach ($directions_array as $key=>$val)
	{
		$directions_array2[$key]=array("directions_text"=>$val, "ingreds_array"=>array());
	}
	//Debugger::dump($directions_array2, $depth = 3);
	//Debugger::dump($recipe['IngredientMapping'], $depth = 3);
	/*
	IngredientMapping:
	array(
	(int) 0 => array(
		'recipe_id' => '141',
		'ingredient_id' => '67',
		'quantity' => '1.5',
		'unit_id' => '11',
		'qualifier' => 'divided',
		'note' => '',
		'optional' => false,
		'sort_order' => '0',
		'id' => '489',
		'Ingredient' => array(
			'name' => 'Granulated Sugar'
		),
		'Unit' => array(
			'name' => 'Cup',
			'abbreviation' => 'c'
		)
	),
	
	*/
	//Check for blank lines in directions_array2.  If found, remove and reindex
	if(is_array($directions_array2))
	{
		//echo "count before: ".count($directions_array2)."<BR>";
		//Debugger::dump($directions_array2, $depth = 3);
		$empty_found=0;
		foreach ($directions_array2 as $key=>$val)
		{
			if (empty($directions_array2[$key]['directions_text']) || is_null($directions_array2[$key]['directions_text']))
			{
					unset($directions_array2[$key]); // remove item at index 0
					$empty_found=1;
			}
			//$directions_array2[$key]=preg_replace( "/\r|\n/", "", $val );
		}
		if ($empty_found)
		{
			$directions_array2 = array_values($directions_array2); // 'reindex' array
		}
		//echo "count after: ".count($directions_array2)."<BR>";
	}
	//Map the ingredients to the directions based on map_array
	foreach ($map_array as $map_key=>$map_val)
	{
		//echo "map_array_key: $map_key map_array_val: $map_val<BR>";
		if(strstr($map_val,"-"))
		{
			$map_dir_array=explode("-",$map_val);
			$map_dir_num=$map_dir_array[1];
			$ingreds_for_one_step=explode(",",$map_dir_array[0]);
			//echo "For direction #".$map_dir_num.", use ingredients: ";
			if(is_array($ingreds_for_one_step))
			{
				if (array_key_exists($map_dir_num, $directions_array))
				{
					$directions_array2[$map_dir_num]["ingreds_array"]=$ingreds_for_one_step;
				}
			}
			//echo "<BR>";
		}
		else
		{
			echo "An error occurred, there are ingredients with no matched steps.  Proper format is |0,1,2-6| where 0, 1 and 2 are ingredients in order, ".
				"and 6 is the 6th step (steps and ingredients start at 0 not 1)<BR>";
		}
	}
	?>
<h2><?php echo h($recipe['Recipe']['name']); ?></h2>
	<?php
	//Show reviews
	 
	if (isset($recipe['Review']) && count($recipe['Review'])>0) 
	{
		echo __('Reviews: ');
		//Debugger::dump($recipe['Review'], $depth = 3);
    	//$numberOfReviews = count($recipe['Review']);
    	//for ($i=0;$i<count($recipe['Review']);$i++)
    	//echo $this->Html->link(__('Edit'), array('action' => 'edit', $review['Review']['recipe_id'], $review['Review']['id']), array('class' => 'ajaxNavigation'));
    
		foreach ($recipe['Review'] as $key=>$val)
    	{
    		echo "<div class=\"rateit\" data-rateit-value=\"".$val['rating']."\" \n". 
             	"title=\"".$val['rating']."out of 5 stars\" data-rateit-ispreset=\"true\" \n".
             	"data-rateit-readonly=\"true\">\n".
        	"</div>\n"; 
    	 	echo $val['comments']."<BR>\n";
    	 	//echo $this->Html->link(__('Edit'), array('action' => 'edit', $review['Review']['recipe_id'], $review['Review']['id']), array('class' => 'ajaxNavigation'));
    	}
	}
	//Show comments
	if(isset($recipe['Recipe']['comments']) && count($recipe['Recipe']['comments'])>0)
	{
		echo __('Comments: '); 
		echo h($recipe['Recipe']['comments']);
	}
	//Show nav menu
	?>
	<div class="actions">
		<ul>
		<?php if ($loggedIn):?>
			<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $recipeId)); ?></li>
			<li><?php echo $this->Html->link(__('Add Review'), array('controller'=>'reviews', 'action' => 'edit', $recipeId)); ?></li>
			<li><?php echo $this->Html->link(__('Add to shopping list'), array('controller' => 'shoppingLists', 'action' => 'addRecipe', 0, $recipeId, $servings)); ?></li>
			<?php endif;?>
			<li><a href="#" onclick="window.print();"><?php echo __('Print');?></a></li>
			<?php if ($loggedIn) :?>
			
			<?php endif;?>
			<li><button id="moreActionLinks">More Actions...</button></li>
		</ul>
		<div style="display: none;">
			<ul id="moreActionLinksContent">
				<li><?php echo $this->Html->link(__('Import'), array('controller' => 'import', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
				<li><?php echo $this->Html->link(__('Export'), array('controller' => 'export', 'action' => 'edit'), array('class' => 'ajaxNavigation')); ?> </li>
			</ul>
		</div> 
	</div>
	<dl class="float50Section">
		<dt><?php echo __('Serving Size'); ?></dt>
		<dd>
                    <input type="text" id="viewServings" value="<?php echo $servings;?>"/>
                    <a id="viewRefresh" href="#"><?php echo __('Refresh');?></a> /
                    <a id="doubleRefresh" href="#"><?php echo __('Double it');?></a>
		</dd>
	</dl>
	<table id="recipecard">
	<tr>
		<th>INGREDIENT</th>
		<th>MODIFIER</th>
		<th>VOLUME</th>
		<th>WEIGHT</th>    
		<?php if ($UseBakers): ?><th>BAKERS %</th><?php endif; ?>
		<th>DIRECTIONS</th>
	</tr>
	<?php
	
 	foreach ($directions_array2 as $key=>$val)
 	{
 		//echo "key: $key val: $val<BR>";
 		//Debugger::dump($recipe['IngredientMapping']);
 		//Debugger::dump($recipe['IngredientMapping'][$key]);
 		$count_ingred=count($val['ingreds_array']);
 		
 		$directions_text=$val['directions_text'];
 		//echo "key: $key count_ingred: $count_ingred directions_text: $directions_text<BR>";
 		if ($count_ingred==0)
 		{
 			$colspan=4;
 			if ($UseBakers)
 			{
 				$colspan=5;
 			}
 			$linetext="<TR class=\"rc_emptyrow\"><TD colspan=$colspan></TD><TD>$directions_text</TD></TR>\n";
 		}
 		elseif ($count_ingred==1)
 		{
 			$ingred_num=$val['ingreds_array'][0];
 			$quantity = $recipe['IngredientMapping'][$ingred_num]['quantity'];
 			if (isset($scale)) $quantity *= $scale;
 			$quantity = $this->Fraction->toFraction($quantity);
        	$unit = $recipe['IngredientMapping'][$ingred_num]['Unit']['abbreviation'];
        	if (isset($recipe['IngredientMapping'][$ingred_num]['Ingredient']['qualifier']))
        	{
        		$qualifier=$recipe['IngredientMapping'][$ingred_num]['qualifier'];
        	}
        	else
        	{
        		$qualifier=NULL;
        	}
 			//echo "ingred_num (=1): $ingred_num qty: $quantity units: $unit<BR>";
			//$ingred_num=$val['ingreds_array'][$ingred_num];
 			$linetext="\t<TR class=\"rc_singlerow\">\n\t\t<TD>".$recipe['IngredientMapping'][$ingred_num]['Ingredient']['name']."</TD>".
 				"<TD>$qualifier</TD>".
 				"<TD></TD><TD>$quantity $unit</TD>";
 			if ($UseBakers) 
 			{ 
 				$linetext.="<TD></TD>"; 
 			}
 			$linetext.="<TD>$directions_text</TD></TR>\n";
 			$quantity=NULL;
 			$unit=NULL;
 		}
 		elseif ($count_ingred>1)
 		{
 			for ($i=0;$i<$count_ingred;$i++)
 			{
 				$ingred_num=$val['ingreds_array'][$i];
 				$quantity = $recipe['IngredientMapping'][$ingred_num]['quantity'];
 				if (isset($scale)) $quantity *= $scale;
 				$quantity = $this->Fraction->toFraction($quantity);
 				$unit = $recipe['IngredientMapping'][$ingred_num]['Unit']['abbreviation'];
				$ingredientName = $recipe['IngredientMapping'][$ingred_num]['Ingredient']['name'];
            	$qualifier = $recipe['IngredientMapping'][$ingred_num]['qualifier'];
            	if (isset($recipe['IngredientMapping'][$ingred_num]['qualifier']))
        		{
        			$qualifier=$recipe['IngredientMapping'][$ingred_num]['qualifier'];
        		}
        		else
        		{
        			$qualifier=NULL;
        		}
            	$optional = $recipe['IngredientMapping'][$ingred_num]['optional'] ? __('(optional)') : "";
 				//echo "ingred_num (>1): $ingred_num qty: $quantity units: $unit name: $ingredientName<BR>";
 			
 				if ($i==0)
 				{
 					$linetext="\t<TR class=\"rc_multirow_first\">\n\t\t<TD>".$recipe['IngredientMapping'][$ingred_num]['Ingredient']['name']."</TD>".
 						"<TD>".$qualifier."</TD>".
 						"<TD></TD><TD>$quantity $unit</TD>";
 					if ($UseBakers) 
 					{ 
 						$linetext.="<TD></TD>"; 
 					}	
 					$linetext.="<TD rowspan=$count_ingred>$directions_text</TD></TR>\n";
 				}
 				elseif ($i>0 && $i<($count_ingred))
 				{
 					$linetext.="\t<TR class=\"rc_multirow_nonfirst\">\n\t\t<TD>".$recipe['IngredientMapping'][$ingred_num]['Ingredient']['name']."</TD>".
 						"<TD>".$qualifier."</TD>".
 						"<Td></TD>".
 						"<TD>$quantity $unit</TD>";
 					if ($UseBakers) 
 					{
 						$linetext.="<TD></TD>"; 
 					}
    				$linetext.="\t</TR>\n";
 				}
 			}
		}
 		echo $linetext;
 		$quantity=NULL;
		$unit=NULL;
	}
?>
	</TABLE>
<?php endif;
?>

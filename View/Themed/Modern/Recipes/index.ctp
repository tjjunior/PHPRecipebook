<script type="text/javascript">
    $(function() {
        setSearchBoxTarget('Recipes');
        
        $(document).on("saved.ethnicity", function() {
            $('#editEthnicityDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).on("saved.baseType", function() {
            $('#editBaseTypeDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).on("saved.course", function() {
            $('#editCourseDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).off("savedPreparationTime.recipes");
        $(document).on("savedPreparationTime.recipes", function() {
            $('#editPrepTimeDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).off("savedDifficulty.recipes");
        $(document).on("savedDifficulty.recipes", function() {
            $('#editDifficultyDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).off("savedSource.recipes");
        $(document).on("savedSource.recipes", function() {
            $('#editSourceDialog').dialog('close');
            ajaxGet('recipes');
        });
        
        $(document).off("savedPreparationMethod.recipes");
        $(document).on("savedPreparationMethod.recipes", function() {
            $('#editPrepMethodDialog').dialog('close');
            ajaxGet('recipes');
        });
    });
    
    function loadImage(imageUrl, caption) {
        $('#selectedRecipeImage img').attr('src', imageUrl).attr('title', caption);
        return false;
    }
</script>
<?php echo $this->Session->flash(); ?>
<?php //echo "sql dump here:<BR>:".$this->element('sql_dump'); ?>
<?php 

//Debugger::dump($this->Auth, $depth = 3); 
if ($this->params['action']<>"index")
{
	//echo "Search method: ".$this->params['action']."<BR>";
}



//Debugger::dump($recipes, $depth = 3); 
//Debugger::dump($this->params['action'], $depth = 3); 
//var_dump($this->params);

?>
<div class="recipes index">
	<h2><?php echo __('Recipes'); ?></h2>
        <?php if ($loggedIn): ?>
        <div class="actions">
            <ul>
                <li><?php echo $this->Html->link(__('Add Recipe'), array('action' => 'edit'));?></li>
                <li><?php echo $this->Html->link(__('Find By Ingredient(s)'), array('action' => 'contains'), array('class' => 'ajaxNavigation'));?></li>
                <li><?php echo $this->Html->link(__('Import'), array('controller' => 'import')); ?></li>
                <li><button id="moreActionLinks">More Actions...</button></li>
            </ul>
            <div style="display: none;">
                <ul id="moreActionLinksContent">
                    <li><?php echo $this->Html->link(__('List Ethnicities'), array('controller' => 'ethnicities', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?></li>
                    <li><?php echo $this->Html->link(__('Add Ethnicity'), array('controller' => 'ethnicities', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editLocationDialog')); ?></li>
                    <li><?php echo $this->Html->link(__('List Base Types'), array('controller' => 'base_types', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?></li>
                    <li><?php echo $this->Html->link(__('Add Base Type'), array('controller' => 'base_types', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editBaseTypeDialog')); ?></li>
                    <li><?php echo $this->Html->link(__('List Courses'), array('controller' => 'courses', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?></li>
                    <li><?php echo $this->Html->link(__('Add Course'), array('controller' => 'courses', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editCourseDialog')); ?></li>
                    <li><?php echo $this->Html->link(__('List Preparation Times'), array('controller' => 'preparation_times', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?></li>
                    <li><?php echo $this->Html->link(__('Add Preparation Time'), array('controller' => 'preparation_times', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editPrepTimeDialog')); ?> </li>
                    <li><?php echo $this->Html->link(__('List Preparation Methods'), array('controller' => 'preparation_methods', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
                    <li><?php echo $this->Html->link(__('Add Preparation Method'), array('controller' => 'preparation_methods', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editPrepMethodDialog')); ?> </li>
                    <li><?php echo $this->Html->link(__('List Difficulties'), array('controller' => 'difficulties', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
                    <li><?php echo $this->Html->link(__('Add Difficulty'), array('controller' => 'difficulties', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editDifficultyDialog')); ?> </li>
                    <li><?php echo $this->Html->link(__('List Sources'), array('controller' => 'sources', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
                    <li><?php echo $this->Html->link(__('Add Source'), array('controller' => 'sources', 'action' => 'edit'), array('class' => 'ajaxLink', 'targetId' => 'editSourceDialog')); ?> </li>
                    <li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index'), array('class' => 'ajaxNavigation')); ?> </li>
                    <li><?php echo $this->Html->link(__('Add User'), array('controller' => 'users', 'action' => 'edit'), array('class' => 'ajaxLink')); ?> </li>
                </ul>
            </div> 

        </div>
        <?php endif;?>
        <p>

	<?php echo $this->Paginator->counter(array('format' => __('Page {:page} of {:pages}')	));?>
        </p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array('class' => 'ajaxLink'), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => '','class'=>'ajaxLink'));
		echo $this->Paginator->next(__('next') . ' >', array('class' => 'ajaxLink'), null, array('class' => 'next disabled'));
// Show form to select records per page
$page_params = $this->Paginator->params();
$limit = $page_params['limit'];
$options = array( 12 => '12', 24 => '24', 48 => '48' );		
		echo $this->Form->create(array('type'=>'get'));
		echo $this->Html->tag('span', 'Per Page:', array('class' => 'paginationtextfield')).'&nbsp;';
		
echo $this->Form->select('limit', $options, array(
    'value'=>$limit, 
    'default'=>24, 
    'empty' => FALSE, 
    'onChange'=>'this.form.submit();', 
    'name'=>'limit'
    )
);
echo $this->Form->end();
	?>
	</div>
        <div class="section">
			<div class="centered main-content-wrapper">
				<div class="section-title-wrapper">
				<?php echo $this->Html->tag('h2', 'Recipes', array('class' => 'section-title')); ?>
				</div>
				<div class="popular-categories-list-wrapper w-dyn-list">
					<div class="category-list w-clearfix w-dyn-items w-row">    	

<?php 

foreach ($recipes as $recipe): 

//Debugger::dump($recipe, $depth = 3);
$background_image_url='url(\'../img/rectangle-green.png\')';
if (($recipe['Attachment']['attachment']<>NULL) && ($recipe['Attachment']['dir']<>NULL))
{
	//echo "photo found: ".$recipe['Attachment']['dir']." filename: ".$recipe['Attachment']['attachment']."<BR>";	
	$baseUrl = Router::url('/');
	$background_image_url='url(\''.$baseUrl."files/image/attachment/".$recipe['Attachment']['dir']. '/' .$recipe['Attachment']['attachment'].'\')';
}
	$RatingText=NULL;
	if (isset($recipe[0]) && isset($recipe[0]['AverageRating']))
	{
		$Rating=round($recipe[0]['AverageRating'],1);
		$RatingText=' ('.$Rating.' stars)';
	}


				$link='		<div class="category-item w-col w-col-4 w-dyn-item">
							<a class="category recipe-image-block w-inline-block"
								href="'.$this->Html->url(array("controller" => "recipes",'action' => 'view', $recipe['Recipe']['id'])).'" '.
					'style="background-image: '.$background_image_url.';">
								<div class="recipe-overlay-block" data-ix="show-arrow-icon"
									style="transition: background-color 0.2s ease 0s;">
									<div class="recipe-title-wrapper">
										<div class="recipe-title">'.$recipe['Recipe']['name'].'</div>
										<div class="recipe-title view-recipes">'.$recipe['Course']['name'].$RatingText.'</div>
									</div>
								</div>
							</a>
						</div>';
				echo $link;		

endforeach; ?>    
					</div>
    			</div> 
    		</div>  
        </div>
    <hr style="clear:both;">
    <p>
	<?php echo $this->Paginator->counter(array('format' => __('Page {:page} of {:pages}')	));?>
        </p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array('class' => 'ajaxLink'), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => '','class'=>'ajaxLink'));
		echo $this->Paginator->next(__('next') . ' >', array('class' => 'ajaxLink'), null, array('class' => 'next disabled'));
		// Show form to select records per page
$page_params = $this->Paginator->params();
$limit = $page_params['limit'];
$options = array( 12 => '12', 24 => '24', 48 => '48' );		
		echo $this->Form->create(array('type'=>'get'));
		echo "<span class=\"paginationtextfield\">Per Page:</span>&nbsp;";
echo $this->Form->select('limit', $options, array(
    'value'=>$limit, 
    'default'=>20, 
    'empty' => FALSE, 
    'onChange'=>'this.form.submit();', 
    'name'=>'limit'
    )
);
echo $this->Form->end();
	?>
	</div>
</div>

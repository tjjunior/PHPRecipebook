<?php
App::uses('AppModel', 'Model');
/**
 * Store Aisle Model
 *
 */
class StoreAisle extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'aisle_descr' => array(
                    'required' => array(
                        'rule' => 'notBlank'
                    )
		)
	);
	
	
	public function getStoreAisles($storeId) {
	    $allAisles = $this->StoreAisle->store_aisles->find('all', array(
	        'conditions' => array('StoreAisle.id'=> $storeId)
	    ));
	    
	    
	    //echo "function name: ".__FUNCTION__."<BR>";
	    //var_dump($search);
	    return $allAisles;
	    
	    $search = array('conditions' => array('StoreAisle.id'=> $storeId),
	        'contain' => array(
	            'ShoppingListIngredient' => array(
	                'fields' => array('unit_id', 'quantity'),
	                'Unit' => array(
	                    'fields' => array('name')
	                ),
	                'Ingredient' => array(
	                    'fields' => array('name', 'location_id')
	                )
	            ),
	            'ShoppingListRecipe' => array(
	                'fields' => array('servings'),
	                'Recipe' => array(
	                    'fields' => array('name'),
	                    'IngredientMapping' => array(
	                        'fields' => array('quantity'),
	                        'Unit' => array(
	                            'fields' => array('name')
	                        ),
	                        'Ingredient' => array(
	                            'fields' => array('name', 'location_id')
	                        )
	                    )
	                )
	            )
	        )
	    );
	    return $this->find('first', $search);
	    
	}
	
}

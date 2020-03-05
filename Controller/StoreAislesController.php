<?php
App::uses('AppController', 'Controller');
/**
 * StoreAisles Controller
 *
 * @property StoreAisle $StoreAisle
 * @property PaginatorComponent $Paginator
 */
class StoreAislesController extends AppController {

    public $components = array('Paginator');
   
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->deny(); // Deny ALL, user must be logged in.
    }
    
    /**
     * index method
     *
     * @return void
     */
    public function index() {
            $this->StoreAisle->recursive = 0;
            $this->set('storeAisles', $this->Paginator->paginate());
    }


    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if ($id != null && !$this->StoreAisle->exists($id)) {
            throw new NotFoundException(__('Invalid store aisle'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->StoreAisle->save($this->request->data)) {
                    $this->Session->setFlash(__('The store aisle has been saved.'), 'success', array('event' => 'savedStoreAisle'));
                    return $this->redirect(array('action' => 'edit'));
            } else {
                    $this->Session->setFlash(__('The store aisle could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('StoreAisle.' . $this->StoreAisle->primaryKey => $id));
            $this->request->data = $this->StoreAisle->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->StoreAisle->id = $id;
        if (!$this->StoreAisle->exists()) {
                throw new NotFoundException(__('Invalid store aisle'));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->StoreAisle->delete()) {
                $this->Session->setFlash(__('The store aisle has been deleted.'), 'success', array('event' => 'savedStoreAisle'));
        } else {
                $this->Session->setFlash(__('The store aisle could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
}

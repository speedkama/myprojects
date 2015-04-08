<?php
/**
 *@name: Cart.php
 *Cart model file
 **/
class Storefront_Models_Cart extends SF_Model_Abstract implements
SeekableIterator, Countable, ArrayAccess
{
    protected $_items= array();
    protected $_subTotal = 0;
    protected $_total = 0;
    protected $_shipping = 0;
    protected $_sessionNamespace;
    
    public function init() {
        $this->loadSession();
    }
    public function addItem(
        Storefront_Resources_Product_Item_Interface $product,
        $qty
    ) {
        if ( 0 > $qty ){
            return false;
        }
        
        if( $qty == 0 ){
            $this->removeItem($product);
        }
        $item = new Storefront_Resources_Cart_Item($product, $qty);
        
        $this->_items[$item->productId] = $item;
        $this->persist();
        return $item;
    }
    public function removeItem($product){
        if(is_int($product)) {
            unset($this->_items[$product]);
        }
        if($product instanceof Storefront_Resources_Product_Item_Interface){
            unset($this->_items[$product->prodictId]);
        }
        $this->persist();
    }
    
    public function setSessionNs( Zend_Session_Namespace $ns) {
        $this->_sessionNamespace = $ns;
    }
    
    public function getSessionNs() {
        if(null === $this->_sessionNamespace){
            $this->setSessionNs(new Zend_Session_Namespace(__CLASS__));
        }
        return $this->_sessionNamespace;
    }
}
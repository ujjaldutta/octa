<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$mysqli = new mysqli("localhost","root","rLHnkRNohWnLeL6ERs9M&WQvB3v","octawp");
// Check connection
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }

$query = "SELECT * FROM wp_octa_product_images where entity_id=167 ORDER by id asc";


/*
 * Assumes doc root is set to ROOT/pub
 */
use \Magento\Framework\App\Bootstrap;

require __DIR__ . '/app/bootstrap.php';
$bootstrap = Bootstrap::create(BP, $_SERVER);
 
// Instance of object manager
$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
$importimageservice=new \OwebDesk\ProductImport\Service\ImportImageService;

if ($result = $mysqli->query($query)) {

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
// Remove Images From Product
$productId =$row["entity_id"]; // Id of product
$product = $objectManager->create('Magento\Catalog\Model\Product')->load($productId);
$productRepository = $objectManager->create('Magento\Catalog\Api\ProductRepositoryInterface');
$existingMediaGalleryEntries = $product->getMediaGalleryEntries();
/*foreach ($existingMediaGalleryEntries as $key => $entry) {
    unset($existingMediaGalleryEntries[$key]);
}
$product->setMediaGalleryEntries($existingMediaGalleryEntries);
$productRepository->save($product);
*/
// Add Images To The Product

$imagePath = str_replace('https://www.octa.com','/var/www/html/live',$row["image"]); // path of the image
$product->addImageToMediaGallery($imagePath, array('image', 'small_image', 'thumbnail'), false, false);
$product->save();
echo 'Entity ID '.$row["entity_id"].' updated'.PHP_EOL;
}

/* free result set */
$result->free();
}

/* close connection */
$mysqli->close();
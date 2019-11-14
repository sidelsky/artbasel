<?php
use Theme\Model\Single;
use App\Helper\Render;

include("header.php");
$render = new Render;


$single = new Single($post->ID);

$images = get_field('gallery');

print_r($images);



?>

<script>
  var GALLERY = <?php echo json_encode($gallery) ?>;
</script>

<?php
// echo $render->view('components/c-single-post', $single->getSingle());
include("footer.php");
?>

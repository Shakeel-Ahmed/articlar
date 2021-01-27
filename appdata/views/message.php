<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <?php if($title) { ?>
        <h4><?php echo $title; ?></h4>
        <hr/>
      <?php } if($message) { ?>
        <p class="text-center">
          <?php echo $message; ?>
        </p>
      <?php } if($action) {?>
        <p class="space-top" style="animation-delay:1s;animation-fill-mode:backwards;">
          <?php echo $action; ?>
        </p>
      <?php } ?>
    </div>
  </div>
</div>
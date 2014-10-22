<?php
extract($theme['modal']);
?>
<div class="modal fade" id="<?php print $modal_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?php if (isset($title)) { print $title; }; ?></h4>
            </div>
            <div class="modal-body">
                <?php if (isset($body)) { print $body; }; ?>
            </div>
            <?php
                if (isset($footer)) {
            ?>
            <div class="modal-footer">
                <?php print $footer; ?>
            </div>
            <?php
                }
            ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="popup-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Skip Approver</h5>
                <button type="button" id="popup_btn_close" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ $body }}
            </div>
            <div class="modal-footer">
                <button type="button" id="popup_btn_cancel" class="btn bg-gradient-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="popup_btn_ok" class="btn bg-gradient-success">Skip</button>
                <button type="button" id="popup_btn_ok_processing" class="btn bg-gradient-success" disabled style="display: none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    {{ __('label.button_processing') }}
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#popup-confirm').on('show.bs.modal', function (e) {
            popupRelatedTarget = e.relatedTarget;
        });
        $('#popup_btn_ok').on('click', function(e) {
            e.preventDefault();
            if(popupRelatedTarget.attr('type') === 'button'){
                let form = popupRelatedTarget[0].form;
                if(form){
                    // lock closing modal
                    $('#popup_btn_cancel').attr('disabled','disabled');
                    $('#popup_btn_close').attr('disabled','disabled');
                    
                    // $('#popup-confirm').data('bs.modal')._config.keyboard = false;
                    $('#popup-confirm').data('bs.modal')._config.backdrop = 'static';
                    // show processing button
                    $(this).hide();
                    $('#popup_btn_ok_processing').show();
                    // push submit name to server (optional)
                    let targetName = $(popupRelatedTarget).attr('name');
                    if(targetName){
                        let hidSubmit = '<input type="hidden" name="' + targetName + '" value="' + targetName + '" />';
                        $(form).append(hidSubmit);
                    }
                    // submit form
                    form.submit();
                }
            }
        });
        $('[data-target="#popup-confirm"]').on('click', function(){
            $('#popup-confirm').modal('show', $(this));
        });
    });
</script>
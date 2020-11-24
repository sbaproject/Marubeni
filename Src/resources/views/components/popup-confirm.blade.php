<div class="modal fade" id="popup-confirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               {{ $body }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $close }}</button>
                <button type="button" id="popup_btn_ok" class="btn btn-primary">{{ $accept }}</button>
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
            if(popupRelatedTarget.getAttribute('type') == 'submit'){
                popupRelatedTarget.form.submit();
            }
            // if(popupRelatedTarget.getAttribute('type') == 'button'){
            //     if(popupRelatedTarget.form){
            //         popupRelatedTarget.form.submit();
            //     }
            // }
        });
    });
</script>
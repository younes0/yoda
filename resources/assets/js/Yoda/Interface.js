import Modal from "../Yeb/Helpers/Modal";
import Layout from "../Yeb/Admin/Layout";

export default class Interface {

    setFormModalUi() {

        var modal = new Modal();

        // growl
        var clickHandler = function() {
            var url = $(this).data('url'), $modal = $('#modal-placeholder');

            var promise = $.ajax({
                url : url,
                data: $.extend({}, $modal.find('form').serializeObject(), {
                    '_token': $modal.find('[name="_token"]').val()
                }),

            }).done(function() {
                modal.getModal().modal('hide');
                $table.fnDraw();
            });
            
            Messenger().expectPromise(function(){
                return promise;
            }, {
                successMessage: 'Done',
                errorMessage  : 'Failed',
                hideAfter     : 1,
            });

        };

        PubSub.subscribe('modal.shown', function() {
            new Layout().setupOnce();
            $('#modal-placeholder').find('.modalButtons button').on('click', clickHandler);
        });
        
    }

}

import Modal from "../Helpers/Modal";

export default class Layout {

    // not affected by ajax calls
    setupOnce() {    
        this.setup();    

        var modal = new Modal();
        modal.setLinks(false, 'body');
 
        $(document)
            .ajaxSend(function() {
                Pace.start();
            })
            .ajaxComplete(function() {
                Pace.stop();
            });
    }

    setup() {
        $('[rel=tooltip]').tooltip();
        $('textarea').autosize();
        // validation
        $('.bsValidator').each(function() {
            $(this).bootstrapValidator({
                live: 'disabled',
        
            }).on('error.field.bv', function(e, field) {
                $('p.help-block').remove();
            });
        });

        $('.select2:not(.tags)').select2();
    }

}

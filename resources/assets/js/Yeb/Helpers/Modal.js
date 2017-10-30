export default class Modal {

    constructor() {
        this.$placeholder = null;
        this.$modal = null;
    }

    // return shown modal object
    getModal() {
        return this.$placeholder.find('.modal');
    }

    getContent(url, params, callback) {
        params = typeof params !== 'undefined' ? params : {};

        PubSub.publish('modal.loading');
        this._setup();

        $.get(url, params).done((resp) => {
            this._build(resp);
            typeof callback !== 'undefined' && callback();
        });
    }

    setLinks(innerModal, context) {

        if (typeof innerModal !== 'undefined' && innerModal === true) {
            context = this.$modal;
        
        } else {
            context = (typeof context !== 'undefined') ? context : null;
        }
        
        var self = this;

        $('a[data-toggle="ajax-modal"]', context).on('click', function(e) {
            e.preventDefault();
            self.getContent(this.href);
        });

        $('form[data-toggle="ajax-modal"]', context).on('submit', function(e) {
            e.preventDefault();
            self.getContent($(this).attr('action'), $(this).serializeObject());
        });

    }

    _setup() {
        this.$placeholder = $('#modal-placeholder');
        
        if ( !this.$placeholder.length ) {
            $('body').append('<div id="modal-placeholder"></div>');
            this.$placeholder = $('#modal-placeholder');
        } 
    }

    _build(content) {    
        
        this.$modal && this.$modal.modal('hide');

        this.$placeholder.html(content);
        this.$modal = this.getModal();

        // needs id
        var id = this.$modal.attr('id'), backdrop = this.$modal.is(".noBackdrop");
        id = id ? id : Math.random().toString(36).slice(2);

        // events
        this.$modal.on('show.bs.modal', function() {
            PubSub.publish('modal.loading.'+id, id);

        }).on('shown.bs.modal', function() {
            PubSub.publish('modal.shown.'+id, id);
        
        }).on('hidden.bs.modal', function() {
            PubSub.publish('modal.hidden.'+id, id);
        });

        // display settings
        var defaults = { backdrop: true, keyboard: false };    
        var settings = $.extend({}, defaults, {
            'keyboard' : this.$modal.is(".keyboard")
        });

        if (this.$modal.is('.noFadeOut')) {
            this.$modal.on('hide.bs.modal', function() {
                $(this).removeClass('fade');
            });
        }

        if (this.$modal.is('.static')) {
            settings.backdrop = 'static';
        }

        this.$modal.modal(settings);

    }

}

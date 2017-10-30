import Modal from "./Modal";

export default class Other {

    static formModalConfirm($form, url) {
        url = typeof url !== 'undefined' ? url : $form.attr('action');

        var modal = new Modal();

        $form.on('submit', function(e) {
            e.preventDefault();

            modal.getContent(url, null, function() {
                modal.getModal().find('.formSubmit').on('click', function() {
                    $form[0].submit();
                });
            });
        });
    }

    static isAndroid() {
        var ua = navigator.userAgent.toLowerCase();
        return (ua.indexOf("android") > -1);
    }

    static getFileName() {
        var url = document.location.href;
        url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));
        url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));
        url = url.substring(url.lastIndexOf("/") + 1, url.length);
        return url;
    }

    static getQueryVariable(variable) {
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i=0;i<vars.length;i++) {
            var pair = vars[i].split("=");
            if (pair[0] == variable) {
                return pair[1];
            }
        }
    }

}

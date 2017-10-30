import Layout from "../Yeb/Admin/Layout";

export default class Base {

    constructor(request) {
        this.setupAjaxToken();

        new Layout().setupOnce();
    }

    setupAjaxToken() {
       // runs before each request
       $.ajaxPrefilter(function(options, originalOptions, xhr) {
            // adds directly to the XmlHttpRequest Object
            return xhr.setRequestHeader('X-CSRF-TOKEN', app._token); 
        });
    }

}

if (app.config.disqusId) {
    var disqus_config = function() {
        this.language     = app.config.locale;
        this.page.api_key = app.config.disqus.apiKey;
    };
}

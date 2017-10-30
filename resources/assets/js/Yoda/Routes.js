import Base from "./Base";
import Nlp from "./Nlp";
import Datatable from "./Datatable";

new anyroutes()
    .defaultCallback(req => new Base(req))
    .any('/users', req => new Datatable(req))
    .any('/links', req => new Datatable(req))
    .any('/origins', req => new Datatable(req))
    .any('/nlp/results', req => new Datatable(req))
    .any('/nlp/documents/:id', req => new Nlp(req))
    .any('/nlp/train/:id', req => new Nlp(req))
;

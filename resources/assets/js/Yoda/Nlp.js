import Base from "./Base";

export default class Nlp extends Base  {

    constructor(request) {
        super(request);

        $('.nlpHighlight').highlight(app.tokens, {
            wordsOnly    : false,
            ignoreAccents: true,
        });
    }
    
}
